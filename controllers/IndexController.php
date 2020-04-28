<?php

namespace app\controllers;

/**
 * Created by PhpStorm.
 *
 * @Author     : yechangqing@styd.cn
 * @CreateTime 2020/4/28 16:17:48
 */
class IndexController extends BaseController
{
    public function actionTest(): void
    {
        \Yii::$app->grpc->client->unaryCall(BaseController::class, 'behaviors', $this);
    }
}
