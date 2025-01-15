<?php

/**
 * 连接服务域
 * TIME : 2025/1/2
 */
namespace pages\config;

use pages\Define;
use pages\Event;
use pages\DConst;

/**
 * @name ConnectServer
 * @abstract
 * @author liulei 2149573631@qq.com
 */
abstract class ConnectServer
{
    /**
     * 文件名称
     * @var string
     */
    protected string $fileName;

    /**
     * 拆分__FILE__
     * @var array
     */
    protected array $parm;

    /**
     * 服务对象
     * @var mixed
     */
    protected mixed $server;
    /**
     * 反射对象
     * @var object
     */
    protected object $reflection;
    /**
     * 初始化服务域
     * @fianl
     * @access protected
     * @name init
     * @param string $_file __FILE__
     * @return void
     */
    final protected function init(string $_file):self
    {
        $this->parm = explode(separator: "/", string: $_file);

        $this->fileName = $this->ReadFileName();
        $this->getReflection();

        /**
         * 处理化服务
         */
        $this->server = (new Define)->initServer(_file: $_file);
        /**
         * 捆绑事件
         */
        (new Event)->bindEvent(server: $this->server,reflection: $this->reflection);


        return $this;
    }
    /**
     * 运行
     * @final
     * @access protected
     * @name run
     * @return void
     */
    final protected function run():void
    {
        $this->server->start();
    }
    /**
     * 读取文件名称
     * @access private
     * @name ReadFileName
     * @return string
     */
    private function ReadFileName(): string
    {
        return $this->parm[count(value: $this->parm) - 1];
    }
    /**
     * 获取反射
     * @access private
     * @return void
     */
    private function getReflection():void
    {
        $name = str_replace(search: ".php",replace: "", subject: $this->fileName);
        $str = "\\".str_replace(search: "/",replace: "\\",subject: DConst::VICTUS_EVENT_DIR)."\\$name";
        $obj = new $str();
        $this->reflection = new \ReflectionClass(objectOrClass: $obj);
    }

}