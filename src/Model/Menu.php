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

use Hyperf\DbConnection\Model\Model;

/**
 * @property int $id
 * @property int $pid
 * @property string $name
 * @property string $title
 * @property string $icon
 * @property string $path
 * @property string $component
 * @property int $is_cache
 * @property int $sort
 * @property \Carbon\Carbon $created_at
 * @property \Carbon\Carbon $updated_at
 * @property string $deleted_at
 */
class Menu extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'menus';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['id', 'pid', 'name', 'title', 'icon', 'path', 'component', 'is_cache', 'sort', 'created_at', 'updated_at', 'deleted_at'];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = ['id' => 'integer', 'pid' => 'integer', 'sort' => 'integer', 'is_cache' => 'integer', 'created_at' => 'datetime', 'updated_at' => 'datetime'];

    /**
     * 设置name 属性.
     * @param $value
     */
    public function setNameAttribute($value)
    {
        $this->attributes['name'] = ucfirst($value);
    }
}
