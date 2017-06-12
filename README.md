# e快下

e瞳网文件分享平台

<http://down.eeyes.net>

## 时间轴

* 2016年10月15日内部测试
* 2016年10月16日v0.1版正式上线
* 2016年10月17日v1.0版上线，当天v1.1版上线
* 2016年10月26日v2.0版上线
* 2016年11月18日v3.0版完成
* 2017年06月02日v4.0版开始开发

## 部署

### 环境要求

<http://www.kancloud.cn/manual/thinkphp5/118006>

    PHP >= 5.4.0
    PDO PHP Extension

### 部署流程

1. 将代码解压到服务器，注意：服务器根目录是`/public`

2. 新建数据库以及数据库用户

3. 将`.env.example`复制到`.env`并修改其中的数据库配置和后台管理密码，生产环境请关闭`app.debug`

4. 将`application/extra.example`复制到`application/extra`，并修改其中的配置

5. 执行`php think migrate:run`

6. 默认下载文件所在目录是`/public/upload/down/`，图标所在目录是`/public/upload/icon/`。本项目为了防止盗链，要求在Nginx虚拟服务器配置文件中加入

    ```nginx
    location /upload/down/ {
        internal;
    }
    ```

## 说明

* 本项目使用[ThinkPHP5.0](http://www.thinkphp.cn/)开发，开发请参考[ThinkPHP5.0完全开发手册](http://www.kancloud.cn/manual/thinkphp5)
* 访问`/admin`进入后台管理页面

## CONTRIBUTORS

### v4.0

* 设计：[DZ鼎足三分](http://yihan.eeyes.net)
* 前端：[Ganlv](https://github.com/ganlvtech)
* 后端：[Ganlv](https://github.com/ganlvtech)

### v3.0

* 设计：[DZ鼎足三分](http://yihan.eeyes.net)
* 前端：[LouisLv](https://github.com/ensorrow)
* 后端：[Ganlv](https://github.com/ganlvtech)

## LICENSE

    Copyright 2016-2017 eeyes.net
    Licensed under the Apache License, Version 2.0 (the "License");
    you may not use this file except in compliance with the License.
    You may obtain a copy of the License at
    
       http://www.apache.org/licenses/LICENSE-2.0
    
    Unless required by applicable law or agreed to in writing, software
    distributed under the License is distributed on an "AS IS" BASIS,
    WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
    See the License for the specific language governing permissions and
    limitations under the License.
