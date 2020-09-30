<?php

declare(strict_types=1);

namespace Bclfp\Skeleton\Request;

use Hyperf\Validation\Request\FormRequest;

class CarouselRequest extends FormRequest
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
            'img_url' => 'required',
            'url' => 'required'
        ];
    }

    public function messages(): array
    {
        return [
            'img_url.*' => '图片地址不能为空',
            'url.*' => '图片链接不能为空'
        ];
    }
}
