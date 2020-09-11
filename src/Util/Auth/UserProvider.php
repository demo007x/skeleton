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

class UserProvider
{
    /**
     * @var array
     */
    protected $config;

    /**
     * @var string
     */
    protected $name;

    /**
     * UserProvider constructor.
     */
    public function __construct(array $config, string $name)
    {
        $this->config = $config;
        $this->name = $name;
    }

    /**
     * 获取 auth 实例.
     * @param $credentials
     * @return mixed
     */
    public function retrieveByCredentials($credentials)
    {
        return call_user_func_array([$this->config['model'], 'retrieveById'], [$credentials]);
    }

    /**
     * 验证
     * @param $credentials
     */
    public function validateCredentials(Authenticatable $user, $credentials): bool
    {
        return $user->getId() === $credentials;
    }
}
