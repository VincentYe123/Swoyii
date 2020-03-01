<?php

return [
    'class' => yii\redis\Connection::class,
    'hostname' => 'localhost',
    'port' => 6379,
    'database' => 0,
    //if don't enable redis auth, comment this config.
    //'password' => '',
    'retries' => 1,
];
