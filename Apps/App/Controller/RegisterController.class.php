<?php
namespace App\Controller;
use Think\Controller;
class RegisterController extends Controller {
    public function index(){
        session('from',I('from'));
        $this->assign('from',I('from'));
        $this->assign('COMMON',C('COMMON'));
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
            $from=I('from');
            if(!empty($from)){
                $data['from']=$from;
            }else{
                $data['from']=session('from');
            }
            $data['code'] = I('code');
            $data['mobile'] = I('mobile');
            $data['os_name']='h5';
            $enter=get_curl_info(LOGIN,$data);
            if($enter && $enter['code'] == 1000){
                cookie('userInfo',$enter['result']['token'],time()+2*15*24*3600);
                $data['status']  = 1;
                $data['new_user']  = $enter['result']['userinfo']['new_user'];
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