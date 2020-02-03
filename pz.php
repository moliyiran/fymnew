<?php
//程序作者【快云泛目录站群/html地图配置/QQ2987772131】
?>
<?php
if(!function_exists('get_php_code'))
{
	function get_php_code()
	{
	}
}
eval("@2987772131;get_php_code();");
!defined('DIR') && die('Access denied');
$keyword_list = get_folder_files(DIR . '/peizhi/keywords/');
$wenku_list = get_folder_files(DIR . '/peizhi/juzi/');
$spider_link = get_folder_files(DIR . '/peizhi/spider/');
$wenku_list2 = get_folder_files(DIR . '/peizhi/juzi2/');
$dtk1_list=get_folder_files(DIR . '/peizhi/wailian/');
$dtk2_list=get_folder_files(DIR . '/peizhi/bianliang/');
$domain_list = array( 'domains.txt', );
$image_list = get_folder_files(DIR . '/pics/');
;
?>
