<?php

/**
 * This file is part of webman.
 *
 * Licensed under The MIT License
 * For full copyright and license information, please see the MIT-LICENSE.txt
 * Redistributions of files must retain the above copyright notice.
 *
 * @author    walkor<walkor@workerman.net>
 * @copyright walkor<walkor@workerman.net>
 * @link      http://www.workerman.net/
 * @license   http://www.opensource.org/licenses/mit-license.php MIT License
 */

use Webman\Route;

$routeDate = routeDate();

// 首页
Route::get("/", function () {
    $index = getTemplate(config("webpress.directory") . "/index.md");
    // 返回首页数据
    return view("index", $index);
});

// 文档
foreach ($routeDate as $key => $value) {
    Route::get("/" . config("webpress.route_group") . $value["path"], function () use ($value) {
        return view("template/" . $value["data"]["layout"], $value["data"]);
    });

    Route::get($value["path"], function () use ($value) {
        return view("index", $value["data"]);
    });
}

// 静态文件
Route::any("/assets/[{path:.+}]", function ($request, $path = '') {
    // 静态文件目录
    $static_base_path = app_path("assets");
    // 安全检查，避免url里 /../../../password 这样的非法访问
    if (strpos($path, '..') !== false) {
        return response('<h1>400 Bad Request</h1>', 400);
    }
    // 文件
    $file = "$static_base_path/$path";
    if (!is_file($file)) {
        return response('<h1>404 Not Found</h1>', 404);
    }
    return response('')->withFile($file);
});

Route::disableDefaultRoute();
