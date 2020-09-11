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
namespace Bclfp\Skeleton\Util;

class ExceptionCode
{
    /** 成功的相应码 */
    const SUCCESS = 0;

    // 定义 auth 异常
    const AUTH = [
        'NOT_LOGIN' => [
            'code' => 401,
            'message' => '未登录',
        ],
    ];

    // token 异常code
    const TOKEN = [
        'SIGNATURE_INVALID' => [
            'code' => 4101,
            'message' => '无效的 token',
        ],
        'EXPIRED' => [
            'code' => 4102,
            'message' => 'token 过期',
        ],
    ];

    /** http 异常 */
    const HTTP = [
        'NOT_FOUND_HTTP' => [
            'code' => 404,
            'message' => '未找到资源',
        ],
        'METHOD_NOT_ALLOWED' => [
            'code' => 405,
            'message' => 'The Http Method Not Allowed',
        ],
        'FORBIDDEN' => [
            'code' => 403,
            'message' => '没有访问权限',
        ],
    ];

    /** 验证参数是否有效 */
    const VALIDATE = [
        'INVALID' => [
            'code' => 5001,
            'message' => '无效的参数',
        ],
    ];

    /** 未找到数据 */
    const DATA_ERROR = [
        'NOT_FOUND' => [
            'code' => 4404,
            'message' => '未找到数据',
        ],
    ];
}
