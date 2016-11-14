<?php
/**
 * 一些常用的函数
 * 命名方式 多个单词用规划用"_"链接
 * 
 */
//字符串截取
function get_string($str){

    $lenth = 20;
    $sub = mb_substr($str,0,$lenth,'utf-8');
    if(strlen($str)>$lenth){
    $sub = $sub.'...';
    }
    
   // print_r($sub);die;
    return $sub;
}
//输出纯文本
function text($text, $parseBr = false, $nr = false){
    $text = htmlspecialchars_decode($text);
    $text = safe($text, 'text');
    if (!$parseBr && $nr) {
        $text = str_ireplace(array("\r", "\n", "\t", "&nbsp;"), '', $text);
        $text = htmlspecialchars($text, ENT_QUOTES);
    } elseif (!$nr) {
        $text = htmlspecialchars($text, ENT_QUOTES);
    } else {
        $text = htmlspecialchars($text, ENT_QUOTES);
        $text = nl2br($text);
    }
    $text = trim($text);
    return $text;
}

//curl获取外网信息
function get_curl_info($url, $data = ''){
    $curl = curl_init();
    $data = http_build_query($data);
    curl_setopt($curl, CURLOPT_URL, $url);
    curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, FALSE);
    curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, FALSE);
    curl_setopt($curl, CURLOPT_POST, 1);
    curl_setopt($curl, CURLOPT_POSTFIELDS, $data);
    curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
    $return = curl_exec($curl);   
    if (curl_errno($curl)) {
        return 'Errno' . curl_error($curl);
    }
    curl_close($curl);
    $return = json_decode($return, true);
    return $return;
}

//获取路牌
function get_nameplate(){
    $nameplate = S('nameplate');
    if($nameplate){
        return $nameplate;
    }
    $res = get_curl_info(NAMEPLATE_LIST);
    $nameplate = isset($res['result']) ? $res['result'] : array();
    S('nameplate', $nameplate, C('CACHE_LOGIN_TIME'));
    return $nameplate;
}

//获取会员口分类
function get_goods_member_category(){
    $goodsMemberCategory = S('goodsMemberCategory');
    if($goodsMemberCategory){
        return $goodsMemberCategory;
    }
    $data=array('type'=>'goodsMemberCategory');
    $res = get_curl_info(GOODSCATEGORY_LIST, $data);
    $goodsMemberCategory = isset($res['result']) ? $res['result'] : array();
    S('goodsMemberCategory', $goodsMemberCategory, C('CACHE_LOGIN_TIME'));
    return $goodsMemberCategory;
}

//获取开通省份
function get_province($data){
    $openProvince = S('openProvince');
    if($openProvince){
        return $openProvince;
    }
    $res = get_curl_info(GET_PROVINCE, $data);
    $openProvince = isset($res['result']) ? $res['result'] : array();
    S('openProvince', $openProvince, C('CACHE_LOGIN_TIME'));
    return $openProvince;
}

//获取开通城市
function get_city($data){
    $proviceId = $data['pid'];

    if($proviceId > 0){
        $cacheName = 'openCity_'.$proviceId;
    }else{
        $cacheName = 'openCity';
    }
    $openCity = S($cacheName);
    if($openCity){
        return $openCity;
    }

    $res = get_curl_info(GET_CITY, $data);
    $openCity = isset($res['result']) ? $res['result'] : array();
    S($cacheName, $openCity, C('CACHE_LOGIN_TIME'));
    return $openCity;
}

//获取关键字
function get_category($data){
    $category = S('category');
    if($category){
        return $category;
    }
    $res = get_curl_info(CASES_LIST, $data);
    $category = isset($res['result']) ? $res['result'] : array();
    S('category', $category, C('CACHE_LOGIN_TIME'));
    return $category;
}