<?php

namespace app\middleware;

use app\component\Request;
use Yii;
use yii\base\InvalidConfigException;

class RequestXssFilter extends RequestInterface
{
    /**
     * 不期望被转义的值
     *
     * @var array
     */
    public $except = [];

    /**
     * @param Request $request
     *
     * @throws InvalidConfigException
     */
    public function beforeAction($request): void
    {
        $cleanQueryParams = $this->xssCleaner($request->getQueryParams());
        $cleanBodyParams = $this->xssCleaner($request->getBodyParams());

        $request->setQueryParams($cleanQueryParams);
        $request->setBodyParams($cleanBodyParams);
    }

    /**
     * @param $waitClean
     *
     * @return array|string
     */
    public function xssCleaner($waitClean)
    {
        if (is_array($waitClean)) {
            foreach ($waitClean as $key => $reqValue) {
                if (in_array($key, $this->except, true)) {
                    $waitClean[$key] = $reqValue;
                } else {
                    $waitClean[$key] = self::xssCleaner($reqValue);
                }
            }
        } else {
            $waitClean = $this->cleanReqVal($waitClean);
        }

        return $waitClean;
    }

    /**
     * @param $reqVal
     *
     * @return string
     */
    public function cleanReqVal($reqVal): string
    {
        return Yii::$app->sanitizer->xss_clean($reqVal);
    }
}
