<?php
/**
 * 一些常用的函数
 * 命名方式 多个单词用规划用"_"链接
 * 
 */

// 判断是否为手机浏览器
function isMobileBrower() {
    $regex_match = "/(nokia|iphone|android|motorola|^mot\-|softbank|foma|docomo|kddi|up\.browser|up\.link|";
    $regex_match .= "htc|dopod|blazer|netfront|helio|hosin|huawei|novarra|CoolPad|webos|techfaith|palmsource|";
    $regex_match .= "blackberry|alcatel|amoi|ktouch|nexian|samsung|^sam\-|s[cg]h|^lge|ericsson|philips|sagem|wellcom|bunjalloo|maui|";
    $regex_match .= "symbian|smartphone|midp|wap|phone|windows ce|iemobile|^spice|^bird|^zte\-|longcos|pantech|gionee|^sie\-|portalmmm|";
    $regex_match .= "jig\sbrowser|hiptop|^ucweb|^benq|haier|^lct|opera\s*mobi|opera\*mini|320×320|240×320|176×220";
    $regex_match .= ")/i";

    return isset($_SERVER['HTTP_X_WAP_PROFILE']) || isset($_SERVER['HTTP_PROFILE']) || preg_match($regex_match, strtolower($_SERVER['HTTP_USER_AGENT']));
    // $this->redirect('passport/login');
}

/*截取字符*/
function get_string($str){

    $lenth = 40;
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
function safe($text, $type = 'html', $tagsMethod = true, $attrMethod = true, $xssAuto = 1, $tags = array(), $attr = array(), $tagsBlack = array(), $attrBlack = array()){
    //无标签格式
    $text_tags = '';
    //只存在字体样式
    $font_tags = '<i><b><u><s><em><strong><font><big><small><sup><sub><bdo><h1><h2><h3><h4><h5><h6>';
    //标题摘要基本格式
    $base_tags = $font_tags . '<p><br><hr><a><img><map><area><pre><code><q><blockquote><acronym><cite><ins><del><center><strike>';
    //兼容Form格式
    $form_tags = $base_tags . '<form><input><textarea><button><select><optgroup><option><label><fieldset><legend>';
    //内容等允许HTML的格式
    $html_tags = $base_tags . '<ul><ol><li><dl><dd><dt><table><caption><td><th><tr><thead><tbody><tfoot><col><colgroup><div><span><object><embed>';
    //专题等全HTML格式
    $all_tags = $form_tags . $html_tags . '<!DOCTYPE><html><head><title><body><base><basefont><script><noscript><applet><object><param><style><frame><frameset><noframes><iframe>';
    //过滤标签
    $text = strip_tags($text, ${$type . '_tags'});
    //过滤攻击代码
    if ($type != 'all') {
        //过滤危险的属性，如：过滤on事件lang js
        while (preg_match('/(<[^><]+) (onclick|onload|unload|onmouseover|onmouseup|onmouseout|onmousedown|onkeydown|onkeypress|onkeyup|onblur|onchange|onfocus|action|background|codebase|dynsrc|lowsrc)([^><]*)/i', $text, $mat)) {
            $text = str_ireplace($mat[0], $mat[1] . $mat[3], $text);
        }
        while (preg_match('/(<[^><]+)(window\.|javascript:|js:|about:|file:|document\.|vbs:|cookie)([^><]*)/i', $text, $mat)) {
            $text = str_ireplace($mat[0], $mat[1] . $mat[3], $text);
        }
    }
    return $text;
}
//随机生成字符串
function generate( $length = 6 ) {
    // 密码字符集，可任意添加你需要的字符
    $chars = '0123456789';
    $password ='';
    for ( $i = 0; $i < $length; $i++ )
    {
        $password .= $chars[ mt_rand(0, strlen($chars) - 1) ];
    }
    return $password;
}
//curl获取外网信息
function get_curl_info($uri, $data = ''){
    $curl = curl_init();
    curl_setopt($curl, CURLOPT_URL, $uri);
    curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, FALSE);
    curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, FALSE);
    curl_setopt($curl, CURLOPT_POST, 1);
    if(is_array($data))
        $data = http_build_query($data);
    curl_setopt($curl, CURLOPT_POSTFIELDS, $data);
    curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($curl, CURLOPT_HTTP_VERSION, CURL_HTTP_VERSION_1_0);
    // curl_setopt($ch, CURLOPT_HTTPHEADER, array('Expect: ')); 
    curl_setopt($curl, CURLOPT_IPRESOLVE, CURL_IPRESOLVE_V4 ); 
    $result = curl_exec($curl);
    if (curl_errno($curl)) {
        return 'Errno' . curl_error($curl);
    }
    curl_close($curl);
    $return = json_decode($result, true);
    return $return;
}

//获取路牌
function get_nameplate(){
    $nameplate = S('nameplate');
    if($nameplate){
        return $nameplate;
    }
    $res = get_curl_info(NAMEPLATE);
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
    $res = get_curl_info(GOODS_CATE, $data);
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
    $proviceId = isset($data['pid']) ? $data['pid'] : '';
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

//显示部分手机号
function hidtel($phone){
    $IsWhat = preg_match('/(0[0-9]{2,3}[\-]?[2-9][0-9]{6,7}[\-]?[0-9]?)/i',$phone); //固定电话
    if($IsWhat == 1){
        return preg_replace('/(0[0-9]{2,3}[\-]?[2-9])[0-9]{3,4}([0-9]{3}[\-]?[0-9]?)/i','$1****$2',$phone);
    }else{
        return  preg_replace('/(1[358]{1}[0-9])[0-9]{4}([0-9]{4})/i','$1****$2',$phone);
    }
}
?>