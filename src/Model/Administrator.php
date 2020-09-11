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

use Bclfp\Skeleton\Util\Auth\Authenticatable;
use Hyperf\Database\Model\SoftDeletes;
use Hyperf\DbConnection\Model\Model;

/**
 * @property int $id
 * @property string $name
 * @property string $password
 * @property string $email
 * @property int $gender
 * @property int $status
 * @property string $avatar
 * @property int $ip
 * @property string $introduction
 * @property \Carbon\Carbon $login_at
 * @property \Carbon\Carbon $created_at
 * @property \Carbon\Carbon $updated_at
 * @property string $deleted_at
 */
class Administrator extends Model implements Authenticatable
{
    use SoftDeletes;

    // 封禁的用户状态
    const FORBID_STATUS = 0;

    // 正常用户的状态
    const NORMAL_STATUS = 1;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'administrators';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['name', 'password', 'email', 'mobile', 'gender', 'avatar', 'introduction', 'status', 'login_at'];

    protected $hidden = ['password'];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = ['id' => 'integer', 'gender' => 'integer', 'status' => 'integer', 'ip' => 'integer', 'login_at' => 'datetime', 'introduction', 'created_at' => 'datetime', 'updated_at' => 'datetime'];

    public static function retrieveById($key): ?Authenticatable
    {
        return (new self())->find($key);
    }

    public function getId()
    {
        // TODO: Implement getId() method.
        return $this->id;
    }

    /**
     * 用户的角色.
     * @return \Hyperf\Database\Model\Relations\BelongsToMany
     */
    public function roles()
    {
        return  $this->belongsToMany(Role::class, 'administrator_role', 'administrator_id', 'role_id');
    }

    /**
     * 获取用户的菜单列表.
     */
    public function menus(): array
    {
        $menuList = [];
        foreach ($this->roles()->get() as $role) {
            // 获取用户所属角色的菜单权限
            $menus = $role->menus()->orderBy('sort')->get();
            foreach ($menus as $menu) {
                if (! in_array($menu->id, $menuList)) {
                    $menuList[$menu->id] = $menu->toArray();
                }
            }
        }
        return array_values($menuList);
    }

    /**
     * 判断用户是否有某个路由的权限.
     */
    public function hasPermission(string $controller, string $action, string $method): bool
    {
        $roles = $this->roles()->get();
        foreach ($roles as $role) {
            if ($role->role_key === config('permission.admin_role_key', 'admin')) {
                return true;
            }
        }
        // 查找当前路由的权限
        $currentPermission = Permission::query()->where('controller', $controller)
            ->where('action', $action)
            ->where('http_method', $method)
            ->first();
        if (! $currentPermission) {
            // 如果当前的路由没有设置权限， 则通过
            return true;
        }

        $hasPermission = false;
        foreach ($roles as $role) {
            $permissions = $role->permissions()->get();
            foreach ($permissions as $permission) {
                if ($permission->id == $currentPermission->id) {
                    $hasPermission = true;
                    break;
                }
            }
        }

        return $hasPermission;
    }
}
