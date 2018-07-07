<?php
/**
 * Created by IntelliJ IDEA.
 * User: li914
 * Date: 18-7-7
 * Time: 下午2:24
 */


//require __DIR__ . '/vendor/autoload.php';
//
use Webpatser\Uuid\Uuid;
//
//require './utf8_chinese.php';

class author_song
{
    //是否开启过滤
    protected $_ifFilter = true;

    //分段
    protected $_section = 1;

    public function run()
    {
        $dirPath = dirname(__FILE__);
        $sourceFilePath = $dirPath . '/chinese-poetry/json/';
        $sqlFileName = "/authors.song-%s.sql";
        $sqlPathString = $dirPath . $sqlFileName;

        //判断古诗词仓库是否存在
        $isPathExist = file_exists($sourceFilePath);
        if ($isPathExist == false) {
            die('古诗词仓库不存在，请按说明下载');
        }

        //唐诗json文件的路径
        $tangFilePathList = glob("{$sourceFilePath}authors.song.json");

        if (empty($tangFilePathList)) {
            die('路径不存在');
        }

        //每一个文件包含多少个json文件的数据
        $eachFileLong = ceil(count($tangFilePathList) / $this->_section);

        for ($i = 1; $i <= $this->_section; $i++) {
            file_put_contents(sprintf($sqlPathString, $i), "INSERT INTO `shi_author` (`author`, `intro`,`dynasty`,`create_time`) VALUES \r\n");
        }
//dynasty/home/li914/work/chinese-poetry-to-mysql-tool/chinese-poetry-tang-1.sql

        $id = 0;
        $converter = new \Woodylan\Converter\Converter();
        $oldNumber = 0;

        $utf8_chinese=new utf8_chinese();

        foreach ($tangFilePathList as $fileCount => $filePath) {
            $fileContent = file_get_contents($filePath);

            $fileContent=$utf8_chinese->big5_gb2312($fileContent);

            $fileContentArray = json_decode($fileContent, true);

            $fileNumber = floor($fileCount / $eachFileLong) + 1;


            $sqlPath = sprintf($sqlPathString, (string)$fileNumber);

            $content = '';
            foreach ($fileContentArray as $value) {
//                var_dump($value);

                $id++;
                //给上一行加入逗号
                if ($oldNumber == $fileNumber) {
                    $content .= ",\r\n";
                }

                $oldNumber = $fileNumber;
                $time = time();
                $content .= "(\"{$value['name']}\",\"{$value['desc']}\",\"S\",{$time})";
            }

            $handle = fopen($sqlPath, 'a+');
            fwrite($handle, $content);
            fclose($handle);
        }

        //最后一行添加分号

        for ($i = 1; $i <= $this->_section; $i++) {
            $handle = fopen(sprintf($sqlPathString, $i), 'a+');
            fwrite($handle, ';');
            fclose($handle);
        }
    }

    //过滤脚本
    public function filter($paragraphs, $sentenceLength = 2, $charLength = 16)
    {
//        if (count($paragraphs) != $sentenceLength) {
//            return false;
//        }

        //判断每句是否长短一样
//        foreach ($paragraphs as $key => $value) {
//            $length = strlen($value);
//            if ($key >= 1) {
//                //判断跟上一个元素长度是否相等
//                if (strlen($paragraphs[$key - 1]) != $length) {
//                    return false;
//                }
//            }
//
//            if ($length > $charLength * 3) {
//                return false;
//            }
//        }

        return true;
    }

    public function createUuid($short = true)
    {
        $uuid = str_replace('-', '', Uuid::generate()->string);
        if ($short) {
            $uuid = substr($uuid, 8, 16);
        }

        return $uuid;
    }

    public function stringInArray($string, array $array)
    {
        foreach ($array as $value) {
            if (strpos($string, $value)) {
                return true;
            }
        }

        return false;
    }
}

//自动运行
$author_song = new author_song();
$author_song->run();