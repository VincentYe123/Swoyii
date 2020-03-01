<?php

namespace app\exception;

use Exception;

class BaseException extends Exception
{
    public const SYSTEM_ERR = 10000;
    public const DATA_ADD_ERROR = 10001;
    public const DATA_SHOW_ERROR = 10002;

    public const FORBIDDEN_CODE = 403;
    public const UNAUTHORIZED_CODE = 401;
    public const OK_CODE = 200;
    public const BAD_REQUEST_CODE = 400;
    public const URI_NOT_EXISTS = 404;
    public const SERVER_ERROR = 500;

    public static $reasons
        = [
            self::SYSTEM_ERR => '系统繁忙，请稍后重试',
            self::DATA_ADD_ERROR => '数据添加/更新失败',
            self::DATA_SHOW_ERROR => '无查询数据',
            self::FORBIDDEN_CODE => '无操作权限',
            self::UNAUTHORIZED_CODE => '登录态失效，请重新登录',
        ];

    public static $statusCode
        = [
            self::DATA_ADD_ERROR => self::SERVER_ERROR,
        ];

    public function __construct($code = null, $message = null)
    {
        parent::__construct();
        $this->code = $code;
        $this->message = $message ?: self::getReason($code);
    }

    public static function getReason($code): string
    {
        return static::$reasons[$code] ?: self::$reasons[self::SYSTEM_ERR];
    }

    public static function getStatusCode($code): int
    {
        return static::$statusCode[$code] ?: self::BAD_REQUEST_CODE;
    }
}
