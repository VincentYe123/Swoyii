<?php

//middleware configs
//uris in only means must check by behavior
//uris in except means ignore behavior check
//actually behavior check logic is beforeAction
return [
    'requestHeaderFilter' => [
        'only' => [],
        'except' => [],
    ],
    'requestParamFilter' => [
        'only' => [],
        'except' => [],
    ],
    'tokenValidate' => [
        'only' => [],
        'except' => ['demo/index/index'],
    ],
    'responseLog' => [
        'only' => [],
        'except' => [],
    ],
    'responseFilter' => [
        'only' => [],
        'except' => [],
    ],
    'responseHashFilter' => [
        'only' => [],
        'except' => [],
        'hashColumn' => [],
    ],
];
