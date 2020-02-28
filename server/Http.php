<?php

namespace app\server;

use swoole_http_request;
use swoole_http_server;
use swoole_server;
use Yii;
use yii\base\InvalidConfigException;

class Http
{
    private $_http;
    private $_swConf;
    private $_appConf;

    /**
     * Http constructor.
     *
     * @param $swConf
     * @param $appConf
     */
    public function __construct($swConf, $appConf)
    {
        $this->_swConf = $swConf;
        $this->_appConf = $appConf;
    }

    /**
     * Main func.
     */
    public function run()
    {
        $this->_http = new swoole_http_server($this->_swConf['ip'], $this->_swConf['port']);
        $this->_http->on('start', [$this, 'onStart']);
        $this->_http->on('WorkerStart', [$this, 'onWorkerStart']);
        $this->_http->on('request', [$this, 'onRequest']);
        $this->_http->on('task', [$this, 'onTask']);
        $this->_http->on('finish', [$this, 'onFinish']);
        $this->_http->on('WorkerStop', [$this, 'onWorkerStop']);
        $this->_http->set($this->_swConf);

        if (PHP_OS === 'Linux') {
            swoole_set_process_name($this->_swConf['process_name']);
        }
        $this->_http->start();
    }

    /**
     * @param $server
     *
     * @CreateTime 2018-12-06 20:07:33
     */
    public function onStart(swoole_server $server)
    {
        $this->startInfo();
        $date = date('Y-m-d H:i:s');
        echo "[info] {$date} Http Server Start, Pid is {$server->master_pid}".PHP_EOL;
    }

    /**
     * @param $server
     * @param $workerId
     *
     * @CreateTime 2018-12-17 09:55:35
     *
     * @throws InvalidConfigException
     */
    public function onWorkerStart(swoole_server $server, $workerId)
    {
        $workName = 'Worker';
        $date = date('Y-m-d H:i:s');

        if ($server->taskworker) {
            $workName = 'Task Worker';
        }
        echo "[info] {$date} {$workName} #{$workerId} Start ".PHP_EOL;

        //Grpc扩展与swoole扩展有冲突, Grpc扩展必须在运行时动态加载, 不能在配置文件中直接启用
        //dl('grpc.so');
        new Application($this->_appConf);
        Yii::$app->sw->setSwServer($server);
    }

    /**
     * @param $request
     * @param $response
     *
     * @CreateTime 2018-12-17 10:16:54
     */
    public function onRequest($request, $response)
    {
        $this->setAppRunEnv($request, $response);
        Yii::$app->run();
    }

    public function onTask(swoole_server $server, $taskId, $srcWorkerId, $data)
    {
        $date = date('Y-m-d H:i:s');
        try {
            Yii::$app->request->headers['x-request-id'] = array_pop($data);
            call_user_func(...$data);
            $server->finish($data);
            echo "[info] {$date} Task Worker #{$taskId} trace_id: ".Yii::$app->request->headers['x-request-id'].PHP_EOL;
            echo "[info] {$date} Task Worker #{$taskId} task_data: ".json_encode($data).PHP_EOL;
        } catch (\Exception $e) {
            echo "[info] {$date} Task Worker #{$taskId} err_file: {$e->getFile()}".PHP_EOL;
            echo "[info] {$date} Task Worker #{$taskId} err_line: {$e->getLine()}".PHP_EOL;
            echo "[info] {$date} Task Worker #{$taskId} err_msg:  {$e->getMessage()}".PHP_EOL;
            echo "[info] {$date} Task Src Worker is #{$srcWorkerId}".PHP_EOL;
        }
    }

    public function onFinish(swoole_server $server, $taskId, $data)
    {
        $date = date('Y-m-d H:i:s');
        echo "[info] {$date} Task Worker #{$taskId} Finish.".PHP_EOL;
    }

    /**
     * @param $server
     * @param $workerId
     *
     * @CreateTime 2018-12-17 09:55:35
     */
    public function onWorkerStop(swoole_server $server, $workerId)
    {
        $date = date('Y-m-d H:i:s');
        echo "[info] {$date} Worker #{$workerId} stop #{$workerId}".PHP_EOL;
    }

    /**
     * Set app run env.
     *
     * @param $request
     * @param $response
     */
    public function setAppRunEnv(swoole_http_request $request, $response)
    {
        Yii::$app->request->clear();
        Yii::$app->response->clear();

        Yii::$app->request->setSwRequest($request);
        Yii::$app->response->setSwResponse($response);

        foreach ($request->server as $k => $v) {
            $_SERVER[strtoupper($k)] = $v;
        }

        Yii::$app->request->setPathInfo($request->server['path_info']);
        foreach ($request->header as $name => $value) {
            Yii::$app->request->getHeaders()->set($name, $value);
        }

        Yii::$app->request->setQueryParams($request->get);
        Yii::$app->request->setBodyParams($request->post);

        if (empty($request->rawContent())) {
            Yii::$app->request->setRawBody('');
        } else {
            Yii::$app->request->setRawBody($request->rawContent());
        }

        Yii::$app->request->setStartTime(microtime(true));
    }

    public function startInfo()
    {
        $appName = APP_NAME;
        $osName = PHP_OS;
        $phpVersion = PHP_VERSION;
        $swooleVersion = swoole_version();
        $yiiVersion = Yii::getVersion();
        $addr = $this->_swConf['ip'];
        $port = $this->_swConf['port'];

        echo <<<EOT
                             .__.__ 
  ________  _  ______ ___.__.|__|__|
 /  ___/\ \/ \/ /  _ <   |  ||  |  |
 \___ \  \     (  <_> )___  ||  |  |
/____  >  \/\_/ \____// ____||__|__|
     \/               \/
Server         Name:      $appName
System         Name:      $osName
PHP            Version:   $phpVersion
Swoole         Version:   $swooleVersion
Framework      Version:   $yiiVersion
Listen         Addr:      $addr
Listen         Port:      $port
EOT;
        echo PHP_EOL;
    }
}