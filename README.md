#古诗词数据转换json-sql

## 简介

把 [chinese-poetry](https://github.com/chinese-poetry/chinese-poetry) 仓库里的json数据转换成 sql 文件的工具,并把古诗的繁体字转换为简体字



## 环境

- php 7.0 +
- git

##主要数据来源以及使用：<br />
古诗词仓库：https://github.com/chinese-poetry/chinese-poetry<br />
古诗词数据转换：https://github.com/woodylan/chinese-poetry-to-mysql-tool<br />


## 使用

1、下载本工具

```shell

```



2、在本工具目录下，下载 [chinese-poetry](https://github.com/chinese-poetry/chinese-poetry) 仓库

~~~shell
cd chinese-poetry-to-mysql-tool
git clone https://github.com/chinese-poetry/chinese-poetry.git
~~~

3、安装composer包依赖

~~~shell
composer install
~~~

4、开始编译成 `sql` 文件

```shell
php work.php
```

