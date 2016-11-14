<?php
namespace Home\Controller;
use Think\Controller;
class HelpController extends BaseController{
    /*
     * 我的求助
     * @param   prairies    [获取开通省份]
     *          navCates    [获取求助意向]
     *          sign        [求助类型]
     * */
    public function index(){
        $prairies=array('used'=>1,'num'=>6);
        $prairies=get_province($prairies);
        $navCates = get_goods_member_category();
        $sign=get_nameplate();
        $this->assign('sign',$sign);
        $this->assign('navCates',$navCates);
        $this->assign('prairies',$prairies);
        $this->assign('secMenu','HelpMe');
        $this->display();
    }

    /*
     * 发送求助
     * */
    public function adopt(){
        $data = array(
            'token'=>$_SESSION['enter']['result']['token'],
            'mobile'=>I('mobile'),
            'category_id'=>I('turnType'),
            'province_id'=>I('pId'),
            'city_id'=>I('cId'),
            'service_start_date'=>date('Y-m-d',I('start')),
            'service_end_date'=>date('Y-m-d',I('over')),
            'member_cat_id'=>I('turnIntention'),
            'is_benefit_lawyer'=>I('lawyer'),
            'is_phone'=>I('phone'),
            'is_only_response'=>I('response')
            );
        $list = get_curl_info(HELP,$data);
        if($list && $list['code'] == 1000){
            $data['status']  = 1;
            $this->ajaxReturn($data);
            exit;
        }elseif($_SESSION['enter']['result']['token']==''){
            $data['status']  = 2;
            //$data['msg'] = '请先登入';
            $this->ajaxReturn($data);
            exit;
        } else{
            $data['status']  = 0;
            $data['msg'] = '发送求助失败!';
            $this->ajaxReturn($data);
            exit;
        }
    }

    /**
     * 根据省查询
     */
    public function province(){
        $pid=I('pId');
        $data=array('used'=>1,'pid'=>$pid);
        $openCity=get_city($data);
        $data=array('type'=>'gold','province_id'=>$pid);
        $lawyer=get_curl_info(LAWYER_TYPE,$data);
        $lawyer=$lawyer['result'];
        $result=array('lawyer'=>$lawyer,'openCitys'=>$openCity);
        echo json_encode($result);
    }
    /**
     * 根据市查询
     */
    public function findCity(){
        $data=array('type'=>'gold','city_id'=>I('city'));
        $lawyer=get_curl_info(LAWYER_TYPE,$data);
        echo json_encode($lawyer);
    }

}