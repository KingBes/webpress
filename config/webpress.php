<?php

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
                "link" => "/docs/guide",
            ],
            [
                "text" => "参考",
                "link" => "/docs/refer",
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
                            "link" => "/docs/guide"
                        ],
                        [
                            "text" => "快速开始",
                            "link" => "/docs/demo"
                        ]
                    ]
                ]
            ],
            "/docs/other/about" => [
                ["text" => "其他"]
            ]
        ],
        // 联系信息
        "socialLinks" => [
            ["icon" => "icon icon-github-fill", "link" => "https://github.com/KingBes/webpress"],
            ["img" => "/assets/gitcode.png", "link" => "https://gitcode.com/Simmah/webpress"]
        ]
    ]
];
