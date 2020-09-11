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

use Bclfp\Skeleton\Util\ExceptionCode;
use Hyperf\HttpMessage\Server\Response;
use Hyperf\HttpMessage\Stream\SwooleStream;
use Psr\Container\ContainerInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;

class AuthMiddleware
{
    /**
     * @var ContainerInterface
     */
    protected $container;

    /**
     * @var Response
     */
    protected $response;

    public function __construct(ContainerInterface $container, Response $response)
    {
        $this->container = $container;
        $this->response = $response;
    }

    public function process(ServerRequestInterface $request, RequestHandlerInterface $handler): ResponseInterface
    {
        $check = auth()->check();
        if (! $check) {
            $data = json_encode([
                'code' => ExceptionCode::AUTH['NOT_LOGIN']['code'],
                'message' => ExceptionCode::AUTH['NOT_LOGIN']['message'],
            ], JSON_UNESCAPED_UNICODE);
            // 阻止异常冒泡
            return $this->response->withStatus(401)
                ->withAddedHeader('Content-Type', 'application/json')
                ->withBody(new SwooleStream($data));
        }
        return $handler->handle($request);
    }
}
