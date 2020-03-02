### 项目简介
```
本项目是基于 Yii2、Swoole 搭建的 API 服务脚手架，内置请求、响应、日志、异常、JWT 组件便于团体基于此项目快速开发。
本项目适应人群：有 Yii2 开发经验、了解并有 Swoole 使用经验。
```
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
├── models/                ----- 数据模型目录
├── repositories/          ----- 数据操作文件目录
├── module/                ----- 功能模块目录
├── params/                ----- 公共参数目录
├── resource/              ----- 资源目录
    ├── template/
│   └── language/
├── routes/                ----- 路由文件目录
├── rule/                  ----- 规则文件目录
├── runtime/               ----- 临时文件目录
├── server/                ----- 服务文件目录
├── test/                  ----- 单元测试目录
├── composer.json
└── yii
```

### 快速启动
- 安装依赖
```
$> composer install
```

- 设置环境变量
```
$> export RUNTIME_ENV=dev
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

### 生成 Model & Repository

```
$> ./yii gii/model --tableName={tableName} --modelClass={className} # 生成Model文件
$> ./yii gii/repository --modelName={className} # 生成Repo文件
```
