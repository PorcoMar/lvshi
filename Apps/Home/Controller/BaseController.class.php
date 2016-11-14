<?php
namespace Home\Controller;
use Think\Controller;
class BaseController extends Controller {

    public function _initialize()
    {
            if(isset($_SESSION['enter'])){
                //$token=$_SESSION['enter']['result']['token'];
                $session=$_SESSION['enter']['result']['userinfo']['nick_name'];
                if($session==''){
                    $session=$_SESSION['enter']['result']['userinfo']['mobile'];
                    $session=substr($session,7);
                }
                //print_r($session);die;
               // $this->assign('token',$_SESSION['enter']['result']['userinfo']['token']);
                $this->assign('token',$_SESSION['enter']['result']['token']);
                $this->assign('through_department_id', $_SESSION['enter']['result']['userinfo']['through_department_id']);
                $this->assign('avatar', $_SESSION['enter']['result']['userinfo']['avatar']);
                $this->assign('session',$session);
            }else{
            $session = '';
            $this->assign('through_department_id','');
            $this->assign('session',$session);
        }
        
    }


    public function toJson($arr, $count){
        $data = array();
        $data['rows'] = $arr;
        $data['count'] = intval($count);
        return json_encode($data);
    }
    public function errorJson($data = array()){
        $data['code'] = !empty($data['code']) ? $data['code'] : '1001';
        $data['msg'] = !empty($data['msg']) ? $data['msg'] : '请求错误';
        $this->ajaxReturn($data);
    }
    //返回正确信息
    public function successJson($data = array()){
        $res['code'] = !empty($data['code']) ? $data['code'] : '1000';
        $res['msg'] = !empty($data['msg']) ? $data['msg'] : '请求成功';
        $res['data'] = $data;
        $this->ajaxReturn($res);
    }
}