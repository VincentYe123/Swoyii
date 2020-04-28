<?php
/**
 * Created by PhpStorm.
 *
 * @Author     : yechangqing@styd.cn
 * @CreateTime 2020/4/20 15:28:52
 */

namespace app\component;

use app\common\GrpcClient;
use yii\base\Component;

class Grpc extends Component
{
    public $hostname;

    /**
     * @var GrpcClient
     */
    public $client;

    /**
     * @CreateTime 2020/4/28 16:13:55
     * @Author     : yechangqing@styd.cn
     */
    public function init()
    {
        $this->client = GrpcClient::getInstance($this->hostname);
    }
}
