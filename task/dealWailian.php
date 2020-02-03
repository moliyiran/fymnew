<?php
set_time_limit(0);
//ini_set('memory_limit','1024M');
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
$queueList = new QueueObj();

while (true) {
    try{
        $i++;
        $tableName = "source_add_queue";
        $sql = "select * from {$tableName} where type=10 limit 10";
        $res=$mysql->doSql($sql);   
        $curDir = __DIR__;
        $childs = [];
        foreach ($res as $key => $value) {
            $id = $value['id'];
            if(!isset($id)||empty($id)){
                continue;
            }
            $tmpK = "sourceQueue_{$id}";
            $taskExists = $redisObj->exists($tmpK);
            if (!$taskExists) {
                $pid = pcntl_fork();
                if ($pid == -1) {
                    //fork失败
                } elseif ($pid > 0) {
                    $childs[$pid] = $value;
                } elseif ($pid == 0) {
                    system("./wailian.sh {$curDir} {$id}");
                }            
            }
        }
        $mysql->close();
        $mysqlHost->close();
        while(count($childs)>0){
            foreach ($childs as $key=>$v) {
                $res=pcntl_waitpid($key, $status,WNOHANG);
                 // If the process has already exited
                if($res==-1||$res>0){
                    unset($childs[$key]);
                }
            }
        }   
        sleep(100);
        $mysql     = new MMYsql($dbInfo['source']);
        $mysqlHost = new MMYsql($dbInfo['host']);
        $redisObj = new RedisObj($dbInfo['redis']);
    }catch(\Exception $e){
        if(!empty($e)){
            file_put_contents('../logs/sourceQueueWailian'.date('Y-m-d').'.txt',json_encode($e), FILE_APPEND);
            file_put_contents('../logs/sourceQueueWailian'.date('Y-m-d').'.txt',json_encode(debug_backtrace()), FILE_APPEND); 
            exit;              
        }        
    }
}


