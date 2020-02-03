<?php
trait Pub
{
    public function getCurrentArray($array)
    {
        if (empty($array)) {
            return [];
        }
        $array = current($array);
        if (empty($array)) {
            return [];
        }
        return $array;
    }

    public function getInsertValidTable($mysql, $tableName, $prefix = 'source_')
    {
        $curRes = $mysql->field(array('num'))
            ->where(array('name' => "'{$tableName}'"))
            ->limit(1)
            ->select("{$prefix}valid_table");
        $curRes = $this->getCurrentArray($curRes);
        if(empty($curRes)){
            return false;
        }
        return ['name' => $prefix.$tableName . '_' . $curRes['num'], 'num' => $curRes['num']];
    }
    public function addSource($mysql, $data,$tableName,$prefix="source_"){
        $tableInfo = $this->getInsertValidTable($mysql,$tableName,$prefix);
        $tableName = $tableInfo['name'];
        $result = $mysql->insert("{$tableName}",$data,1);
        return $result;
    }
    public function decodeJson($arr){
        return unserialize($arr);
    }
    public function encodeJson($arr){
        //return json_encode($arr,JSON_UNESCAPED_UNICODE);
        return serialize($arr);
    }
    public function GetCurUrlPath($server)
    {
        if(!empty($server["REQUEST_URI"]))
        {
            $scriptName = $server["REQUEST_URI"];
            $nowurl = $scriptName;
        }
        else
        {
            $scriptName = $server["PHP_SELF"];
            if(empty($server["QUERY_STRING"]))
            {
            $nowurl = $scriptName;
            }
            else
            {
            $nowurl = $scriptName."?".$server["QUERY_STRING"];
            }
        }
        return $nowurl;
    }
    public function urlType($server){
        $path = $this->GetCurUrlPath($server);
        $origPath = explode('/', rtrim($path,'/'));
        if(!isset($origPath[1]) or strstr($origPath[1],"index")){
            return 0;
        }
        $path = $origPath[count($origPath)-1];
        $path = explode('.', $path);
        $type = 0;
        if(count($path)>1){
            $type = $path[0]!='index' ? 2 : 1;
        }else if(count($path)==1){
            $type = 1;
        }
        return $type;
    } 

    public function rdomain($_var_5)
    {
        $_var_6 = array();
        return str_replace('*', dechex(date('s') . mt_rand(1111, 9999)) . $_var_6[rarray_rand($_var_6)], $_var_5);
    }
    public function rarray_rand($_var_7)
    {
        return mt_rand(0, count($_var_7) - 1);
    }
    public function varray_rand($_var_8)
    {
        return $_var_8[$this->rarray_rand($_var_8)];
    }   
    public function zm_content($str){
        $content_sz = $this->mb_str_split($str);
        $contents = '';
        foreach($content_sz as $content){
            $contents .= '&#'.base_convert(bin2hex(mb_convert_encoding($content, 'ucs-4', 'utf-8')), 16, 10).';';
        }
        return $contents;
    }

    public function mb_str_split($str){  
        return preg_split('/(?<!^)(?!$)/u', $str );  
    } 

    public function randCode($length, $type) {
        $arr = array(1 => "abcdefghijklmnopqrstuvwxyz", 2 => "ABCDEFGHIJKLMNOPQRSTUVWXYZ", 3 => "0123456789");
        if($type == 0) {
            array_pop($arr);
            $string = implode("", $arr);
        }elseif($type == "-1") {
            $string = implode("", $arr);
        }else{
            $string = $arr[$type];
        }
        $count = strlen($string) - 1;
        $code = '';
        for($i = 0; $i < $length; $i++) {
            $str[$i] = $string[rand(0, $count)];
            $code .= $str[$i];
        }
        return $code;
    }
    public function read_luanma($str){
        $luanma = iconv("gb2312","utf-8//IGNORE",$str);
        return $luanma;
    }
    public function getFileTotalLines($path){
        $line = 0 ; //初始化行数 
        //打开文件 
        $fp = fopen($path , 'r') or die("open file failure!"); 
        if($fp){ 
        //获取文件的一行内容，注意：需要php5才支持该函数； 
        while(stream_get_line($fp,8192,"\n")){ 
         $line++; 
        } 
        fclose($fp);//关闭文件 
        } 
        return $line;       
    }
    public function getFileLine($file_path,$starline=1,$length=5,$open_mode = "rb+"){

        $fp = new SplFileObject($file_path, $open_mode);

        $fp->seek($starline - 1); // 转到第N行, seek方法参数从0开始计数

        for($i=0;$i<=$length;$i++){
            $curContent = trim($fp->current());
            if(!empty($curContent)){
                yield ['content'=>trim($fp->current()),'curLine'=>$starline+$i];
            }
            
            $fp->next(); // 下一行
        }
    }
    public function characet($data){
      if( !empty($data) ){   
        $fileType = mb_detect_encoding($data , array('UTF-8','GBK','LATIN1','BIG5','gb2312')) ;
        if( $fileType != 'UTF-8'){  
          $data = mb_convert_encoding($data ,'utf-8' , $fileType);  
        } 
      }  
      return $data;   
    }   
}
