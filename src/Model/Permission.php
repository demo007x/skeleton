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
 * @property string $description
 * @property string $controller
 * @property string $action
 * @property string $http_method
 * @property \Carbon\Carbon $created_at
 * @property \Carbon\Carbon $updated_at
 * @property string $deleted_at
 */
class Permission extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'permissions';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['id', 'pid', 'name', 'description', 'controller', 'action', 'http_method', 'created_at', 'updated_at', 'deleted_at'];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = ['id' => 'integer', 'pid' => 'integer', 'created_at' => 'datetime', 'updated_at' => 'datetime'];

    public function getHttpMethodAttribute($value)
    {
        return strtoupper($value);
    }

    public function setControllerAttribute($val)
    {
        $this->attributes['controller'] = ucfirst($val);
    }
}
