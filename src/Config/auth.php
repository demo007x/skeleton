<?php

declare(strict_types=1);

// use Qbhy\SimpleJwt\Encoders;
// use Qbhy\SimpleJwt\EncryptAdapters as Encrypter;

return [
    'default' => [
        'guard' => 'jwt',
        'provider' => 'users',
    ],
    'guards' => [
        'jwt' => [
            'driver' => Bclfp\Skeleton\Util\Auth\JwtGuard::class,
            /*
            * 必填
            * jwt key
            */
            'secret' => env('SIMPLE_JWT_SECRET', '517af8cf3806acc4b0c846b7b69f7eac1ef7e38e'),

            /*
             * 可选配置
             * jwt 生命周期，单位秒
             */
            'ttl' => (int) env('SIMPLE_JWT_TTL', 60 * 60 * 12),

            /*
             * 可选配置
             * 允许过期多久以内的 token 进行刷新
             */
            'refresh_ttl' => (int) env('SIMPLE_JWT_REFRESH_TTL', 60 * 60 * 24 * 7),

            /*
             * 可选配置
             * 默认使用的加密类
             */
            // 'default' => Encrypter\PasswordHashEncrypter::class,

            /*
             * 可选配置
             * 缓存类
             */
            'cache' => Bclfp\Skeleton\Model\Token::class,
            // 如果需要分布式部署，请选择 redis 或者其他支持分布式的缓存驱动
            //            'cache' => function () {
            //                return make(\Qbhy\HyperfAuth\HyperfRedisCache::class);
            //            },

            /*
             * 可选配置
             * 缓存前缀
             */
            'prefix' => env('SIMPLE_JWT_PREFIX', 'default'),
        ],
    ],
    'providers' => [
        'admin' => [
            'driver' => Bclfp\Skeleton\Util\Auth\UserProvider::class,
            'model' => Bclfp\Skeleton\Model\Administrator::class,
        ],
        'users' => [
            'driver' => Bclfp\Skeleton\Util\Auth\UserProvider::class,
            'model' => Bclfp\Skeleton\Model\User::class,
        ],
    ],
];