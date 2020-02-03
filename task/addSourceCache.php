<?php

$dbInfo = include '../db/conn.php';
//include '../db/MMYsql.php';
include '../init.php';
try{
	$mysqlSource    = new MMYsql($dbInfo['source']);
	$redisObj = new RedisObj($dbInfo['redis']);
	$sourceObj = new visiterInfo();
	$i = 0;
	while (true) {
		$i++;
		$sourceObj->setDb($mysqlSource)->setRedis($redisObj)->createCache();
		sleep(2);
		if($i>100){
			$mysqlSource->close();
			$mysqlSource    = new MMYsql($dbInfo['source']);
			$redisObj = new RedisObj($dbInfo['redis']);
		}
	}	
}catch(\Exception $e){
	if(!empty($e)){
		file_put_contents('../logs/sourceCache'.date('Y-m-d').'.txt',json_encode($e), FILE_APPEND);
		file_put_contents('../logs/sourceCache'.date('Y-m-d').'.txt',json_encode(debug_backtrace()), FILE_APPEND);
		exit;	
	}
}
