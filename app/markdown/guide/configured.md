## 配置

> 配置文件位于 `config/webpress.php` 中。

```php
/**
 * 这个是webpress的配置文件
 */
return [
    // 基础配置
    "base" => [
        // 路由组
        "routeGroup" => "__webpress__",
        // 文档目录
        "directory" => app_path('markdown'),
    ],
    // 网站配置
    "webInfo" => [
        // 网站标题
        "title" => "WebPress",
        // 网站LOGO(图片路径)
        "logo" => "/assets/bunny/bunny.png",
        // 网站描述
        "description" => "将 Markdown 变成优雅的文档，只需几分钟",
        // 网站关键词
        "keywords" => "WebPress, Web, Press",
        // 备案号
        "icp" => "粤ICP备******号",
    ],
    // 主题配置
    "themeConfig" => [
        // 导航栏
        "nav" => [
            [
                "text" => "首页",
                "link" => "/"
            ],
            [
                "text" => "指南",
                "link" => "/guide/webpress",
            ],
            [
                "text" => "参考",
                "link" => "/refer/demo",
            ]
        ],
        // 侧边栏
        "sidebar" => [
            "default" => [
                [
                    "text" => "简介",
                    "children" => [
                        [
                            "text" => "什么是 WebPress ?",
                            "link" => "/guide/webpress"
                        ],
                        [
                            "text" => "快速开始",
                            "link" => "/guide/introduction"
                        ]
                    ]
                ]
            ],
            "/refer" => [
                [
                    "text" => "其他",
                ]
            ]
        ],
        // 联系信息
        "socialLinks" => [
            ["icon" => "icon icon-github-fill", "link" => "https://github.com/KingBes/webpress"],
            ["img" => "/assets/gitcode.png", "link" => "https://gitcode.com/Simmah/webpress"]
        ]
    ]
];
```

### 基础配置

`routeGroup` 配置路由前缀名称，默认值为 `__webpress__`。

`directory` 配置文档目录，默认值为 `app_path('markdown')`。

```php
// 基础配置
"base" => [
    // 路由组
    "routeGroup" => "__webpress__",
    // 文档目录
    "directory" => app_path('markdown'),
]
```

### 网站配置

`title` 配置网站标题，默认值为 `WebPress`。

`logo` 配置网站LOGO(图片路径)，默认值为 `/assets/bunny/bunny.png`。

`description` 配置网站描述，默认值为 `将 Markdown 变成优雅的文档，只需几分钟`。

`keywords` 配置网站关键词，默认值为 `WebPress, Web, Press`。

`icp` 配置备案号，默认值为 `粤ICP备******号`。

```php
// 网站配置
"webInfo" => [
    // 网站标题
    "title" => "WebPress",
    // 网站LOGO(图片路径)
    "logo" => "/assets/bunny/bunny.png",
    // 网站描述
    "description" => "将 Markdown 变成优雅的文档，只需几分钟",
    // 网站关键词
    "keywords" => "WebPress, Web, Press",
    // 备案号
    "icp" => "粤ICP备******号",
]
```

### 主题配置

用于配置主题相关的配置，例如导航栏、侧边栏、联系信息等。

```php
"themeConfig" => [
    ...
]
```

#### 导航栏

用于配置导航栏的配置，例如导航栏的文本、链接等。

`txt` 配置导航栏的文本。

`link` 配置导航栏的链接。

```php
"nav" => [
    [
        "text" => "首页",
        "link" => "/"
    ],
    [
        "text" => "指南",
        "link" => "/guide/webpress",
    ]
]
```

#### 侧边栏

用于配置侧边栏的配置，例如侧边栏的文本、链接等。

`default` 默认侧边栏配置

`/refer` 自定义侧边栏配置,(会匹配路由前缀为 `/refer` 的路由)

```php
// 侧边栏
"sidebar" => [
    "default" => [
        [
            "text" => "简介",
            "children" => [
                [
                    "text" => "什么是 WebPress ?",
                    "link" => "/guide/webpress"
                ],
                [
                    "text" => "快速开始",
                    "link" => "/guide/introduction"
                ]
            ]
        ]
    ],
    "/refer" => [
        [
            "text" => "其他",
        ]
    ],
    // 自定义侧边栏配置
    ...
]
```

#### 联系信息

用于配置联系信息，例如联系信息的图标、链接等。

`icon` 为图标类名
`img` 为图片路径（img和icon必须选一个）
`link` 为链接地址

```php
// 联系信息
"socialLinks" => [
    ["icon" => "icon icon-github-fill", "link" => "https://github.com/KingBes/webpress"],
    ["img" => "/assets/gitcode.png", "link" => "https://gitcode.com/Simmah/webpress"]
]
```