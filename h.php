<?php

$dbInfo = include './db/conn.php';
include './db/mmysql.php';
include './init.php';
$mysqlHost     = new MMYsql($dbInfo['source']);
$content = addslashes(file_get_contents('D:\\wwwroot\\FYMold\\peizhi\\ditu\\index.html'));
$sql = $mysqlHost->insert('source_moban_1_copy1',['content'=>$content]);exit;
$num = 0;
for($i=1;$i<602;$i++){
	$sql ="select count(1) as t from host_visiter{$i}_1 where id>0";
	$res=$mysqlHost->doSql($sql);
	$s=current($res);
	if($s['t']>0){
		echo $i.'='.$s['t'].PHP_EOL;
		$num +=$s['t'];
	}
}
echo $num;
