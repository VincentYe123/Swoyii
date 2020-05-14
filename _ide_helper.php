<?php

/**
 * 组件提示,无任何实际功能
 * Class Yii.
 */
class Yii
{
    /**
     * @var MyApplication
     */
    public static $app;
}

/**
 * 自定义应用组件
 * Class MyApplication.
 */
class MyApplication
{
    /** @var yii\redis\Connection */
    public $redis;

    /** @var app\component\Sw */
    public $sw;

    /** @var voku\helper\AntiXSS */
    public $sanitizer;

    /** @var app\component\Hashid */
    public $hashid;

    /** @var app\component\Jwt */
    public $jwt;

    /** @var app\component\PasswordHash */
    public $phpass;

    /** @var app\component\Grpc */
    public $grpc;
}
