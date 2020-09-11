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

class MenuRequest extends FormRequest
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
            'pid' => 'required',
            'name' => [
                'required',
                'alpha',
                'min:1',
                'max:100',
                Rule::unique('menus')->ignore($this->route('id')),
            ],
            'title' => 'required|min:1|max:100',
            'path' => 'required|alpha_dash',
        ];

        if ($this->post('pid') != '') {
            $rules['component'] = 'required';
        }

        if ($this->getMethod() == 'PUT') {
            $rules['id'] = 'required';
        }

        return $rules;
    }

    public function messages(): array
    {
        $messages = [
            'id.*' => 'id 不能为空',
            'name.required' => 'name不能为空',
            'name.alpha' => 'name 必须为字符',
            'name.min' => 'name 长度不能小于1',
            'name.max' => 'name 长度不能大于100',
            'name.unique' => 'name 已存在',
            'title.required' => 'title 必填',
            'title.min' => 'title 长度不能小于1',
            'title.max' => 'title 长度不能大于100',
            'path.required' => 'path 不能为空',
            'path.alpha_dash' => 'path只能为字符、数字、下划线',
        ];

        if ($this->post('pid') != '') {
            $messages['component.*'] = '组件地址不能为空';
        }

        return $messages;
    }
}
