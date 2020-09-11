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

use Hyperf\ExceptionHandler\ExceptionHandler;
use Hyperf\HttpMessage\Stream\SwooleStream;
use Hyperf\Validation\ValidationException;
use Psr\Http\Message\ResponseInterface;
use Throwable;

class CustomValidationExceptionHandler extends ExceptionHandler
{
    public function handle(Throwable $throwable, ResponseInterface $response)
    {
        if ($throwable instanceof ValidationException) {
            // 格式化输出
            $data = json_encode([
                'code' => $throwable->status,
                'message' => $throwable->validator->errors()->first(),
            ], JSON_UNESCAPED_UNICODE);
            // 阻止异常冒泡
            $this->stopPropagation();
            return $response->withStatus(200)
                ->withAddedHeader('Content-Type', 'application/json')
                ->withBody(new SwooleStream($data));
        }

        return $response;
    }

    public function isValid(Throwable $throwable): bool
    {
        return $throwable instanceof ValidationException;
    }
}
