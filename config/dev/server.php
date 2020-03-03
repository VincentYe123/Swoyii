<?php
/**
 * swoole http server config.
 */
return [
    'process_name' => APP_NAME, //线程名称
    'ip' => '0.0.0.0', //监听地址
    'port' => 9203, //监听端口
    'worker_num' => 1, //Worker 进程数。【默认值：CPU 核数】
    'max_request' => 1, //worker 进程的最大任务数。【默认值：0 即不会退出进程】
    'task_worker_num' => 1, //task 进程的数量。【默认值：未配置则不启动 task】
    'task_max_request' => 1, // task 进程的最大任务数。【默认值：0】
    'dispatch_mode' => 3, //数据包分发策略：1 轮询、2 固定、3 抢占、4 IP分配、5 UID分配，7 stream 模式
    'daemonize' => 0, //守护进程化
    'reload_async' => true, //设置异步重启开关
    'enable_coroutine' => false, //开启异步风格服务器的协程支持
    'log_file' => __DIR__.'/../../log/swoyii.log', //指定 Swoole 错误日志文件
    'pid_file' => __DIR__.'/../../bin/server.pid', //设置 pid 文件地址
];
