<?php
namespace Home\Controller;
use Think\Controller;
class DirectController extends BaseController {
    private $token;
    function _initialize()
    {
        parent::_initialize();
        $token = session('userInfo.token');
        if (!$token) {
            if (IS_AJAX) {
                $this->ajaxReturn(array('code' => '901', 'msg' => 'token获取失败,请先登录'));
            } else {
                $this->redirect('/Passport/index', array(), 0, '请先登录');
            }
        }
        $this->token = $token;
        $this->assign('lawyer', $_SESSION['enter']['result']['userinfo']['lawyer']);
        $this->assign('session', $_SESSION['enter']['result']['userinfo']['nick_name']);
    }

    /*
     * 我的直达号
     * */
    public function index(){
        $data = array('token' => $this->token,);
        $direct = get_curl_info(LAWYER_DIRECT,$data);
        $direct=$direct['result'];
        //print_r($direct);die;
        $date=date('Y-m-d');
        //session('through_group_id',$direct['through_group_id']);
        $this->assign('date', $date);
        $this->assign('direct', $direct);
        $this->display();
    }

    /*
     * 直达号提交
     * */
    public function throughGroup(){
        $data=array(
            'token'=>$this->token,
            'name' =>I('doctor'),
            'create_time'=>I('serviceTime'),
            'mobile'=>I('phone'),
            'address'=>I('place'),
            'lawyer_id'=>I('lawyer')
        );
        if(I('parentName')==''){
            $data['status']  = 0;
            $data['msg'] = '发送求助失败!';
            $this->ajaxReturn($data);
            exit;
        }
        $orders = get_curl_info(LAWYER_GROUP,$data);
       // print_r($orders);die;
        if( $orders['code'] == 1000){
            $data['status']  = 1;
            $this->ajaxReturn($data);
            exit;
        }else{
            $data['status']  = 0;
            $data['msg'] = '发送求助失败!';
            $this->ajaxReturn($data);
            exit;
        }

    }


}

