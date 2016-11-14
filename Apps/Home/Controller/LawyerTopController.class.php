<?php
namespace Home\Controller;
use Think\Controller;
header('content-type:text/html; charset=utf-8');
class LawyerTopController extends BaseController {
    private $page_size = 10;
    private $cat = 17;
    public function index(){
         //律师排行
        $paramsTop = array(
            'page_index' => I('get.page',1),
            'page_size' => $this->page_size
        );
        $lawyerTop=get_curl_info(LAWYER_TOP,$paramsTop);
        //名律名访
        $params = array(
            'cat' => $this->cat,
            'page_index' => 1,
            'page_size' => $this->page_size
        );
        $lawyerList=get_curl_info(CASES_LAWYER, $params);
        $this->assign('size',I('get.page', 1));
        $this->assign('lawyerList',$lawyerList);
        $this->assign('top','select');
        $this->assign('lawyerTop',$lawyerTop);
        $this->display();
    }

    public  function detail(){
        $map=array('id'=>I('get.id'));
        $detailActive=get_curl_info(CASES_DETAIL,$map);
        if($detailActive['code']=='1000'){
            $detail = $detailActive['result'];
        }else{
            $detail = array();
        }
        $this->assign('top','select');
        $this->assign('detailList',$detail);
        $this->display();
    }
}