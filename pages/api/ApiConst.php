<?php

namespace pages\api;

class ApiConst
{
    public const DECODE_JSON = "JSON";
    public const DECODE_BASE64 = "BASE64";
    public const LEVEL_ONE = 1;
    public const LEVEL_TWO = 2;
    public const ERROR_NOT_GET_API_NAME = "未获取到对应api名称!";
    public const ERROR_API_IS_NOT_STRING = "api类型属于非字符串!";
    public const ERROR_API_IS_NOT_HAVING = "api不存在!";
    public const ERROR_DECODE_IS_NOT_EXIST = "解码类型不存在!";
}