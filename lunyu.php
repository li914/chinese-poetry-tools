<?php
/**
 * Created by IntelliJ IDEA.
 * User: li914
 * Date: 18-8-17
 * Time: 下午7:57
 */
use Webpatser\Uuid\Uuid;
require_once './utf8_chinese.php';
require_once __DIR__ . '/vendor/autoload.php';
class lunyu{

    protected $_ifFilter=true;
    protected $_section=1;



    public function run(){
        $dirPath=dirname(__FILE__);
        $sourceFilePath=$dirPath.'/chinese-poetry/lunyu/';
        $sqlFileName='/sql/chinese-lunyu.sql';

        $sqlPathString=$dirPath.$sqlFileName;

        $isPathExist=file_exists($sourceFilePath);

        if ($isPathExist==false){
            die("该分类不存在,请检查");
        }

        $lunyuFilePathList=glob("{$sourceFilePath}lunyu.json");

        if (empty($lunyuFilePathList)){
            die("文件不存在!");
        }

        $eachFileLong=ceil(count($lunyuFilePathList)/$this->_section);
        for ($i=0;$i<=$this->_section;++$i){
            file_put_contents(sprintf($sqlPathString,$i),"INSERT INTO lunyu ('lunyu_id','title','content','create_time','update_time') VALUES \r\n");
        }

        $id=0;
        $converter = new \Woodylan\Converter\Converter();
        $oldNumber=0;

        $utf8_chinese=new utf8_chinese();
        foreach ($lunyuFilePathList as $fileConut=>$filePath){
            var_dump($filePath);
            $fileContent=file_get_contents($filePath);

            $fileContent=$utf8_chinese->big5_gb2312($fileContent);

            $fileContentArray=json_decode($fileContent,true);

            $fileNumber=floor($fileConut/$eachFileLong)+1;

            $sqlPath=sprintf($sqlPathString,(string)$fileNumber);

            $content='';
            foreach ($fileContentArray as $value){
                if ($this->_ifFilter){
                    $isAllow=$this->filter($value['paragraphs']);
                    if ($isAllow==false){
                        continue;
                    }
                }

                $paragraphs=implode($value['paragraphs'],'\n');

                if ($this->stringInArray($paragraphs,['□'])){
                    continue;
                }
                $paragraphs=$converter->turn($paragraphs);
                $id++;

                if ($oldNumber==$fileNumber){
                    $content.=",\r\n";
                }
                $oldNumber=$fileNumber;

                $uuid=$this->createUuid();

                $time=date('Y-m-d H:i:s');


                $content.="(\"{$uuid}\",\"{$value['chapter']}\",\"{$paragraphs}\",\"{$time}\",\"\")";

            }
            $handle=fopen($sqlPath,'a+');
            fwrite($handle,$content);
            fclose($handle);

        }
        for ($i=0;$i<$this->_section;++$i){
            $handle = fopen(sprintf($sqlPathString, $i), 'a+');
            fwrite($handle, ';');
            fclose($handle);
        }

    }

    public function filter($paragraphs, $sentenceLength = 2, $charLength = 16){

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


$lunyu=new lunyu();
$lunyu->run();