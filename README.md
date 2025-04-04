# WebPress 

🎉 由 webman 和 bunnyUI 组建的文档网站

将 Markdown 变成优雅的文档，只需几分钟

[在线站点](http://webpress.kllxs.top/)

## 快速开始

- [webman](https://www.workerman.net/doc/webman/) 文档。

- [BunnyUI](https://github.com/workbunny/bunny-ui) 文档。

- [webpress](https://gitcode.com/Simmah/webpress) 文档。

### 环境要求

- PHP >= 8.1
- [Composer](https://getcomposer.org/) >= 2.0

### 安装

`gitcode`安装(国内推荐)

```bash
git clone https://gitcode.com/Simmah/webpress.git
```

`github`安装

```bash
git clone https://github.com/KingBes/webpress.git
```

`Composer`安装依赖, 请确保已经安装了 `Composer`，进入webpress目录，运行以下命令：

```bash
composer install
```

#### 运行

进入webpress目录

##### windows用户

双击 `windows.bat` 或者运行 `php windows.php` 启动

##### linux用户

调试方式运行（用于开发调试，打印数据会显示在终端，终端关闭后webman服务也随之关闭）

```bash
php start.php start
```

守护进程方式运行（用于正式环境，打印数据不会显示在终端，终端关闭后webman服务会持续运行）

```bash
php start.php start -d
```
##### 访问

浏览器访问 `http://ip地址:8787`