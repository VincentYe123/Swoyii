<?php

use yii\helpers\ArrayHelper;
use yii\helpers\FileHelper;

$routes = [];
$fileList = FileHelper::findFiles(__DIR__.'/../routes', ['only' => ['*.php'], 'except' => [basename(__FILE__)]]);

foreach ($fileList as $file) {
    $routes = ArrayHelper::merge($routes, include $file);
}

return $routes;
