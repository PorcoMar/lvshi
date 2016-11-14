<?php
namespace Org\Util;
header('content-type:text/html; charset=utf-8');
class WeiboConnect{
    /**
     * 获取sina Login 跳转到的地址值
     * @return array 返回包含code state
     *
     **/
    public function login($app_id, $callback){
        $_SESSION['state'] = md5(uniqid(rand(), TRUE)); //CSRF protection
        $login_url= "https://api.weibo.com/oauth2/authorize?client_id="
          .$app_id."&response_type=code&redirect_uri=". urlencode($callback)
        ."&state=" . $_SESSION['state'] ."&display=default";
        //显示出登录地址
        header('Location:'.$login_url);
    }
    /**
     * 获取access_token值
     * @return array 返回包含access_token,过期时间的数组
     * */
    private function get_token($app_id,$app_key,$code,$callback,$state){
        if($state !== $_SESSION['state']){
            return false;
            exit();
        }
        $url = "https://api.weibo.com/oauth2/access_token";
        $param = array(
            "grant_type"    =>    "authorization_code",
            "client_id"     =>    $app_id,
            "client_secret" =>    $app_key,
            "code"          =>    $code,
            "state"         =>    $state,
            "redirect_uri"  =>    $callback
        );
        $response = $this->get_curl_info($url, $param);
        if($response == false) {
            return false;
        }
        $params=json_decode($response);
        session('access_token',$params);
        return $params;
    }

    /**
     * 获取用户信息
     * @param $token
     * @return array 用户的信息数组
     * */
    private  function get_user_info($token) {
        $requetUrl = 'https://api.weibo.com/2/users/show.json?access_token=' . $token->access_token .
            '&uid=' . $token->uid;
        $user_info = json_decode(file_get_contents($requetUrl));
        return $user_info;
    }

public function tuichu(){
      /*$url='https://api.weibo.com/2/account/end_session.json?access_token='
          . $_SESSION['access_token']->access_token;*/
    $url='https://api.weibo.com/oauth2/revokeoauth2?access_token='
        . $_SESSION['access_token']->access_token;
    $user_info = json_decode(file_get_contents($url));
    //print_r($user_info);die;
    return $user_info;

  }


    /**
     * 请求URL地址，返回callback得到返回字符串
     * @param $url 微博提供的api接口地址
     * */

    public function callback($app_id, $app_key, $callback) {
        $code = $_GET['code'];
        $state = $_GET['state'];
        $token = $this->get_token($app_id,$app_key,$code,$callback,$state);
        $get_user_info = $this->get_user_info($token);

        //print_r($tuichu);die;
        if(!$token || !$get_user_info) {
            setcookie("users");
            session_destroy();
            return false;
            exit();
        }
        return array('get_user_info' => $get_user_info,'token' => $token);
    }


    function get_curl_info($url, $params = ''){
        $curl = curl_init();
        curl_setopt($curl, CURLOPT_URL, $url);
        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, FALSE);
        curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, FALSE);
        curl_setopt($curl, CURLOPT_POST, 1);
        if(is_array($params))
        $params = http_build_query($params);
        curl_setopt($curl, CURLOPT_POSTFIELDS, $params);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($curl, CURLOPT_HTTP_VERSION, CURL_HTTP_VERSION_1_0);
        curl_setopt($curl, CURLOPT_IPRESOLVE, CURL_IPRESOLVE_V4 );
        $result = curl_exec($curl);
        $status = curl_getinfo($curl);
        if(intval($status["http_code"]) == 200) {
            return $result;
        } else {
            return false;
        }
    }

}