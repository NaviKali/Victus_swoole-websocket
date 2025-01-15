<?php

namespace pages;

use Swoole\WebSocket\Server as WSServer;

class Define
{
    /**
     * 读取定义服务文件 
     * @access public
     * @param string $_file
     * @return WSServer
     */
    public function initServer(string $_file):WSServer
    {
        $data = require str_replace("server", DConst::VICTUS_DEFINE_DIR, $_file);
        return new WSServer($data["host"], $data["port"]);
    }
}