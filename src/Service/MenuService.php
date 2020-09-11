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

use Bclfp\Skeleton\Model\Menu;
use Bclfp\Skeleton\Util\ExceptionCode;

class MenuService
{
    /**
     * 删除菜单.
     * @throws \Exception
     * @return null|bool|int|mixed
     */
    public static function destroyMenu(int $id)
    {
        $menu = Menu::query()->find($id);
        if (! $menu) {
            throw new \Exception(ExceptionCode::DATA_ERROR['NOT_FOUND']['message'], ExceptionCode::DATA_ERROR['NOT_FOUND']['code']);
        }
        // 查找次菜单下面是否还有其他子的菜单
        $childMenu = Menu::query()->where('pid', $menu->id)->get();
        if ($childMenu->count() > 0) {
            throw new \Exception('此菜单下面还有未删除的子菜单，请先删除子菜单');
        }
        return $menu->delete();
    }
}
