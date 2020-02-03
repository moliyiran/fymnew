<?php
if (empty($res)) {
    if(!isset($html['table_no'])||empty($html['table_no'])||!isset($html['id'])||empty($html['id'])||!isset($html['content'])||empty($html['content'])){
        die;
    }
    $commonData                 = $userDataArr = [];
    $commonData['read_tpl']     = ['table_no' => $html['table_no'], 'id' => $html['id']];
    $html                       = $html['content'];
    $userDataArr['visiter_key'] = $visiterKey;
    $userDataArr['url']         = $yuming;
}
if(empty($tempRes)){
    $commonDataTemp                 = $userDataArrTemp = [];
    $commonDataTemp['read_tpl'] = $html;
    $userDataArrTemp['visiter_key'] = $visiterKey;
}
$renderObj = new RenderTpl($html);
$curArr    = ['<ky目录>' => $mulu_name, '<ky当前地址>' => $url, '<ky当前域名>' => $yuming, '<ky首页标题>' => $indexInfo['title'], '<ky首页关键词>' => $indexInfo['keywords'], '<ky首页描述>' => $indexInfo['description']];
foreach ($curArr as $key => $value) {
    $curHtml = $renderObj->tpl;
    if (strstr($curHtml, $key)) {
        $renderObj->singleRender($key, $value);
    }
}

$doubleArr = [
    'ky_btjz'   => ['txt' => '<ky变态句子>', 'var' => 'juzi'],
    'ky_bt'     => ['txt' => '<ky标题>', 'var' => 'title'],
    'cur_kytp'  => ['txt' => '<ky图片>', 'var' => 'pic'],
    'ky_xt'     => ['txt' => '<ky小图>', 'var' => 'img'],
    'ky_qzbt'   => ['txt' => '<ky权重标题>', 'var' => 'duankous'],
    'ky_lmmc'   => ['txt' => '<ky栏目名称>', 'var' => 'lanmu'],
    'ky_luanma' => ['txt' => '<ky乱码>', 'var' => 'juzi'],
    'ky_sjgjc'  => ['txt' => '<ky随机关键词>', 'var' => '_keyword'],
    'wailian'   => ['txt' => '<ky随机外链>', 'var' => 'wailian'],
    'ky_juzi'   => ['txt' => '<ky句子>', 'var' => 'juzi'],
];
foreach ($doubleArr as $key => $value) {
    $curHtml = $renderObj->tpl;
    if (strstr($curHtml, $value['txt'])) {
        $doZm = 0;
        $wk   = count(explode($value['txt'], $curHtml)) - 1;
        if (in_array($key, ['ky_btjz', 'ky_luanma'])) {
            $doZm = 1;
        } else if ($value['txt'] == 'ky_juzi') {
            $doZm = 2;
        }
        $curVar = $value['var'];
        if (!empty($res)) {
            for ($wi = 0; $wi < $wk; $wi++) {
                if ($key == 'wailian') {
                    $curInfo  = $sourceObj->setDb($mysqlSource)->setRedis($redisObj)->getRandomStrByKey($curVar);
                    $cur_kybt = $curInfo['content'] ?? '';
                } else {
                    $cur_kybt = $commonData[$key][$wi] ?? '';
                }
                if(empty($tempRes)){
                    $commonDataTemp[$key][$wi] = $cur_kybt;
                }
                
                if ($doZm == 1) {
                    $cur_kybt = $sourceObj->zm_content($cur_kybt);
                } else if ($doZm == 2) {
                    $newtext  = preg_replace_callback("/(。|？|！|；|…|·|—)/iUs", "dyy_xgl", $cur_kybt);
                    $cur_kybt = UnicodeEncode($newtext);
                }

                $renderObj->singleRender($value['txt'], $cur_kybt, 1);
            }
        } else {
            for ($wi = 0; $wi < $wk; $wi++) {
                $curInfo  = $sourceObj->setDb($mysqlSource)->setRedis($redisObj)->getRandomStrByKey($curVar);
                $cur_kybt = $curInfo['content'] ?? '';
                if ($key != 'wailian') {
                    $commonData[$key][$wi]     = !empty($curInfo) ? ['table_no' => $curInfo['table_no'], 'id' => $curInfo['id']] : '';
                    if(empty($tempRes)){
                        $commonDataTemp[$key][$wi] = $curInfo['content'] ?? '';
                    }
                }
                if ($doZm == 1) {
                    $cur_kybt = $sourceObj->zm_content($cur_kybt);
                } else if ($doZm == 2) {
                    $newtext  = preg_replace_callback("/(。|？|！|；|…|·|—)/iUs", "dyy_xgl", $cur_kybt);
                    $cur_kybt = UnicodeEncode($newtext);
                }

                $renderObj->singleRender($value['txt'], $cur_kybt, 1);
            }
        }
    }
}
$_arr = [
    'read_keyword' => ['txt' => '<ky关键词>', 'var' => '_keyword'],
    'bt_keyword'   => ['txt' => '<ky变态关键词>', 'var' => '_keyword'],
    'var'          => ['txt' => '<ky吸引标题>', 'var' => 'var'],
    'vic_title'    => ['txt' => '<ky关键词2>', 'var' => 'vic_title'],
    'ky_btgjc2'    => ['txt' => '<ky变态关键词2>', 'var' => 'vic_title'],
];
foreach ($_arr as $key => $value) {
    $curHtml = $renderObj->tpl;
    if (strstr($curHtml, $value['txt'])) {
        //按人存
        if (!empty($res)) {
            $txt = $commonData[$key] ?? '';
            if(empty($tempRes)){
                $commonDataTemp[$key] = $txt;
            }
            if (in_array($key, ['bt_keyword', 'ky_btgjc2'])) {
                $txt = $sourceObj->zm_content($txt);
            }

            $renderObj->singleRender($value['txt'], $txt);

        } else {
            $curVar               = $value['var'];
            $curInfo              = $sourceObj->setDb($mysqlSource)->setRedis($redisObj)->getRandomStrByKey($curVar);
            if(!empty($curInfo)){
                $txt                  = $curInfo['content'] ?? '';
                $commonData[$key]     = !empty($curInfo) ? ['table_no' => $curInfo['table_no'], 'id' => $curInfo['id']] : '';
                if(empty($tempRes)){
                    $commonDataTemp[$key] = $txt;
                }
                if (in_array($key, ['bt_keyword', 'ky_btgjc2'])) {
                    $txt = $sourceObj->zm_content($txt);
                }                
            }else {
                $txt = '';
            }
            $renderObj->singleRender($value['txt'], $txt);
        }
    }
}

$_arr = [
    'ky_sj'   => ['txt' => '<ky时间>'],
    'ky_dtsj' => ['txt' => '<ky当天时间>'],
];
foreach ($_arr as $key => $value) {
    $curHtml = $renderObj->tpl;
    if (strstr($curHtml, $value['txt'])) {
        //按人存
        if (!empty($res)) {
            $txt = $userDataArr[$key] ?? '';
            if(empty($tempRes)){
                $userDataArrTemp[$key] = $txt;
            }
            $renderObj->singleRender($value['txt'], $txt);
        } else {
            switch ($key) {
                case 'ky_sj':
                    $txt = date("Y年m月d日 H:i");
                    break;
                case 'ky_dtsj':
                    $txt = date("Y年m月d日 ");
                    break;
            }
            $userDataArr[$key]     = $txt;
            if(empty($tempRes)){
                $userDataArrTemp[$key] = $txt;
            }
            $renderObj->singleRender($value['txt'], $txt);
        }
    }
}

$_arr = [
    'ky_sjzf' => ['txt' => '<ky随机字符>'],
    'ky_sjsz' => ['txt' => '<ky随机数字>'],
    'ky_sjzm' => ['txt' => '<ky随机字母>'],
    'ky_dzf'  => ['txt' => '<ky短字符>'],
];
foreach ($_arr as $key => $value) {
    //按人存
    $curHtml = $renderObj->tpl;
    if (strstr($curHtml, $value['txt'])) {
        if (!empty($res)) {
            if ($key == 'ky_dzf') {
                $txt = $userDataArr[$key] ?? '';
                if(empty($tempRes)){
                    $userDataArrTemp[$key] = $txt;
                }
                $renderObj->singleRender($value['txt'], $txt);
                continue;
            }
            $wk = count(explode($value['txt'], $curHtml)) - 1;
            for ($wi = 0; $wi < $wk; $wi++) {
                $txt = $userDataArr[$key][$wi] ?? '';
                if(empty($tempRes)){
                    $userDataArrTemp[$key][$wi] = $txt;
                }
                $renderObj->singleRender($value['txt'], $txt, 1);
            }
        } else {
            if ($key == 'ky_dzf') {
                $txt                   = $sourceObj->randCode(mt_rand(3, 6), 1);
                $userDataArr[$key]     = $txt;
                if(empty($tempRes)){
                    $userDataArrTemp[$key] = $txt;
                }
                $renderObj->singleRender($value['txt'], $txt);
                continue;
            }
            $wk = count(explode($value['txt'], $curHtml)) - 1;
            for ($wi = 0; $wi < $wk; $wi++) {
                switch ($key) {
                    case 'ky_sjzf':
                        $txt = $sourceObj->randCode(mt_rand(2, 8), -1);
                        break;
                    case 'ky_sjsz':
                        $txt = $sourceObj->randCode(mt_rand(3, 7), 3);
                        break;
                    case 'ky_sjzm':
                        $txt = $sourceObj->randCode(mt_rand(3, 7), 0);
                        break;
                    case 'ky_dzf':
                        $txt = $sourceObj->randCode(mt_rand(3, 6), 1);
                        break;
                }
                $userDataArr[$key][$wi]     = $txt;
                if(empty($tempRes)){
                    $userDataArrTemp[$key][$wi] = $txt;
                }
                $renderObj->singleRender($value['txt'], $txt, 1);
            }
        }
    }
}
$html = $renderObj->tpl;
if(!empty($visiterKey)){
    if(empty($res)&&!empty($commonData)){
        $queueObj = new QueueObj;
        $result = $queueObj->addRqueue($redisObj,['userData'=>$userDataArr,'commonData'=>$commonData,'userDataTemp'=>$userDataArrTemp,'commonDataTemp'=>$commonDataTemp]);
    }
    if(!empty($res)&&empty($tempRes)){
        $queueObj = new QueueObj;
        $result = $queueObj->addRqueue($redisObj,['userDataTemp'=>$userDataArrTemp,'commonDataTemp'=>$commonDataTemp]);
    }    
}
$res = $userDataArr = $commonData = $userDataArrTemp = $commonDataTemp = NULL;
function dyy_xgl($aa = array(''))
{
    $xxgl[0] = "";
    $xxgl[1] = "";
    $xxgl[2] = "";
    $xxgl[3] = "";
    $ds      = mt_rand(3, 5);
    $hash    = "";
    for ($i = 0; $i < $ds; $i++) {
        $hash .= $xxgl[mt_rand(0, 3)];
    }
    return $aa[0] . $hash;
}
function UnicodeEncode($str)
{
    //split word
    preg_match_all('/./u', $str, $matches);

    $unicodeStr = "";
    foreach ($matches[0] as $m) {
        //拼接
        $unicodeStr .= $m;
    }
    return $unicodeStr;
}
