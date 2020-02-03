<?php
set_time_limit(0);
//define('DIR', dirname(__FILE__));
$dbInfo = include '../db/conn.php';
//include '../db/MMYsql.php';
include '../init.php';
$maxNum     = $dbInfo['extra']['tableMax'];
$tempMaxNum = $dbInfo['extra']['tempMax'];
try{
    while (true) {
        //$i--;
        $mysqlArr = [
            'source' => new MMYsql($dbInfo['source']),
            'host'   => new MMYsql($dbInfo['host']),
            'user'   => new MMYsql($dbInfo['user']),
            'temp'   => new MMYsql($dbInfo['temp']),
        ];
        foreach ($mysqlArr as $key => $model) {
            if ($key == 'temp') {
                $sql = "select max(id) as mid from temp_user_content";
                $res = $model->doSql($sql);
                $res = current($res);
                if (isset($res['mid']) && $res['mid'] >= $tempMaxNum) {
                    truncateTable($model, 'temp_user_content');
                }
            } else {
                $sql = "select `name`,`num` from {$key}_valid_table where id>0";
                $res = $model->doSql($sql);
                foreach ($res as $k => $v) {
                    $name   = $key . '_' . $v['name'] . '_' . $v['num'];
                    $sql    = "select count(id) as tnum from `{$name}` where id>0 limit 1";
                    $curRes = $model->doSql($sql);
                    $curRes = current($curRes);
                    if (isset($curRes['tnum']) && $curRes['tnum'] >= $maxNum) {
                        $newNum = createTable($model, "{$key}_" . $v['name'], $v['num'], $v['name'], $key);
                    }
                }
            }        
            $model->close();
        }
        $mysqlArr = null;
        sleep(30*60);
    }
}catch(\Exception $e){
    if(!empty($e)){
        file_put_contents('../logs/checkTables'.date('Y-m-d').'.txt',json_encode($e), FILE_APPEND);
        file_put_contents('../logs/checkTables'.date('Y-m-d').'.txt',json_encode(debug_backtrace()), FILE_APPEND);
        exit;   
    }
}
function truncateTable($mysql, $tableName)
{
    $sql = "truncate table {$tableName}";
    $res = $mysql->doSql($sql);
}
function createTable($mysql, $name, $curNum, $shortName, $prefix)
{
    try {
        $num      = $curNum;
        $oldTable = "{$name}_{$num}";
        $num++;
        $newTable = "{$name}_{$num}";
        $sql = "create table {$newTable} like {$oldTable}";
        $res = $mysql->doSql($sql);
        $sql = "select * from information_schema.tables where table_name ='" . $newTable . "'";
        $res = $mysql->doSql($sql);
        if (empty($res)) {
            return false;
        }
        $sql = "update {$prefix}_valid_table set num={$num} where name='" . $shortName . "'"; //echo $sql;
        $res = $mysql->doSql($sql); //var_dump($res);
        if (empty($res)) {
            return false;
        }
    } catch (\Exception $e) {
        file_put_contents('../logs/checkTables'.date('Y-m-d').'.txt',json_encode($e), FILE_APPEND);
        return false;
    }
}
/*function updateNum($mysql,$name,$num){
$sql = "update fym_valid_table set num={$num} where name='".$name."'";
$res=$mysql->doSql($sql);
return $res;
}*/
