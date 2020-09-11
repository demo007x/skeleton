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

use Bclfp\Skeleton\Model\Permission;

class PermissionService
{
    /**
     * 添加权限.
     */
    public static function createPermission(array $data)
    {
        try {
            if (! isset($data['pid']) || $data['pid'] == 0) {
                // 添加的是权限组名称
                return Permission::create($data);
            }
            $permissions = Permission::query()->where('controller', $data['controller'])
                ->where('action', $data['action'])
                ->where('http_method', $data['http_method'])
                ->get();
            if ($permissions->count() > 0) {
                throw new \Exception('已存在此权限');
            }
            // 保存
            return Permission::create($data);
        } catch (\Throwable $exception) {
            throw $exception;
        }
    }
}
