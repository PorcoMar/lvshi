<?php
namespace Home\Controller;
use Think\Controller;
header('content-type:text/html; charset=utf-8');
class CaseHotController extends BaseController {
    private $page_size = 10;
    private $cat = 15;
    public function index(){
        //热点热评
        $params = array(
            'cat' => $this->cat,
            'page_index' => I('get.page', 1),
            'page_size' => $this->page_size
        );
        //幻灯片
        //幻灯片
        $paramsFlag = array(
            'cat' => $this->cat,
            'page_index' =>1,
            'page_size' => 100
        );
        $caseHot=get_curl_info(CASES_LAWYER, $params);
        $caseData=get_curl_info(CASES_LAWYER, $paramsFlag);
        $caseFlag =array();
        foreach((array)$caseData['result'] as $key=>$value){
            if($value['is_commend']=='1'){
                $caseFlag[] = $value;
            }
        }
        //精编案例
        $params = array(
            'cat' => 18,
            'sub' =>1,
            'page_index' => $this->page_index,
            'page_size' => 5
        );
        $caseList=get_curl_info(CASES_LAWYER,$params);
        $this->assign('caseList',$caseList);
        $this->assign('hotList',$caseHot);
        $this->assign('hotFlag',$caseFlag);
        $this->assign('hot','select');
        $this->assign('params', 'cat=' . $this->cat);
        $this->display();
    }

    public  function detail(){
        $map=array('id'=>I('get.id'));
        $detailHot=get_curl_info(CASES_DETAIL,$map);
        $params = array(
            'cat' => 18,
            'sub' =>1,
            'page_index' => $this->page_index,
            'page_size' =>5
        );
        //评论接口
        $comMap = array('article_id'=>I('get.id'), 'page_index' => $this->page_index, 'page_size' => $this->page_size);
        $commList = get_curl_info(COMMENT_LIST,$comMap);
        if(isset($commList['result'])){
            $num = count($commList['result']);
        }else{
            $num = 0;
        }
        $caseList=get_curl_info(CASES_LAWYER,$params);
        $this->assign('hot','select');
        $this->assign('commList',$commList);
        $this->assign('num',$num);
        $this->assign('caseList',$caseList);
        $this->assign('detailList',$detailHot);
        $this->display();
    }
    //添加评论
    public function comment(){
        if(isset($_SESSION['enter'])){
            $params = array(
                'token' => $_SESSION['enter']['result']['token'],
                'article_id' => I('post.article_id'),
                'content' => I('post.comment')
            );
            $result = get_curl_info(COMMENT_DETAIL,$params);
            if ($result['code'] == '1000') {
                $data['status'] = 1;
                $this->ajaxReturn($data);
            } else {
                $data['status'] = 0;
                $data['msg'] = '发表评论失败!';
                $this->ajaxReturn($data);
            }
        }else{
            $data['status'] = 3;
            $data['msg'] = '您还未登录!';
            $this->ajaxReturn($data);
        }
    }
}