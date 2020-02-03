<?php  
if(empty($_GET['s'])){
$count=2000;
}else{
$count=$_GET['s'];
}
$server_name = $_SERVER['HTTP_HOST'];
function rand_str($length = 5)
{
$str    = '';
$strPol = "ABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789abcdefghijklmnopqrstuvwxyz";
$max    = strlen($strPol)-1;
for($i = 0; $i < $length; $i++)
{
$str   .=$strPol[rand(0,$max)];
}
return $str;
}
for ($i = 0; $i < $count; $i++) {
$urls[]="http://{$server_name}/".rand_str().'/'.rand_str().'.xml';
}
$api = 'http://data.zz.baidu.com/urls?site=http://'.$server_name.'&token=u9t314zobyOHXAFP';
$ch = curl_init();
$options =  array(
CURLOPT_URL => $api,
CURLOPT_POST => true,
CURLOPT_RETURNTRANSFER => true,
CURLOPT_POSTFIELDS => implode("\n", $urls),
CURLOPT_HTTPHEADER => array('Content-Type: text/plain'),
);
curl_setopt_array($ch, $options);
$result = curl_exec($ch);
echo $result;
?>