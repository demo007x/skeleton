<?php

declare (strict_types=1);
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
 * @property int $user_id 
 * @property string $controller 
 * @property string $action 
 * @property string $method 
 * @property string $data 
 * @property string $url 
 * @property string $browser 
 * @property string $os 
 * @property string $ip 
 * @property \Carbon\Carbon $created_at 
 * @property \Carbon\Carbon $updated_at 
 * @property string $deleted_at 
 */
class ActionLog extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'action_logs';
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['id', 'user_id', 'user_name', 'controller', 'action', 'method', 'data', 'url', 'browser', 'os', 'ip', 'created_at', 'updated_at', 'deleted_at'];
    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = ['id' => 'integer', 'user_id' => 'integer', 'created_at' => 'datetime', 'updated_at' => 'datetime'];

    protected $hidden= ['data'];
}