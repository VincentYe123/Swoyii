<?php

namespace app\middleware;

use Yii;
use yii\web\Response;

class ResponseFilter extends ResponseInterface
{
    public $successCode = 0;

    public $successMsg = 'success';

    public function beforeSend($response)
    {
        $data = $response->data;

        Yii::$app->response->headers->add('x-request-id', Yii::$app->request->headers['x-request-id']);

        if (Response::FORMAT_JSON !== Yii::$app->response->format) {
            return;
        }

        if (isset($data['code'], $data['msg'])) {
            return;
        }

        $response->data = [
            'code' => $this->successCode,
            'msg' => $this->successMsg,
            'data' => is_array($data) && !empty($data) ? $data : (object) [],
        ];
    }
}
