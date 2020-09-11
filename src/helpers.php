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
if (!function_exists('successResponse')) {
    /**
     * 成功的响应.
     *
     * @param array $data
     * @return \Psr\Http\Message\ResponseInterface
     */
    function successResponse($data = [])
    {
        $response = \Hyperf\Utils\Context::get(\Psr\Http\Message\ResponseInterface::class);
        $responseData = json_encode(
            [
                'code' => Bclfp\Skeleton\Util\ExceptionCode::SUCCESS,
                'data' => $data,
            ],
            JSON_UNESCAPED_UNICODE
        );

        return $response->withStatus(200)
            ->withHeader('Content-Type', 'application/json')
            ->withBody(new \Hyperf\HttpMessage\Stream\SwooleStream($responseData));
    }
}

if (!function_exists('errorResponse')) {
    /**
     * 错误响应.
     *
     * @return \Psr\Http\Message\ResponseInterface
     */
    function errorResponse(Throwable $throwable)
    {
        $code = $throwable->getCode() == 0 ? 5000 : $throwable->getCode();
        $message = $throwable->getMessage();
        if ($throwable instanceof \Firebase\JWT\ExpiredException) {
            // token 过期异常
            $code = Bclfp\Skeleton\Util\ExceptionCode::TOKEN['EXPIRED']['code'];
            $message = Bclfp\Skeleton\Util\ExceptionCode::TOKEN['EXPIRED']['message'];
        }

        if ($throwable instanceof \Firebase\JWT\SignatureInvalidException) {
            // token 签名无效
            $code = Bclfp\Skeleton\Util\ExceptionCode::TOKEN['SIGNATURE_INVALID']['code'];
            $message = Bclfp\Skeleton\Util\ExceptionCode::TOKEN['SIGNATURE_INVALID']['message'];
        }

        $responseData = json_encode(
            [
                'code'    => $code,
                'message' => $message,
            ],
            JSON_UNESCAPED_UNICODE
        );
        $response = \Hyperf\Utils\Context::get(\Psr\Http\Message\ResponseInterface::class);

        return $response->withStatus(200)
            ->withHeader('Content-Type', 'application/json')
            ->withBody(new \Hyperf\HttpMessage\Stream\SwooleStream($responseData));
    }
}

if (!function_exists('auth')) {
    /**
     * 建议视图中使用该函数，其他地方请使用注入.
     * @return
     */
    function auth(?string $guard = null, ?string $provider = null): Bclfp\Skeleton\Util\Auth\AuthGuard
    {
        $auth = \Hyperf\Utils\ApplicationContext::getContainer()->get(Bclfp\Skeleton\Util\Auth\AuthManager::class);

        $guard = $guard ?? 'jwt';
        $provider = $provider ?? 'users';
        return $auth->guard($guard, $provider);
    }
}

if (!function_exists('adminAuth')) {
    /**
     * admin 授权.
     * @return Bclfp\Skeleton\Util\Auth\AuthGuard
     */
    function adminAuth(): Bclfp\Skeleton\Util\Auth\AuthGuard
    {
        return auth('jwt', 'admin');
    }
}

if (!function_exists('trees')) {
    /**
     * 将菜单遍历为树形式.
     * @param int $pid
     */
    function trees(array $lists, $pid = 0): array
    {
        $arr = [];
        foreach ($lists as $list) {
            if ($pid == $list['pid']) {
                $child = trees($lists, $list['id']);
                if (!empty($child)) {
                    $list['children'] = $child;
                }
                $arr[] = $list;
            }
        }
        return $arr;
    }
}

/**
 * 获取浏览器类型.
 */
function getBrowserInfo(string $userAgent): string
{
    if (stripos($userAgent, 'Firefox/') > 0) {
        preg_match('/Firefox\\/([^;)]+)+/i', $userAgent, $b);
        $exp[0] = 'Firefox';
        $exp[1] = $b[1];    //获取火狐浏览器的版本号
    } elseif (stripos($userAgent, 'Maxthon') > 0) {
        preg_match('/Maxthon\\/([\\d.]+)/', $userAgent, $aoyou);
        $exp[0] = '傲游';
        $exp[1] = $aoyou[1];
    } elseif (stripos($userAgent, 'MSIE') > 0) {
        preg_match('/MSIE\\s+([^;)]+)+/i', $userAgent, $ie);
        $exp[0] = 'IE';
        $exp[1] = $ie[1];  //获取IE的版本号
    } elseif (stripos($userAgent, 'OPR') > 0) {
        preg_match('/OPR\\/([\\d.]+)/', $userAgent, $opera);
        $exp[0] = 'Opera';
        $exp[1] = $opera[1];
    } elseif (stripos($userAgent, 'Edge') > 0) {
        //win10 Edge浏览器 添加了chrome内核标记 在判断Chrome之前匹配
        preg_match('/Edge\\/([\\d.]+)/', $userAgent, $Edge);
        $exp[0] = 'Edge';
        $exp[1] = $Edge[1];
    } elseif (stripos($userAgent, 'Chrome') > 0) {
        preg_match('/Chrome\\/([\\d.]+)/', $userAgent, $google);
        $exp[0] = 'Chrome';
        $exp[1] = $google[1];  //获取google chrome的版本号
    } elseif (stripos($userAgent, 'rv:') > 0 && stripos($userAgent, 'Gecko') > 0) {
        preg_match('/rv:([\\d.]+)/', $userAgent, $IE);
        $exp[0] = 'IE';
        $exp[1] = $IE[1];
    } else {
        $exp[0] = '未知浏览器';
        $exp[1] = '';
    }
    return $exp[0] . '(' . $exp[1] . ')';
}

/**
 * 获取操作系统类型.
 */
function getOs(string $userAgent): string
{
    $os = '';
    if (preg_match('/win/i', $userAgent) && preg_match('/nt 10.0/i', $userAgent)) {
        $os = 'Windows 10'; #添加win10判断
    } elseif (preg_match('/win/i', $userAgent) && preg_match('/nt 5/i', $userAgent)) {
        $os = 'Windows 2000';
    } elseif (preg_match('/win/i', $userAgent) && preg_match('/nt/i', $userAgent)) {
        $os = 'Windows NT';
    } elseif (preg_match('/linux/i', $userAgent)) {
        $os = 'Linux';
    } elseif (preg_match('/unix/i', $userAgent)) {
        $os = 'Unix';
    } elseif (preg_match('/sun/i', $userAgent) && preg_match('/os/i', $userAgent)) {
        $os = 'SunOS';
    } elseif (preg_match('/ibm/i', $userAgent) && preg_match('/os/i', $userAgent)) {
        $os = 'IBM OS/2';
    } elseif (preg_match('/Mac/i', $userAgent) && preg_match('/Macintosh/i', $userAgent)) {
        $os = 'Mac';
    } elseif (preg_match('/PowerPC/i', $userAgent)) {
        $os = 'PowerPC';
    } elseif (preg_match('/AIX/i', $userAgent)) {
        $os = 'AIX';
    } elseif (preg_match('/HPUX/i', $userAgent)) {
        $os = 'HPUX';
    } elseif (preg_match('/NetBSD/i', $userAgent)) {
        $os = 'NetBSD';
    } elseif (preg_match('/BSD/i', $userAgent)) {
        $os = 'BSD';
    } elseif (preg_match('/OSF1/i', $userAgent)) {
        $os = 'OSF1';
    } elseif (preg_match('/IRIX/i', $userAgent)) {
        $os = 'IRIX';
    } elseif (preg_match('/FreeBSD/i', $userAgent)) {
        $os = 'FreeBSD';
    } elseif (preg_match('/teleport/i', $userAgent)) {
        $os = 'teleport';
    } elseif (preg_match('/flashget/i', $userAgent)) {
        $os = 'flashget';
    } elseif (preg_match('/webzip/i', $userAgent)) {
        $os = 'webzip';
    } elseif (preg_match('/offline/i', $userAgent)) {
        $os = 'offline';
    } else {
        $os = '未知操作系统';
    }

    return $os;
}
