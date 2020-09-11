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
namespace Bclfp\Skeleton\Model;

use Bclfp\Skeleton\Util\Auth\CacheInterface;
use Carbon\Carbon;
use Hyperf\DbConnection\Model\Model;

/**
 * @property int $id
 * @property string $token
 * @property int $expire
 */
class Token extends Model implements CacheInterface
{
    public $timestamps = false;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'tokens';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['id', 'token', 'expire'];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = ['id' => 'integer', 'expire' => 'integer'];

    /**
     * 保存 token.
     * @param string $key token
     * @param int $expire token 过期时间戳
     * @throws \Exception
     * @return mixed|void
     */
    public function store(string $key, int $expire): bool
    {
        $token = self::query()->where('token', $key)->first();
        if ($token && $token->expire_date != null && $token->expire_date < Carbon::now()->timestamp) {
            // 当前的token 已过期, 则删除
            $token->delete();
        }

        // 保存新的token
        $result = (new self())->create(
            [
                'token' => $key,
                'expire' => $expire,
            ]
        );

        return (bool) $result;
    }

    /**
     * 删除token.
     * @throws \Exception
     * @return mixed|void
     */
    public function clean(string $token)
    {
        $token = self::query()->where('token', $token)->first();

        return $token && $token->delete();
    }

    /**
     * 验证token.
     */
    public function verify(string $token): bool
    {
        $token = self::query()->where('token', $token)->first();

        if (! $token) {
            return false;
        }

        if ($token->expire < Carbon::now()->timestamp) {
            $token->delete();
            return false;
        }

        return true;
    }
}
