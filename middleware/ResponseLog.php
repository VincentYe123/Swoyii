<?php

namespace app\middleware;

use Yii;

class ResponseLog extends ResponseInterface
{
    public $maxTime = 2000;

    public function beforeSend($response)
    {
        $log = [
            'request' => Yii::$app->request->getInfo(),
            'response' => $response->data,
            'start_time' => Yii::$app->request->getStartTime(),
            'end_time' => microtime(true),
        ];

        $log['excl_time'] = ($log['end_time'] - $log['start_time']) * 1000;
        $log['excl_time'] = explode('.', $log['excl_time'])[0];

        if ($log['excl_time'] > $this->maxTime) {
            $key = Yii::$app->id.':'.date('Ymd').':TimeId:'.Yii::$app->request->getExUniqueId();
            Yii::$app->sw->task([
                'app\services\MailService',
                'sendException',
            ], '【API Too Slow, Handle Time: '.$log['excl_time'].'ms 】', $key, [
                'request_info' => Yii::$app->request->getInfo(),
            ]);
        }

        Yii::info(json_encode($log));
    }
}
