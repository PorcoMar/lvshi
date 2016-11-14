<?php
namespace Home\Controller;
use Think\Controller;
header('content-type:text/html; charset=utf-8');
class AutoCaseController extends BaseController {
    private $page_index = 1;
    public function index(){
        //自动合同分类
        $typeCase = get_curl_info(GOODS_CATE);
        //精编案例
        $params = array('cat' => 18, 'sub' =>1, 'page_index' => $this->page_index, 'page_size' => 5);
        $caseList=get_curl_info(CASES_LAWYER,$params);
        //自动文书
        foreach((array)$typeCase['result']['categoryList'] as $key=>$value){
            $autoParams = array('page_index'=>$this->page_index,'page_size'=>10,'goods_cat_id'=>$value['id']);
            $autoList[$key]=get_curl_info(AUTO_LIST,$autoParams);
        }
        $this->assign('caseType',$typeCase);
        $this->assign('autoList',$autoList);
        $this->assign('caseList',$caseList);
        $this->display();
    }
    public  function goods(){
        //自动合同分类
        $typeCase = get_curl_info(GOODS_CATE);
        if(isset($_GET['goods_cat_id'])){
            $catId = I('get.goods_cat_id');
        }
        $name ="";
        foreach((array)$typeCase['result']['categoryList'] as $key=>$value){
            if($catId==$value['id']){
                $name = $value['cat_name'];
            }
        }
        //精编案例
        $params = array('cat' => 18, 'sub' =>1, 'page_index' => $this->page_index, 'page_size' => 5);
        $caseList=get_curl_info(CASES_LAWYER,$params);
        $autoParams = array('page_index'=>I('get.page', 1),'page_size'=>10,'goods_cat_id'=>$catId);
        $autoList=get_curl_info(AUTO_LIST,$autoParams);
        $this->assign('params', 'goods_cat_id='.$catId);
        $this->assign('goods_cat_id',$catId);
        $this->assign('caseType',$typeCase);
        $this->assign('name',$name);
        $this->assign('caseList',$caseList);
        $this->assign('autoList',$autoList);
        $this->display();
    }
    public  function detail(){
        $typeCase = get_curl_info(GOODS_CATE);
        if(isset($_GET['goods_cat_id'])){
            $catId = I('get.goods_cat_id');
        }
        $name ="";
        foreach((array)$typeCase['result']['categoryList'] as $key=>$value){
            if($catId==$value['id']){
                $name = $value['cat_name'];
            }
        }
        $autoParams = array('id' => I('get.id'));
        $detailList=get_curl_info(TEMPLATE_DETAIL,$autoParams);
        //精编案例
        $params = array('cat' => 18, 'sub' =>1, 'page_index' => $this->page_index, 'page_size' => 5);
        $caseList=get_curl_info(CASES_LAWYER,$params);
        $this->assign('caseList',$caseList);
        $this->assign('goods_cat_id',$catId);
        $this->assign('caseType',$typeCase);
        $this->assign('name',$name);
        $this->assign('detailList',$detailList);
        $this->display();
    }
}