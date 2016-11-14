<?php
namespace Home\Controller;
use Think\Controller;
class TripartiteController extends Controller {
    /*
    * Type类型，初始化
    * QQConnet  WeiboConnect
    */
    public function index(){
        switch (I('type')) {
            /* QQ互联登录 */
            case qq:
                $app_id = C('QQ_AUTH.APP_ID');
                $scope = C('QQ_AUTH.SCOPE');
                $callback = C('QQ_AUTH.CALLBACK');
                $sns = new \Org\Util\QQConnect;
                $sns->login($app_id, $callback, $scope);
                break;
            /* 新浪微博登录 */
            case sina:
                $app_id = C('SINA_AUTH.APP_ID');
                $callback = C('SINA_AUTH.CALLBACK');
                $sns = new \Org\Util\WeiboConnect;
                $sns->login($app_id, $callback);
                break;
        }
    }
    /*
     * 互联登录返回信息
     * 获取code 和 state状态，查询数据库
     *  */
    public function callback(){
        switch (I('type')) {
            /* 接受QQ互联登录返回值 */
            case qq:
                empty($_GET['code']) && $this->error("无效的第三方方式",U('/Passport/index'));
                $app_id = C('QQ_AUTH.APP_ID');
                $app_key = C('QQ_AUTH.APP_KEY');
                $callback = C('QQ_AUTH.CALLBACK');
                $qq = new \Org\Util\QQConnect;
                /* callback返回openid和access_token */
                $back = $qq->callback($app_id , $app_key, $callback);
                //防止刷新
                empty($back) && $this->error("请重新授权登录",U('/Passport/index'));
                //此处省略数据库查询，查询返回的$back['openid']
                $user=$qq->get_user_info($app_id,$back['token'],$back['openid']);
                $type=I('type');
                if($type=='qq'){
                    $type=1;
                }
                $triplicities=array(
                    'sex'=>$user['gender'],
                    'open_id'=>$back['openid'],
                    'type'=>$type,
                    'avatar'=>$user['figureurl_qq_1'],
                    'nick_name'=>$user['nickname']
                );
                //print_r($triplicities);die;
                $ties= get_curl_info(TRIPLICITIES,$triplicities);
                $_SESSION['enter']=$ties;
                redirect("/index.php");
                break;
            /* 接受新浪微博登录返回值     */
            case sina:
                empty($_GET['code']) && $this->error("无效的第三方方式",U('/Passport/index'));
                $app_id = C('SINA_AUTH.APP_ID');
                $app_key = C('SINA_AUTH.APP_KEY');
                $callback = C('SINA_AUTH.CALLBACK');
                $weibo = new \Org\Util\WeiboConnect;
                /* callback返回get_user_info和access_toke */
                $back = $weibo->callback($app_id , $app_key, $callback);
                empty($back) && $this->error("请重新授权登录",U('Passport/index'));
                //此处省略数据库查询，查询返回的$back
                if($back['get_user_info']->gender=='m'){
                    $back['get_user_info']->gender='男';
                }elseif($back['get_user_info']->gender=='f'){
                    $back['get_user_info']->gender='女';
                }else{
                    $back['get_user_info']->gender='未知';
                }
                $type=I('type');
                if($type=='sina'){
                    $type=3;
                }
                $triplicities=array(
                    'sex'=>$back['get_user_info']->gender,
                    'open_id'=>$back['get_user_info']->id,
                    'type'=>$type,
                    'avatar'=>$back['get_user_info']->avatar_large,
                    'nick_name'=>$back['get_user_info']->screen_name
                );
                $ties= get_curl_info(TRIPLICITIES,$triplicities);
                $_SESSION['enter']=$ties;
                redirect("/index.php");
                break;
        }
    }


    //微信
    public function weixin(){
        $type=I('type');
        session('type',$type);
        $appid = C('App_id');
        $appsecret = C('AppSecret');
        $redirect_uri = C('WX_NOTIFY_URL');
        $_SESSION['state'] = md5(uniqid(rand(), TRUE));
        header("location:https://open.weixin.qq.com/connect/qrconnect?appid=".$appid."&redirect_uri=".urlencode($redirect_uri)."&response_type=code&scope=snsapi_login&state=".$_SESSION['state']."#wechat_redirect");
        exit;
    }
}