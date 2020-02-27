### 环境依赖
- `PHP-7.1` 或更高版本
- `Swoole-4.4.x`
- `Redis-4.2.0`
- `Grpc-1.17.0`

### 目录结构

```
├── app/    ----- 应用代码目录
│   ├── common/            ----- 具有独立功能的 class bean
│   ├── components/        ----- 组件代码目录
│   ├── console/           ----- 命令行代码目录
│   ├── exceptions/        ----- 定义异常类目录
│   ├── helper/            ----- 助手函数
│   ├── http/              ----- HTTP 服务代码目录
│   │   ├── controller/
│   │   └── service/
│   ├── middlewares/       ----- 中间件代码目录
│   ├── models/            ----- 模型、逻辑等代码目录
│   │   ├── dao/
│   │   └── entity/
│   ├── routes/            ----- 路由代码目录
│   └── rules/             ----- 校验规则代码目录
├── bin/
│   ├── server.pid
│   └── swoyii             ----- Swoyii 入口文件
├── config/                ----- 应用配置目录
│   ├── dev                ----- 开发环境配置目录
│   └── local              ----- 本地环境配置目录
├── logs/                  ----- 日志文件存放目录
├── public/                ----- 公共目录
├── resource/              ----- 应用资源目录
│   └── language/              ----- 语言资源目录  
├── runtime/               ----- 临时文件目录
├── servers/               ----- 服务文件目录
├── test/                  ----- 单元测试目录
├── composer.json
└── yii
```