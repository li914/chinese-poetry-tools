<?php
/**
 * Created by IntelliJ IDEA.
 * User: li914
 * Date: 18-7-7
 * Time: 下午12:20
 */




//require __DIR__ . '/vendor/autoload.php';
//
use Webpatser\Uuid\Uuid;
//
//require './utf8_chinese.php';

class song
{
    //是否开启过滤
    protected $_ifFilter = true;

    //分段
    protected $_section = 12;

    public function run()
    {
        $dirPath = dirname(__FILE__);
        $sourceFilePath = $dirPath . '/chinese-poetry/json/';
        $sqlFileName = "/chinese-poetry-song-%s.sql";
        $sqlPathString = $dirPath . $sqlFileName;

        //判断古诗词仓库是否存在
        $isPathExist = file_exists($sourceFilePath);
        if ($isPathExist == false) {
            die('古诗词仓库不存在，请按说明下载');
        }

        //唐诗json文件的路径
        $tangFilePathList = glob("{$sourceFilePath}poet.song.*.json");

        if (empty($tangFilePathList)) {
            die('路径不存在');
        }

        //每一个文件包含多少个json文件的数据
        $eachFileLong = ceil(count($tangFilePathList) / $this->_section);

        for ($i = 1; $i <= $this->_section; $i++) {
            file_put_contents(sprintf($sqlPathString, $i), "INSERT INTO `shi` (`shi_id`, `title`, `author`,`dynasty`,`tags`, `content`,`strains`,`create_time`) VALUES \r\n");
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
                //过滤
                if ($this->_ifFilter) {
                    $isAllow = $this->filter($value['paragraphs']);
                    if ($isAllow == false) {
                        continue;
                    }
                }

                $paragraphs = implode($value['paragraphs'], '\n');

                //过滤掉乱码的诗词
                if ($this->stringInArray($paragraphs, ['□'])) {
                    continue;
                }

                $paragraphs = $converter->turn($paragraphs);

                $id++;
                //给上一行加入逗号
                if ($oldNumber == $fileNumber) {
                    $content .= ",\r\n";
                }

                $oldNumber = $fileNumber;

                $uuid = $this->createUuid();
                $time = time();

//                $strains = $value['strains'];
                $strains = implode($value['strains'], '\n');
                $strains = $converter->turn($strains);
                $tags = isset($value['tags'])?$value['tags']:null;
                $tags=$tags==null?"":implode($tags,',');
//                var_dump($tags);

                $content .= "(\"{$uuid}\",\"{$value['title']}\",\"{$value['author']}\",\"S\",\"{$tags}\",\"{$paragraphs}\",\"{$strains}\",{$time})";
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
$song = new song();
$song->run();