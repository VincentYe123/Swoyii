<?php
/**
 * Created by PhpStorm.
 *
 * @Author     : yechangqing@styd.cn
 * @CreateTime 2020/4/21 09:15:58
 */

namespace app\common;

use Closure;
use Grpc\ChannelCredentials;

class GrpcClient
{
    /**
     * @var GrpcClient
     */
    private static $instance = null;

    //服务端地址
    private $hostname;

    //请求头 key 仅支持 '-' 连接单词
    private $header = [];

    //事件函数
    private $events = [];

    //发起请求的客户端
    private $clients = [];

    //发送请求前
    public const BEFORE_REQUEST = 'BEFORE_REQUEST';

    //获取response后
    public const AFTER_REQUEST = 'AFTER_REQUEST';

    //成功响应状态码
    public const GRPC_STATUS_OK = 0;

    //超时时间
    public const DEFAULT_TIME_OUT = 1000;

    private function __construct(array $hostname)
    {
        $this->hostname = $hostname;
    }

    private function __clone()
    {
    }

    private function __wakeup()
    {
    }

    public function setHostname(array $hostname): void
    {
        $this->hostname = $hostname;
    }

    public function getHostname(): string
    {
        return $this->hostname[array_rand($this->hostname)];
    }

    public function setHeader(array $header): void
    {
        $this->header = array_merge($this->header, $header);
    }

    public function getHeader(array $header): array
    {
        return $this->header;
    }

    public static function getInstance(array $hostname): GrpcClient
    {
        if (null === static::$instance) {
            static::$instance = new static($hostname);
        }

        return static::$instance;
    }

    public function registerEvent(string $eventName, Closure $callBack): void
    {
        $this->events[$eventName][] = $callBack;
    }

    public function removeEvent(string $eventName): void
    {
        $this->events[$eventName] = [];
    }

    public function trigger(string $eventName): void
    {
        if (!empty($this->events[$eventName])) {
            foreach ($this->events[$eventName] as $callBack) {
                $callBack(self::$instance);
            }
        }
    }

    /**
     * @param string $serviceName
     * @param string $action
     * @param        $request
     * @param int    $timeOut
     *
     * @CreateTime 2020/4/21 16:37:08
     * @Author     : yechangqing@styd.cn
     */
    public function unaryCall(string $serviceName, string $action, $request, int $timeOut = self::DEFAULT_TIME_OUT)
    {
        $isNew = self::DEFAULT_TIME_OUT === $timeOut;
        $this->trigger(static::BEFORE_REQUEST);
        $client = $this->getClient($serviceName, $timeOut, $isNew);

        [$reply, $status] = $client->$action($request)->wait();
        if (self::GRPC_STATUS_OK != $status->code) {
            throw new \RuntimeException("GRPC call failed service is: {$serviceName}, action is: {$action}");
        }

        return $reply->getMessage();
    }

    /**
     * 获取 Grpc 客户端.
     *
     * @param string $serviceName
     * @param int    $timeOut
     * @param bool   $isNew
     * @param null   $channel
     *
     * @return mixed
     * @CreateTime 2020/4/21 15:08:17
     * @Author     : yechangqing@styd.cn
     */
    private function getClient(string $serviceName, int $timeOut, bool $isNew = false, $channel = null)
    {
        if ($isNew) {
            return new $serviceName($this->getHostname(),
                [
                    'credentials' => ChannelCredentials::createInsecure(),
                    'timeout' => $timeOut,
                ],
                $channel);
        }

        if (empty($this->clients[$serviceName])) {
            $this->clients[$serviceName] = new $serviceName($this->getHostname(),
                [
                    'credentials' => ChannelCredentials::createInsecure(),
                    'timeout' => $timeOut,
                ],
                $channel);
        }

        return $this->clients[$serviceName];
    }
}
