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
use Bclfp\Skeleton\Middleware\AdminAuthMiddleware;
use Bclfp\Skeleton\Model\Role;
use Bclfp\Skeleton\Request\RoleRequest;
use Bclfp\Skeleton\Service\RoleService;
use Bclfp\Skeleton\Util\ExceptionCode;
use Hyperf\HttpServer\Annotation\Controller;
use Hyperf\HttpServer\Annotation\DeleteMapping;
use Hyperf\HttpServer\Annotation\GetMapping;
use Hyperf\HttpServer\Annotation\Middleware;
use Hyperf\HttpServer\Annotation\Middlewares;
use Hyperf\HttpServer\Annotation\PostMapping;
use Hyperf\HttpServer\Annotation\PutMapping;

/**
 * Class RolesController.
 *
 * @Controller(prefix="backend/role")
 * @Middlewares({
 *     @Middleware(AdminAuthMiddleware::class),
 *     @Middleware(\App\Middleware\PermissionMiddleware::class)
 * })
 */
class RolesController extends AbstractController
{
    /**
     * @GetMapping(path="{id}")
     * @return \Psr\Http\Message\ResponseInterface
     */
    public function detail(int $id)
    {
        try {
            $role = Role::query()->find($id);
            if (! $role) {
                throw new \Exception('未找到数据');
            }

            return successResponse($role);
        } catch (\Exception $exception) {
            return errorResponse($exception);
        }
    }

    /**
     * 添加角色.
     * @PostMapping(path="/backend/role", methods="post")
     * @return \Psr\Http\Message\ResponseInterface
     */
    public function create(RoleRequest $request)
    {
        try {
            $data = $request->all();
            // 创建角色
            $role = RoleService::createRole($data);
            return successResponse($role);
        } catch (\Throwable $exception) {
            return errorResponse($exception);
        }
    }

    /**
     * 更新角色.
     * @PutMapping(path="{id}")
     * @return \Psr\Http\Message\ResponseInterface
     */
    public function update(RoleRequest $request, int $id)
    {
        try {
            $data = $request->all();
            $role = RoleService::updateRole($id, $data);
            return successResponse($role);
        } catch (\Throwable $exception) {
            return errorResponse($exception);
        }
    }

    /**
     * 获取角色列表.
     * @GetMapping(path="/backend/roles")
     * @return \Psr\Http\Message\ResponseInterface
     */
    public function list()
    {
        try {
            $roles = Role::query()->with('menus')->get();
            foreach ($roles as $role) {
                $role->permissions;
            }
            return successResponse($roles);
        } catch (\Exception $exception) {
            return errorResponse($exception);
        }
    }

    /**
     * @DeleteMapping(path="{id}")
     * @return \Psr\Http\Message\ResponseInterface
     */
    public function destroy(int $id)
    {
        try {
            $role = Role::query()->find($id);
            if (! $role) {
                throw new \Exception(ExceptionCode::DATA_ERROR['NOT_FOUND']['message']);
            }
            $administrators = $role->administrators()->get();
            if ($administrators->count() > 0) {
                throw new \Exception('此角色拥有管理员，请先移除管理员后再操作!');
            }
            return successResponse($role->delete());
        } catch (\Throwable $exception) {
            return errorResponse($exception);
        }
    }
}
