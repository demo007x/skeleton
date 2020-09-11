<?php

declare(strict_types=1);

namespace Bclfp\Skeleton\Listener;

use Bclfp\Skeleton\Event\ActionLog;
use Bclfp\Skeleton\Service\ActionLogService;
use Hyperf\Event\Annotation\Listener;
use Psr\Container\ContainerInterface;
use Hyperf\Event\Contract\ListenerInterface;

/**
 * @Listener
 */
class ActionLogListener implements ListenerInterface
{
    /**
     * @var ContainerInterface
     */
    private $container;

    public function __construct(ContainerInterface $container)
    {
        $this->container = $container;
    }

    public function listen(): array
    {
        return [
            ActionLog::class
        ];
    }

    /**
     * @param ActionLog $event
     */
    public function process(object $event)
    {
        $services = $event->request->getServerParams();
        $userAgent = $event->request->getHeader('user-agent');
        $log = [
            'user_id' => $event->auth->id,
            'user_name' => $event->auth->name,
            'controller' => $event->controller,
            'action' => $event->action,
            'method' => $event->request->getMethod(),
            'data' => json_encode($event->request->all()),
            'url' => $event->request->getUri(),
            'browser' => getBrowserInfo($userAgent[0]),
            'os' => getOs($userAgent[0]),
            'ip' => $services['remote_addr'],
        ];

        ActionLogService::saveLog($log);
    }
}
