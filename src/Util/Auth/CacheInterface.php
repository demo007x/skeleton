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
namespace Bclfp\Skeleton\Util\Auth;

/**
 * token 存储接口类
 * Interface CacheInterface.
 */
interface CacheInterface
{
    /**
     * token 保存.
     * @param string $token token
     * @param int $expire token 过期时间戳
     * @return mixed
     */
    public function store(string $token, int $expire);

    /**
     * 清除token.
     * @return mixed
     */
    public function clean(string $token);

    public function verify(string $token): bool;
}
