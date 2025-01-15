<?php

/**
 * 事件处理
 * TIME : 2025/1/2
 */

namespace pages;

use pages\DConst;

class Event
{
    /**
     * 事件列表
     * @var array
     */
    public array $eventList = [
        "BeforeHandshakeResponse",
        "HandShake",
        "Open",
        "Message",
        "oRequest",
        "Disconnect",
    ];
    /**
     * 捆绑事件
     * @access public
     * @param \Swoole\WebSocket\Server $server
     * @param object $reflection 
     * @return void
     */
    public function bindEvent(\Swoole\WebSocket\Server $server, object $reflection): void
    {
        $obj = "\\{$reflection->name}";

        if (!$reflection->hasMethod("Message")) {
            echo "请定义Message方法!\n在:{$reflection->name}.php文件中\n";
            die;
        }

        foreach ($this->eventList as $k => $v) {
            if ($reflection->hasMethod($v)) { {
                    $server->on($v, function ($server, $any) use ($reflection, $obj, $v) {
                        $reflection->getMethod($v)->invoke(new $obj(), $server, $any);
                    });
                }
            }
        }
    }
}