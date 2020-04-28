<?php

namespace app\controllers;

use app\middleware\ParamsValidate;
use app\middleware\RequestXssFilter;
use app\middleware\TokenValidate;
use Yii;
use yii\rest\Controller;

class BaseController extends Controller
{
    public function behaviors(): array
    {
        return [
            'requestXssFilter' => [
                'class' => RequestXssFilter::class,
            ],
            'requestHeaderFilter' => [
                'class' => ParamsValidate::class,
                'data' => Yii::$app->request->getSwRequest()->header,
                'rules' => Yii::$app->params['requestHeaderRule'],
                'except' => Yii::$app->params['requestHeaderFilter']['except'],
                'only' => Yii::$app->params['requestHeaderFilter']['only'],
            ],
            'requestParamFilter' => [
                'class' => ParamsValidate::class,
                'data' => array_merge(Yii::$app->request->getQueryParams(), Yii::$app->request->getBodyParams()),
                'rules' => Yii::$app->params['requestParamRule'],
                'except' => Yii::$app->params['requestParamFilter']['except'],
                'only' => Yii::$app->params['requestParamFilter']['only'],
            ],
           'tokenValidate' => [
               'class' => TokenValidate::class,
               'except' => Yii::$app->params['tokenValidate']['except'],
               'only' => Yii::$app->params['tokenValidate']['only'],
           ],
        ];
    }
}
