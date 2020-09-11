<?php

declare(strict_types=1);

namespace Bclfp\Skeleton\Event;


use Bclfp\Skeleton\Model\Administrator;
use Hyperf\HttpServer\Contract\RequestInterface;

class ActionLog
{
    public $request;
    public $controller;
    public $action;
    public $auth = null;


    public function __construct(RequestInterface $request, Administrator $auth, string $controller, $action)
    {
        $this->request = $request;
        $this->auth = $auth;
        $this->controller = $controller;
        $this->action = $action;
    }
}