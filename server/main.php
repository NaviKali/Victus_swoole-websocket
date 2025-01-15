<?php
/**
 * WebSocket开启监听
 * @version 1.0
 * @author LiuLei
 * todo 开启一个WebSocket服务端监听
 */
namespace server;

require_once "../vendor/autoload.php";

use pages\Start;

(new Start(__FILE__));