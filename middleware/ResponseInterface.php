<?php

namespace app\middleware;

use yii\web\Response;
use Yii;

abstract class ResponseInterface extends BaseMiddleware
{
    public function events()
    {
        return [
            Response::EVENT_BEFORE_SEND => 'eventBeforeSend',
        ];
    }

    public function eventBeforeSend()
    {
        if (!$this->isActive()) {
            $info = Yii::$app->request->getInfo();
            Yii::error(var_export($info, true), 'path');

            return;
        }
        $this->beforeSend(Yii::$app->response);
    }

    /**
     * Response before send.
     *
     * @param $response
     */
    abstract public function beforeSend($response);
}
