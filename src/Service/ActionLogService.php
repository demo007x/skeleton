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

use Bclfp\Skeleton\Model\ActionLog;

class ActionLogService
{
    /**
     * 保存用户的操作日志.
     *
     * @param array $log
     * @return
     */
    public static function saveLog(array $log)
    {
        return (new ActionLog())->create($log);
    }
}
