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

class PermissionRequest extends FormRequest
{
    const METHODS = [
        'GET',
        'POST',
        'PUT',
        'DELETE',
        'OPTIONS',
    ];

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
            'name' => [
                'required',
                'max:100',
                Rule::unique('permissions')->ignore($this->route('id')),
            ],
        ];

        if ($this->post('pid', 0) != 0) {
            $rules = array_merge(
                $rules,
                [
                    'description' => 'max:100',
                    'controller' => 'required|max:100',
                    'action' => 'required|max:100',
                    'http_method' => [
                        'required',
                        Rule::in(self::METHODS),
                    ],
                ]
            );
        }

        return $rules;
    }

    public function messages(): array
    {
        return [
            'name.required' => '权限名称必填',
            'name.unique' => '权限名称已存在',
            'name.max' => '权限名称长度超过100个字符',
            'description.*' => '描述长度超过100个字符',
            'controller.*' => 'controller 必填',
            'action.*' => 'action 必填',
            'http_method.*' => 'method 必填',
        ];
    }
}
