<?php
/**
 * Created by PhpStorm.
 *
 * @Author: sunqiang@styd.cn
 * @CreateTime 2019-05-07 15:20:22
 */

namespace app\component;

use app\exceptions\RequestException;
use Hashids\Hashids;
use Yii;

class Hashid extends Hashids
{
    //hash加密Key
    public $key;

    //hash后id长度
    public $length;

    private static $instance = null;

    /**
     * 获取单例方法.
     *
     * @param $key
     * @param $length
     *
     * @return Hashids|null
     * @CreateTime 2019-05-07 15:49:40
     */
    public static function getInstance($key, $length): ?Hashids
    {
        if (null === self::$instance) {
            self::$instance = new Hashids($key, $length);
        }

        return self::$instance;
    }

    /**
     * 加密ID.
     *
     * @param mixed ...$numbers
     *
     * @return string
     *
     * @throws RequestException
     * @CreateTime 2019-05-07 15:49:32
     */
    public function encodeId(...$numbers): string
    {
        $idStr = self::getInstance($this->key, $this->length)::encode($numbers);
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
     * @CreateTime 2019-05-07 17:03:17
     */
    public function decodeId($hash)
    {
        $ids = self::getInstance($this->key, $this->length)->decode($hash);
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
        $ids = self::getInstance($this->key, $this->length)->decode($hash);

        if (empty($ids)) {
            throw new RequestException(RequestException::INVALID_PARAM);
        }

        return $ids;
    }
}
