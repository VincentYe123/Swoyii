<?php

namespace app\component;

use Yii;
use yii\base\InvalidConfigException;

class Request extends \yii\web\Request
{
    private $_swRequest;

    private $_startTime;

    public function setSwRequest($request): void
    {
        $this->_swRequest = $request;
    }

    public function getSwRequest()
    {
        return $this->_swRequest;
    }

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
