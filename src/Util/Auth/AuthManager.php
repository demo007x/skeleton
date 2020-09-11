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

use Hyperf\Contract\ConfigInterface;

class AuthManager
{
    /**
     * 默认的 guard jwt.
     * @var string
     */
    protected $defaultDriver = 'jwt';

    /**
     * 默认的 provider.
     * @var string
     */
    protected $defaultProvider = 'users';

    /**
     * 默认的guard.
     * @var string
     */
    protected $guard = 'default';

    /**
     * @var array
     */
    protected $providers = [];

    /**
     * @var ConfigInterface
     */
    protected $config;

    public function __construct(ConfigInterface $config)
    {
        $this->config = $config->get('auth');
    }

    /**
     * 返回一个 auth 处理器.
     * @throws \Exception
     */
    public function guard(?string $name = null, ?string $provider = null): AuthGuard
    {
        $guard = $name ?? $this->defaultGuard();
        if (! isset($this->config['guards'][$guard])) {
            throw new \Exception("Does not support this driver: {$guard}");
        }
        $guardConfig = $this->config['guards'][$guard];

        if (! isset($this->config['providers'][$this->defaultProvider()])) {
            throw new \Exception("Does not support this driver: {$guardConfig['provider']}");
        }

        $provider = $provider ?? $this->defaultProvider();
        $userProvider = $this->provider($provider);

        return make(
            $guardConfig['driver'],
            [
                'name' => $guard,
                'config' => $guardConfig,
                'userProvider' => $userProvider,
                'cache' => new $guardConfig['cache'](),
            ]
        );
    }

    /**
     * @throws \Exception
     */
    public function provider(?string $name = null): UserProvider
    {
        if (empty($this->config['providers'][$name])) {
            throw new \Exception("Does not support this provider: {$name}");
        }

        $config = $this->config['providers'][$name];

        return $this->providers[$name] ?? $this->providers[$name] = make(
            $config['driver'],
            [
                'config' => $config,
                'name' => $name,
            ]
        );
    }

    /**
     * 获取默认的 guard.
     */
    public function defaultGuard(): string
    {
        return $this->config['default']['guard'] ?? $this->defaultDriver;
    }

    /**
     * 获取默认的 provider.
     */
    public function defaultProvider(): string
    {
        return $this->config['default']['provider'] ?? $this->defaultProvider;
    }
}
