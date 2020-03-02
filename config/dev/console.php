<?php

use app\common\repository\GenerateRepo;
use app\common\StdoutTarget;
use app\component\ErrorHandle;
use app\models\BaseModel;
use app\repositories\BaseRepository;
use yii\gii\generators\model\Generator;
use yii\gii\Module;
use yii\caching\FileCache;

$params = require __DIR__.'/../../params/params.php';
$db = require __DIR__.'/db.php';

$config = [
    'id' => 'swoyii-console',
    'basePath' => dirname(__DIR__).'/../',
    'controllerNamespace' => 'app\command',
    'bootstrap' => ['log'],
    'components' => [
        'cache' => [
            'class' => FileCache::class,
        ],
        'errorHandler' => [
            'class' => ErrorHandle::class,
        ],
        'log' => [
            'flushInterval' => 1,
            'targets' => [
                [
                    'class' => StdoutTarget::class,
                    'categories' => ['application'],
                    'exportInterval' => 1,
                    'logVars' => [],
                    'levels' => [
                        'info',
                        'warning',
                        'error',
                    ],
                ],
            ],
        ],
        'db' => $db,
    ],
    'params' => $params,
];

if (YII_DEBUG) {
    $config['bootstrap'][] = 'gii';
    $config['modules']['gii'] = [
        'class' => Module::class,
        'generators' => [
            'model' => [
                'class' => Generator::class,
                'baseClass' => BaseModel::class,
                'templates' => [
                    'default' => '@app/resource/template',
                ],
            ],
            'repository' => [
                'class' => GenerateRepo::class,
                'baseClass' => BaseRepository::class,
                'templates' => [
                    'default' => '@app/resource/template',
                ],
            ],
        ],
    ];
}

return $config;
