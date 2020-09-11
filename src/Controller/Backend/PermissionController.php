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
use Bclfp\Skeleton\Model\Permission;
use Bclfp\Skeleton\Request\PermissionRequest;
use Bclfp\Skeleton\Service\PermissionService;
use Bclfp\Skeleton\Util\ExceptionCode;
use Hyperf\HttpServer\Annotation\Controller;
use Hyperf\HttpServer\Annotation\DeleteMapping;
use Hyperf\HttpServer\Annotation\GetMapping;
use Hyperf\HttpServer\Annotation\Middleware;
use Hyperf\HttpServer\Annotation\Middlewares;
use Hyperf\HttpServer\Annotation\PostMapping;
use Hyperf\HttpServer\Annotation\PutMapping;
use Hyperf\HttpServer\Contract\RequestInterface;
use Hyperf\HttpServer\Contract\ResponseInterface;

/**
 * Class PermissionController.
 * @Controller(prefix="backend/permission")
 * @Middlewares({
 *     @Middleware(AdminAuthMiddleware::class),
 *     @Middleware(\Bclfp\Skeleton\Middleware\PermissionMiddleware::class)
 * })
 */
class PermissionController extends AbstractController
{
    /**
     * 获取权限列表.
     *
     * @GetMapping(path="/backend/permissions")
     */
    public function list(RequestInterface $request, ResponseInterface $response)
    {
        try {
            $permissions = Permission::query()->orderByDesc('created_at')->get();
            return successResponse($permissions);
        } catch (\Throwable $exception) {
            return errorResponse($exception);
        }
    }

    /**
     * 获取权限树.
     *
     * @GetMapping(path="/backend/permissions/tree", methods="get")
     * @return \Psr\Http\Message\ResponseInterface
     */
    public function tree(RequestInterface $request, ResponseInterface $response)
    {
        try {
            $permissions = Permission::query()->orderByDesc('created_at')->get();
            $permissions = trees($permissions->toArray());
            return successResponse($permissions);
        } catch (\Throwable $exception) {
            return errorResponse($exception);
        }
    }

    /**
     * update permission.
     * @PostMapping(path="/backend/permission", methods="post")
     * @return \Psr\Http\Message\ResponseInterface
     */
    public function create(PermissionRequest $request)
    {
        try {
            $data = $request->all();
            // 查找此controller，action method 对应的权限是否存在
            $result = PermissionService::createPermission($data);
            return successResponse($result);
        } catch (\Throwable $exception) {
            return errorResponse($exception);
        }
    }

    /**
     * 更新权限.
     * @PutMapping(path="{id}", methods="put")
     * @return \Psr\Http\Message\ResponseInterface
     */
    public function update(PermissionRequest $request, int $id)
    {
        try {
            $data = $request->all();
            $permission = Permission::query()
                ->where('id', '!=', $id)
                ->when(isset($data['name']), function ($query) use ($data) {
                    return $query->where('name', $data['name']);
                })
                ->when(isset($data['controller']), function ($query) use ($data) {
                    return $query->where('controller', $data['controller']);
                })
                ->when(isset($data['action']), function ($query) use ($data) {
                    return $query->where('action', $data['action']);
                })
                ->when(isset($data['http_method']), function ($query) use ($data) {
                    return $query->where('http_method', $data['http_method']);
                })
                ->first();
            if ($permission) {
                throw new \Exception('已存在此权限');
            }
            $permission = Permission::query()->find($id);
            $permission->fill($data)->save();
            return successResponse($permission);
        } catch (\Throwable $exception) {
            return errorResponse($exception);
        }
    }

    /**
     * 删除权限.
     * @DeleteMapping(path="{id}")
     * @return \Psr\Http\Message\ResponseInterface
     */
    public function destroy(int $id)
    {
        try {
            $permission = Permission::query()->find($id);
            if (! $permission) {
                throw new \Exception(ExceptionCode::DATA_ERROR['NOT_FOUND']['message']);
            }
            // 如果权限是一个父级权限， 则删除的时候吧所有子类也一起删除
            if ($permission->pid == 0) {
                Permission::query()
                    ->where('pid', $permission->id)
                    ->delete();
            }
            return successResponse($permission->delete());
        } catch (\Throwable $exception) {
            return errorResponse($exception);
        }
    }
}
