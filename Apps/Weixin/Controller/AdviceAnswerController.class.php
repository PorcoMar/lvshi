<?php
namespace Weixin\Controller;
use Think\Controller;
class AdviceAnswerController extends Controller {
    public function index(){
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