<?php

namespace app\component;

use Swoole\Http\Server;
use Yii;
use yii\base\Component;

class Sw extends Component
{
    /**
     * @var Server
     */
    public $_swServer;

    public function setSwServer(Server $server): void
    {
        $this->_swServer = $server;
    }

    public function task(...$paramArr)
    {
        $paramArr[] = Yii::$app->request->headers['x-request-id'] ?: md5(time());
        $this->_swServer->task($paramArr);
    }

    public function stop(): void
    {
        $this->_swServer->stop();
    }
}
