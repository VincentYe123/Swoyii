<?php

namespace app\server;

use Exception;
use Throwable;
use Yii;
use app\common\DbKeepAlive;

class Application extends \yii\web\Application
{
    public function __construct($config = [])
    {
        parent::__construct($config);

        //Mysql keep alive
        foreach (Yii::$app->components as $key => $component) {
            if ('yii\db\Connection' === $component['class']) {
                Yii::$app->$key->commandMap['mysql'] = DbKeepAlive::class;
                Yii::$app->$key->commandMap['mysqli'] = DbKeepAlive::class;
            }
        }
    }

    /**
     * @return int
     * @CreateTime 2020/2/28 10:34:02
     * @Author     : yechangqing@styd.cn
     */
    public function run(): int
    {
        try {
            $this->state = self::STATE_BEFORE_REQUEST;
            $this->trigger(self::EVENT_BEFORE_REQUEST);

            $this->state = self::STATE_HANDLING_REQUEST;
            $response = $this->handleRequest($this->getRequest());

            $this->state = self::STATE_AFTER_REQUEST;
            $this->trigger(self::EVENT_AFTER_REQUEST);

            $this->state = self::STATE_SENDING_RESPONSE;
            $response->send();

            $this->state = self::STATE_END;

            return $response->exitStatus;
        } catch (Exception $exception) {
            Yii::$app->errorHandler->handleException($exception);
        } catch (Throwable $errorException) {
            Yii::$app->errorHandler->handleException($errorException);
        }
    }
}
