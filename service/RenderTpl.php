<?php
class RenderTpl{
	public $tpl = null;
	public function __construct($tpl=''){
		if(!empty($tpl)){
			$this->tpl = $tpl;
		}
	}
	public function rarray_rand($_var_7)
	{
		return mt_rand(0, count($_var_7) - 1);
	}
	public function varray_rand($_var_8)
	{
		return $_var_8[$this->rarray_rand($_var_8)];
	}
	public function singleRender($source,$target,$limit=-1){
		if(!is_string($target)){
			return false;
		}
		$source = trim($source,'/');
		$this->tpl = preg_replace("/{$source}/",$target,$this->tpl,$limit);
		
	}
	public function getRandomStr($arr){
		$curInfo = $this->varray_rand($arr);
		if(!empty($curInfo)) {
			$curInfo['content'] = htmlspecialchars_decode(stripslashes($curInfo['content']));
		}
		return $curInfo;			
	}
	public function arrayRender($info,$count){
		/*
		for ($wi = 0; $wi < $wk; $wi++) {
			$curInfo = trim(varray_rand($juziContent));
			$curInfo['content'] = htmlspecialchars_decode(stripslashes($curInfo['content']));
			$userDataArr['ky_juzi'][$wi] = ['table_no'=>$juzi['table_no'],'id'=>$curInfo['id']];
			$newtext = preg_replace_callback("/(。|？|！|；|…|·|—)/iUs", "dyy_xgl", $curInfo['content']);
			$newtext = UnicodeEncode($newtext);
			$html = preg_replace('/<ky句子>/', $newtext, $html, 1);
			$this->singleRender($source,$target,$this->tpl,1);
		}	
		*/	
	}	
}