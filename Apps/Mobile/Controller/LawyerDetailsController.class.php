<?php
namespace Mobile\Controller;
use Think\Controller;
class LawyerDetailsController extends BaseController{
	//律师详情
	public function index(){
		$goodsId = I('goodsId');
		$mid = I('id');//获取律师ID
		//echo $mid;
		$data = array('mid'=>$mid);//律师ID
		$lawyerDetails = get_curl_info(LAWYERDETAILS_LIST,$data);//律师详情
		//print_r($lawyerDetails);die;
		$goods = array('lawyer_id'=>$mid);
		$goodsList = get_curl_info(MYSERVICE_LIST,$goods);//服务项目
		//print_r($goodsList);
		//用户评价
		$lawyer = array('lawyer_id'=>$mid);
		$lawyerComment = get_curl_info(LAWYERVIEWS_LIST,$lawyer);
		$lawyerViewsCount = count($lawyerComment['result']);//评价数量
		foreach($lawyerComment['result'] as $k=>$v){
			$lawyerComment['result'][$k]['create'] = date("y-m-d h:i:s",$v['create_time']);
		}
		$caseList = get_curl_info(MYCASE_LIST,$data);//案例列表
		$caseCount = count($caseList['result']);
		$this->assign('caseCount',$caseCount);
		$this->assign('caseList',$caseList);
		$this->assign('goodsId',$goodsId);
		$this->assign('lawyerViewsCount',$lawyerViewsCount);
		$this->assign('lawyerComment',$lawyerComment);
		$this->assign('goodsList',$goodsList);
		$this->assign('lawyerDetails',$lawyerDetails);
		$this->display('lawyer_details');
	}
}