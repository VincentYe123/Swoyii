<?php

namespace app\component;

use Yii;
use yii\base\InvalidConfigException;

class Request extends \yii\web\Request
{
    private $_swRequest;

    private $_startTime;

    /**
     * @param $request
     *
     * @CreateTime 2018-12-26 14:17:36
     */
    public function setSwRequest($request): void
    {
        $this->_swRequest = $request;
    }

    /**
     * @return mixed
     * @CreateTime 2018-12-26 14:17:39
     */
    public function getSwRequest()
    {
        return $this->_swRequest;
    }

    /**
     * @param $time
     *
     * @CreateTime 2018-12-26 14:17:43
     */
    public function setStartTime($time): void
    {
        $this->_startTime = $time;
    }

    public function getStartTime()
    {
        return $this->_startTime;
    }

    /**
     * @return array
     *
     * @throws InvalidConfigException
     * @CreateTime 2018-12-17 10:39:11
     */
    public function getInfo(): array
    {
        return [
            'path_info' => $this->getPathInfo(),
            'method' => $this->getMethod(),
            'header' => $this->getHeaders()->toArray(),
            'get' => $this->getQueryParams(),
            'post' => $this->getBodyParams(),
        ];
    }

    /**
     * @return string
     *
     * @throws InvalidConfigException
     * @CreateTime 2018-12-17 10:39:17
     */
    public function getExUniqueId(): string
    {
        $str = json_encode([
            $this->getPathInfo(),
            $this->getMethod(),
            $this->getQueryParams(),
            $this->getBodyParams(),
        ]);

        return md5($str);
    }

    public function clear(): void
    {
        $this->setQueryParams([]);
        $_GET = [];
        $this->headers->removeAll();
        Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
    }
}
