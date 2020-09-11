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

use Firebase\JWT\JWT;
use Hyperf\HttpServer\Contract\RequestInterface;
use Hyperf\Utils\Context;
use Hyperf\Utils\Str;

class JwtGuard implements AuthGuard
{
    /**
     * @var string
     */
    protected $name;

    /**
     * @var array
     */
    protected $config;

    /**
     * @var UserProvider
     */
    protected $userProvider;

    /**
     * @var RequestInterface
     */
    protected $request;

    /**
     * @var CacheInterface
     */
    protected $cache;

    /**
     * JwtGuard constructor.
     */
    public function __construct(string $name, array $config, UserProvider $userProvider, RequestInterface $request, CacheInterface $cache)
    {
        $this->name = $name;
        $this->config = $config;
        $this->userProvider = $userProvider;
        $this->request = $request;
        $this->cache = $cache;
    }

    /**
     * 登录用户 获取 token.
     */
    public function token(Authenticatable $user): string
    {
        $jwtKey = $this->config['secret'];
        $payload = [
            'iss' => env('APP_URL', null), // 签发人
            'aud' => '*',           // 受众
            'exp' => time() + $this->config['ttl'], // 过期时间
            'iat' => time(), // 签发时间
            'nbf' => time(),  // 生效时间,
            'uid' => $user->id,
        ];
        $token = \Firebase\JWT\JWT::encode($payload, $jwtKey);
        // 设置 content 的值
        $key = $this->resultKey($token);
        Context::set($key, $user);
        // 缓存保存token
        $this->cache->store($token, $payload['exp']);
        return $token;
    }

    /**
     * 获取 user.
     */
    public function user(?string $token = null): ?Authenticatable
    {
        $token = $token ?? $this->parseToken();
        // 验证缓存中的token
        $result = $this->cache->verify($token);
        if (! $result) {
            return null;
        }
        if (Context::has($key = $this->resultKey($token))) {
            return Context::get($key);
        }

        $jwt = JWT::decode($token, $this->config['secret'], ['HS256']);
        $uid = $jwt->uid;
        return $uid ? $this->userProvider->retrieveByCredentials($uid) : null;
    }

    /**
     * 检测 token.
     *
     * @throws \Throwable
     */
    public function check(?string $token = null): bool
    {
        try {
            // $token  = $token ?? $this->parseToken();
            // $jwt = JWT::decode($token, $this->config['secret'], ['HS256']);
            // // 检查过期时间
            // if ($jwt->exp <= time()) {
            //     return false;
            // }

            $user = $this->user();
            return $user instanceof Authenticatable;
        } catch (\Exception $exception) {
            return false;
        }
    }

    /**
     * @throws \Throwable
     */
    public function guest(?string $token = null): bool
    {
        return ! $this->check($token);
    }

    public function logout(?string $token = null)
    {
        $token = $token ?? $this->parseToken();
        $this->cache->clean($token);
        if ($token) {
            if (Context::has($this->resultKey($token))) {
                Context::destroy($this->resultKey($token));
                return true;
            }
            return false;
        }
        return false;
    }

    public function getProvider(): UserProvider
    {
        // TODO: Implement getProvider() method.

        return  $this->userProvider;
    }

    /**
     * 解析 token.
     * @return null|string
     */
    protected function parseToken()
    {
        $token = $this->request->header('Authorization', '');
        if (Str::startsWith($token, 'Bearer ')) {
            return  Str::substr($token, 7);
        }

        return $this->request->header('X-Token', null);
    }

    protected function resultKey($token)
    {
        return $this->name . '.auth.result.' . $token;
    }
}
