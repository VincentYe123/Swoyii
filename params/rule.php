<?php

//yii2 params&header rules
// The homepage URL is: https://www.yiiframework.com/doc/guide/2.0/zh-cn/input-validation
use yii\helpers\FileHelper;

$ruleList = [
    'requestHeaderRule' => [],
    'requestParamRule' => [],
];

$fileList = FileHelper::findFiles(__DIR__.'/../rule/header', ['only' => ['*.php']]);
foreach ($fileList as $file) {
    $ruleList['requestHeaderRule'] = array_merge($ruleList['requestHeaderRule'], include $file);
}

$fileList = FileHelper::findFiles(__DIR__.'/../rule/param', ['only' => ['*.php']]);
foreach ($fileList as $file) {
    $rules = [];
    foreach (include $file as $controllerStr => $rule) {
        $controllerArr = explode(',', $controllerStr);
        foreach ($controllerArr as $controller) {
            if (isset($rules[trim($controller)])) {
                $rules[trim($controller)] = array_merge($rules[trim($controller)], $rule);
            } else {
                $rules[trim($controller)] = $rule;
            }
        }
    }
    $ruleList['requestParamsRule'] = array_merge($ruleList['requestParamsRule'], $rules);
}

return $ruleList;
