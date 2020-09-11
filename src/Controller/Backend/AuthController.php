<?php


namespace Bclfp\Skeleton\Controller\Backend;


use Bclfp\Skeleton\Controller\AbstractController;
use Bclfp\Skeleton\Model\Administrator;
use Bclfp\Skeleton\Service\AdministratorService;
use Carbon\Carbon;
use Hyperf\HttpServer\Annotation\Controller;
use Hyperf\HttpServer\Annotation\GetMapping;
use Hyperf\HttpServer\Annotation\Middlewares;
use Hyperf\HttpServer\Annotation\Middleware;
use Hyperf\Di\Annotation\Inject;
use Hyperf\HttpServer\Annotation\PostMapping;
use Hyperf\HttpServer\Contract\RequestInterface;
use Hyperf\HttpServer\Contract\ResponseInterface;
use Hyperf\Validation\Contract\ValidatorFactoryInterface;

/**
 * Class AuthController
 * @package Bclfp\Skeleton\Controller\Backend
 * @Controller(prefix="backend")
 */
class AuthController extends AbstractController
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
     * @return \Psr\Http\Message\ResponseInterface
     * @PostMapping(path="login")
     */
    public function login(RequestInterface $request)
    {
        try {
            $validation = $this->validationFactory->make(
                $request->all(),
                [
                    'email' => 'required',
                    'password' => 'required',
                ],
                [
                    'email.*' => '邮箱不能为空',
                    'password.*' => '密码不能为空',
                ]
            );
            if ($validation->fails()) {
                $message = $validation->errors()->first();
                throw new \Exception($message);
            }

            $administrator = Administrator::query()
                ->where('email', $request->post('email'))
                ->where('status', '!=', Administrator::FORBID_STATUS)
                ->first();
            if (! $administrator) {
                throw new \Exception('邮箱或者密码错误');
            }
            if (! password_verify($request->post('password'), $administrator->password)) {
                throw new \Exception('邮箱或者密码错误');
            }

            // 更新登录时间
            $administrator->login_at = Carbon::now()->format('Y-m-d H:i:s');
            $administrator->save();
            $adminAuth = adminAuth();
            return successResponse([
                'token' => $adminAuth->token($administrator),
                'user' => $administrator,
            ]);
        } catch (\Throwable $exception) {
            return errorResponse($exception);
        }
    }
}