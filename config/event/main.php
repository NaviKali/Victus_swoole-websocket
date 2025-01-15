<?php
/**
 * 配置事件
 * @author      LiuLei
 * @version     1.0
 * @link        https://wiki.swoole.com/zh-cn/#/websocket_server?id=%e4%ba%8b%e4%bb%b6
 * @date        2025/1/2
 */
namespace config\event;


class main extends \pages\api\ApiController
{
    public static function Open($server,$request)
    {
        $server->push($request->fd,"HelloWorld!");
    }
    public function Message($server, $frame)
    {
        $this->openSendError()->setApiName("api")->isOpenApi($server,$frame);
    }
}