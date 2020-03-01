<?php

use app\common\FileTarget;

return [
    'flushInterval' => 1,
    'targets' => [
        [
            // async write log
            'class' => FileTarget::class,
            'logFileName' => 'ex_'.date('Y-m-d').'.log',
            'logDir' => static function ($logFileName) {
                return dirname(__DIR__).'/../log/'.$logFileName;
            },
            'categories' => ['ex'],
            'exportInterval' => 1,
            'maxFileSize' => 10240 * 5,
            //10M * 5
            'logVars' => [],
            'levels' => ['error'],
            'prefix' => static function ($msg) {
                if (null === Yii::$app) {
                    return '';
                }
                $traceId = Yii::$app->request->headers['x-request-id'];

                return "[{$traceId}]";
            },
        ],
        [
            'class' => FileTarget::class,
            'logFileName' => 'app_'.date('Y-m-d').'.log',
            'logDir' => static function ($logFileName) {
                return dirname(__DIR__).'/../log/'.$logFileName;
            },
            'categories' => ['application'],
            'exportInterval' => 1,
            'maxFileSize' => 10240 * 5,
            'logVars' => [],
            'levels' => [
                'info',
                'warning',
                'error',
            ],
            'prefix' => static function ($msg) {
                if (null === Yii::$app) {
                    return '';
                }
                $traceId = Yii::$app->request->headers['x-request-id'];

                return "[{$traceId}]";
            },
        ],
    ],
];
