## skeleton

### 介绍

[skeleton](https://github.com/anziguoer/skeleton) 是一个使用 Hyperf 框架的框架应用程序。包含了基本的系统基础功能， 权限设置验证，角色管理，用户管理， 菜单管理等。

[skeleton](https://github.com/anziguoer/skeleton) 项目只有后端代码， 所有的数据都是基于接口请求。所以需要结合前后端分离项目 [skeleton-admin](https://github.com/anziguoer/skeleton-admin) ，

### 要求

Hyperf 对系统环境有一定要求，只能在 Linux 和 Mac 环境下运行，但由于 Docker 虚拟化技术的发展，Docker for Windows 也可以作为 Windows 下的运行环境。
如果要使用 docker 部署此程序， 请参考 hyperf docker 部署教程。

#### 环境依赖

```
PHP >= 7.2
Swoole PHP extension >= 4.4，(并关闭短标记)
OpenSSL PHP extension
JSON PHP extension
PDO PHP extension （如果要用到mysql， 需要启用改扩展）
Redis PHP extension （如果要用到redis， 需要启用改扩展）
Protobuf PHP extension （如果要用到gRPC， 需要启用改扩展）
Installation using Composer 安装composer

```

#### git 拉取代码

```
    git clone https://github.com/anziguoer/skeleton.git path/to/install
```

#### 安装依赖

```
    composer install
```

#### 配置环境

```
[项目配置]
APP_NAME=skeleton
APP_ENV=dev
APP_URL=http://localhost:9501

[mysql 配置]
DB_DRIVER=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=skeleton
DB_USERNAME=root
DB_PASSWORD=root
DB_CHARSET=utf8mb4
DB_COLLATION=utf8mb4_unicode_ci
DB_PREFIX=

[redis配置]
REDIS_HOST=localhost
REDIS_AUTH=(null)
REDIS_PORT=6379
REDIS_DB=0

# 默认管理员的账户ID
ADMINISTRATOR_ID=1

[jwt token key]
SIMPLE_JWT_SECRET=
```

#### 数据库

导入 `/storage/databases/` 下面的所有 sql 文件

#### 启动项目

```
cd path/to/install

php bin/hyperf.php start
```

#### 初始账户密码

    账户: admin@admin.com
    密码: admin123

### 骨架基本功能

    [*] 用户管理
    [*] 权限管理
    [*] 菜单管理
    [*] 角色管理
    [*] 中间件 （权限、用户认证）

### QQ 交流群： 1031212459

### 如果你觉得这个项目帮助到了你，你可以帮作者买一杯果汁表示鼓励 🍹

![alipay](./public/screenshot/alipay.png)
![alipay](./public/screenshot/wechat_pay.png)

### QQ 交流： 1031212459

### 微信交流：加我请备注说明

![wechat](./public/screenshot/friends.png)

Copyright (c) 2020-anziguoer
