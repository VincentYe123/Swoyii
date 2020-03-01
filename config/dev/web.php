<?php

use app\component\ErrorHandle;
use app\component\Hashid;
use app\component\Jwt;
use app\component\PasswordHash;
use app\component\Request;
use app\component\Response;
use app\component\Sw;
use app\middleware\ResponseFilter;
use app\middleware\ResponseLog;
use voku\helper\AntiXSS;
use yii\web\JsonParser;
use yii\caching\FileCache;

$db = require __DIR__.'/db.php';
$log = require __DIR__.'/log.php';
$redis = require __DIR__.'/redis.php';
$params = require __DIR__.'/params.php';
$route = require __DIR__.'/../../params/route.php';

return [
    'id' => APP_ID,
    'name' => APP_NAME,
    'basePath' => dirname(__DIR__).'/../',
    'bootstrap' => ['log'],
    'modules' => [
        'v1' => [
            'class' => app\module\v1\V1Mod::class,
        ],
    ],
    'components' => [
        'urlManager' => [
            'enablePrettyUrl' => true,
            'enableStrictParsing' => true,
            'showScriptName' => false,
            'rules' => $route,
        ],
        'errorHandler' => [
            'class' => ErrorHandle::class,
        ],
        'request' => [
            'class' => Request::class,
            'cookieValidationKey' => 'swoyii!@#$%^&*()',
            'enableCsrfValidation' => false,
            'parsers' => [
                'application/json' => JsonParser::class,
            ],
        ],
        'response' => [
            'class' => Response::class,
            'format' => \yii\web\Response::FORMAT_JSON,
            'as log' => [
                'class' => ResponseLog::class,
                'except' => [],
                'only' => [],
            ],
            'as filter' => [
                'class' => ResponseFilter::class,
                'except' => [],
                'only' => [],
            ],
        ],
        'sanitizer' => [
            'class' => AntiXSS::class,
        ],
        'cache' => [
            'class' => FileCache::class,
        ],
        'phpass' => [
            'class' => PasswordHash::class,
        ],
        'hashid' => [
            'class' => Hashid::class,
            'key' => HASH_ID_KEY,
            'length' => HASH_ID_LENGTH,
        ],
        'jwt' => [
            'class' => Jwt::class,
            'key' => JWT_KEY,
            'expTime' => JWT_DURATION,
        ],
        'sw' => [
            'class' => Sw::class,
        ],
        'db' => $db,
        'log' => $log,
        'redis' => $redis,
    ],
    'params' => $params,
];
