<?php
define('DIR', dirname(__FILE__));
$dirs = [DIR. DIRECTORY_SEPARATOR.'db',DIR. DIRECTORY_SEPARATOR.'service'];
$speStr = DIRECTORY_SEPARATOR;
spl_autoload_register(function ($class_name) use ($dirs,$speStr) {
	foreach($dirs as $v){
		$curFileName = "{$v}{$speStr}{$class_name}.php";//echo $curFileName;
		if(file_exists($curFileName)){
			require_once $curFileName;
			break;
		}		
	}    
});