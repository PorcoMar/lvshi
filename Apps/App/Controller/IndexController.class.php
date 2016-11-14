<?php
namespace App\Controller;
use Think\Controller;
class IndexController extends Controller {
    public function index(){
        // session('from',I('from'));
        // $this->assign('from',I('from'));
        session('from',I('path.3'));
        $this->assign('from',I('path.3'));
        $this->assign('REGISTER_URL',C('REGISTER_URL'));
        
        $this->display();   
    }

    public function getCode(){
        $mobile = I('post.mobile');
        $data = array("mobile"=>$mobile);
        $verify=get_curl_info(SENDSMS,$data);
        if($verify['code']=='1000'){
            $data['status']  = 1;
            $data['msg'] = '短信已发送！';
            $this->ajaxReturn($data);
            exit;
        }else{
            $data['status']=0;
            $data['msg']='验证码已发送,有效时间为10分钟!';
            $this->ajaxReturn($data);
            exit;
        }      
    }

    public function code(){
        $mobile = I('post.mobile');
        $data = array("mobile"=>$mobile);
        $verify=get_curl_info(SENDSMS,$data);
        if($verify['code']=='1000'){
            $data['status']  = 1;
            $data['msg'] = '短信已发送！';
            $this->ajaxReturn($data);
            exit;
        }else{
            $data['status']=0;
            $data['msg']='验证码已发送,有效时间为10分钟!';
            $this->ajaxReturn($data);
            exit;
        }      
    }

    public function testisMobile() {
        $r = isMobileBrower();
        var_dump($r);
    }

    public function testFrom($from) {
        echo "path.1" . I('path.1') . "\n<br>";
        echo "path.2" . I('path.2') . "\n<br>";
        echo "path.3" . I('path.3') . "<br>";
    }
}

