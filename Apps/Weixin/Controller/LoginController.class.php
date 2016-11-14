<?php
namespace Weixin\Controller;
use Think\Controller;
class LoginController extends Controller {
    public function index(){
        $this->display();
    }

    /*
     * 手机验证码
     * @param   [json]  verify  [获取验证码接口]
     * */
    public function code(){
        $mobile['mobile'] = I('mobile');
        $verify=get_curl_info(SENDSMS,$mobile);
        if($verify['code']=='1000'){
            $data['status']  = 1;
            $data['msg'] = '短信已发送！';
            $this->ajaxReturn($data);
            exit;
        }else{
            $data['status']=0;
            $data['msg']='请不要重复发送验证码!';
            $this->ajaxReturn($data);
            exit;
        }
    }

    /*
    * 登入验证
    * @param  [json]  enter   [登入接口]
    * */
    public function login(){
        if (IS_AJAX){
            $data['code'] = I('code');
            $data['mobile'] = I('mobile');
            $enter=get_curl_info(LOGIN,$data);
            if($enter && $enter['code'] == 1000){
                cookie('userInfo',$enter['result']['token'],time()+2*15*24*3600);

                /*var_dump($aa);
                print_r($_COOKIE);
                die;*/
               // session('userInfo',$enter['result']);
                $data['status']  = 1;
                $this->ajaxReturn($data);
                exit;
            }else{
                $data['status']  = 0;
                $data['msg'] = $enter['msg'];
                $this->ajaxReturn($data);
                exit;
            }
        }
    }
}