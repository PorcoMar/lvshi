<?php
namespace Mobile\Controller;

use Think\Controller;

class IndexController extends Controller
{

    public function index()
    {
        $this->parent();
        $navCates = get_goods_member_category();
        //print_r($navCates);die;
        $this->assign('navCates', $navCates);
        $this->display();
    }

    /**
     * @desc tree => left right
     */
    public function parent()
    {
        //获取路牌
        $nameplate = get_nameplate();
        $len = count($nameplate);
        $info = array();
        for ($i = 0, $j = 2; $i < $len; $i++) {
            $info[] = array(
                'left' => $nameplate[$i],
                'right' => $nameplate[$i + 1],
                'code' => $j,
            );
            $i++;
            $j++;
        }
        $this->assign('nameplate', $info);
    }


    public function register(){
        $code = I('code');
        $this->assign('code',$code);
        $this->display();
    }


    public function registers(){
        //var_dump(I('post.'));die;
        if(I('code')){
            $codes = I('code');
            $register=array('mobile'=>I('mobile'),'password'=>md5(I('password')),'code'=>I('verify'),'codes'=>$codes);
        }else{
            $register=array('mobile'=>I('mobile'),'password'=>md5(I('password')),'code'=>I('verify'));
        }   
        //var_dump($register);die;
        $enroll=get_curl_info(REGISTER,$register);
        //var_dump($enroll);die;
        if($enroll['code']=='1000'){
            $this->success("注册成功！","http://www.andlvshi.com/app.html");
        }else{
            $this->error("注册失败！");
        }
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
}