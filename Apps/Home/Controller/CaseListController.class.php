<?php
namespace Home\Controller;
use Think\Controller;
header('content-type:text/html; charset=utf-8');
class CaseListController extends BaseController {
    private $page_size = 10;
    private $page_index= 1;
    public function index(){
        //分类
        $typeParams = array('parent_id' => 18);
        $typeCase = get_curl_info(CMS_LIST,$typeParams);
        if(isset($_GET['catId'])){
            $catId = I('get.catId');
        }else{
            $catId = 18;
        }
        //精编案例
        $caseParams = array(
            'cat' =>$catId,
            'sub'=>I('get.sub', 1),
            'page_index' => I('get.page', 1),
            'page_size' =>$this->page_size
        );
        $caseList = get_curl_info(CASES_LAWYER,$caseParams);
        //热点热评
        $paramsHot = array('cat' => 15, 'page_index' => 1, 'page_size' =>$this->page_size);
        $caseHot=get_curl_info(CASES_LAWYER, $paramsHot);
        $name ="";
        foreach((array)$typeCase['result'] as $key=>$value){
            if($catId==$value['id']){
                $name = $value['name'];
            }
        }
        $this->assign('caseHot',$caseHot);
        $this->assign('case','select');
        $this->assign('caseType',$typeCase);
        $this->assign('caseList',$caseList);
        $this->assign('params', 'catId=' . $catId);
        $this->assign('catId',$catId);
        $this->assign('title',$name);
        $this->display();
    }

    public  function detail(){
        //分类
        $typeParams = array('parent_id' => 18);
        $typeCase = get_curl_info(CMS_LIST,$typeParams);
        $map=array('id'=>I('get.id'));
        $detailCase=get_curl_info(CASES_DETAIL,$map);
        $name="";
        if($detailCase['code']=='1000'){
            foreach((array)$typeCase['result'] as $key=>$value){
                if($detailCase['result']['category_id']==$value['id']){
                    $name = $value['name'];
                }
            }
        }
        //热点热评
        $paramsHot = array('cat' => 15, 'page_index' => 1, 'page_size' =>5);
        $caseHot=get_curl_info(CASES_LAWYER, $paramsHot);
        //评论接口
        $comMap = array('article_id'=>I('get.id'), 'page_index' => $this->page_index, 'page_size' => $this->page_size);
        $commList = get_curl_info(COMMENT_LIST,$comMap);
        if(isset($commList['result'])){
            $num = count($commList['result']);
        }else{
            $num = 0;
        }
        $this->assign('case','select');
        $this->assign('caseHot',$caseHot);
        $this->assign('title',$name);
        $this->assign('commList',$commList);
        $this->assign('num',$num);
        $this->assign('detailList',$detailCase);
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