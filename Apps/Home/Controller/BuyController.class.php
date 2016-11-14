<?php
namespace Home\Controller;
use Think\Controller;
class BuyController extends BaseController {
    /**
     * 购买页面
     */
    public function index(){
         $lawyer_id = I('id');
         $id = I('goods_id');
         $data = array('lawyer_id'=>$lawyer_id,'goods_id'=>$id);
         $dataGood=array('id'=>$id);
         $goodsDetail=get_curl_info(GOODS_DETAIL,$dataGood);
         $lawyer = get_curl_info(LAWYER_QUOTED,$data);
         $lawyer['result']['style']=ceil(floor($lawyer['result']['star']) + $lawyer['result']['star']);
         //print_r($lawyer);die;
         /*$lawyer_tag = get_curl_info(LAWYER_TAG,array('lawyer_id'=>$lawyer_id));
         print_r($lawyer_tag);die;*/
         $lawyer['result']['lawyer_id'] = $lawyer_id;
         $this->assign('goodsDetail',$goodsDetail);
         $this->assign('lawyer',$lawyer);
         $this->assign('secMenu','lawyer');
         $this->display();
    }

    /**
     * 创建订单
     */
    public function add(){
        $token = $_SESSION['enter']['result']['token'];
        $lawyer_id = I('lawyer_id');
        $goods_id = I('goods_id');
        $phone = I('phone');
        $user= I('user');
        $address = I('address');
        $data = array(
            'token'=>$token,
            'goods_id'=>$goods_id,
            'lawyer_id'=>$lawyer_id,
            'address'=>$address,
            'mobile'=>$phone,
            'through_username'=>$user
        );
        if($token!="" AND $token!=null){
            $order=get_curl_info(CREATE_ORDER,$data);
            if($order['code']==1000){
                $data['status']  = 1;
                $this->ajaxReturn($data);
                exit;
            }else{
                $data['status']  = 0;
                $data['msg'] = '下单失败!';
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