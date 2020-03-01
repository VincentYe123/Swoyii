<?php

use app\component\ErrorHandle;
use app\component\Hashid;
use app\component\Jwt;
use app\component\PasswordHash;
use app\component\Request;
use app\component\Response;
use app\component\Sw;
use app\middleware\ResponseFilter;
use app\middleware\ResponseHashFilter;
use app\middleware\ResponseLog;
use voku\helper\AntiXSS;
use yii\web\JsonParser;
use yii\caching\FileCache;

$db = require __DIR__.'/db.php';
$log = require __DIR__.'/log.php';
$redis = require __DIR__.'/redis.php';
$params = require __DIR__.'/../../params/params.php';
$routes = require __DIR__.'/../../routes/routes.php';

return [
    'id' => APP_ID,
    'name' => APP_NAME,
    'basePath' => dirname(__DIR__).'/../',
    'bootstrap' => ['log'],
    'modules' => [
        'demo' => [
            'class' => app\module\demo\demoMod::class,
        ],
    ],
    'components' => [
        'urlManager' => [
            'enablePrettyUrl' => true,
            'enableStrictParsing' => true,
            'showScriptName' => false,
            'rules' => $routes,
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
                //解析xml
                //'text/xml' => XmlParser::class,
            ],
        ],
        'response' => [
            'class' => Response::class,
            'format' => \yii\web\Response::FORMAT_JSON,
            'as log' => [
                'class' => ResponseLog::class,
                'except' => $params['responseLog']['except'],
                'only' => $params['responseLog']['only'],
            ],
            'as filter' => [
                'class' => ResponseFilter::class,
                'except' => $params['responseFilter']['except'],
                'only' => $params['responseFilter']['only'],
            ],
            'as hashfilter' => [
                'class' => ResponseHashFilter::class,
                'except' => $params['responseHashFilter']['except'],
                'only' => $params['responseHashFilter']['only'],
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
