<?php
try{
	require_once('./run.php');
}catch(\Exception $e){
    if(!empty($e)){
        file_put_contents('./logs/index'.date('Y-m-d').'.txt',json_encode($e), FILE_APPEND);           
    }      
}
