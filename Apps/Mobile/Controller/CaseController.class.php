<?php
namespace Mobile\Controller;
use Think\Controller;
class CaseController extends BaseController {
	//案例
    public function index(){
       $data2 = array('type'=>'goodsCategory');
       $nameplateList = get_curl_info(NAMEPLATE_LIST, $data2);//路牌
       $data3 = array('used'=>'1');//已开通的城市列表条件
       $cityList = get_curl_info(City_LIST,$data3);//城市列表
       $cid = I('cid','',int);
       $tid = I('tid','',int);
       //print_r($nameplateList);die;
       foreach($nameplateList['result'] as $k=>$v){
          if($v['id'] == $tid){
            $name = $v['cat_name'];
          }
       }
       foreach($cityList['result'] as $k=>$v){
          if($v['id'] == $cid){
            $city = $v['name'];
          }
       }
       $data1 = array('goodsCategoryId'=>$tid,'cityId'=>$cid);//案例列表
       $list = get_curl_info(CASE_LIST, $data1);//案例列表
       //$getOne = 
       //print_r($list);die;
       $this->assign('tid',$tid);
       $this->assign('cid',$cid);
       $this->assign('city',$city);
       $this->assign('name',$name);
       $this->assign('cityList',$cityList);//城市列表
       $this->assign('nameplateList',$nameplateList);//路牌
       $this->assign('list',$list);//案例列表
       $this->display('case');
    }  
}