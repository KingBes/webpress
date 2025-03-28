<?php

namespace app\common;


class Tool
{
    /**
     * assign function
     *
     * @param string|array $name
     * @param mixed $value
     * @param string|null $plugin
     * @return void
     */
    public function assign(string|array $name, mixed $value = null, string $plugin = null)
    {
        $request = \request();
        $plugin = $plugin === null ? ($request->plugin ?? '') : $plugin;
        $handler = \config($plugin ? "plugin.$plugin.view.handler" : 'view.handler');
        $handler::assign($name, $value);
    }

    /**
     * 获取路由数据
     *
     * @return array
     */
    public function routeDate(): array
    {
        // 入口文件夹
        $dir = config("webpress.base.directory");
        // 文件夹后缀
        $suffix = "md";
        // 遍历获取文件夹下的所有md文件
        $files = new \RecursiveIteratorIterator(
            new \RecursiveDirectoryIterator(
                $dir,
                \RecursiveDirectoryIterator::SKIP_DOTS
            )
        );
        $arr = [];
        // 遍历文件夹
        foreach ($files as $file) {
            if ($file->isFile() && $file->getExtension() === $suffix) {
                $arr[] = [
                    // 获取文件路径
                    "file" => $file->getPathname(),
                    // 获取文件名
                    "name" => $file->getFilename(),
                    // 获取文件相对路径
                    "path" => str_replace(
                        [$dir, ".{$suffix}", "\\"],
                        ["", "", "/"],
                        $file->getPathname()
                    ),
                    // 获取数据
                    "data" => $this->getTemplate($file->getPathname()),
                    // 获取文件创建时间
                    "time" => $file->getCTime(),
                    // 获取文件修改时间
                    "mtime" => $file->getMTime(),
                ];
            }
        }
        return $arr;
    }

    /**
     * 获取模板数据
     *
     * @param string $file 模板文件
     * @return array
     */
    public function getTemplate(string $file): array
    {
        $content = file_get_contents($file);
        $arr = $this->splitString($content);
        $data = \Symfony\Component\Yaml\Yaml::parse($arr[0]);
        $data["__CONTENT__"] = (new \Parsedown)->text($arr[1]);
        if (!isset($data["layout"])) {
            $data["layout"] = "default";
        }
        return $data;
    }

    /**
     * 分割字符串
     *
     * @param string $str
     * @return array
     */
    public function splitString(string $str): array
    {
        if (substr($str, 0, 3) !== '---') {
            return ['', $str]; // 非---开头直接返回
        }
        $pattern = '/^---(.*?)---(.*)$/s';
        if (preg_match($pattern, $str, $matches)) {
            return [trim($matches[1]), trim($matches[2])];
        }
        return ['', $str];
    }

    /**
     * 导航栏无限级菜单
     *
     * @param array $items
     * @return string
     */
    public function generateMenu(array $items): string
    {
        $html = '';
        foreach ($items as $item) {
            $html .= '<li class="bny-nav-item">';
            $link = $item['link'] ?? 'javascript:;';
            $html .= sprintf('<a href="%s">', htmlspecialchars($link, ENT_QUOTES));
            $html .= '<cite>' . htmlspecialchars($item['text']) . '</cite>';

            if (!empty($item['children'])) {
                $html .= '<i class="icon icon-you2 arrow"></i>';
            }

            $html .= '</a>';

            if (!empty($item['children'])) {
                $html .= '<ul class="bny-nav-sub">';
                $html .= $this->generateMenu($item['children']);
                $html .= '</ul>';
            }

            $html .= '</li>';
        }
        return $html;
    }

    /**
     * 左侧栏菜单
     *
     * @param array $items
     * @return string
     */
    public function getSidebar(array $items): string
    {
        $html = '';
        foreach ($items as $item) {
            $show = empty($item['children']) === false ? "showMenu" : "";
            $html .= '<li class="' . $show . '">';
            $link = $item['link'] ?? 'javascript:;';
            if (!empty($item['children'])) {
                $html .= '<div class="bny-nav-iocn">';
            }
            $html .= sprintf('<a href="%s">', htmlspecialchars($link, ENT_QUOTES));
            $html .= htmlspecialchars($item['text']);
            $html .= '</a>';
            if (!empty($item['children'])) {
                $html .= '<i class="icon icon-xia2 arrow"></i>';
                $html .= '</div>';
            }
            if (!empty($item['children'])) {
                $html .= '<ul class="bny-nav-child">';
                $html .= $this->getSidebar($item['children']);
                $html .= '</ul>';
            }
        }
        return $html;
    }


    public function searchMdByKeyword(string $mdString, string $keyword)
    {
        $lines = explode("\n", $mdString);
        $blocks = [];
        $currentBlock = null;
        $inCodeBlock = false;

        foreach ($lines as $line) {
            // 处理代码块标记
            if (strpos(trim($line), '```') === 0) {
                $inCodeBlock = !$inCodeBlock;
                continue;
            }
            if ($inCodeBlock) {
                continue;
            }

            // 检查标题行
            if (preg_match('/^(#+)\s+(.*)/', $line, $matches)) {
                if ($currentBlock !== null) {
                    $blocks[] = $currentBlock;
                }
                $currentBlock = [
                    'level' => strlen($matches[1]),
                    'title' => $matches[2],
                    'content' => []
                ];
            } else {
                if ($currentBlock !== null) {
                    $currentBlock['content'][] = $line;
                }
            }
        }
        if ($currentBlock !== null) {
            $blocks[] = $currentBlock;
        }

        // 搜索包含关键词的块
        foreach ($blocks as $block) {
            foreach ($block['content'] as $contentLine) {
                if (strpos($contentLine, $keyword) !== false) {
                    $result = str_repeat('#', $block['level']) . ' ' . $block['title'] . "\n\n";
                    $result .= implode("\n", $block['content']);
                    return $result;
                }
            }
        }

        return '';
    }
}
