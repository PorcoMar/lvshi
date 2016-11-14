<?php
namespace Mobile\Controller;
use Think\Controller;
class GoodsController extends BaseController {
    public function index(){
    	$data = array('type'=>'goodsCategory');//商品分类列表
    	$goodsCategoryList = get_curl_info(GOODSCATEGORY_LIST,$data);//商品分类列表
    	$name = I('name');
    	if($name==""){
    		$name = 1;
    	}
    	if($name==1){
    		$nameOrder = '智能排序';
    	}elseif($name==3){
			$nameOrder = '按成交量';
    	}elseif($name==5){
			$nameOrder = '按价格';
    	}elseif($name==2){
			$nameOrder = '按评价';
    	}

    	
    	$datagoods = array(
        'member_cat_id'=>I('member_cat_id'),
        'cat_id'=>I('signsId'),
        'name'=>$name
        );
        //商品列表
        
    	foreach($goodsCategoryList['result'] as $K=>$v){//默认分类显示
    			if($v['id'] == I('signsId')){
    				$category = $v['cat_name'];
    			}
    	}
/*print_r($goodsCategoryList);die;*/
        //print_r($category);die;
    	$goodsList = get_curl_info(GOODS_LIST,$datagoods);//商品列表
    	$this->assign('nameOrder',$nameOrder);
    	$this->assign('category',$category);
    	$this->assign('catId',I('signsId'));
    	$this->assign('name',$name);
    	$this->assign('goodsList',$goodsList);
    	$this->assign('goodsCategoryList',$goodsCategoryList);
        $this->display('service');
    }   
}