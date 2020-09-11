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
namespace Bclfp\Skeleton\Controller\Backend;

use Bclfp\Skeleton\Controller\AbstractController;
use Bclfp\Skeleton\Middleware\AdminAuthMiddleware;
use Bclfp\Skeleton\Model\Menu;
use Bclfp\Skeleton\Request\MenuRequest;
use Bclfp\Skeleton\Service\MenuService;
use Bclfp\Skeleton\Util\ExceptionCode;
use Hyperf\HttpServer\Annotation\Controller;
use Hyperf\HttpServer\Annotation\DeleteMapping;
use Hyperf\HttpServer\Annotation\GetMapping;
use Hyperf\HttpServer\Annotation\Middleware;
use Hyperf\HttpServer\Annotation\Middlewares;
use Hyperf\HttpServer\Annotation\PostMapping;
use Hyperf\HttpServer\Annotation\PutMapping;

/**
 * Class MenuController.
 * @Controller(prefix="backend/menu")
 * @Middlewares({
 *     @Middleware(AdminAuthMiddleware::class),
 *     @Middleware(\Bclfp\Skeleton\Middleware\PermissionMiddleware::class)
 * })
 */
class MenuController extends AbstractController
{
    /**
     * 获取菜单列表.
     * @GetMapping(path="/backend/menus/tree", methods="get")
     * @return \Psr\Http\Message\ResponseInterface
     */
    public function trees()
    {
        try {
            $menus = Menu::query()->orderBy('sort')->get();
            $menus = trees($menus->toArray());
            return successResponse($menus);
        } catch (\Throwable $exception) {
            return errorResponse($exception);
        }
    }

    /**
     * 获取菜单列表.
     * @GetMapping(path="/backend/menus", methods="get")
     * @return \Psr\Http\Message\ResponseInterface
     */
    public function lists()
    {
        try {
            $admin = adminAuth()->user();
            $menuList = $admin->menus();
            // $menuList = [];
            // foreach ($admin->roles()->get() as $role) {
            //     // 获取用户所属角色的菜单权限
            //     $menus = $role->menus()->orderBy('sort')->get()->toArray();
            //     $menuList = array_merge($menuList, $menus);
            // }
            // // $menus = Menu::query()->orderBy('sort')->get();
            // // var_dump($menuList);
            return successResponse($menuList);
        } catch (\Throwable $exception) {
            return errorResponse($exception);
        }
    }

    /**
     * 获取某一个菜单的详情.
     *
     * @GetMapping(path="{id}")
     * @return \Psr\Http\Message\ResponseInterface
     */
    public function info(int $id)
    {
        try {
            $menu = Menu::query()->find($id);
            if (! $menu) {
                throw new \Exception(ExceptionCode::DATA_ERROR['NOT_FOUND']['message'], ExceptionCode::DATA_ERROR['NOT_FOUND']['code']);
            }
            return successResponse($menu);
        } catch (\Throwable $exception) {
            return errorResponse($exception);
        }
    }

    /**
     * 获取菜单以及子菜单.
     * @GetMapping(path="{id}/children")
     * @return \Psr\Http\Message\ResponseInterface
     */
    public function menuAndChildren(int $id)
    {
        try {
            $menu = Menu::query()->find($id);
            if (! $menu) {
                throw new \Exception(ExceptionCode::DATA_ERROR['NOT_FOUND']['message'], ExceptionCode::DATA_ERROR['NOT_FOUND']['code']);
            }
            $children = Menu::query()->where('pid', $id)->get();
            // todo

            return successResponse($menu);
        } catch (\Throwable $exception) {
            return errorResponse($exception);
        }
    }

    /**
     * 创建菜单.
     * @PostMapping(path="/backend/menu", methods="post")
     * @return \Psr\Http\Message\ResponseInterface
     */
    public function create(MenuRequest $request)
    {
        try {
            $data = $request->all();
            // $data['name'] = ucfirst($data['name']);
            $data['path'] = strtolower($data['path']);
            if ($data['pid'] != 0) {
                if (! Menu::query()->where('id', $data['pid'])->exists()) {
                    throw new \Exception('未找到父级菜单', ExceptionCode::DATA_ERROR['NOT_FOUND']['code']);
                }
            }
            $menu = Menu::create($data);
            return successResponse($menu);
        } catch (\Throwable $exception) {
            return errorResponse($exception);
        }
    }

    /**
     * 更新菜单.
     * @PutMapping(path="{id}")
     * @return \Psr\Http\Message\ResponseInterface
     */
    public function update(MenuRequest $request, int $id)
    {
        try {
            $data = $request->all();
            $menu = Menu::query()->find($data['id']);
            if (! $menu) {
                throw new \Exception(ExceptionCode::DATA_ERROR['NOT_FOUND']['message'], ExceptionCode::DATA_ERROR['NOT_FOUND']['code']);
            }

            if ($data['pid'] != 0) {
                if (! Menu::query()->where('id', $data['pid'])->exists()) {
                    throw new \Exception('未找到父级菜单', ExceptionCode::DATA_ERROR['NOT_FOUND']['code']);
                }

                if ($data['id'] == $data['pid']) {
                    throw new \Exception('pid 和 id 不能相同', ExceptionCode::DATA_ERROR['NOT_FOUND']['code']);
                }
            }

            if ($id != $data['id']) {
                throw new \Exception('数据错误', ExceptionCode::DATA_ERROR['NOT_FOUND']['code']);
            }

            $menu->fill($data)->save();
            // 更新成功后返回菜单树
            return successResponse([
                'info' => $menu,
                'tree' => trees(Menu::query()->orderBy('sort')->get()->toArray()),
            ]);
        } catch (\Throwable $exception) {
            return errorResponse($exception);
        }
    }

    /**
     * @DeleteMapping(path="{id}")
     * @return \Psr\Http\Message\ResponseInterface
     */
    public function destroy(int $id)
    {
        try {
            $result = MenuService::destroyMenu($id);
            return successResponse($result);
        } catch (\Throwable $exception) {
            return errorResponse($exception);
        }
    }
}
