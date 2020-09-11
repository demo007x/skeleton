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
 * @property string $name
 * @property string $role_key
 * @property string $description
 * @property \Carbon\Carbon $created_at
 * @property \Carbon\Carbon $updated_at
 * @property string $deleted_at
 */
class Role extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'roles';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'role_key', 'name', 'description',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = ['id' => 'integer', 'role_key' => 'string', 'name' => 'string', 'created_at' => 'datetime', 'updated_at' => 'datetime'];

    /**
     * 角色中的管理员.
     * @return \Hyperf\Database\Model\Relations\BelongsToMany
     */
    public function administrators()
    {
        return $this->belongsToMany(Administrator::class, 'role_id', 'administrator_role', 'administrator_id');
    }

    /**
     * 角色拥有的菜单.
     * @return \Hyperf\Database\Model\Relations\BelongsToMany
     */
    public function menus()
    {
        return $this->belongsToMany(Menu::class, 'role_menu', 'role_id', 'menu_id');
    }

    public function permissions()
    {
        return $this->belongsToMany(Permission::class, 'role_permission', 'role_id', 'permission_id');
    }
}
