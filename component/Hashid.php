<?php

namespace app\component;

use app\exception\RequestException;
use Hashids\Hashids;
use Yii;

class Hashid extends Hashids
{
    //hash加密Key
    public $key;

    //hash后id长度
    public $length;

    /** @var Hashids */
    private static $instance = null;

    public function init()
    {
        self::$instance = new Hashids($this->key, $this->length);
    }

    /**
     * 加密ID.
     *
     * @param mixed ...$numbers
     *
     * @return string
     *
     * @throws RequestException
     */
    public function encodeId(...$numbers): string
    {
        $idStr = self::$instance->encode($numbers);
        if (empty($idStr)) {
            Yii::error('encode id error, input numbers is '.implode('-', $numbers));
            throw new RequestException(RequestException::INVALID_PARAM);
        }

        return $idStr;
    }

    /**
     * 解密单个ID.
     *
     * @param $hash
     *
     * @return mixed
     *
     * @throws RequestException
     */
    public function decodeId($hash)
    {
        $ids = self::$instance->decode($hash);
        if (empty($ids)) {
            throw new RequestException(RequestException::INVALID_PARAM);
        }

        return $ids[0];
    }

    /**
     * 解密多个ID.
     *
     * @param $hash
     *
     * @return array
     *
     * @throws RequestException
     * @CreateTime 2019-05-07 15:49:36
     */
    public function decodeIds($hash): array
    {
        $ids = self::$instance->decode($hash);

        if (empty($ids)) {
            throw new RequestException(RequestException::INVALID_PARAM);
        }

        return $ids;
    }
}
