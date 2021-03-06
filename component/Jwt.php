<?php

namespace app\component;

use Lcobucci\JWT\Token;
use Yii;
use Lcobucci\JWT\Signer\Hmac\Sha256;
use yii\base\Exception;

class Jwt extends \sizeg\jwt\Jwt
{
    public $key;

    public $expTime = 3600 * 24 * 7;

    /**
     * @param array $privatePayloads
     *
     * @return Token
     *
     * @throws Exception
     */
    public function issue($privatePayloads = []): Token
    {
        $request = Yii::$app->request;
        $security = Yii::$app->getSecurity();
        $sign = new Sha256();
        $time = time();
        $uid = $security->generateRandomString();
        $builder = $this->getBuilder()
                        ->setIssuer($request->getHostName())
                        ->setIssuedAt($time)
                        ->setAudience($request->getRemoteHost())
                        ->setId($uid)
                        ->setNotBefore($time - 10)
                        ->setExpiration($time + $this->expTime);

        foreach ($privatePayloads as $name => $v) {
            $builder->set($name, $v);
        }

        return $builder->sign($sign, $this->key)->getToken();
    }
}
