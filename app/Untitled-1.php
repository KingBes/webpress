<?php

namespace app;

/**
 * Markdown 类，用于将 Markdown 文本转换为 HTML 标记。
 */
class markdown
{
    // 定义版本号
    const version = '0.0.1';

    /**
     * 将输入的文本转换为 HTML 标记。
     *
     * 该方法接收一个文本字符串，将其转换为 Markdown 元素，然后将这些元素转换为 HTML 标记。
     * 最后，它会去除 HTML 标记中的首尾换行符。
     *
     * @param string $text 输入的 Markdown 文本。
     * @return string 转换后的 HTML 标记。
     */
    function text($text)
    {
        // 将输入的文本转换为 Markdown 元素
        $Elements = $this->textElements($text);

        // 将 Markdown 元素转换为 HTML 标记
        $markup = $this->elements($Elements);

        // 去除 HTML 标记中的首尾换行符
        $markup = trim($markup, "\n");

        return $markup;
    }

    /**
     * 将输入的文本转换为 Markdown 元素数组。
     *
     * 该方法接收一个文本字符串，执行一系列预处理步骤，包括重置定义数据、标准化换行符、去除首尾换行符和分割文本为行。
     * 然后调用`linesElements`方法将这些行转换为 Markdown 元素数组。
     *
     * @param string $text 输入的 Markdown 文本。
     * @return array 转换后的 Markdown 元素数组。
     */
    protected function textElements($text)
    {
        // 确保没有设置任何定义数据，重置定义数据数组
        $this->DefinitionData = array();

        // 标准化换行符，将 Windows 和 Mac 的换行符统一转换为 Unix 的换行符
        $text = str_replace(array("\r\n", "\r"), "\n", $text);

        // 去除文本首尾的换行符
        $text = trim($text, "\n");

        // 将文本按换行符分割成数组，每行作为一个元素
        $lines = explode("\n", $text);

        // 遍历每一行，识别 Markdown 块并转换为元素数组
        return $this->linesElements($lines);
    }

    /**
     * 设置是否启用换行符转换为 HTML 换行标签。
     *
     * @param bool $breaksEnabled 是否启用换行符转换。
     * @return $this 返回当前对象实例，支持链式调用。
     */
    function setBreaksEnabled($breaksEnabled)
    {
        $this->breaksEnabled = $breaksEnabled;

        return $this;
    }

    // 存储是否启用换行符转换的标志
    protected $breaksEnabled;

    /**
     * 设置是否对 HTML 标记进行转义。
     *
     * @param bool $markupEscaped 是否对 HTML 标记进行转义。
     * @return $this 返回当前对象实例，支持链式调用。
     */
    function setMarkupEscaped($markupEscaped)
    {
        $this->markupEscaped = $markupEscaped;

        return $this;
    }

    // 存储是否对 HTML 标记进行转义的标志
    protected $markupEscaped;

    /**
     * 设置是否自动将 URL 转换为可点击的链接。
     *
     * @param bool $urlsLinked 是否自动将 URL 转换为可点击的链接。
     * @return $this 返回当前对象实例，支持链式调用。
     */
    function setUrlsLinked($urlsLinked)
    {
        $this->urlsLinked = $urlsLinked;

        return $this;
    }

    // 存储是否自动将 URL 转换为可点击的链接的标志，默认启用
    protected $urlsLinked = true;

    /**
     * 设置是否启用安全模式。
     *
     * @param bool $safeMode 是否启用安全模式。
     * @return $this 返回当前对象实例，支持链式调用。
     */
    function setSafeMode($safeMode)
    {
        $this->safeMode = (bool) $safeMode;

        return $this;
    }

    // 存储是否启用安全模式的标志
    protected $safeMode;

    /**
     * 设置是否启用严格模式。
     *
     * @param bool $strictMode 是否启用严格模式。
     * @return $this 返回当前对象实例，支持链式调用。
     */
    function setStrictMode($strictMode)
    {
        $this->strictMode = (bool) $strictMode;

        return $this;
    }

    // 存储是否启用严格模式的标志
    protected $strictMode;

    // 安全链接的白名单，用于在安全模式下验证链接
    protected $safeLinksWhitelist = array(
        'http://',
        'https://',
        'ftp://',
        'ftps://',
        'mailto:',
        'tel:',
        'data:image/png;base64,',
        'data:image/gif;base64,',
        'data:image/jpeg;base64,',
        'irc:',
        'ircs:',
        'git:',
        'ssh:',
        'news:',
        'steam:',
    );

    #
    # Lines
    #

    // 定义不同行首字符对应的块类型
    protected $BlockTypes = array(
        '#' => array('Header'),
        '*' => array('Rule', 'List'),
        '+' => array('List'),
        '-' => array('SetextHeader', 'Table', 'Rule', 'List'),
        '0' => array('List'),
        '1' => array('List'),
        '2' => array('List'),
        '3' => array('List'),
        '4' => array('List'),
        '5' => array('List'),
        '6' => array('List'),
        '7' => array('List'),
        '8' => array('List'),
        '9' => array('List'),
        ':' => array('Table'),
        '<' => array('Comment', 'Markup'),
        '=' => array('SetextHeader'),
        '>' => array('Quote'),
        '[' => array('Reference'),
        '_' => array('Rule'),
        '`' => array('FencedCode'),
        '|' => array('Table'),
        '~' => array('FencedCode'),
    );

    # ~

    // 定义无需特定行首字符的块类型
    protected $unmarkedBlockTypes = array(
        'Code',
    );

    #
    # Blocks
    #

    /**
     * 将多行文本转换为 HTML 标记。
     *
     * @param array $lines 多行文本数组。
     * @return string 转换后的 HTML 标记。
     */
    protected function lines(array $lines)
    {
        return $this->elements($this->linesElements($lines));
    }

    /**
     * 将多行文本转换为 Markdown 元素数组。
     *
     * 该方法遍历每一行文本，根据行首字符和缩进识别不同的 Markdown 块类型，
     * 并将其转换为对应的元素数组。
     *
     * @param array $lines 多行文本数组。
     * @return array 转换后的 Markdown 元素数组。
     */
    protected function linesElements(array $lines)
    {
        // 存储转换后的 Markdown 元素数组
        $Elements = array();
        // 当前正在处理的块
        $CurrentBlock = null;

        // 遍历每一行文本
        foreach ($lines as $line) {
            // 如果当前行为空行
            if (chop($line) === '') {
                if (isset($CurrentBlock)) {
                    // 记录当前块被中断的次数
                    $CurrentBlock['interrupted'] = (isset($CurrentBlock['interrupted'])
                        ? $CurrentBlock['interrupted'] + 1 : 1
                    );
                }

                // 跳过当前空行，继续处理下一行
                continue;
            }

            // 处理制表符，将其转换为空格
            while (($beforeTab = strstr($line, "\t", true)) !== false) {
                // 计算制表符前的字符长度与 4 的余数，确定需要补充的空格数
                $shortage = 4 - mb_strlen($beforeTab, 'utf-8') % 4;

                // 将制表符替换为相应数量的空格
                $line = $beforeTab
                    . str_repeat(' ', $shortage)
                    . substr($line, strlen($beforeTab) + 1);
            }

            // 计算当前行的缩进空格数
            $indent = strspn($line, ' ');

            // 获取去除缩进后的文本
            $text = $indent > 0 ? substr($line, $indent) : $line;

            # ~

            // 存储当前行的信息，包括原始行、缩进和去除缩进后的文本
            $Line = array('body' => $line, 'indent' => $indent, 'text' => $text);

            # ~

            // 如果当前块可以继续处理
            if (isset($CurrentBlock['continuable'])) {
                // 构建继续处理当前块的方法名
                $methodName = 'block' . $CurrentBlock['type'] . 'Continue';
                // 调用相应的方法继续处理当前块
                $Block = $this->$methodName($Line, $CurrentBlock);

                if (isset($Block)) {
                    // 更新当前块为继续处理后的块
                    $CurrentBlock = $Block;

                    // 跳过后续处理，继续处理下一行
                    continue;
                } else {
                    // 如果当前块可以完成处理
                    if ($this->isBlockCompletable($CurrentBlock['type'])) {
                        // 构建完成处理当前块的方法名
                        $methodName = 'block' . $CurrentBlock['type'] . 'Complete';
                        // 调用相应的方法完成处理当前块
                        $CurrentBlock = $this->$methodName($CurrentBlock);
                    }
                }
            }

            # ~

            // 获取当前行的第一个字符，作为标记字符
            $marker = $text[0];

            # ~

            // 初始化块类型数组，包含无需特定行首字符的块类型
            $blockTypes = $this->unmarkedBlockTypes;

            // 如果标记字符在预定义的块类型映射中
            if (isset($this->BlockTypes[$marker])) {
                // 将对应的块类型添加到块类型数组中
                foreach ($this->BlockTypes[$marker] as $blockType) {
                    $blockTypes[] = $blockType;
                }
            }

            #
            # ~

            // 遍历所有可能的块类型
            foreach ($blockTypes as $blockType) {
                // 构建处理当前块类型的方法名
                $Block = $this->{"block$blockType"}($Line, $CurrentBlock);

                if (isset($Block)) {
                    // 设置当前块的类型
                    $Block['type'] = $blockType;

                    // 如果当前块未被识别过
                    if (! isset($Block['identified'])) {
                        if (isset($CurrentBlock)) {
                            // 将当前块转换为元素并添加到元素数组中
                            $Elements[] = $this->extractElement($CurrentBlock);
                        }

                        // 标记当前块已被识别
                        $Block['identified'] = true;
                    }

                    // 如果当前块可以继续处理
                    if ($this->isBlockContinuable($blockType)) {
                        // 标记当前块为可继续处理
                        $Block['continuable'] = true;
                    }

                    // 更新当前块为新的块
                    $CurrentBlock = $Block;

                    // 跳过后续块类型的检查，继续处理下一行
                    continue 2;
                }
            }

            # ~

            // 如果当前块为段落类型
            if (isset($CurrentBlock) and $CurrentBlock['type'] === 'Paragraph') {
                // 尝试继续处理当前段落块
                $Block = $this->paragraphContinue($Line, $CurrentBlock);
            }

            if (isset($Block)) {
                // 更新当前块为继续处理后的块
                $CurrentBlock = $Block;
            } else {
                if (isset($CurrentBlock)) {
                    // 将当前块转换为元素并添加到元素数组中
                    $Elements[] = $this->extractElement($CurrentBlock);
                }

                // 创建一个新的段落块
                $CurrentBlock = $this->paragraph($Line);

                // 标记新的段落块已被识别
                $CurrentBlock['identified'] = true;
            }
        }

        # ~

        // 如果当前块可以继续处理且可以完成处理
        if (isset($CurrentBlock['continuable']) and $this->isBlockCompletable($CurrentBlock['type'])) {
            // 构建完成处理当前块的方法名
            $methodName = 'block' . $CurrentBlock['type'] . 'Complete';
            // 调用相应的方法完成处理当前块
            $CurrentBlock = $this->$methodName($CurrentBlock);
        }

        # ~

        if (isset($CurrentBlock)) {
            // 将当前块转换为元素并添加到元素数组中
            $Elements[] = $this->extractElement($CurrentBlock);
        }

        # ~

        // 返回转换后的 Markdown 元素数组
        return $Elements;
    }

    /**
     * 从组件数组中提取元素。
     *
     * 如果组件数组中未定义元素，则根据组件的标记或隐藏状态创建元素。
     *
     * @param array $Component 组件数组。
     * @return array 提取的元素数组。
     */
    protected function extractElement(array $Component)
    {
        // 如果组件数组中未定义元素
        if (! isset($Component['element'])) {
            // 如果组件数组中定义了标记
            if (isset($Component['markup'])) {
                // 创建一个包含原始 HTML 标记的元素
                $Component['element'] = array('rawHtml' => $Component['markup']);
            // 如果组件数组中定义了隐藏状态
            } elseif (isset($Component['hidden'])) {
                // 创建一个空元素
                $Component['element'] = array();
            }
        }

        // 返回提取的元素数组
        return $Component['element'];
    }

    /**
     * 检查指定类型的块是否可以继续处理。
     *
     * 通过检查是否存在对应的继续处理方法来判断。
     *
     * @param string $Type 块的类型。
     * @return bool 如果可以继续处理返回 true，否则返回 false。
     */
    protected function isBlockContinuable($Type)
    {
        // 检查是否存在以 'block' 开头，后跟块类型和 'Continue' 的方法
        return method_exists($this, 'block' . $Type . 'Continue');
    }

    /**
     * 检查指定类型的块是否可以完成处理。
     *
     * 通过检查是否存在对应的完成处理方法来判断。
     *
     * @param string $Type 块的类型。
     * @return bool 如果可以完成处理返回 true，否则返回 false。
     */
    protected function isBlockCompletable($Type)
    {
        // 检查是否存在以 'block' 开头，后跟块类型和 'Complete' 的方法
        return method_exists($this, 'block' . $Type . 'Complete');
    }

    #
    # Code

    /**
     * 处理代码块。
     *
     * 如果当前行的缩进大于等于 4，则将其视为代码块。
     *
     * @param array $Line 当前行的信息，包括原始行、缩进和去除缩进后的文本。
     * @param array|null $Block 当前正在处理的块，默认为 null。
     * @return array|null 如果是代码块，返回包含代码块元素的数组；否则返回 null。
     */
    protected function blockCode($Line, $Block = null)
    {
        // 如果当前块为段落类型且未被中断
        if (isset($Block) and $Block['type'] === 'Paragraph' and ! isset($Block['interrupted'])) {
            return;
        }

        // 如果当前行的缩进大于等于 4
        if ($Line['indent'] >= 4) {
            // 获取去除前 4 个缩进空格后的文本
            $text = substr($Line['body'], 4);

            // 构建代码块元素数组
            $Block = array(
                'element' => array(
                    'name' => 'pre',
                    'element' => array(
                        'name' => 'code',
                        'text' => $text,
                    ),
                ),
            );

            // 返回代码块元素数组
            return $Block;
        }
    }

    /**
     * 继续处理代码块。
     *
     * 如果当前行的缩进大于等于 4，则将其内容添加到代码块中。
     *
     * @param array $Line 当前行的信息，包括原始行、缩进和去除缩进后的文本。
     * @param array $Block 当前正在处理的代码块。
     * @return array|null 如果可以继续处理，返回更新后的代码块元素数组；否则返回 null。
     */
    protected function blockCodeContinue($Line, $Block)
    {
        // 如果当前行的缩进大于等于 4
        if ($Line['indent'] >= 4) {
            // 如果代码块之前被中断过
            if (isset($Block['interrupted'])) {
                // 在代码块内容中添加相应数量的换行符
                $Block['element']['element']['text'] .= str_repeat("\n", $Block['interrupted']);

                // 移除中断标记
                unset($Block['interrupted']);
            }

            // 在代码块内容中添加换行符
            $Block['element']['element']['text'] .= "\n";

            // 获取去除前 4 个缩进空格后的文本
            $text = substr($Line['body'], 4);

            // 将当前行的文本添加到代码块内容中
            $Block['element']['element']['text'] .= $text;

            // 返回更新后的代码块元素数组
            return $Block;
        }
    }

    /**
     * 完成处理代码块。
     *
     * 目前只是简单返回当前代码块，可根据需要添加更多处理逻辑。
     *
     * @param array $Block 当前正在处理的代码块。
     * @return array 完成处理后的代码块元素数组。
     */
    protected function blockCodeComplete($Block)
    {
        // 返回当前代码块元素数组
        return $Block;
    }

    #
    # Comment

    /**
     * 处理 HTML 注释块。
     *
     * 如果当前行以 '<!--' 开头，且未启用标记转义或安全模式，则将其视为 HTML 注释块。
     *
     * @param array $Line 当前行的信息，包括原始行、缩进和去除缩进后的文本。
     * @return array|null 如果是 HTML 注释块，返回包含注释块元素的数组；否则返回 null。
     */
    protected function blockComment($Line)
    {
        // 如果启用了标记转义或安全模式，不处理 HTML 注释块
        if ($this->markupEscaped or $this->safeMode) {
            return;
        }

        // 如果当前行以 '<!--' 开头
        if (strpos($Line['text'], '<!--') === 0) {
            // 构建 HTML 注释块元素数组
            $Block = array(
                'element' => array(
                    'rawHtml' => $Line['body'],
                    'autobreak' => true,
                ),
            );

            // 如果当前行包含 '-->，表示注释块已结束
            if (strpos($Line['text'], '-->') !== false) {
                $Block['closed'] = true;
            }

            // 返回 HTML 注释块元素数组
            return $Block;
        }
    }

    /**
     * 继续处理 HTML 注释块。
     *
     * 如果注释块未结束，则将当前行的内容添加到注释块中。
     *
     * @param array $Line 当前行的信息，包括原始行、缩进和去除缩进后的文本。
     * @param array $Block 当前正在处理的 HTML 注释块。
     * @return array|null 如果可以继续处理，返回更新后的注释块元素数组；否则返回 null。
     */
    protected function blockCommentContinue($Line, array $Block)
    {
        // 如果注释块已结束，不继续处理
        if (isset($Block['closed'])) {
            return;
        }

        // 将当前行的内容添加到注释块的原始 HTML 标记中
        $Block['element']['rawHtml'] .= "\n" . $Line['body'];

        // 如果当前行包含 '-->，表示注释块已结束
        if (strpos($Line['text'], '-->') !== false) {
            $Block['closed'] = true;
        }

        // 返回更新后的注释块元素数组
        return $Block;
    }

    #
    # Fenced Code

    /**
     * 处理围栏代码块。
     *
     * 如果当前行以至少 3 个相同的字符（如 ``` 或 ~~~）开头，则将其视为围栏代码块。
     *
     * @param array $Line 当前行的信息，包括原始行、缩进和去除缩进后的文本。
     * @return array|null 如果是围栏代码块，返回包含代码块元素的数组；否则返回 null。
     */
    protected function blockFencedCode($Line)
    {
        // 获取当前行的第一个字符，作为围栏代码块的标记字符
        $marker = $Line['text'][0];

        // 计算标记字符的连续出现次数
        $openerLength = strspn($Line['text'], $marker);

        // 如果标记字符的连续出现次数小于 3，不是围栏代码块
        if ($openerLength < 3) {
            return;
        }

        // 获取围栏代码块的信息字符串，去除首尾的制表符和空格
        $infostring = trim(substr($Line['text'], $openerLength), "\t ");

        // 如果信息字符串中包含标记字符，不是有效的围栏代码块
        if (strpos($infostring, '`') !== false) {
            return;
        }

        // 构建代码块元素数组
        $Element = array(
            'name' => 'code',
            'text' => '',
        );

        // 如果信息字符串不为空
        if ($infostring !== '') {
            // 获取代码块的语言类型
            $language = substr($infostring, 0, strcspn($infostring, " \t\n\f\r"));

            // 为代码块元素添加语言类型的类名
            $Element['attributes'] = array('class' => "language-$language");
        }

        // 构建围栏代码块元素数组
        $Block = array(
            'char' => $marker,
            'openerLength' => $openerLength,
            'element' => array(
                'name' => 'pre',
                'element' => $Element,
            ),
        );

        // 返回围栏代码块元素数组
        return $Block;
    }

    /**
     * 继续处理围栏代码块。
     *
     * 如果围栏代码块未结束，则将当前行的内容添加到代码块中。
     * 如果遇到与开头相同数量的标记字符行，则表示代码块结束。
     *
     * @param array $Line 当前行的信息，包括原始行、缩进和去除缩进后的文本。
     * @param array $Block 当前正在处理的围栏代码块。
     * @return array|null 如果可以继续处理，返回更新后的代码块元素数组；否则返回 null。
     */
    protected function blockFencedCodeContinue($Line, $Block)
    {
        // 如果围栏代码块已结束，不继续处理
        if (isset($Block['complete'])) {
            return;
        }

        // 如果围栏代码块之前被中断过
        if (isset($Block['interrupted'])) {
            // 在代码块内容中添加相应数量的换行符
            $Block['element']['element']['text'] .= str_repeat("\n", $Block['interrupted']);

            // 移除中断标记
            unset($Block['interrupted']);
        }

        // 如果当前行以与开头相同数量的标记字符开头，且剩余部分为空
        if (($len = strspn($Line['text'], $Block['char'])) >= $Block['openerLength']
            and chop(substr($Line['text'], $len), ' ') === ''
        ) {
            // 去除代码块内容开头的换行符
            $Block['element']['element']['text'] = substr($Block['element']['element']['text'], 1);

            // 标记围栏代码块已结束
            $Block['complete'] = true;

            // 返回更新后的代码块元素数组
            return $Block;
        }

        // 将当前行的内容添加到代码块内容中
        $Block['element']['element']['text'] .= "\n" . $Line['body'];

        // 返回更新后的代码块元素数组
        return $Block;
    }

    /**
     * 完成处理围栏代码块。
     *
     * 目前只是简单返回当前代码块，可根据需要添加更多处理逻辑。
     *
     * @param array $Block 当前正在处理的围栏代码块。
     * @return array 完成处理后的代码块元素数组。
     */
    protected function blockFencedCodeComplete($Block)
    {
        // 返回当前代码块元素数组
        return $Block;
    }

    #
    # Header

    /**
     * 处理标题块。
     *
     * 如果当前行以一个或多个 '#' 开头，则将其视为标题块。
     * 标题级别由 '#' 的数量决定，最多为 6 级。
     *
     * @param array $Line 当前行的信息，包括原始行、缩进和去除缩进后的文本。
     * @return array|null 如果是标题块，返回包含标题块元素的数组；否则返回 null。
     */
    protected function blockHeader($Line)
    {
        // 计算当前行开头 '#' 的连续出现次数，即标题级别
        $level = strspn($Line['text'], '#');

        // 如果标题级别大于 6，不是有效的标题块
        if ($level > 6) {
            return;
        }

        // 去除标题文本前后的 '#' 字符
        $text = trim($Line['text'], '#');

        // 如果启用了严格模式，且标题文本开头不是空格，不是有效的标题块
        if ($this->strictMode and isset($text[0]) and $text[0] !== ' ') {
            return;
        }

        // 去除标题文本前后的空格
        $text = trim($text, ' ');

        // 构建标题块元素数组
        $Block = array(
            'element' => array(
                'name' => 'h' . $level,
                'handler' => array(
                    'function' => 'lineElements',
                    'argument' => $text,
                    'destination' => 'elements',
                )
            ),
        );

        // 返回标题块元素数组
        return $Block;
    }

    #
    # List

    /**
     * 处理列表块。
     *
     * 如果当前行以列表标记（如 '*', '+', '-', 数字后跟 '.' 或 ')'）开头，则将其视为列表块。
     * 根据列表标记的类型，确定列表是无序列表（ul）还是有序列表（ol）。
     *
     * @param array $Line 当前行的信息，包括原始行、缩进和去除缩进后的文本。
     * @param array|null $CurrentBlock 当前正在处理的块，默认为 null。
     * @return array|null 如果是列表块，返回包含列表块元素的数组；否则返回 null。
     */
    protected function blockList($Line, ?array $CurrentBlock = null)
    {
        // 根据列表标记的第一个字符，确定列表类型（无序列表或有序列表）
        list($name, $pattern) = $Line['text'][0] <= '-' ? array('ul', '[*+-]') : array('ol', '[0-9]{1,9}+[.\)]');

        // 使用正则表达式匹配列表标记和列表项内容
        if (preg_match('/^(' . $pattern . '([ ]++|$))(.*+)/', $Line['text'], $matches)) {
            // 计算列表项内容的缩进空格数
            $contentIndent = strlen($matches[2]);

            // 如果缩进空格数大于等于 5，调整缩进和列表标记
            if ($contentIndent >= 5) {
                $contentIndent -= 1;
                $matches[1] = substr($matches[1], 0, -$contentIndent);
                $matches[3] = str_repeat(' ', $contentIndent) . $matches[3];
            // 如果缩进空格数为 0，添加一个空格
            } elseif ($contentIndent === 0) {
                $matches[1] .= ' ';
            }

            // 获取去除空格后的列表标记
            $markerWithoutWhitespace = strstr($matches[1], ' ', true);

            // 构建列表块元素数组
            $Block = array(
                'indent' => $Line['indent'],
                'pattern' => $pattern,
                'data' => array(
                    'type' => $name,
                    'marker' => $matches[1],
                    'markerType' => ($name === 'ul' ? $markerWithoutWhitespace : substr($markerWithoutWhitespace, -1)),
                ),
                'element' => array(
                    'name' => $name,
                    'elements' => array(),
                ),
            );
            // 为列表标记类型构建正则表达式，用于后续匹配
            $Block['data']['markerTypeRegex'] = preg_quote($Block['data']['markerType'], '/');

            // 如果是有序列表
            if ($name === 'ol') {
                // 获取列表的起始数字
                $listStart = ltrim(strstr($matches[1], $Block['data']['markerType'], true), '0') ?: '0';

                // 如果起始数字不为 1，且当前块为段落类型且未被中断，不处理该列表项
                if ($listStart !== '1') {
                    if (
                        isset($CurrentBlock)
                        and $CurrentBlock['type'] === 'Paragraph'
                        and ! isset($CurrentBlock['interrupted'])
                    ) {
                        return;
                    }

                    // 为有序列表元素添加起始数字属性
                    $Block['element']['attributes'] = array('start' => $listStart);
                }
            }

            // 构建列表项元素数组
            $Block['li'] = array(
                'name' => 'li',
                'handler' => array(
                    'function' => 'li',
                    'argument' => !empty($matches[3]) ? array($matches[3]) : array(),
                    'destination' => 'elements'
                )
            );

            // 将列表项元素添加到列表元素数组中
            $Block['element']['elements'][] = &$Block['li'];

            // 返回列表块元素数组
            return $Block;
        }
    }

    /**
     * 继续处理列表块。
     *
     * 根据当前行的缩进和列表标记，判断是否继续当前列表项或开始新的列表项。
     * 如果当前行的缩进不足，且符合列表标记规则，则开始新的列表项；
     * 如果当前行的缩进足够，则继续当前列表项。
     *
     * @param array $Line 当前行的信息，包括原始行、缩进和去除缩进后的文本。
     * @param array $Block 当前正在处理的列表块。
     * @return array|null 如果可以继续处理，返回更新后的列表块元素数组；否则返回 null。
     */
    protected function blockListContinue($Line, array $Block)
    {
        // 如果列表块之前被中断过，且当前列表项内容为空，不继续处理
        if (isset($Block['interrupted']) and empty($Block['li']['handler']['argument'])) {
            return null;
        }

        // 计算继续当前列表项所需的最小缩进空格数
        $requiredIndent = ($Block['indent'] + strlen($Block['data']['marker']));

        // 如果当前行的缩进小于所需缩进，且符合列表标记规则
        if (
            $Line['indent'] < $requiredIndent
            and (
                (
                    // 如果是有序列表，检查是否匹配有序列表标记规则
                    $Block['data']['type'] === 'ol'
                    and preg_match('/^[0-9]++' . $Block['data']['markerTypeRegex'] . '(?:[ ]++(.*)|$)/', $Line['text'], $matches)
                ) or (
                    // 如果是无序列表，检查是否匹配无序列表标记规则
                    $Block['data']['type'] === 'ul'
                    and preg_match('/^' . $Block['data']['markerTypeRegex'] . '(?:[ ]++(.*)|$)/', $Line['text'], $matches)
                )
            )
        ) {
            // 如果列表块之前被中断过
            if (isset($Block['interrupted'])) {
                // 在当前列表项内容中添加一个空行，标记列表为松散列表
                $Block['li']['handler']['argument'][] = '';

                $Block['loose'] = true;

                // 移除中断标记
                unset($Block['interrupted']);
            }

            // 移除当前列表项引用
            unset($Block['li']);

            // 获取新列表项的内容
            $text = isset($matches[1]) ? $matches[1] : '';

            // 更新列表块的缩进为当前行的缩进
            $Block['indent'] = $Line['indent'];

            // 构建新的列表项元素数组
            $Block['li'] = array(
                'name' => 'li',
                'handler' => array(
                    'function' => 'li',
                    'argument' => array($text),
                    'destination' => 'elements'
                )
            );

            // 将新的列表项元素添加到列表元素数组中
            $Block['element']['elements'][] = &$Block['li'];

            // 返回更新后的列表块元素数组
            return $Block;
        // 如果当前行的缩进小于所需缩进，且可以开始新的列表块，不继续处理当前列表块
        } elseif ($Line['indent'] < $requiredIndent and $this->blockList($Line)) {
            return null;
        }

        // 如果当前行以 '[' 开头，且可以处理引用块，继续处理当前列表块
        if ($Line['text'][0] === '[' and $this->blockReference($Line)) {
            return $Block;
        }

        // 如果当前行的缩进大于等于所需缩进
        if ($Line['indent'] >= $requiredIndent) {
            // 如果列表块之前被中断过
            if (isset($Block['interrupted'])) {
                // 在当前列表项内容中添加一个空行，标记列表为松散列表
                $Block['li']['handler']['argument'][] = '';

                $Block['loose'] = true;

                // 移除中断标记
                unset($Block['interrupted']);
            }

            // 获取去除缩进后的当前行文本
            $text = substr($Line['body'], $requiredIndent);

            // 将当前行文本添加到当前列表项内容中
            $Block['li']['handler']['argument'][] = $text;

            // 返回更新后的列表块元素数组
            return $Block;
        }

        // 如果列表块之前未被中断过
        if (! isset($Block['interrupted'])) {
            // 去除当前行开头的缩进空格
            $text = preg_replace('/^[ ]{0,' . $requiredIndent . '}+/', '', $Line['body']);

            // 将当前行文本添加到当前列表项内容中
            $Block['li']['handler']['argument'][] = $text;

            // 返回更新后的列表块元素数组
            return $Block;
        }
    }

    /**
     * 完成处理列表块。
     *
     * 如果列表是松散列表（即列表项之间有空行），在每个列表项的末尾添加一个空行。
     *
     * @param array $Block 当前正在处理的列表块。
     * @return array 完成处理后的列表块元素数组。
     */
    protected function blockListComplete(array $Block)
    {
        // 如果列表是松散列表
        if (isset($Block['loose'])) {
            // 遍历列表中的每个列表项
            foreach ($Block['element']['elements'] as &$li) {
                // 如果列表项的最后一个内容不为空，添加一个空行
                if (end($li['handler']['argument']) !== '') {
                    $li['handler']['argument'][] = '';
                }
            }
        }

        // 返回完成处理后的列表块元素数组
        return $Block;
    }

    #
    # Quote

    /**
     * 处理引用块。
     *
     * 如果当前行以 '>' 开头，则将其视为引用块。
     * 引用块的内容为去除 '>' 及其后面的空格后的文本。
     *
     * @param array $Line 当前行的信息，包括原始行、缩进和去除缩进后的文本。
     * @return array|null 如果是引用块，返回包含引用块元素的数组；否则返回 null。
     */
    protected function blockQuote($Line)
    {
        // 使用正则表达式匹配引用块的内容
        if (preg_match('/^>[ ]?+(.*+)/', $Line['text'], $matches)) {
            // 构建引用块元素数组
            $Block = array(
                'element' => array(
                    'name' => 'blockquote',
                    'handler' => array(
                        'function' => 'linesElements',
                        'argument' => (array) $matches[1],
                        'destination' => 'elements',
                    )
                ),
            );

            // 返回引用块元素数组
            return $Block;
        }
    }

    /**
     * 继续处理引用块。
     *
     * 如果引用块未被中断，且当前行以 '>' 开头，则继续添加引用块内容；
     * 如果引用块未被中断，且当前行不以 '>' 开头，也将其作为引用块内容添加。
     *
     * @param array $Line 当前行的信息，包括原始行、缩进和去除缩进后的文本。
     * @param array $Block 当前正在处理的引用块。
     * @return array|null 如果可以继续处理，返回更新后的引用块元素数组；否则返回 null。
     */
    protected function blockQuoteContinue($Line, array $Block)
    {
        // 如果引用块之前被中断过，不继续处理
        if (isset($Block['interrupted'])) {
            return;
        }

        // 如果当前行以 '>' 开头，且匹配引用块内容的正则表达式
        if ($Line['text'][0] === '>' and preg_match('/^>[ ]?+(.*+)/', $Line['text'], $matches)) {
            // 将匹配到的引用块内容添加到引用块处理参数中
            $Block['element']['handler']['argument'][] = $matches[1];

            // 返回更新后的引用块元素数组
            return $Block;
        }

        // 如果引用块之前未被中断过
        if (! isset($Block['interrupted'])) {
            // 将当前行的文本添加到引用块处理参数中
            $Block['element']['handler']['argument'][] = $Line['text'];

            // 返回更新后的引用块元素数组
            return $Block;
        }
    }

    #
    # Rule

    /**
     * 处理水平线块。
     *
     * 如果当前行由至少 3 个相同的字符（如 '*', '-', '_'）组成，且去除首尾空格后只剩下这些字符，
     * 则将其视为水平线块。
     *
     * @param array $Line 当前行的信息，包括原始行、缩进和去除缩进后的文本。
     * @return array|null 如果是水平线块，返回包含水平线块元素的数组；否则返回 null。
     */
    protected function blockRule($Line)
    {
        // 获取当前行的第一个字符，作为水平线的标记字符
        $marker = $Line['text'][0];

        // 如果当前行中标记字符的数量至少为 3，且去除首尾空格和标记字符后为空
        if (substr_count($Line['text'], $marker) >= 3 and chop($Line['text'], " $marker") === '') {
            // 构建水平线块元素数组
            $Block = array(
                'element' => array(
                    'name' => 'hr',
                ),
            );

            // 返回水平线块元素数组
            return $Block;
        }
    }

    #
    # Setext

    /**
     * 处理 Setext 标题块。
     *
     * 如果当前行的缩进小于 4，且去除首尾空格后只剩下一个重复的字符（如 '=', '-'），
     * 并且前一个块是段落块且未被中断，则将前一个段落块转换为 Setext 标题块。
     * 根据重复字符的不同，标题级别为 h1 或 h2。
     *
     * @param array $Line 当前行的信息，包括原始行、缩进和去除缩进后的文本。
     * @param array|null $Block 当前正在处理的块，默认为 null。
     * @return array|null 如果可以转换为 Setext 标题块，返回更新后的块元素数组；否则返回 null。
     */
    protected function blockSetextHeader($Line, ?array $Block = null)
    {
        // 如果前一个块不存在，或不是段落块，或已被中断，不处理 Setext 标题块
        if (! isset($Block) or $Block['type'] !== 'Paragraph' or isset($Block['interrupted'])) {
            return;
        }

        // 如果当前行的缩进小于 4，且去除首尾空格和重复字符后为空
        if ($Line['indent'] < 4 and chop(chop($Line['text'], ' '), $Line['text'][0]) === '') {
            // 根据重复字符的不同，将段落块元素的名称改为 h1 或 h2
            $Block['element']['name'] = $Line['text'][0] === '=' ? 'h1' : 'h2';

            // 返回更新后的块元素数组
            return $Block;
        }
    }

    #
    # Markup

    /**
     * 处理 HTML 标记块。
     *
     * 如果未启用标记转义或安全模式，且当前行匹配 HTML 标记的正则表达式，
     * 则将其视为 HTML 标记块。排除文本级别的 HTML 元素。
     *
     * @param array $Line 当前行的信息，包括原始行、缩进和去除缩进后的文本。
     * @return array|null 如果是 HTML 标记块，返回包含标记块元素的数组；否则返回 null。
     */
    protected function blockMarkup($Line)
    {
        // 如果启用了标记转义或安全模式，不处理 HTML 标记块
        if ($this->markupEscaped or $this->safeMode) {
            return;
        }

        // 使用正则表达式匹配 HTML 标记
        if (preg_match('/^<[\/]?+(\w*)(?:[ ]*+' . $this->regexHtmlAttribute . ')*+[ ]*+(\/)?>/', $Line['text'], $matches)) {
            // 获取 HTML 元素的名称，并转换为小写
            $element = strtolower($matches[1]);

            // 如果 HTML 元素是文本级别的元素，不处理该标记块
            if (in_array($element, $this->textLevelElements)) {
                return;
            }

            // 构建 HTML 标记块元素数组
            $Block = array(
                'name' => $matches[1],
                'element' => array(
                    'rawHtml' => $Line['text'],
                    'autobreak' => true,
                ),
            );

            // 返回 HTML 标记块元素数组
            return $Block;
        }
    }

    /**
     * 继续处理 HTML 标记块。
     *
     * 如果标记块未结束且未被中断，则将当前行的内容添加到标记块中。
     *
     * @param array $Line 当前行的信息，包括原始行、缩进和去除缩进后的文本。
     * @param array $Block 当前正在处理的 HTML 标记块。
     * @return array|null 如果可以继续处理，返回更新后的标记块元素数组；否则返回 null。
     */
    protected function blockMarkupContinue($Line, array $Block)
    {
        // 如果标记块已结束或已被中断，不继续处理
        if (isset($Block['closed']) or isset($Block['interrupted'])) {
            return;
        }

        // 将当前行的内容添加到标记块的原始 HTML 标记中
        $Block['element']['rawHtml'] .= "\n" . $Line['body'];

        // 返回更新后的标记块元素数组
        return $Block;
    }

    #
    # Reference

    /**
     * 处理引用块。
     *
     * 如果当前行匹配引用块的正则表达式，即包含 ']:' 且符合引用格式，
     * 则将其视为引用块，并提取引用的 ID、URL 和可选的标题。
     *
     * @param array $Line 当前行的信息，包括原始行、缩进和去除缩进后的文本。
     * @return array|null 如果是引用块，返回包含引用块数据的数组；否则返回 null。
     */
    protected function blockReference($Line)
    {
        // 如果当前行包含 ']'，且匹配引用块的正则表达式
        if (
            strpos($Line['text'], ']') !== false
            and preg_match('/^\[(.+?)\]:[ ]*+<?(\S+?)>?(?:[ ]+["\'(](.+)["\')])?[ ]*+$/', $Line['text'], $matches)
        ) {
            // 获取引用的 ID，并转换为小写
            $id = strtolower($matches[1]);

            // 构建引用块数据数组
            $Data = array(
                'url' => $matches[2],
