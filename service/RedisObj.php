<?php
class RedisObj{
	public $redis = null; //静态属性,所有数据库实例共用,避免重复连接数据库
	public $prefix="";
	public function __construct($dbinfo){
        $this->_connect($dbinfo);
	}
	protected function _connect($dbinfo){
		$host = $dbinfo['host'];
		$port = $dbinfo['port'];
		$pwd = $dbinfo['passwd'];
		$db = $dbinfo['dbname'];
		$prifix = $dbinfo['prifix'];
		$this->prifix = $prifix;
		$this->redis = new \Redis();  
		$this->redis->connect($host, $port);//serverip port
		if(!empty($pwd)){
			$this->redis->auth($pwd);//my redis password 
		}
		$this->redis->select($db);
	}
	protected function getKey($key){
		return $this->prefix."{$key}";
	}
	public function setM($key,$value,$expire=600){
		$_key = $this->getKey($key);
		$exists = $this->redis->exists($_key);
		$this->redis->sAdd($_key,$value);
		if(!$exists){
			$this->redis->expire($_key,$expire);			
		}
	}
	public function getM($key){
		$_key = $this->getKey($key);
		return $this->redis->sRandMember($_key);
	}
	public function lPush($key,$value){
		$_key = $this->getKey($key);
		if(is_array($value)){
			$value = serialize($value);
		}
		return $this->redis->lPush($_key,$value);
	}
	public function rPop($key){
		$_key = $this->getKey($key);
		return $this->redis->rPop($_key);
	}
	public function hSet($key,$field,$value,$expire=0){
		$_key = $this->getKey($key);
		if(is_array($value)){
			$value = serialize($value);
		}
		$result = $this->redis->hSet($_key,trim((string)$field),$value);
		if($expire){
			$this->redis->expire($_key,$expire);
		}
	}
	public function hGet($key,$field){
		$_key = $this->getKey($key);
		return $this->redis->hGet($_key,$field);
	}
	public function close(){
		return $this->redis->close();
	}
	public function set($key,$value){
		$_key = $this->getKey($key);
		return $this->redis->set($_key,$value);
	}
	public function get($key){
		$_key = $this->getKey($key);
		return $this->redis->get($_key);
	}
	public function delete($key){
		$_key = $this->getKey($key);
		return $this->redis->delete($_key);
	}		
	public function sCard($key){
		$_key = $this->getKey($key);
		return $this->redis->sCard($_key);
	}
	public function exists($key){
		$_key = $this->getKey($key);
		return $this->redis->exists($_key);	
	}
}
/*$dbinfo = [
		'host'=>'127.0.0.1',
		'port'=>6379,
		'passwd'=>'',
		'dbname'=>'3',
		'prifix'=>'fym_'
	];
$a = new RedisObj($dbinfo);	
exit('3');	*/
