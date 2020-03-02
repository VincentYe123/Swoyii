<?php

use yii\helpers\ArrayHelper;
use yii\helpers\FileHelper;

$params = [];
$fileList = FileHelper::findFiles(__DIR__.'/../params', ['only' => ['*.php'], 'except' => [basename(__FILE__)]]);

foreach ($fileList as $file) {
    $params = ArrayHelper::merge($params, include $file);
}

return $params;
