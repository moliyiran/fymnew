<?php
set_time_limit(0);
$dbInfo = include '../db/conn.php';
include '../init.php';
$mysql     = new MMYsql($dbInfo['user']);
$mysqlHost     = new MMYsql($dbInfo['host']);
$mysqlTemp     = new MMYsql($dbInfo['temp']);
$redis = new RedisObj($dbInfo['redis']);
$visiter = new visiterInfo($mysqlHost);
$queueObj = new QueueObj;
$i = 0;
while (true) {
	$info = $queueObj->getRqueue($redis);

	if(empty($info)){
		sleep(2);
		continue;
	}
	try{
		if(!isset($info['userData'])&&!isset($info['commonData'])){
			//只存临时
			$visiterKey = $info['userDataTemp']['visiter_key'] ?? '';
			if(!empty($visiterKey)) {
	    		$info = [
					'commonData'=>$info['commonDataTemp'] ?? [],
					'userData'=>$info['userDataTemp'] ?? []
				];
				if(empty($info['commonData'])&&empty($info['userData'])){
					continue;
				}
				$visiter->setDb($mysqlTemp)->setTempUserData(['mkey'=>trim($visiterKey),'content'=>$visiter->encodeJson($info)]);
				$info = NULL;
			}
		}else{
			$result = $visiter->setDb($mysqlHost)->setRedis($redis)->addUserData($mysql,$info);
		    if((int)$result == 2){
		    	if(isset($info['commonDataTemp'])&&!empty($info['commonDataTemp'])){
					$visiterKey = $info['userData']['visiter_key'] ?? '';
					if(!empty($visiterKey)) {
			    		$info = [
							'commonData'=>$info['commonDataTemp'] ?? [],
							'userData'=>$info['userDataTemp'] ?? []
						];
						$visiter->setDb($mysqlTemp)->setTempUserData(['mkey'=>trim($visiterKey),'content'=>$visiter->encodeJson($info)]);
						$info = NULL;
					}
		    	}   	
		    }			
		}
	}catch(\Exception $e){
		if(!empty($e)){
			file_put_contents('../logs/userQueue'.date('Y-m-d').'.txt',json_encode($e), FILE_APPEND);
			file_put_contents('../logs/userQueue'.date('Y-m-d').'.txt',json_encode(debug_backtrace()), FILE_APPEND);
			exit;	
		}
	}
    $i++;
    if ($i > 100) {
        $i     = 0;
        $mysql->close();
		$mysqlHost->close();
		$mysqlTemp->close();
		//exit;
		$mysql     = new MMYsql($dbInfo['user']);
		$mysqlHost     = new MMYsql($dbInfo['host']);
		$mysqlTemp     = new MMYsql($dbInfo['temp']);
		$redis = new RedisObj($dbInfo['redis']);
    }
}