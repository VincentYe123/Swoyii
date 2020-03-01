<?php

namespace app\middleware;

use yii\base\Controller;

abstract class RequestInterface extends BaseMiddleware
{
    public function events(): array
    {
        return [
            Controller::EVENT_BEFORE_ACTION => 'eventBeforeAction',
        ];
    }

    public function eventBeforeAction(): void
    {
        if (!$this->isActive()) {
            return;
        }

        $this->beforeAction(\Yii::$app->request);
    }

    abstract public function beforeAction($request);
}
