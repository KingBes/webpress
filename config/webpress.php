<?php

/**
 * 这个是webpress的配置文件
 */
return [
    // 路由组
    "route_group" => "__webpress__",
    // 文档目录
    "directory" => app_path('markdown'),
    // 网站标题
    "title" => "WebPress",
    // 网站LOGO(图片路径)
    "logo" => "/assets/bunny/bunny.png",
    // 网站描述
    "description" => "将 Markdown 变成优雅的文档，只需几分钟",
    // 网站关键词
    "keywords" => "WebPress, Web, Press",
    // 主题配置
    "themeConfig" => [
        // 导航栏
        "nav" => [
            [
                "text" => "首页",
                "link" => "/"
            ],
            [
                "text" => "文档",
                "link" => "/docs/hello",
            ]
        ],
        // 侧边栏
        "sidebar" => [
            "default" => [
                [
                    "text" => "默认",
                    "children" => [
                        [
                            "text" => "第一个内容",
                            "link" => "/docs/hello"
                        ],
                        [
                            "text" => "第二个内容",
                            "link" => "/docs/demo",
                            "children" => [
                                [
                                    "text" => "第三个内容",
                                    "link" => "/docs/other/about"
                                ]
                            ]
                        ]
                    ]
                ]
            ],
            "/docs/other/about" => [
                "text" => "其他",
            ]
        ],
        // 联系信息
        "socialLinks" => [
            ["icon" => "icon icon-github-fill", "link" => "https://github.com/KingBes/webpress"]
        ]
    ]
];
