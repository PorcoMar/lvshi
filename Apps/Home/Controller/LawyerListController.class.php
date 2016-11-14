<?php
namespace Home\Controller;
use Think\Controller;
header('content-type:text/html; charset=utf-8');
class LawyerListController extends BaseController {
    private $page_size = 10;
    private $cat = 17;
    public function index(){
        //名律名访
        $params = array(
            'cat' => $this->cat,
            'page_index' => I('get.page', 1),
            'page_size' => $this->page_size
        );
        //律师排行
        //幻灯片
        $paramsFlag = array(
            'cat' => $this->cat,
            'page_index' => 1,
            'page_size' => 100
        );
        $lawyerTop=get_curl_info(LAWYER_TOP);
        $lawyerList=get_curl_info(CASES_LAWYER,$params);
        $caseData=get_curl_info(CASES_LAWYER,$paramsFlag);
        $caseFlag =array();
        foreach((array)$caseData['result'] as $key=>$value){
            if($value['is_commend']=='1'){
                $caseFlag[] = $value;
            }
        }
        $this->assign('lawyerList',$lawyerList);
        $this->assign('lawyerFlag',$caseFlag);
        $this->assign('lawyer','select');
        $this->assign('lawyerTop',$lawyerTop);
        $this->display();
    }

    public  function detail(){
        $map=array('id'=>I('get.id'));
        $detailLawyer=get_curl_info(CASES_DETAIL,$map);
        $this->assign('lawyer','select');
        $this->assign('detailList',$detailLawyer);
        $this->display();
    }
}