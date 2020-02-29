<?php

namespace app\middleware;

use app\exception\RequestException;
use Yii;
use yii\base\DynamicModel;
use yii\base\InvalidConfigException;
use yii\helpers\ArrayHelper;

class ParamsValidate extends RequestInterface
{
    /**
     * validate data
     * ```
     * 'requestHeadersFilter' => [
     *      'class' => ParamsValidate::class,
     *      'data' => \Yii::$app->request->getHeaders()->toArray(),
     *      ...
     * ].
     *
     * ```
     *
     * @var
     */
    public $data;

    /**
     * validate rule
     * ```
     * 'requestHeadersFilter' => [
     *      'class' => ParamsValidate::class,
     *      'data' => \Yii::$app->request->getHeaders()->toArray(),
     *      'rules' => [
     *          '*' => [
     *              [['head1'], 'required']
     *          ],
     *          'user/create' => [
     *              [['param1', 'param2'], 'required']
     *          ]
     *      ]
     * ].
     *
     * ```
     *
     * @var array
     */
    public $rules = [];

    /**
     * err Func.
     *
     * ```
     * 'errFunc' => function($data){
     *      Yii::$app->response->setStatusCode(403);
     *      throw new RequestException(RequestException::INVALID_PARAM, $data);
     * }
     *
     * ```
     *
     * @var
     */
    public $errFunc;

    private $_validateKey = [];

    public function init()
    {
        if (!is_callable($this->errFunc)) {
            $this->errFunc = static function ($data) {
                throw new RequestException(RequestException::INVALID_PARAM, reset($data));
            };
        }
    }

    /**
     * @param $request
     *
     * @throws InvalidConfigException
     */
    public function beforeAction($request)
    {
        $url = rtrim(Yii::$app->controller->action->getUniqueId(), '/');
        $rules = array_merge(ArrayHelper::getValue($this->rules, '*', []), ArrayHelper::getValue($this->rules, $url, []));
        $this->setValidateKey($rules);
        $this->setValidateVal($this->data);

        $DynamicModel = DynamicModel::validateData($this->_validateKey, $rules);
        if ($DynamicModel->hasErrors()) {
            call_user_func($this->errFunc, $DynamicModel->getFirstErrors());
        }
    }

    public function setValidateKey($rules)
    {
        foreach ($rules as $rule) {
            if (is_array($rule[0])) {
                foreach ($rule[0] as $v) {
                    $this->_validateKey[$v] = '';
                }
                continue;
            }

            $this->_validateKey[$rule[0]] = '';
        }
    }

    public function setValidateVal($post)
    {
        foreach ($this->_validateKey as $k => $v) {
            if (isset($post[$k])) {
                $this->_validateKey[$k] = $post[$k];
            }
        }
    }
}
