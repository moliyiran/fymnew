<?php
require_once dirname(__FILE__).'/Common.php';
class visiterInfo{
	use Pub;
	public $mysql = '';
	public static $redis = null;
	protected static $sourceTnum = null;
	public const  KMAP = [
		'_keyword'=>'keyword',
		'vic_title'=>'hou',
		'html1'=>'moban',
		'html2'=>'moban',
		'html3'=>'moban',
		'title'=>'bt',
		'img'=>'img',
		'pic'=>'pic',
		'juzi'=>'juzi',
		'duankous'=>'wzmz',
		'lanmu'=>'lanmu',
		'wailian'=>'wailian',
		'var'=>'zhon'
	];
	public const USERKMAP = [
	    'ky_btjz'   => 'juzi',
	    'ky_bt'     => 'title',
	    'cur_kytp'  => 'pic',
	    'ky_xt'     => 'img',
	    'ky_qzbt'   => 'duankous',
	    'ky_lmmc'   => 'lanmu',
	    'ky_luanma' => 'juzi',
	    'ky_sjgjc'  => '_keyword',
	    'wailian'   => 'wailian',
	    'ky_juzi'   => 'juzi',
	    'read_keyword' => '_keyword',
	    'bt_keyword'   => '_keyword',
	    'var'          => 'var',
	    'vic_title'    => 'vic_title',
	    'ky_btgjc2'    => 'vic_title',
	    'read_tpl'	   => 'html3'	
	];
	public function __construct($mysql=''){
		if(!empty($mysql))
			$this->mysql = $mysql;
	}
	public function setDb($mysql){
		$this->mysql = $mysql;
		return $this;
	}
	public function setRedis($redis){
		if(!empty($redis)&&self::$redis==null){
			self::$redis = $redis;
		}
		return $this;
	}
	public function getIdByUrl($url){
		if(empty($url)){
			return false;
		}
		$key = 'host_map';
		$field = $url;
		if(self::$redis != null){
			$res = self::$redis->hGet($key,$field);
			if(!empty($res)){
				$res = $this->decodeJson($res);
			}
			if(!empty($res)){
				return $res;
			}
		}
		$res = $this->mysql->field(array('id','content'))
		    ->where(array('url'=>"'".$url."'"))
		    ->limit(1)
		    ->select('host_map');
	    $res = $this->getCurrentArray($res);
		if(self::$redis != null&&!empty($res)){
			self::$redis->hSet($key,$field,$this->encodeJson($res));
		}

	    return $res;		
	}
	public function getVisiterInfoByHost($k,$url='',$type=0){
		$url = trim($url);
		if(empty($url)){
			$curTable = 'visiter_index';
		}else{
			$tableInfo = $this->getIdByUrl($url);
			if(empty($tableInfo)){
				return $type ? false : true;
			}
			$curTable = 'visiter'.$tableInfo['id'];
		}
		$k = trim($k);
		$tableInfo = $this->getInsertValidTable($this->mysql,$curTable,'host_');
		if(empty($tableInfo)){
			return $type ? false : true;
		}
		$num = $tableInfo['num'];
		//$tableName = $tableInfo['name'];
		for($i=1;$i<=$num;$i++){ 
			$tmpTable = "host_{$curTable}_{$i}";
			$res = $this->mysql->field(array('table_no','tid'))
			    ->where(array('ukey'=>"'".$k."'"))
			    ->limit(1)
			    ->select($tmpTable);
		    if(!empty($res)){
		    	break;
		    }		    
		}    
		$res = $this->getCurrentArray($res);
	    if(!empty($res)){
	    	$res = ['id'=>$res['tid'],'table_no'=>$res['table_no']];
	    }
	    return $res;
	}

	public function getSourceDetail($mysql,$tableName,$tableNo,$id){
		$curTable = "source_{$tableName}_{$tableNo}";
		$res = $mysql->field(array('content'))
		    ->where(array('id'=>$id))
		    ->limit(1)
		    ->select($curTable);
		$res = $this->getCurrentArray($res);
		if(empty($res)){
			return '';
		}
		return empty($res['content']) ? '' : htmlspecialchars_decode(stripslashes($res['content']));  
	}

	public function getSourceTableNum(){
		if(self::$sourceTnum == null){
			$sql = "select `name`,`num` from source_valid_table where id>0";
			$res=$this->mysql->doSql($sql);
			foreach ($res as $key => $value) {
				self::$sourceTnum[$value['name']] = $value['num'];
			}
		}
	}
	public function getRandSql($tableName,$num=1,$type=0){
		if(!$type){
			$sql = "SELECT t1.id,t1.content FROM `{$tableName}` AS t1 JOIN (SELECT ROUND(RAND() * ((SELECT MAX(id) FROM `{$tableName}`)-(SELECT MIN(id) FROM `{$tableName}`))+(SELECT MIN(id) FROM `{$tableName}`)) AS id) AS t2 WHERE t1.id >= t2.id ORDER BY t1.id LIMIT {$num}";
		}else{
			$sql = "SELECT t1.id,t1.content FROM `{$tableName}` AS t1 JOIN (SELECT ROUND(RAND() * ((SELECT MAX(id) FROM `{$tableName}`)-(SELECT MIN(id) FROM `{$tableName}`))+(SELECT MIN(id) FROM `{$tableName}`)) AS id) AS t2 WHERE t1.id >= t2.id  and type = {$type} ORDER BY t1.id LIMIT {$num}";
		}
		return $sql;
	}
	public function createCache(){
		if(self::$redis==null){
			return false;
		}
		$arr = self::KMAP;
		foreach ($arr as $key => $value) {
			$info = self::$redis->getM($key);
			if(!empty($info)){
				continue;
			}
			$type = 0;
			switch ($key) {
				case 'html1':
					$type = 1;
					break;
				case 'html2':
					$type = 2;
					break;
				case 'html3':
					$type = 3;
					break;
			}
			$this->getRandomStrByKey($key,$type,1);
		}
	}
	public function getRandomStrByKey($k,$type=0,$flag=0){
		if(self::$redis==null){
			return false;
		}
		$k = trim($k);
		if($type&&!$flag){
			$k="{$k}{$type}";
		}
		$info = self::$redis->getM($k);
		$sourceName =  self::KMAP[$k];
		if(empty($sourceName)){
			return '';
		}
		//if($k=='wailian'){var_dump('333');var_dump($info);exit('5');}
		if(empty($info)){
			foreach ($this->readSource($sourceName,$type) as $value) {
				if(!empty($value)){
					self::$redis->setM($k,$this->encodeJson($value));
				}
			}
		}
		if($flag){			
			$curNum = self::$redis->sCard($k);
			if($curNum<3000){
				$curSourceName = "source_{$sourceName}_1";
				$andSql = $type ? " and type={$type} " : '';
				$sql = "select id,content from {$curSourceName} where id>0 {$andSql} order by id desc limit 3000";
				$res = $this->mysql->doSql($sql);
				if(!empty($res)){
					foreach ($res as $key => $value) {
						if(!empty($value)&&isset($value['id'])&&!empty($value['id'])&&isset($value['content'])&&!empty($value['content'])){
							$tmpTableNo = 1;							
							self::$redis->setM($k,$this->encodeJson(['table_no'=>$tmpTableNo,'id'=>$value['id'],'content'=>$value['content']]));								
						}					
					}
				}
			}
			return true;
		}
		$info = self::$redis->getM($k);
		if(empty($info)){
			return [];
		}
		$info = $this->decodeJson($info);
		if(isset($info['content'])){
			$info['content'] = htmlspecialchars_decode(stripslashes($info['content']));
		}
		if(!isset($info['table_no'])||!isset($info['id'])||!isset($info['content'])||empty($info['table_no'])||empty($info['id'])){
			return '';
		}
		return $info;			
	}
	public function readSource($sourceName,$type=0,$num=3000){
		if(self::$sourceTnum == null){
			$this->getSourceTableNum();
		}
		$tNum = self::$sourceTnum[$sourceName];
		$isData = 0;
		$i = 0;
		while($i<3){
			$tNum = rand(1,$tNum);
			$tableName = "source_{$sourceName}_{$tNum}";
			$sql = $this->getRandSql($tableName,$num,$type);
			$res=$this->mysql->doSql($sql);
			
			if(!empty($res)){
				foreach ($res as $key => $value) {
					if(!empty($value)){
						yield ['table_no'=>$tNum,'id'=>$value['id'],'content'=>$value['content']];
						$isData = 1;
					}					
				}
			}
			if($type&&!$isData){
				$i = 0;
			}
			if(count($res)>500){
				break;
			}else if($type&&count($res)>10){
				break;
			}
			$i++;
		}

		//return ['table_no'=>$tNum,'info'=>$res];
	}
	public function getTempUserData($userKey){
		$userKey = trim($userKey);
		$userInfo = $this->mysql->field(array('content'))
			    ->where(array('mkey'=>"'".$userKey."'"))
			    ->limit(1)
			    ->select('temp_user_content');
		$userInfo = $this->getCurrentArray($userInfo);
		if(!empty($userInfo)&&!empty($userInfo['content'])){
			$userInfo = $this->decodeJson($userInfo['content']);
		}else{
			return [];
		}
		return $userInfo;			    		
	}
	public function setTempUserData($data){
		return $this->mysql->insert('temp_user_content',$data,1);
	}
	public function getUserData($userMysql,$sourceMysql,$userKey,$url=''){
		$userData = $this->getVisiterInfoByHost($userKey,$url,1);
		if(empty($userData)){
			return [];
		}
		if(!isset($userData['table_no'])||empty($userData['table_no'])||!isset($userData['id'])||empty($userData['id'])){
			return [];
		}
		$tableNo = $userData['table_no'];
		$id = $userData['id'];
		$curTable = "user_detail_{$tableNo}";
		$userInfo = $userMysql->field(array('content'))
			    ->where(array('id'=>$id))
			    ->limit(1)
			    ->select($curTable);
		$userInfo = $this->getCurrentArray($userInfo);
		if(empty($userInfo)||!isset($userInfo['content'])||empty($userInfo['content'])){
			return [];
		}
		$userInfo = $this->decodeJson($userInfo['content']);
		$commonData = $userInfo['commonData'];
		foreach ($commonData as $key => $value) {
			$newValue = [];
			$curIndex = self::USERKMAP[$key];
			$curTname = self::KMAP[$curIndex];
			if(isset($value['table_no'])){
				if(empty($curTname)||empty($value['table_no'])||empty($value['id'])){
					$newValue = '';
				} else {
					//单数组
					$newValue = $this->getSourceDetail($sourceMysql,$curTname,$value['table_no'],$value['id']);
				}
			}else{
				foreach ($value as $k => $v) {
					if(empty($curTname)||empty($v['table_no'])||empty($v['id'])){
						$value[$k] = '';
					} else {
						$value[$k] = $this->getSourceDetail($sourceMysql,$curTname,$v['table_no'],$v['id']);
					}
				}
				$newValue = $value;
			}
			$commonData[$key] = $newValue;
		}
		$userInfo['commonData'] = $commonData;
		return $userInfo;
	}
	public function addUserData($userMysql,$data){
		$userData = $data['userData'] ?? [];
		$commonData = $data['commonData'] ?? [];
		if(empty($userData)&&empty($commonData)){
			return true;
		}
		$data = NULL;
		$visiterKey = trim($userData['visiter_key']);
	    unset($userData['visiter_key']);
		$url = $userData['url'] ?? '';
	    unset($userData['url']);
	    if(empty($visiterKey)){
	    	return true;
	    }
	    if(!empty($url)){
			$curTable = $this->getIdByUrl($url);
			if(empty($curTable)){
				return true;
			}
			$id = $curTable['id'];
			if(empty($id)){
				return true;
			}
			$hostData = $this->getVisiterInfoByHost($visiterKey,$url);
			if(!empty($hostData)){
				return true;
			}
	    }
		$detailData = ['userData'=>$userData,'commonData'=>$commonData];
		$curTable = 'detail';
		$tableInfo = $this->getInsertValidTable($userMysql,$curTable,'user_');
		if(empty($tableInfo)||!isset($tableInfo['name'])||empty($tableInfo['name'])){
			return true;
		}
		$detailTable = $tableInfo['name'];
		$userId = $userMysql->insert($detailTable,['content'=>$this->encodeJson($detailData)],1);
		if(empty($userId)){
			return true;
		}
		$tableNo = $tableInfo['num'];
		if($url){
			$curTable = "visiter{$id}";
		}else{
			$curTable = "visiter_index";
		}
		$tableInfo = $this->getInsertValidTable($this->mysql,$curTable,'host_');
		if(empty($tableInfo)||!isset($tableInfo['name'])||empty($tableInfo['name'])){
			$userMysql->where(['id'=>$userId])->delete($detailTable);
			return true;
		}
		$result = $this->mysql->insert($tableInfo['name'],['ukey'=>$visiterKey,'table_no'=>$tableNo,'tid'=>$userId],1);
		if(empty($result)){
			$userMysql->where(['id'=>$userId])->delete($detailTable);
			return true;
		}
		return 2;
	}
}
