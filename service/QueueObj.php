<?php
require_once dirname(__FILE__).'/Common.php';
class QueueObj{
	use Pub;
	public $mysql = '';
	public static $redis = null;
	public function __construct($mysql=''){
		if(!empty($mysql))
			$this->mysql = $mysql;
	}
	public function setRedis($redis){
		if(self::$redis == null){
			self::$redis = $redis;
		}
		return $this;
	}
	public function setDb($mysql){
		$this->mysql = $mysql;
		return $this;
	}
	public function getSourceQueue($num=10,$prefix='source_'){
		$tableName = "{$prefix}add_queue";
		$sql = "select * from {$tableName} where type!=10 order by id asc limit {$num}";
        $curRes=$this->mysql->doSql($sql);
        return $curRes;
        /*
        if($num == 1){
			return $this->getCurrentArray($curRes);
        } else {
			return $curRes;
        } 
        */       
	}
	public function getFallData($path){
		return trim(file_get_contents($path));
	}
	public function getFlineData($path){
		$file = fopen($path,"r");
		while(!feof($file))
		{
		    yield trim(fgets($file));
		}
		fclose($file);		
	}
	public function addHost($url,$type=0){
		$addData = [];
		if($type){
			$data = $url;
			$url = isset($data[0]) ? trim($data[0]) : '';
			if(empty($url)){
				return false;
			}
			$tmpData['title'] = isset($data[1]) ? trim($data[1]) : '';
			$tmpData['keywords'] = isset($data[2]) ? trim($data[2]) : '';
			$tmpData['description'] = isset($data[3]) ? trim($data[3]) : '';
			$addData['content'] = $this->encodeJson($tmpData);
			$hostInfo = $this->checkHost($url);
			if(!empty($hostInfo)){
		   		$tableId = $this->mysql->where(['id'=>$hostInfo['id']])->update("host_map",$addData);    
				return $tableId;		
			}
			$addData['url'] = $url;
		} else {
			$addData = ['url'=>trim($url)];
		}
   		$mapId=$tableId = $this->mysql->insert("host_map",$addData,1);
   		if(empty($tableId)){
   			return false;
   		}
   		$tableId = $this->mysql->insert("host_valid_table",['name'=>"visiter{$tableId}"],1);
   		if(empty($tableId)){
	        $this->mysql->where(['id'=>$mapId])->delete("host_map"); 
   			return false;
   		}
        $oldTable = "host_visiter_samp";
        $newTable = "host_visiter{$mapId}_1";
        $i=0;
        while($i<3){
	        $sql = "create table {$newTable} like {$oldTable}";
	        $res=$this->mysql->doSql($sql);
	        $sql = "select * from information_schema.tables where table_name ='".$newTable."'";
	        $res=$this->mysql->doSql($sql);
	        if(!empty($res)){     	
	            break;
	        }
	        $i++;
        }
        if(empty($res)){
	        $this->mysql->where(['id'=>$mapId])->delete("host_map");     
	        $this->mysql->where(['id'=>$tableId])->delete("host_valid_table");      	
            return false;
        }
        return true;	
	}
	public function hostMapCache(){
   		if(self::$redis!=null){
			$keyName = 'host_map';
	        $sql = "select * from host_map where id>0";
	        $res=$this->mysql->doSql($sql);
	        foreach ($res as $key => $value) {
	        	$url = trim($value['url']);
	        	$curVal = ['id'=>$value['id'],'title'=>'','keywords'=>'','description'=>''];
	        	if(!empty($value['content'])){
	        		$curArr = $this->decodeJson($value['content']);
	        		$curVal['title'] = $curArr['title'] ?? '';
	        		$curVal['keywords'] = $curArr['keywords'] ?? '';
	        		$curVal['description'] = $curArr['description'] ?? '';
	        	}
   				self::$redis->hSet($keyName,$url,$curVal);
	        }
   		}   		
	}
	public function checkHost($url){
		$url = trim($url);
		$res = $this->mysql->field(array('id'))
		    ->where(array('url'=>"'".$url."'"))
		    ->limit(1)
		    ->select('host_map');
		return $this->getCurrentArray($res);    
	}
	public function checkData($key,$tableName,$prefix="source_"){
		$tableInfo = $this->getInsertValidTable($this->mysql,$tableName,$prefix);
		$num = (int)$tableInfo['num'];
		$tName = "{$prefix}{$tableName}";
		$res = [];
		for($i=1;$i<=$num;$i++){
			$curTable = "{$tName}_{$i}";
			$res = $this->mysql->field(array('id'))
			    ->where(array('mkey'=>"'".$key."'"))
			    ->limit(1)
			    ->select($curTable);
		    if(!empty($res)){
		    	break;
		    }	
		}
		return $this->getCurrentArray($res);
	}
	public function delQueue($id,$prefix="source_"){
		return $this->mysql->where(['id'=>$id])->delete("{$prefix}add_queue");
	}

	public function addRqueue($redis,$data){
		if(empty($data)){
			return false;
		}
		$keyName = 'visiterQueue';
		$data = $this->encodeJson($data);
		return $redis->lPush($keyName,$data);
	}

	public function getRqueue($redis){
		$keyName = 'visiterQueue';
		$info = $redis->rPop($keyName);
		if(!empty($info)){
			$info = $this->decodeJson($info);
		}
		return $info;
	}
}
