<?php
namespace Mobile\Controller;
use Think\Controller;
class GoodsDetailsController extends BaseController {
//商品详情
	public function index(){
		$data = array('id'=>I('id'));//商品详情
		$one = get_curl_info(GOODSDETAILS,$data);//商品详情
		$lawyer = array('goods_id'=>I('id'));//律师列表
		$lawyerList = get_curl_info(LAWYER_LIST,$lawyer);//律师列表
		$this->assign('lawyerList',$lawyerList);//律师列表
		//print_r($lawyerList);die;
		$this->assign('goodsId',I('id'));
		$this->assign('one',$one);//商品详情
		$this->display('service_details');
	}
}