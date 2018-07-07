<?php
/**
 * Created by IntelliJ IDEA.
 * User: li914
 * Date: 18-7-7
 * Time: 下午3:24
 */

require __DIR__ . '/vendor/autoload.php';

file_exists(__DIR__.'/sql')?printf( "sql文件夹存在\n"):mkdir(__DIR__.'/sql');
echo "sql文件夹检查完成\n";


require './utf8_chinese.php';

echo "正在处理宋朝诗人。。。\n";
require './author_song.php';
echo "正在处理唐朝诗人。。。\n";
require './author_tang.php';
echo "正在处理宋词。。。\n";
require './ci.php';
echo "正在处理宋词诗人。。。\n";
require './ci_author.php';
echo "正在处理宋诗。。。\n";
require './song.php';
echo "正在处理唐诗。。。\n";
require './tang.php';
echo "处理完成了。。。\n";