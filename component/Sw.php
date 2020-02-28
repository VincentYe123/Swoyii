<?php

namespace app\component;

use swoole_http_server;
use Yii;
use yii\base\Component;

class Sw extends Component
{
    /**
     * @var swoole_http_server
     */
    public $_swServer;

    public function setSwServer(swoole_http_server $server)
    {
        $this->_swServer = $server;
    }

    /**
     * async func.
     *
     * @param mixed ...$paramArr , Same as call_func_user() Params
     *
     * @CreateTime 2018/7/27 13:49:20
     */
    public function task(...$paramArr)
    {
        $paramArr[] = Yii::$app->request->headers['x-request-id'] ?: md5(time());
        $this->_swServer->task($paramArr);
    }

    public function stop()
    {
        $this->_swServer->stop();
    }
}
