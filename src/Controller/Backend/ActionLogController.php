<?php

declare(strict_types=1);

namespace Bclfp\Skeleton\Controller\Backend;

use Bclfp\Skeleton\Controller\AbstractController;
use Bclfp\Skeleton\Model\ActionLog;
use Hyperf\HttpServer\Annotation\Controller;
use Hyperf\HttpServer\Annotation\GetMapping;
use Hyperf\HttpServer\Annotation\Middlewares;
use Hyperf\HttpServer\Annotation\Middleware;
use Hyperf\HttpServer\Contract\RequestInterface;
use Hyperf\HttpServer\Contract\ResponseInterface;
use Bclfp\Skeleton\Middleware\AdminAuthMiddleware;
use Bclfp\Skeleton\Middleware\PermissionMiddleware;

/**
 * Class ActionLogController
 * @package Bclfp\Skeleton\Controller\Backend
 * @Controller(prefix="backend/log")
 * @Middlewares({
 *      @Middleware(AdminAuthMiddleware::class),
 *      @Middleware(PermissionMiddleware::class)
 * })
 */
class ActionLogController extends AbstractController
{
    /**
     * @param RequestInterface $request
     * @param ResponseInterface $response
     * @GetMapping(path="/backend/logs")
     * @return \Psr\Http\Message\ResponseInterface
     */
   public function logs(RequestInterface $request, ResponseInterface $response)
   {
       try {
           $size = $request->query('limit', 20);
           $logs = ActionLog::query()->orderByDesc('id')->paginate((int)$size);

           return successResponse($logs);
       } catch (\Throwable $exception) {
            return errorResponse($exception);
       }
   }
}
