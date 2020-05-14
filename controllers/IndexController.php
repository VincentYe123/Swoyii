<?php

namespace app\controllers;

use Helloworld\HelloClient;
use Helloworld\HelloRequest;

/**
 * Created by PhpStorm.
 *
 * @Author     : yechangqing@styd.cn
 * @CreateTime 2020/4/28 16:17:48
 */
class IndexController extends BaseController
{
    public function actionTest()
    {
        $req = new HelloRequest();
        $req->setName('grpc');
        return [\Yii::$app->grpc->client->unaryCall(HelloClient::class, 'SayHello', $req)];
    }
}
