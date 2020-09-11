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

use Bclfp\Skeleton\Model\Administrator;
use Bclfp\Skeleton\Model\AdministratorRole;
use Bclfp\Skeleton\Model\Role;
use Hyperf\DbConnection\Db;
use Hyperf\HttpServer\Contract\RequestInterface;

class AdministratorService
{
    /**
     * 更新 Administrator.
     *
     * @throws \Exception
     * @return null|\Hyperf\Database\Model\Builder|\Hyperf\Database\Model\Model|object
     */
    public function updateAdministratorById(int $id, array $data)
    {
        try {
            $administrator = Administrator::query()->find($id);
            if (! $administrator) {
                throw new \Exception('未找到数据');
            }

            if (isset($data['email']) && trim($data['email']) != '') {
                // 查找 email 是否已被其他人员暂用
                $emailAdmin = Administrator::query()->where('email', $data['email'])->where('id', '!=', $id)->first();
                if ($emailAdmin) {
                    throw new \Exception('此邮箱已被其他管理员占用, 请更换其他邮箱');
                }
            }
            DB::beginTransaction();
            $administrator->update($data);

            if (isset($data['roles_id'])) {
                $this->setAdminRole($id, $data['roles_id']);
            }
            DB::commit();
            $administrator->roles;
            return  $administrator;
        } catch (\Throwable $exception) {
            Db::rollBack();
            throw $exception;
        }
    }

    /**
     * 创建管理员.
     *
     * @throws \Throwable
     * @return mixed
     */
    public function createAdministrator(RequestInterface $request)
    {
        try {
            $data = [
                'name' => $request->post('name'),
                'email' => $request->post('email'),
                'password' => password_hash($request->post('password'), PASSWORD_DEFAULT),
                'status' => Administrator::NORMAL_STATUS,
                'mobile' => $request->post('mobile'),
                'gender' => $request->post('gender', 0),
                'introduction' => $request->post('introduction', ''),
            ];
            // 查找是否存在邮箱的 admin
            $result = Administrator::query()->where('email', $data['email'])->first();
            if ($result) {
                throw new \Exception('此邮箱已被占用');
            }
            DB::beginTransaction();
            // 添加管理员
            $administrator = Administrator::create($data);
            // 设置用户角色
            $roles_id = $request->post('roles_id', []);
            if (count($roles_id) > 0) {
                $this->setAdminRole($administrator->id, array_values($roles_id));
            }
            DB::commit();
            return $administrator;
        } catch (\Throwable $exception) {
            DB::rollBack();
            throw $exception;
        }
    }

    /**
     * 设置管理员的角色.
     *
     * @throws \Exception
     * @return bool
     */
    public function setAdminRole(int $administrator_id, array $roles_id)
    {
        $admin = Administrator::query()->find($administrator_id);
        if (! $admin) {
            throw new \Exception('未找到管理员用户');
        }

        // 先删除用户的所有角色
        AdministratorRole::query()
            ->where('administrator_id', $administrator_id)
            ->delete();

        $roles = Role::query()->find($roles_id);
        if ($roles->count() == 0) {
            return false;
        }
        $inserts = array_map(function ($role_id) use ($administrator_id) {
            return [
                'administrator_id' => $administrator_id,
                'role_id' => $role_id,
            ];
        }, $roles_id);
        return AdministratorRole::insert($inserts);
    }
}
