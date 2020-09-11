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
namespace Bclfp\Skeleton\Request;

use Hyperf\Validation\Request\FormRequest;
use Hyperf\Validation\Rule;

class RoleRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     */
    public function rules(): array
    {
        $rules = [
            'role_key' => [
                'required',
                'alpha',
                Rule::unique('roles')->ignore($this->route('id')),
            ],
            'name' => [
                'required',
                'alpha',
                Rule::unique('roles')->ignore($this->route('id')),
            ],
        ];
        if ($this->getMethod() == 'PUT') {
            $rules['id'] = 'required';
        }

        return $rules;
    }

    public function messages(): array
    {
        $message = [
            'role_key.required' => '角色key不能为空',
            'role_key.unique' => '角色key:' . $this->post('name') . '已存在',
            'role_key.alpha' => '角色key必须为字母',
            'name.required' => '角色名称不能为空',
            'name.unique' => '角色名称' . $this->post('name') . '已存在',
            'name.alpha' => '角色名称必须为字母',
        ];

        if ($this->getMethod() == 'PUT') {
            $message['id.required'] = 'id 不能为空';
        }

        return $message;
    }
}
