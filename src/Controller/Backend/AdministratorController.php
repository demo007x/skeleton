<?php

declare(strict_types=1);
/**
 * This file is part of Hyperf.
 *
 * @link     https://www.hyperf.io
 * @document https://doc.hyperf.io
 * @contact  group@hyperf.io
 * @license  https://github.com/hyperf/hyperf/blob/master/LICENSE
 */
namespace Bclfp\Skeleton\Controller\Backend;

use Bclfp\Skeleton\Controller\AbstractController;
use Bclfp\Skeleton\Model\Administrator;
use Bclfp\Skeleton\Service\AdministratorService;
use Bclfp\Skeleton\Middleware\AdminAuthMiddleware;
use Bclfp\Skeleton\Middleware\PermissionMiddleware;
use Carbon\Carbon;
use Hyperf\Di\Annotation\Inject;
use Hyperf\HttpServer\Annotation\Middleware;
use Hyperf\HttpServer\Annotation\Middlewares;
use Hyperf\HttpServer\Annotation\Controller;
use Hyperf\HttpServer\Annotation\GetMapping;
use Hyperf\HttpServer\Annotation\PostMapping;
use Hyperf\HttpServer\Annotation\PutMapping;
use Hyperf\HttpServer\Contract\RequestInterface;
use Hyperf\HttpServer\Contract\ResponseInterface;
use Hyperf\Validation\Contract\ValidatorFactoryInterface;

/**
 * Class AdministratorController.
 * @Controller(prefix="backend/administrator")
 * @Middlewares({
 *    @Middleware(AdminAuthMiddleware::class),
 *    @Middleware(PermissionMiddleware::class)}
 * )
 */
class AdministratorController extends AbstractController
{
    /**
     * @Inject
     * @var ValidatorFactoryInterface
     */
    protected $validationFactory;

    /**
     * @Inject
     * @var AdministratorService
     */
    protected $administratorService;

    /**
     * logout
     * @param RequestInterface $request
     * @PostMapping(path="/backend/logout")
     * @return \Psr\Http\Message\ResponseInterface
     */
    public function logout(RequestInterface $request)
    {
        try {
            $isLogout = adminAuth()->logout();
            return  successResponse($isLogout);
        } catch (\Throwable $exception) {
            return errorResponse($exception);
        }
    }

    /**
     * 列表.
     * @GetMapping(path="list")
     * @return \Psr\Http\Message\RequestInterface|\Psr\Http\Message\ResponseInterface
     */
    public function list(RequestInterface $request, ResponseInterface $response)
    {
        try {
            $params = $request->query();
            $query = Administrator::query();
            if (isset($params['name']) && trim($params['name']) != '') {
                $query->where('name', 'like', '%' . $params['name'] . '%');
            }

            if (isset($params['email']) && trim($params['email']) != '') {
                $query->where('email', $params['email']);
            }

            $limit = (int) $params['limit'] ?? 20;
            if (isset($params['sort']) && $params['sort'] == '-id') {
                $query->orderByDesc('id');
            }

            if (isset($params['sort']) && $params['sort'] == '+id') {
                $query->orderBy('id');
            }
            $list = $query->orderByDesc('login_at')->with('roles')->paginate($limit);
            return successResponse($list);
        } catch (\Throwable $exception) {
            return errorResponse($exception);
        }
    }

    /**
     * 获取当前管理员的信息.
     * @GetMapping(path="/backend/administrator")
     * @return \Psr\Http\Message\RequestInterface|\Psr\Http\Message\ResponseInterface
     */
    public function getCurrentAdministratorInfo(RequestInterface $request)
    {
        try {
            $admin = adminAuth()->user();
            $roles = [];
            foreach ($admin->roles as $role) {
                $roles[] = $role->role_key;
            }
            if ($admin->status == Administrator::FORBID_STATUS) {
                throw new \Exception('此用户禁止登录');
            }
            return  successResponse($admin);
        } catch (\Throwable $exception) {
            return errorResponse($exception);
        }
    }

    /**
     * 获取管理员的信息.
     * @GetMapping(path="{id}")
     */
    public function getAdministratorInfo(RequestInterface $request, int $id)
    {
        try {
            $administrator = Administrator::query()->with('roles')->find($id);
            if (! $administrator) {
                throw new \Exception('未找到信息', 404);
            }
            return successResponse($administrator->toArray());
        } catch (\Throwable $exception) {
            return errorResponse($exception);
        }
    }

    /**
     * 创建管理员.
     * @PostMapping()
     * @return \Psr\Http\Message\RequestInterface|\Psr\Http\Message\ResponseInterface
     */
    public function create(RequestInterface $request)
    {
        try {
            $validator = $this->validationFactory->make(
                $request->all(),
                [
                    'name' => 'required',
                    'email' => 'required|email',
                    'password' => 'required|min:6',
                    're_password' => 'required|same:password',
                ],
                [
                    'name.*' => '名称必填',
                    'email.*' => '无效的邮箱',
                    'password.required' => '密码必填',
                    'password.min' => '密码的长度不能小于 6 位',
                    're_password.*' => '两次密码不一致',
                ]
            );
            if ($validator->fails()) {
                // Handle exception
                $errorMessage = $validator->errors()->first();
                throw new \Exception($errorMessage);
            }

            $user = $this->administratorService->createAdministrator($request);
            return successResponse($user);
        } catch (\Throwable $exception) {
            return errorResponse($exception);
        }
    }

    /**
     * 修改管理员信息.
     * @PutMapping(path="{id:\d+}")
     * @return \Psr\Http\Message\RequestInterface|\Psr\Http\Message\ResponseInterface
     */
    public function update(RequestInterface $request, int $id)
    {
        try {
            $administrator = $this->administratorService->updateAdministratorById($id, $request->all());
            return successResponse($administrator);
        } catch (\Throwable $exception) {
            return errorResponse($exception);
        }
    }

    /**
     * 修改管理员密码
     * @PutMapping(path="{id:\d+}/password")
     * @return \Psr\Http\Message\ResponseInterface
     */
    public function updatePassword(RequestInterface $request, int $id)
    {
        try {
            $validation = $this->validationFactory->make(
                $request->all(),
                [
                    'password' => 'required|min:6',
                    're_password' => 'required|same:password',
                ],
                [
                    'password.required' => '密码不能为空',
                    'password.min' => '密码至少长度不能小于 6',
                    're_password.required' => '重复密码不能为空',
                    're_password.same' => '两次密码不一致',
                ]
            );

            if ($validation->fails()) {
                throw new \Exception($validation->errors()->first());
            }
            $administrator = Administrator::query()->find($id);
            if (! $administrator) {
                throw new \Exception('未找到用户');
            }

            $administrator->password = password_hash($request->post('password'), PASSWORD_DEFAULT);
            return successResponse($administrator->save());
        } catch (\Throwable $exception) {
            return errorResponse($exception);
        }
    }

    /**
     * 封禁用户.
     * @GetMapping(path="{id:\d+}/forbid")
     * @return \Psr\Http\Message\RequestInterface|\Psr\Http\Message\ResponseInterface
     */
    public function forbid(RequestInterface $request, int $id)
    {
        try {
            if (env('ADMINISTRATOR_ID', 1) == $id) {
                throw new \Exception('此账户不能封禁');
            }
            $admin = Administrator::query()->find($id);
            if (! $admin) {
                throw new \Exception('未找到用户');
            }
            if ($admin->status == Administrator::NORMAL_STATUS) {
                $admin->status = Administrator::FORBID_STATUS;
            } else {
                $admin->status = Administrator::NORMAL_STATUS;
            }

            $admin->save();

            return successResponse();
        } catch (\Throwable $exception) {
            return errorResponse($exception);
        }
    }

    /**
     * 删除用户
     * @param RequestInterface $request
     * @param int $id
     * @GetMapping(path="{id:\d+}/destroy")
     * @return \Psr\Http\Message\ResponseInterface
     */
    public function destroy(RequestInterface $request, int $id)
    {
        try {
            if (env('ADMINISTRATOR_ID', 1) == $id) {
                throw new \Exception('此账户不能删除');
            }
            $admin = Administrator::query()->find($id);
            if (! $admin) {
                throw new \Exception('未找到用户');
            }
            $admin->delete();

            return successResponse();
        } catch (\Throwable $exception) {
            return errorResponse($exception);
        }
    }

    /**
     * 设置管理员的角色.
     * @PostMapping(path="role")
     * @return \Psr\Http\Message\ResponseInterface
     */
    public function setAdminRole(RequestInterface $request)
    {
        try {
            $data = $request->all();
            $validate = $this->validationFactory->make(
                $data,
                [
                    'administrator_id' => 'required',
                    'role_id' => 'required',
                ],
                [
                    'administrator_id.*' => 'administrator_id is required',
                    'role_id.*' => 'role_id is required',
                ]
            );
            if ($validate->fails()) {
                throw new \Exception($validate->getMessageBag()->first());
            }
            $result = $this->administratorService->setAdminRole($data['administrator_id'], $data['role_id']);
            return successResponse($result);
        } catch (\Throwable $exception) {
            return errorResponse($exception);
        }
    }
}
