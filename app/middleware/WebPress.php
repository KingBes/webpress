<?php

namespace app\middleware;

use Webman\MiddlewareInterface;
use Webman\Http\Response;
use Webman\Http\Request;

class WebPress implements MiddlewareInterface
{
    public function process(Request $request, callable $handler): Response
    {
        $config = config("webpress");
        // 配置信息
        assign("webpress", $config);
        // seo
        assign([
            "title" => $config["title"],
            "description" => $config["description"],
            "keywords" => $config["keywords"]
        ]);
        return $handler($request);
    }
}
