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
namespace Bclfp\Skeleton\Service;

use Bclfp\Skeleton\Model\Role;
use Bclfp\Skeleton\Model\RoleMenu;
use Bclfp\Skeleton\Model\RolePermission;
use Bclfp\Skeleton\Util\ExceptionCode;
use Hyperf\DbConnection\Db;

class RoleService
{
    /**
     * 创建角色.
     * @throws \Throwable
     * @return mixed
     */
    public static function createRole(array $data)
    {
        try {
            Db::beginTransaction();
            $role = Role::create($data);
            if (isset($data['menus_id']) && count($data['menus_id']) > 0) {
                $rolesMenus = [];
                foreach (array_values($data['menus_id']) as $menu_id) {
                    $rolesMenus[] = [
                        'role_id' => $role->id,
                        'menu_id' => $menu_id,
                    ];
                }
                RoleMenu::inster($rolesMenus);
            }
            if (isset($data['permission_ids']) && count($data['permission_ids']) > 0) {
                $rolesPermissions = [];
                foreach (array_values($data['permission_ids']) as $permissions_id) {
                    $rolesPermissions[] = [
                        'role_id' => $role->id,
                        'permission_id' => $permissions_id,
                    ];
                }
                RolePermission::insert($rolesPermissions);
            }
            Db::commit();
            return $role;
        } catch (\Throwable $exception) {
            Db::rollBack();
            throw $exception;
        }
    }

    /**
     * 更新角色以及角色菜单.
     * @throws \Throwable
     */
    public static function updateRole(int $id, array $data)
    {
        try {
            Db::beginTransaction();
            $role = Role::query()->find($id);
            if (! $role) {
                throw new \Exception('未找到角色数据', ExceptionCode::DATA_ERROR['NOT_FOUND']['code']);
            }
            $role->fill($data)->update();
            RoleMenu::query()->where('role_id', $role->id)->delete();
            if (isset($data['menus_id']) && count($data['menus_id']) > 0) {
                $rolesMenus = [];
                foreach ($data['menus_id'] as $menu_id) {
                    $rolesMenus[] = [
                        'role_id' => $role->id,
                        'menu_id' => $menu_id,
                    ];
                }
                RoleMenu::insert($rolesMenus);
            }
            RolePermission::query()->where('role_id', $role->id)->delete();
            if (isset($data['permission_ids']) && count($data['permission_ids']) > 0) {
                $rolesPermissions = [];
                foreach (array_values($data['permission_ids']) as $permissions_id) {
                    $rolesPermissions[] = [
                        'role_id' => $role->id,
                        'permission_id' => $permissions_id,
                    ];
                }
                RolePermission::insert($rolesPermissions);
            }
            Db::commit();
            $role->menus;
            $role->permissions;
            return $role;
        } catch (\Throwable $exception) {
            Db::rollBack();
            throw $exception;
        }
    }
}
