<?php
set_time_limit(0);
ini_set('memory_limit','568M');
$orig = $result = [];
$lines = 0;//初始化行数 

//$txt = 'D:\\wwwroot\\fym\\peizhi\\keyword\\a.txt';
//$txt ='/data/wwwroot/www.shangbo09.com/peizhi/keyword/top.txt';
$txt = './keyword/top.txt';
$line = count(file($txt));
if($line>4999){
    $data = "";
    $numbytes = file_put_contents($txt, $data);    
}  
if ($fh = fopen($txt,'r')) {//打开文件
 while (! feof($fh)) {//判断是否已经达到文件底部
  if (fgets($fh)) {//读取一行内容
   $orig[] = $fh; 
  }
 }
}
$bdUrl = $soUrl = [];
$bdUrl[]='http://top.baidu.com/buzz?b=341&c=513&fr=topbuzz_b1_c513';
$bdUrl[] = 'http://top.baidu.com/buzz?b=1&c=513&fr=topbuzz_b42_c513';
$bdUrl[] = 'http://top.baidu.com/buzz?b=42&c=513&fr=topbuzz_b341_c513';
$soUrl[] = 'http://top.sogou.com/hot/shishi_1.html';
$soUrl[] = 'http://top.sogou.com/hot/shishi_2.html';
$soUrl[] = 'http://top.sogou.com/hot/shishi_3.html';
$soUrl[] = 'http://top.sogou.com/hot/sevendsnews_1.html';
$soUrl[] = 'http://top.sogou.com/hot/sevendsnews_2.html';
$soUrl[] = 'http://top.sogou.com/hot/sevendsnews_3.html';

$patternBd = '/<a class="list-title"(.*)>(.*)<\/a>/';
$patternSoSo = '/<span class="s2"><p class="p1"><a([.|\s|\S|\n]*?)target="_blank">([.|\s|\S|\n]*?)<\/a><\/p>([.|\s|\S|\n]*?)<\/span>/';
$patternSoSo_other = '/<span class="s2"><p class="p3"><a([.|\s|\S|\n]*?)target="_blank">([.|\s|\S|\n]*?)<\/a><\/p>([.|\s|\S|\n]*?)<\/span>/';
mb_regex_encoding('utf-8');
foreach ($soUrl as $v) {
    $content = file_get_contents($v);//var_dump($content);exit;
    if($content!=''){
        //先单独匹热点
        preg_match_all($patternSoSo,$content,$matArr);
        if(isset($matArr[2])){
            foreach ($matArr[2] as $value) {
               if(empty($value)){
                    continue;
               }//$curArr[] = $value;
               //$str = mb_convert_encoding($value,"utf-8","GBK");
               $str = $value;
               $str = trim($str);
               if(!in_array($str,$orig) and !in_array($str, $result)){
                    $result[] = $str;
               }
            }
        }
        preg_match_all($patternSoSo_other,$content,$matArr);
        if(isset($matArr[2])){
            foreach ($matArr[2] as $value) {
               if(empty($value)){
                    continue;
               }
               //$str = mb_convert_encoding($value,"utf-8","GBK");
               $str = $value;//$curArr[] = $value;
               $str = trim($str);
               if(!in_array($str,$orig) and !in_array($str, $result)){
                    $result[] = $str;
               }
            }
        }
       // echo count($curArr).'<br>';
        
    }
}//exit;
$matArr = [];
foreach ($bdUrl as $v) {//$curArr = [];
    $content = file_get_contents($v);//var_dump($content);exit;
    if($content!=''){
        preg_match_all($patternBd,$content,$matArr);
        if(!isset($matArr) or empty($matArr[2])){
            continue;
        }
        foreach ($matArr[2] as $value) {
           if(empty($value)){
                continue;
           }
           $str = mb_convert_encoding($value,"utf-8","GBK");
           $str = trim($str);//$curArr[]=$str;
           if(!in_array($str,$orig) and !in_array($str, $result)){
                $result[] = $str;
           }
        }
        
    }//echo count($curArr).'<br>';
}//exit;
unset($orig);
if(!empty($result)){
    $handle=fopen($txt,"a+");
    $_i = count($result)-1; 
    foreach ($result as $k=>$value) {
        if($k!=$_i){
            $str = $value."\n";
        }else{
            $str = $value;
        }        
        fwrite($handle,$str);
    }
    fclose($handle);
}
