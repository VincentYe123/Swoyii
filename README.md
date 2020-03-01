### 环境依赖
- `PHP >= 7.1`
- `Swoole >= 4.4.16`

### 目录结构

```
├── bin/                   ----- 程序入口目录
│   ├── server.pid
│   └── swoyii
├── command/               ----- 命令行代码目录
├── common/                ----- 具有独立功能的 class bean
├── component/             ----- 组件代码目录
├── config/                ----- 应用配置目录
│   ├── dev                ----- 开发环境配置目录
│   └── local              ----- 本地环境配置目录
├── exception/             ----- 异常类目录
├── helper/                ----- 助手函数
├── log/                   ----- 日志文件存放目录
├── middleware/            ----- 中间件代码目录
├── model/                 ----- 模型、逻辑等代码目录
│   ├── dao/
│   └── entity/
├── module/                ----- 功能模块目录
├── params/                ----- 公告参数目录
├── resource/              ----- 资源目录
    ├── template/
│   └── language/
├── routes/                ----- 路由文件目录
├── rules/                 ----- 规则文件目录
├── runtime/               ----- 临时文件目录
├── server/                ----- 服务文件目录
├── test/                  ----- 单元测试目录
├── composer.json
└── yii
```

### 快速启动
- 安装Vendor
```
$> composer install
```

- 启动服务器
```
$> ./bin/swoyii start
```

- 如果一切顺利，运行到最后你将看到如下的输出：
```
                             .__.__ 
  ________  _  ______ ___.__.|__|__|
 /  ___/\ \/ \/ /  _ <   |  ||  |  |
 \___ \  \     (  <_> )___  ||  |  |
/____  >  \/\_/ \____// ____||__|__|
     \/               \/
Server         Name:      swoyii
System         Name:      Darwin
PHP            Version:   7.2.12
Swoole         Version:   4.4.16
Framework      Version:   2.0.32
Listen         Addr:      0.0.0.0
Listen         Port:      9203
[info] 2020-02-28 15:07:53 Http Server Start, Pid is 88624
[info] 2020-02-28 15:07:53 Task Worker #1 Start 
[info] 2020-02-28 15:07:53 Worker #0 Start 
```