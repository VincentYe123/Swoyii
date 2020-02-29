<?php

namespace app\middleware;

use Yii;
use yii\base\Behavior;
use yii\helpers\StringHelper;

class BaseMiddleware extends Behavior
{
    /**
     * only url
     * ```
     * [
     *      'user/create',
     *      ...
     * ]
     * ```.
     *
     * @var
     */
    public $only = [];

    /**
     * except url
     * ```
     * [
     *      'user/create',
     *      'user/*'
     *      ...
     * ]
     * ```.
     *
     * @var array
     */
    public $except = [];

    /**
     * @return bool
     * @CreateTime 2019-04-19 14:03:13
     * @Author     : xingxiaohe@styd.cn
     */
    public function isActive()
    {
        $id = Yii::$app->requestedRoute;
        if (empty($id)) {
            return false;
        }

        if (empty($this->only)) {
            $onlyMatch = true;
        } else {
            $onlyMatch = false;
            foreach ($this->only as $pattern) {
                if (StringHelper::matchWildcard($pattern, $id)) {
                    $onlyMatch = true;
                    break;
                }
            }
        }

        $exceptMatch = false;
        foreach ($this->except as $pattern) {
            if (StringHelper::matchWildcard($pattern, $id)) {
                $exceptMatch = true;
                break;
            }
        }

        return !$exceptMatch && $onlyMatch;
    }
}
