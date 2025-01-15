<?php

/**
 * 开启服务域
 * TIME : 2025/1/2
 */

namespace pages;

/**
 * @name Start
 * @author liulei 2149573631@qq.com
 * @extends \pages\config\ConnectServer 连接服务域
 */
class Start extends \pages\config\ConnectServer 
{
    /**
     * 开启功能服务域
     * @access public
     * @param string $_file 传入__FILE__即可。
     */
    public function __construct(string $_file)
    {
        $this->init($_file)->run();
    }
}