<?php

use yii\rest\UrlRule;

return [
    [
        'class' => UrlRule::class,
        'pluralize' => false,
        'controller' => 'index',
        'extraPatterns' => [
            'GET test' => 'test',
        ],
    ],
];
