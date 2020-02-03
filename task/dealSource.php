<?php
set_time_limit(0);
ini_set('memory_limit','1024M');
$dbInfo = include '../db/conn.php';
include '../init.php';
/*include '../db/mmysql.php';
include '../service/queueObj.php';*/
$mysql     = new MMYsql($dbInfo['source']);
$mysqlHost = new MMYsql($dbInfo['host']);
$redisObj = new RedisObj($dbInfo['redis']);
$_config = $dbInfo['extra'];
$hostSep = $_config['hostSeparator'];
$seoSep = $_config['seoSeparator'];
$hostTableKey = 'host_map';
$pathDir   = '';
$i         = 0;
$tableArr  = ['1' => 'juzi', '2' => 'bt', '3' => 'pic', '4' => 'img', '5' => 'wzmz', '6' => 'lanmu', '7' => 'keyword', '8' => 'zhon', '9' => 'hou', '10' => 'wailian', '11' => 'moban', '12' => 'juzi2', '13' => 'ditu'];
$queueList = new QueueObj();

while (true) {
    try{
        $i++;
        $res = $queueList->setDb($mysql)->getSourceQueue(1);
        if (empty($res)) {
            sleep(1);
            if($i>3){
                $mysql->close();
                $mysqlHost->close();
                exit;
            }
            continue;
        }
        foreach ($res as $key => $value) {
            //$value['path'] = $pathDir . DIRECTORY_SEPARATOR . ltrim($value['path'], DIRECTORY_SEPARATOR);
            $info          = json_decode($value['data'], true);
            if (!file_exists($value['path']) || empty($info)) {
                $queueList->setDb($mysql)->delQueue($value['id']);
                //判断path合法
                if(file_exists($value['path'])){
                    unlink($value['path']);                
                }
                continue;
            }
            $curData = [];
            if ($info['type'] == 11) {
                //moban
                $fileContent = $queueList->setDb($mysql)->getFallData($value['path']);
                if (empty($fileContent)) {
                    $queueList->setDb($mysql)->delQueue($value['id']);
                    //判断path合法
                    unlink($value['path']);
                    continue;
                }
                $fileContent = trim($fileContent);
                $curData['mkey'] = md5($fileContent);
                $curData['type'] = $info['moban'];
                $exists          = $queueList->setDb($mysql)->checkData($curData['mkey'], 'moban');
                if (empty($exists)) {
                    $fileContent=$queueList->characet($fileContent);
                    $fileContent = addslashes(htmlspecialchars($fileContent,ENT_QUOTES,'UTF-8'));
                    if (empty($fileContent)) {
                        $queueList->setDb($mysql)->delQueue($value['id']);
                        //判断path合法
                        unlink($value['path']);
                        continue;
                    }
                    $curData['content'] =  $fileContent;
                    //$curData['content'] =  addslashes(htmlspecialchars($fileContent));
                    $result             = $queueList->setDb($mysql)->addSource($mysql, $curData, 'moban');
                    if (!$result) {
                        continue;
                    }
                }
                $curData = $fileContent = NULL;
            }else if ($info['type'] == 13) {
                //ditu
                $fileContent = $queueList->setDb($mysql)->getFallData($value['path']);
                if (empty($fileContent)) {
                    $queueList->setDb($mysql)->delQueue($value['id']);
                    //判断path合法
                    unlink($value['path']);
                    continue;
                }
                $fileContent = trim($fileContent);
                $curData['mkey'] = md5($fileContent);
                $exists          = $queueList->setDb($mysql)->checkData($curData['mkey'],'ditu');
                if (empty($exists)) {
                    $fileContent=$queueList->characet($fileContent);
                    $fileContent = addslashes(htmlspecialchars($fileContent,ENT_QUOTES,'UTF-8'));
                    if (empty($fileContent)) {
                        $queueList->setDb($mysql)->delQueue($value['id']);
                        //判断path合法
                        unlink($value['path']);
                        continue;
                    }
                    $curData['content'] =  $fileContent;
//                    $curData['content'] = addslashes(htmlspecialchars($fileContent));
                    $result             = $queueList->setDb($mysql)->addSource($mysql, $curData,'ditu');
                    if (!$result) {
                        continue;
                    }
                }
                $curData = $fileContent = NULL;
            } else if ($info['type'] == 14) {
                //域名
                foreach ($queueList->setDb($mysqlHost)->getFlineData($value['path']) as $va) {
                    if (empty($va)) {
                        continue;
                    }
                    $exists = $queueList->setDb($mysqlHost)->checkHost($va);
                    if (empty($exists)) {
                        $result = $queueList->setDb($mysqlHost)->addHost($va);
                        if (!$result) {
                            continue;
                        }
                    }
                }
            } else if ($info['type'] == 15) {
                //域名seo
                $fileContent = $queueList->setDb($mysqlHost)->getFallData($value['path']);
                if (empty($fileContent)) {
                    continue;
                }
                $hostInfo = explode($hostSep, trim($fileContent));
                foreach ($hostInfo as $k => $v) {
                    if(empty($v)){
                        continue;
                    }
                    $seoInfo = explode($seoSep, trim($v));
                    if(!empty($seoInfo)){
                        $result = $queueList->setDb($mysqlHost)->addHost($seoInfo,1);
                        if (!$result) {
                            continue;
                        }                    
                    } 
                }
                $hostInfo = $fileContent = NULL;
            }  else {
                $curType  = (string) $info['type'];
                $curTname = $tableArr[$curType] ?? '';
                if(empty($curTname)){
                    unlink($value['path']);
                    continue;
                }
                $tmpId = $value['id'];
                if(empty($tmpId)){
                    break;
                }
                $tmpK = "sourceQueue_{$tmpId}";
                $tmpCurLine = $redisObj->get($tmpK);
                if(empty($tmpCurLine)){
                    $tmpTotalLines = $queueList->getFileTotalLines($value['path']);
                    $tmpStart = 1;
                }else{
                    $tmpArr = json_decode($tmpCurLine,true);
                    $tmpTotalLines = (int)$tmpArr['tmpTotalIndex'];
                    $tmpStart = (int)$tmpArr['index'];                    
                }
                //foreach ($queueList->setDb($mysql)->getFlineData($value['path']) as $va) {
                foreach ($queueList->getFileLine($value['path'],$tmpStart,$tmpTotalLines) as $va) {
                    try{
                        if (empty($va)||!isset($va['content'])||empty($va['content'])) {
                            continue;
                        }
                        $vaContent = $va['content'];
                        $vaIndex = $va['curLine'];
                        $tmpCurData['mkey'] = md5($vaContent);
                        $exists          = $queueList->setDb($mysql)->checkData($tmpCurData['mkey'], $curTname);
                        if (empty($exists)) {
                            $tmpCurData['content'] =  addslashes(htmlspecialchars($va['content']));
                            $result             = $queueList->setDb($mysql)->addSource($mysql, $tmpCurData, $curTname);
                            if (!$result) {
                                continue;
                            }
                        }
                        if($vaIndex<$tmpTotalLines){
                            $tmpCurLine = $redisObj->set($tmpK,json_encode(['index'=>$vaIndex,'tmpTotalIndex'=>$tmpTotalLines]));
                        } else {
                            $tmpCurLine = $redisObj->delete($tmpK);
                        }
                    }catch(\Exception $e){
                        file_put_contents('../logs/sourceQueue'.date('Y-m-d').'.txt','other:'.json_encode($e), FILE_APPEND);
                        $mysql->close();
                        $mysqlHost->close();
                        $mysql     = new MMYsql($dbInfo['source']);
                        $mysqlHost = new MMYsql($dbInfo['host']);
                        $redisObj = new RedisObj($dbInfo['redis']);
                        continue;
                    }
                }
            }
            if(in_array((int)$info['type'], [14,15])){
                $queueList->setDb($mysqlHost)->setRedis($redisObj)->hostMapCache();
            }
            $queueList->setDb($mysql)->delQueue($value['id']);
            //判断path合法
            if(file_exists($value['path'])){
                unlink($value['path']);                
            }
        }
        $mysql->close();
        $mysqlHost->close();
        $mysql     = new MMYsql($dbInfo['source']);
        $mysqlHost = new MMYsql($dbInfo['host']);
        $redisObj = new RedisObj($dbInfo['redis']);
    }catch(\Exception $e){
        if(!empty($e)){
            file_put_contents('../logs/sourceQueue'.date('Y-m-d').'.txt',json_encode($e), FILE_APPEND);
            file_put_contents('../logs/sourceQueue'.date('Y-m-d').'.txt',json_encode(debug_backtrace()), FILE_APPEND); 
            exit;              
        }        
    }
}


