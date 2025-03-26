<?php

namespace app\middleware;

use Webman\MiddlewareInterface;
use Webman\Http\Response;
use Webman\Http\Request;

class WebPress implements MiddlewareInterface
{
    public function process(Request $request, callable $handler): Response
    {
        $webpress = config("webpress");
        // 基础配置
        $base = $webpress["base"];
        assign("base", $base);
        // 网站配置
        $webInfo = $webpress["webInfo"];
        assign($webInfo);
        // 导航栏
        $nav = $webpress["themeConfig"]["nav"];
        assign("nav", $nav);
        // 联系信息
        $contact = $webpress["themeConfig"]["socialLinks"];
        assign("contact", $contact);
        // 侧边栏
        $path = $request->path();
        if ($path == "/") {
            $path = "/index";
        }
        $data = config("webpress.themeConfig.sidebar");
        if (!isset($data[str_replace("/" . $webpress["base"]["routeGroup"], "", $path)])) {
            $sidebar = $data["default"];
        } else {
            $sidebar = $data[str_replace("/" . $webpress["base"]["routeGroup"], "", $path)];
        }
        assign("sidebar", $sidebar);
        return $handler($request);
    }
}
