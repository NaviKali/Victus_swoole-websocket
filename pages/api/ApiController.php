<?php

/**
 * Api控制层
 * @author liulei
 */

namespace pages\api;


use pages\api\ApiConst;


/**
 * @method self setApiName(string $apiName)
 * @method void isOpenApi(object $server, object $frame, string $decode = DECODE_JSON)
 */
class ApiController
{
    /**
     * 子类类名
     * @var string
     */
    private string $son_Class;
    /**
     * 前端返回数据
     * @var string|array|object
     */
    public string|array|object $data;
    /**
     * 获取前端api
     * @var string
     */
    public string $api;
    /**
     * 前端api名称
     * @var string default:api
     */
    private string $apiName = "api";
    /**
     * Api层级片
     * @var array
     */
    public array $apiSlice = [];
    /**
     * Api层级
     * @var int default:0
     */
    public int $apiLevel = 0;
    /**
     * 解码列表
     * @var array
     */
    public array $encodeList = [
        "json",
        "base64",
    ];
    /**
     * 发送错误功能
     * @var bool default:false
     */
    public bool $sendError = false;
    /**
     * 错误信息
     * @var string 
     */
    private string $errorData;

    public function __construct()
    {
        $calledClassArr = explode("\\", get_called_class());
        $this->son_Class = $calledClassArr[count($calledClassArr) - 1];
    }
    /**
     * 设置api名称
     * @access public
     * @param string $apiName api名称
     * @return self
     */
    public function setApiName(string $apiName): self
    {
        $this->apiName = $apiName;
        return $this;
    }
    /**
     * 是否开启Api模式
     * @access public
     * @param object $server 服务
     * @param object $frame 
     * @param string $decode 解码类型 default:ApiConst::DECODE_JSON
     * @return void
     */
    public function isOpenApi(object $server, object $frame, string $decode = ApiConst::DECODE_JSON): void
    {
        $decode = strtolower($decode);

        if (!in_array($decode, $this->encodeList)) {
            echo $this->MakeError(ApiConst::ERROR_DECODE_IS_NOT_EXIST);
            return;
        }

        $this->BackDecodeFrameData(frame: $frame, decode: $decode);

        $this->isGetApiUrl(isGet: true);

        $this->getApiLevel(isReturnIndexMode: false);

        if ($this->sendError and isset($this->errorData))
            $server->push($frame->fd, $this->errorData);

        $this->run(server: $server, frame: $frame);

    }
    /**
     * 是否打开发送错误功能
     * @access public
     * @return self
     */
    public function openSendError(): self
    {
        $this->sendError = true;
        return $this;
    }
    /**
     * 运行Api
     * @access public
     */
    public function run(object $server, object $frame): void
    {
        //*验证Api层级
        $namespace = $this->VerApiLevel();

        $data = $this->getApiData($namespace,$this->data);//*返回数据

        $server->push($frame->fd, $data);
    }
    /**
     * 获取Api数据
     * @return mixed
     */
    public function getApiData(string $namespace, mixed ...$avgs): mixed
    {
        $ref = (new \ReflectionClass($namespace));
        $void = $this->apiSlice[count($this->apiSlice) - 1];
        if (!$ref->hasMethod($void)) {
            echo $this->MakeError(ApiConst::ERROR_API_IS_NOT_HAVING);
            return null;
        }

        $obj = "\\{$ref->name}";
        return $ref->getMethod($void)->invoke(new $obj(), $avgs);
    }
    /**
     * 验证Api层级
     * @return string
     */
    private function VerApiLevel(): string
    {
        return match ($this->apiLevel) {
            ApiConst::LEVEL_ONE => "\app\controller\index",
            ApiConst::LEVEL_TWO => (function (): string{
                    $file = $this->apiSlice[0];
                    $namespace = "\app\controller\\" . $file;
                    return $namespace;
                })(),
        };
    }
    /**
     * 获取Api层级
     * @access private
     * @param bool $isReturnIndexMode 是否返回索引模式 default:false
     * @return void
     */
    private function getApiLevel(bool $isReturnIndexMode = false): void
    {
        $this->apiSlice = explode("/", $this->api);
        $level = count($this->apiSlice);
        $this->apiLevel = $isReturnIndexMode ? $level - 1 : $level;
    }
    /**
     * 解码Frame前端返回数据
     * @access private
     * @param object $frame 
     * @param string $decode 解码类型
     * @return void
     */
    private function BackDecodeFrameData(object $frame, string $decode): void
    {
        $this->data = match ($decode) {
            "json" => (array) json_decode($frame->data),
            "base64" => base64_decode($frame->data),
        };
    }

    /**
     * 是否获取Api路径
     * @access private
     * @param bool $isGet 是否获取 default:true
     * @return void
     */
    private function isGetApiUrl(bool $isGet = true): void
    {
        if (!isset($this->data[$this->apiName])) {
            echo $this->MakeError(ApiConst::ERROR_NOT_GET_API_NAME);
            return;
        } else if (!is_string($this->data[$this->apiName])) {
            echo $this->MakeError(ApiConst::ERROR_API_IS_NOT_STRING);
            return;
        }

        if ($isGet)
            $this->api = $this->data[$this->apiName];
    }
    /**
     * 生成错误
     * @return void
     */
    private function MakeError(string $data): string
    {
        $this->errorData = $data;
        return ("*\t**************\t*\n*\t$data\t*\n*\t服务来至于:{$this->son_Class}!\t*\n*\t**************\t*\n");
    }
}