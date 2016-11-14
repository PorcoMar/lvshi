<?php
namespace App\Controller;
use Think\Controller;
class AdviceAnswerController extends Controller {
    public function index(){
        $this->assign('from',I('from'));
        $this->display();
    }
    /*
     * 选择角色
     * */
    public function character(){

        $cookie=cookie('userInfo');

        if (!empty($cookie))
        {
            $data=array(
                'token'=>$cookie,
                'type'=>I('type')
            );
//            $user_agent = $_SERVER['HTTP_USER_AGENT'];
//            print_r($user_agent);die;
//            print_r($data);die;
            $enter=get_curl_info(CHARACTER,$data);
            if($enter && $enter['code'] == 1000){
                $data['status']  = 1;
                $this->ajaxReturn($data);
                exit;
            }else{
                $data['status']  = 0;
                $data['msg'] = $enter['msg'];
                $this->ajaxReturn($data);
                exit;
            }
        }else{
            $data['status']  = 2;
            $this->ajaxReturn($data);
            exit;
        }

    }
}