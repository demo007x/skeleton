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

class AuthRequest extends FormRequest
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
        return [
            'email' => 'required|email',
            'password' => 'required|min:6|alpha_dash',
        ];
    }

    public function messages(): array
    {
        return [
            'email.*' => '无效的邮箱',
            'password.required' => '密码不能为空',
            'password.min' => '密码的长度不能小于 6 位',
            'password' => '密码中存在无效的字符',
        ];
    }
}
