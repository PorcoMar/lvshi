<?php
namespace Home\Controller;
use Think\Controller;
header('content-type:text/html; charset=utf-8');
class IndexController extends BaseController {
    private $page_index = 1;
    private $page_size = 50;
    public function index(){
        //律政新闻
        $lawyer=array('cat'=>'14','page_index'=>$this->page_index,'page_size'=>$this->page_size);
        $caseLawyer=get_curl_info(CASES_LAWYER,$lawyer);
       //获取热门的文章
        $caseLawyerHot = array();
        foreach((array)$caseLawyer['result'] as $key=>$value){
              if($value['is_hot']=='1'){
                  $caseLawyerHot[] = $value;
              }
        }
        //热点热评
        $hot=array('cat'=>'15','page_index'=>$this->page_index,'page_size'=>$this->page_size);
        $caseHot=get_curl_info(CASES_LAWYER,$hot);
       //获取热门的文章
        $caseHotHot =array();
        foreach((array)$caseHot['result'] as $key=>$value){
            if($value['is_hot']=='1'){
                $caseHotHot[] = $value;
            }
        }
        //活动公告
        $active=array('cat'=>'16','page_index'=>$this->page_index,'page_size'=>$this->page_size);
        $caseActive=get_curl_info(CASES_LAWYER,$active);
       //获取热门的文章
        $caseActiveHot = array();
        foreach((array)$caseActive['result'] as $key=>$value){
            if($value['is_hot']=='1'){
                $caseActiveHot[] = $value;
            }
        }
        //名律名访
        $params=array('cat'=>'17','page_index'=>$this->page_index,'page_size'=>30);
        $lawyerList=get_curl_info(CASES_LAWYER,$params);
        //律师排行
        $lawyerTop=get_curl_info(LAWYER_TOP);
        //精编案例
        $paramsCase = array('cat'=>'18','sub'=>1,'page_index' => $this->page_index, 'page_size' => 6);
        $caseList=get_curl_info(CASES_LAWYER,$paramsCase);
        $this->assign('lawyerTop',$lawyerTop);
        $this->assign('lawyerList',$lawyerList);
        $this->assign('caseLawyerHot',$caseLawyerHot);
        $this->assign('index','select');
        $this->assign('caseHotHot',$caseHotHot);
        $this->assign('caseActiveHot',$caseActiveHot);
        $this->assign('caseList',$caseList);
        $this->display();
    }

    //意见反馈
    public function agree(){
        $this->assign('index','select');
        $this->assign('code','agree');
        $this->display();
    }
    //关于我们
    public function content(){
        $params = array('code'=>I('get.code'));
        $contentDetail = get_curl_info(CONTENT_DETAIL,$params);
        $this->assign('contentDetail',$contentDetail);
        $this->assign('code',I('get.code'));
        $this->assign('index','select');
        $this->display();
    }
    //关于我们
    public function lawyer(){
        $params = array('code'=>I('get.code'));
        $contentDetail = get_curl_info(CONTENT_DETAIL,$params);
        $this->assign('contentDetail',$contentDetail);
        $this->assign('index','select');
        $this->display();
    }
    //关于我们
    public function other(){
        $params = array('code'=>I('get.code'));
        $contentDetail = get_curl_info(CONTENT_DETAIL,$params);
        $this->assign('contentDetail',$contentDetail);
        $this->assign('code',I('get.code'));
        $this->assign('index','select');
        $this->display();
    }
    //意见反馈
    public function send(){
        if(isset($_SESSION['enter'])){
            $params = array(
                'token' => $_SESSION['enter']['result']['token'],
                'mobile' => I('post.mobile'),
                'type' => 1,
                'content' => I('post.content')
            );
            $result = get_curl_info(AGREEBACK,$params);
            if ($result['code'] == '1000') {
                $data['status'] = 1;
                $data['msg'] = '您信息提交成功，我们会及时为您解答！';
                $this->ajaxReturn($data);
            } else {
                $data['status'] = 0;
                $data['msg'] = '提交反馈信息失败，请重新提交';
                $this->ajaxReturn($data);
            }
        }else{
            $data['status'] = 2;
            $data['msg'] = '您还未登录!';
            $this->ajaxReturn($data);
        }
    }
    //意见反馈
    public function app(){
        $this->display();
    }
}