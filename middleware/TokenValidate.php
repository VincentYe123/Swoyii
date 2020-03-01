<?php

namespace app\middleware;

use Lcobucci\JWT\Token;
use Throwable;
use Yii;
use app\exception\RequestException;
use yii\web\Request;

class TokenValidate extends RequestInterface
{
    /**
     * @param Request $request
     *
     * @return bool
     *
     * @throws RequestException
     * @throws Throwable
     */
    public function beforeAction($request): bool
    {
        $token = $request->getHeaders()->get('Token');
        /**
         * @var Token
         */
        $jwt = Yii::$app->jwt->loadToken($token);
        if (null === $jwt) {
            $this->handleFailure();
        }

        //todo::增加业务逻辑

        return true;
    }

    /**
     * @throws RequestException
     */
    public function handleFailure(): void
    {
        throw new RequestException(RequestException::UNAUTHORIZED_TOKEN);
    }
}
