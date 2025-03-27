## 目录结构

大致目录结构与 webman 一致，主应用目录为 `app`

```
webpress /
├── app  # 主应用目录
|   ├── assets  # 静态资源目录
|   ├── markdown  # markdown文件目录
|   ├── middleware  # 中间件目录
|   ├── view  # 模板文件目录
|   ├── process  # process目录
|   └─ functions.php  # 公共函数文件
|
|── config  # 配置目录
|   ├── webpress.php  # webpress配置文件
|
...
```

## app目录

1. 静态资源目录

静态资源目录为 `app/assets`，用于存放静态资源文件，如图片、CSS、JS等。

访问静态资源的 URL 为 `/assets/文件名`，如 `/assets/style.css`。

2. markdown文件目录

markdown文件目录为 `app/markdown`，用于存放 markdown 文件。

访问 markdown 文件的 URL 为 主入口，如 `/` 为 `/index.md`。

3. view 模板文件目录

view 模板文件目录为 `app/view`，用于存放模板文件。

## config目录

1. webpress配置文件

- webpress配置文件为 `config/webpress.php`，用于配置 [webpress](/guide/configured) 的相关参数。