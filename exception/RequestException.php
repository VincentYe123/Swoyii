<?php

namespace app\exception;

class RequestException extends BaseException
{
    public const URI_ERR = 10001;
    public const PERMISSION_DENIED = 10002;
    public const INVALID_SIGNATURE = 10003;
    public const INVALID_PARAM = 10004;
    public const REPEAT_REQUEST = 10005;
    public const REQUEST_TIMEOUT = 10006;
    public const UNAUTHORIZED_TOKEN = 10007;
    public const CURL_ERROR = 10008;

    public static $reasons
        = [
            self::URI_ERR => '路由不存在',
            self::PERMISSION_DENIED => '无操作权限',
            self::INVALID_SIGNATURE => '签名异常',
            self::INVALID_PARAM => '参数不合法',
            self::REPEAT_REQUEST => '重复请求',
            self::REQUEST_TIMEOUT => '请求超时',
            self::UNAUTHORIZED_TOKEN => '登录态失效',
            self::CURL_ERROR => '请求失败',
        ];

    public static $statusCode
        = [
            self::INVALID_PARAM => BaseException::BAD_REQUEST_CODE,
            self::UNAUTHORIZED_TOKEN => BaseException::UNAUTHORIZED_CODE,
            self::PERMISSION_DENIED => BaseException::FORBIDDEN_CODE,
        ];
}
