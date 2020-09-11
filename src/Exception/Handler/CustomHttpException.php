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
namespace Bclfp\Skeleton\Exception\Handler;

use App\Util\ExceptionCode;
use Hyperf\ExceptionHandler\ExceptionHandler;
use Hyperf\HttpMessage\Exception\HttpException;
use Hyperf\HttpMessage\Exception\MethodNotAllowedHttpException;
use Hyperf\HttpMessage\Exception\NotFoundHttpException;
use Hyperf\HttpMessage\Stream\SwooleStream;
use Psr\Http\Message\ResponseInterface;
use Throwable;

class CustomHttpException extends ExceptionHandler
{
    public function handle(Throwable $throwable, ResponseInterface $response)
    {
        if ($throwable instanceof NotFoundHttpException) {
            // 格式化输出
            $data = json_encode(
                [
                    'code' => ExceptionCode::HTTP['NOT_FOUND_HTTP']['code'],
                    'message' => $throwable->getMessage(),
                ],
                JSON_UNESCAPED_UNICODE
            );
            // 阻止异常冒泡
            $this->stopPropagation();
            return $response->withStatus(404)
                ->withAddedHeader('Content-Type', 'application/json')
                ->withBody(new SwooleStream($data));
        }
        if ($throwable instanceof MethodNotAllowedHttpException) {
            // 格式化输出
            $data = json_encode(
                [
                    'code' => ExceptionCode::HTTP['METHOD_NOT_ALLOWED']['code'],
                    'message' => $throwable->getMessage(),
                ],
                JSON_UNESCAPED_UNICODE
            );
            // 阻止异常冒泡
            $this->stopPropagation();
            return $response->withStatus(405)
                ->withAddedHeader('Content-Type', 'application/json')
                ->withBody(new SwooleStream($data));
        }
    }

    public function isValid(Throwable $throwable): bool
    {
        return $throwable instanceof HttpException;
    }
}
