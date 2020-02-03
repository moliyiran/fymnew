<?php
error_reporting(0);
set_time_limit(0);
date_default_timezone_set("Asia/Shanghai");
//ini_set('memory_limit','516M');
//测试代码
/*
for($i=1;$i<600;$i++){
	$host = "www.q{$i}.com";
	$hostArr[] = $host;
}

$_SERVER['HTTP_HOST']=$hostArr[rand(0,count($hostArr)-1)];
$g = rand(0,1000000);
if($g>200000){
	$_SERVER["REQUEST_URI"]=rand_str();	
}else if($g>300000){
	$_SERVER["REQUEST_URI"]='/a/b/'.rand_str();	
}else{
	$_SERVER["REQUEST_URI"]='/a/'.rand_str();	
}
*/

$arr = explode("/",$_SERVER['REQUEST_URI']);
$num = sizeof($arr);
//require_once './init.php';
header("Content-type: text/html; charset=utf-8");
if($arr[$num - 1] == 'sitemap.xml'){
	header("Content-Type: text/xml");
	$map = "\t<urlset>\r\n";
	$host = 'http://'.$_SERVER['HTTP_HOST'].'/';
	$date = date("Y-m-d");
	for($i=0;$i<2000;$i++){
	$tmp = $host.rand_str().'/'.rand_str().'.xml';
	$map .= "\t\t<url>\n";
	$map .= "\t\t\t<loc>{$tmp}</loc>\r\n";
	$map .= "\t\t\t<priority>{$date}</priority>\r\n";
	$map .= "\t\t\t<lastmod>daily</lastmod>\r\n";
	$map .= "\t\t\t<changefreq>0.8</changefreq>\r\n";
	$map .= "\t\t</url>\n";
	}
	$map .= "\t</urlset>";
	echo $map;
	die;
}
if($arr[$num - 1] == 'sitemap.txt'){
	header("Content-Type: text/txt");
	$map = "";
	$host = 'http://'.$_SERVER['HTTP_HOST'].'/';
	$date = date("Y-m-d");
	for($i=0;$i<1000;$i++){
	$tmp = $host.rand_str().'/'.rand_str().'.xml';
	$map .= "{$tmp}\r";
	}
	$map .= "";
	echo $map;
	die;
}
header('HTTP/1.1 200 OK');
function GetRandStr($length){
	$str='abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789';
	$len=strlen($str)-1;
	$randstr='';
	for($i=0;$i<$length;$i++){
	$num=mt_rand(0,$len);
	$randstr .= $str[$num];
	}
	return $randstr;
}
function rand_str($length = 5)
{
	$str    = '';
	$strPol = "ABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789abcdefghijklmnopqrstuvwxyz";
	$max    = strlen($strPol)-1;
	for($i = 0; $i < $length; $i++)
	{
	$str   .=$strPol[rand(0,$max)];
	}
	return $str;
}
//$number=GetRandStr(6);
if(!empty($_SERVER['HTTP_HOST'])&&!strstr($_SERVER['HTTP_HOST'],'www.')){
	$_SERVER['HTTP_HOST'] = "www.".$_SERVER['HTTP_HOST'];
}
$visiterKey = sha1($_SERVER['HTTP_HOST'].$_SERVER["REQUEST_URI"]);
$cachefile = 'cache/'.$visiterKey.'.txt';//如需要动态请修改成：$number
ob_start();
if(file_exists($cachefile)){	
	include($cachefile);
	ob_end_flush();
	exit;
}
$dbInfo = include './db/conn.php';
//include './db/mmysql.php';
include './init.php';
$mysqlSource    = new MMYsql($dbInfo['source']);
$mysqlHost     = new MMYsql($dbInfo['host']);
$redisObj = new RedisObj($dbInfo['redis']);
$sourceObj = new visiterInfo();
$res = 0;
$tempRes = 0;
$mulu_name = '/';//
$url=$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI'];
$yuming=$_SERVER['HTTP_HOST'];
$indexInfo = ['title'=>'','keywords'=>'','description'=>''];

if(!empty($visiterKey)){
	$curRes = [];
	$mysqlTemp    = new MMYsql($dbInfo['temp']);
	$curRes = $sourceObj->setDb($mysqlTemp)->getTempUserData($visiterKey);

	if(empty($curRes)){
		$mysql     = new MMYsql($dbInfo['user']);
		$curRes = $sourceObj->setDb($mysqlHost)->setRedis($redisObj)->getUserData($mysql,$mysqlSource,$visiterKey,$yuming);		
	} else {
		$tempRes = 1;
	}
	if(!empty($curRes)){
		$userDataArr = $curRes['userData'] ?? [];
		$commonData = $curRes['commonData'] ?? [];
		$curRes = NULL;
		if(empty($commonData)){
			$res = 0;
		}else{
			$res = 1;
		}
	}
}
$urlType = $sourceObj->urlType($_SERVER);
if(!$urlType&&!empty($yuming)) {//首页
	$indexCurInfo = $sourceObj->setDb($mysqlHost)->setRedis($redisObj)->getIdByUrl(trim($yuming));
	if(!empty($indexCurInfo)&&isset($indexCurInfo['content'])&&!empty($indexCurInfo['content'])){
		$indexCurInfo = $sourceObj->decodeJson($indexCurInfo['content']);
		$indexInfo['title'] = $indexCurInfo['title'] ?? '';
		$indexInfo['keywords'] = $indexCurInfo['keywords'] ?? '';
		$indexInfo['description'] = $indexCurInfo['description'] ?? '';
	}	
}
if(empty($res)){
	$html = $sourceObj->setDb($mysqlSource)->setRedis($redisObj)->getRandomStrByKey('html',((int)$urlType+1));
	if(empty($html)&&$urlType){
		$html = $sourceObj->setDb($mysqlSource)->setRedis($redisObj)->getRandomStrByKey('html',1);
	}
}else{
	$html = $commonData['read_tpl'];
}
if(empty($html)){
	die;
}
include 'replace.php';
echo $html;
//exit;
if(is_dir('cache')){
	$info = ob_get_contents();
	file_put_contents($cachefile,$info);
}else{
	if(@mkdir('cache')){
		$info = ob_get_contents();
		file_put_contents($cachefile,$info);
	}
}

?>



