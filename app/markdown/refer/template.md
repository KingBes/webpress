## MD 的使用

> 配置SEO

网页 `标题` 、 `关键词` 、 `简介` 。

配置所使用的模板等等

开头使用 `---` 分隔，采用 `yaml` 格式。

以 `变量` 的形式输出给模板使用。

```markdown
---
layout: 模板文件名

title: 网页标题

keywords: 网页关键词

description: 网页简介

---

# 以下是网页的内容
```

当`layout`不填时，默认使用`默认模板`。`view/default.html`
