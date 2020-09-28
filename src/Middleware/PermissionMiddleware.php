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
namespace Bclfp\Skeleton\Middleware;

use Bclfp\Skeleton\Event\ActionLog;
use Bclfp\Skeleton\Util\ExceptionCode;
use Hyperf\Contract\ConfigInterface;
use Hyperf\Di\Annotation\Inject;
use Hyperf\HttpServer\Contract\RequestInterface;
use Hyperf\HttpServer\Contract\ResponseInterface as HttpResponse;
use Hyperf\HttpServer\Router\Dispatched;
use Psr\Container\ContainerInterface;
use Psr\EventDispatcher\EventDispatcherInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;

class PermissionMiddleware implements MiddlewareInterface
{
    /**
     * @var ContainerInterface
     */
    protected $container;

    /**
     * @var RequestInterface
     */
    protected $request;

    /**
     * @var HttpResponse
     */
    protected $response;

    /**
     * @Inject
     * @var ConfigInterface
     */
    protected $config;


    /**
     * @Inject()
     * @var EventDispatcherInterface
     */
    protected $eventDispatcher;

    public function __construct(ContainerInterface $container, HttpResponse $response, RequestInterface $request)
    {
        $this->container = $container;
        $this->response = $response;
        $this->request = $request;
    }

    public function process(ServerRequestInterface $request, RequestHandlerInterface $handler): ResponseInterface
    {
        $white_list_route = $this->config->get('permission.white_list_route', []);
        if (in_array($this->request->getRequestUri(), $white_list_route)) {
            return $handler->handle($request);
        }

        try {
            [$controller, $action] = $this->request->getAttribute(Dispatched::class)->handler->callback;
        } catch (\Exception $exception) {
            $data = [
                'code' => ExceptionCode::HTTP['NOT_FOUND_HTTP']['code'],
                'message' => ExceptionCode::HTTP['NOT_FOUND_HTTP']['message'],
            ];
            return $this->response->json($data);
        }

        $controllerArray = explode('\\', $controller);
        $controller = $controllerArray[count($controllerArray) - 1];
        $controllerName = substr($controller, 0, -10);
        $user = adminAuth()->user();
        // 判断当前用户是否有当前请求的权限
        if (! $user->hasPermission($controllerName, $action, $this->request->getMethod())) {
            $data = [
                'code' => ExceptionCode::HTTP['FORBIDDEN']['code'],
                'message' => ExceptionCode::HTTP['FORBIDDEN']['message'],
            ];
            return $this->response->json($data);
        }
        $this->eventDispatcher->dispatch(new ActionLog($this->request, $user, $controllerName, $action));
        return $handler->handle($request);
    }
}
