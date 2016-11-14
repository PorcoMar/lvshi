<?php
namespace Mobile\Controller;
use Think\Controller;
class LawyerController extends BaseController {
    public function index(){
        if(I('orderId')>0 && I('orderId')<4){
            //var_dump(I('get.'));die;
            $order = I('orderId');
            $this->assign('orderId',$order);
            $page = I('page') + 1 ;
            $data = array(
            'goods_id'=>I('goodsId'),
            'sortOrder'=>$order,
            'page_index'=>$page);
             //var_dump($data);die;
            $list = get_curl_info(LAWYER_LIST,$data);//律师列表
        }else{
            $page = I('page') + 1 ;
            $data = array(
            'goods_id'=>I('goodsId'),
            'page_index'=>$page);//律师列表
             $this->assign('orderId',4);
            $list = get_curl_info(LAWYER_LIST,$data);//律师列表
        }
    	
    	//print_r($list);die;
        $pageSum = $list['page_sum'];
        $pageSize = $list['page_size'];
        $pageCount = ceil($pageSum / $pageSize);
        //echo $pageCount;die;
        $this->assign('pageCount',$pageCount);
    	$this->assign('goodsId',I('goodsId'));
    	$this->assign('list',$list);
        $this->display('lawyer');
    }    
    //排序处理
    public function order(){
    	$order = I('post.orderId','');
    	$data = array('goods_id'=>I('goodsId'),'sortOrder'=>$order);//律师列表
    	$list = get_curl_info(LAWYER_LIST,$data);//律师列表
    	$json = json_encode($list);
    	echo $json;
    }
}