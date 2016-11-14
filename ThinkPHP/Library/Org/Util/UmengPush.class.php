<?php

/**
 * 友盟server API 接口 
 * Class ServerAPI
 * @author  caolong
 * @date    2016-04-14
 *
 */
namespace Org\Util;
class UmengPush{
    private $method;                //提交方法
    private $url;                   //URL地址
    private $iosAppKey;             //IOS应用唯一标识。友盟消息推送服务提供的appkey
    private $iosAppMasterSecret;    //IOS服务器秘钥，用于服务器端调用API请求时对发送内容做签名验证。
    private $androidAppKey;         //android应用唯一标识。友盟消息推送服务提供的appkey
    private $androidAppMasterSecret;//android服务器秘钥，用于服务器端调用API请求时对发送内容做签名验证。
    private $deviceTokens;          //友盟消息推送服务对设备的唯一标识。Android的device_token是44位字符串, iOS的device-token是64位。
    private $ticker;                //通知栏提示文字
    private $title;                 //通知标题
    private $text;                  //通知文字描述
    private $description;           //发送消息描述
    private $extra;                 //用户自定义key-value

    /**
     * 参数初始化
     * @param $appKey
     * @param $appSecret
     * @param string $format
     */
    public function __construct($deviceTokens, $ticker, $title, $text, $description, $extra, $method, $url, $iosAppKey, $iosAppMasterSecret, $androidAppKey, $androidAppMasterSecret){
        $this->method = $method;
        $this->url = $url;
        $this->iosAppKey = $iosAppKey;
        $this->iosAppMasterSecret = $iosAppMasterSecret;
        $this->androidAppKey = $androidAppKey;
        $this->androidAppMasterSecret = $androidAppMasterSecret;
        $this->deviceTokens = $deviceTokens;
        $this->ticker = $ticker;
        $this->title = $title;
        $this->text = $text;
        $this->description = $description;
        $this->extra = $extra;
    }

    /*
     * 安卓发送
     * @param [string]    $deviceTokens  [唯一设备]
     *        [string]    $ticker        [通知栏提示文字]
     *        [string]    $title         [通知标题]
     *        [string]    $text          [通知文字描述]
     *        [string]    $postData      [拼接POST数据]
     *        [string]    $sign,$url     [拼接签名]
     *        [string]    $description   [发送消息描述]
     * @return [json]      [返回json数据]
     * */
    public function ptoAndroid() {
        $deviceTokens=$this->deviceTokens;
        $ticker=$this->ticker;
        $title=$this->title;
        $text=$this->text;
        $description=$this->description;
        $explode=explode(',',$deviceTokens);
        $count=count($explode);
        if($count > 500) {
            die('设备超过500个');
        }
        if(empty($ticker) OR !$ticker){
            die('通知栏提示文字必填');
        }
        if(empty($title) OR !$title){
            die('通知标题必填');
        }
        if(empty($text) OR !$text){
            die('通知文字描述必填');
        }
        if(!empty($description)) {
            $description;
        }else{
            $this->description='俺的律师';
        }
        $postData = $this->_android();
        $sign = $this->_makeSign($postData,1);
        $url = $this->url . '?sign=' . $sign;
        //发送请求
        $res = $this->_curl($url,$postData);
        //判断
        if($res['ret']!='SUCCESS'){
            return $res;
        }else{
            return $res;
        }
    }

    /*
        android post_body设置
        @param      ticker  str 提示栏文字
                    title   str 通知标题
                    text    str 文字描述
                    deviceTokens    array 设备号
                    description     str  描述
        @return
    */
    private function _android() {
        $tempArr            = array(
            'appkey'        => $this->androidAppKey,
            'timestamp'     => time(),
            'type'          => 'listcast',//listcast，broadcast
            'device_tokens' => $this->deviceTokens,
            'payload'       => array(
                'display_type'      => 'notification',//通知，友盟接管处理
                'body'              => array(
                    'ticker'        => $this->ticker,
                    'title'         => $this->title,
                    'text'          => $this->text,
                    'after_open'    => 'go_custom',
                    'custom'        => 'do things', //点击通知后做的事
                ),
                'extra'=>$this->extra,
            ),
            'description'           => $this->description,//描述
            'production_mode'       => 'false',//测试，上线为true
        );
        return json_encode($tempArr);
    }


    /*
    * IOS发送
    * @param [string]    $deviceTokens  [唯一设备]
    *        [string]    $text          [通知文字描述]
    *        [string]    $postData      [拼接post数据]
    *        [string]    $sign,$url     [拼接签名]
    *        [string]    $description   [发送消息描述]
    * @return [json]      [返回json数据]
    * */
    public function ptoIos() {
        $deviceTokens=$this->deviceTokens;
        $text=$this->text;
        $description=$this->description;
        $explode=explode(',',$deviceTokens);
        $count=count($explode);
        if($count > 500) {
            die('设备超过500个');
        }
        if(!isset($text) OR !$text) {
            die('通知文字描述必填');
        }
        if(!empty($description)) {
            $description;
        }else{
            $this->description='俺的律师';
        }
        $postData = $this->_ios();
        $sign = $this->_makeSign($postData,2);
        $url = $this->url . '?sign=' . $sign;
        //发送请求
        $res = $this->_curl($url,$postData);
        //判断
        if($res['ret']!='SUCCESS'){
            return $res;
        }else{
            return $res;
        }
    }

    /*
       ios post_body消息体设置
       @param      appkey  str 应用唯一标识
                   timestamp Int 时间戳
                   text     str 文字描述
                   device_tokens    array 设备号
                   payload  str   消息内容
                   description    str  发送消息描述
       @return
   */
    private function _ios() {
        $tempArr =  array(
            'appkey'        => $this->iosAppKey,
            'timestamp'     => time(),
            'type'          => 'listcast',//消息类型,列播
            'device_tokens' =>  $this->deviceTokens,
            'payload'       => array(
                'aps'           => array(
                    'alert'         => $this->text,
                    /*'after_open'  => 'go_custom',//点击通知后做的事
                    'custom'        => 'do things',*/
                ),

                $this->extra,
            ),
            'description'           => $this->description,
            'production_mode'       => 'false',//测试，上线为true
        );
        return json_encode($tempArr);
    }


    /*
        生成签名
        @param      post_body 请求体
                    type    1,安卓，2 ios
        @return     str
    */
    private function _makeSign($post_body,$type) {
        if($type == 1)
            return strtolower(md5($this->method . $this->url . $post_body . $this->androidAppMasterSecret));
        else 
            return strtolower(md5($this->method . $this->url . $post_body . $this->iosAppMasterSecret));
    }

    /*
        拼接curl请求
    */
    private function _curl($url,$post) {
        $ch             = curl_init($url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_BINARYTRANSFER, 1);
        curl_setopt($ch, CURLOPT_POST,1);
        curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 60);
        curl_setopt($ch, CURLOPT_TIMEOUT, 60);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $post);
        $result         = curl_exec($ch);
        $httpCode       = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        $curlErrNo      = curl_errno($ch);
        $curlErr        = curl_error($ch);
        curl_close($ch);
        if ($httpCode == "0"){
            return array('code'=>$curlErrNo,'message'=> $curlErr);
        }
        else if ($httpCode != "200"){
            return array('code'=>$httpCode,'message'=> $result);
        }
        else{
            return (array)json_decode($result);
        }
    }
}
