<?php
namespace Mobile\Controller;
use Think\Controller;
class CaseDetailsController extends BaseController {
 //案例详情
    public function index(){
      $id = I('id','',int);
      $name = I('name','',int);
      $data = array('id'=>$id);//案例详情
      $one = get_curl_info(CASE_ONE, $data);//案例详情
      $one['result']['name'] = $name;
      $datas = array('caseId'=>$id,'page_size'=>3);//案例详情评论
      $revies = get_curl_info(REVICES_LIST, $datas);//案例详情评论
      foreach ($revies['result'] as $k => $v){
      	$time = (time()-$v['create_time'])/60;
      	$v['time'] = ceil($time);
      	$revies['result'][$k] = $v;
      	
      }
     //print_r($revies);die;
      //print_r($one);die;
      $this->assign('name',$name);
      $this->assign('id',$id);
      $this->assign('revies',$revies);
      $this->assign('one',$one);
      $this->display('case_details');
    }
    //发表案例评论
  /*public function addRevies(){
  	 print_r(I('post.'));
  }*/
  //查看更多评论
  public function revies(){
    //var_dump(I('POST.'));die;
    $pageSize = I('pageSize');
    $pageIndex = I('pageIndex')+1;
    $caseId = I('caseId');
    $data = array('caseId'=>$caseId,'page_size'=>$pageSize,'page_index'=>$pageIndex);
    $getRevies = get_curl_info(REVICES_LIST, $data);
     foreach ($getRevies['result'] as $k => $v){
        $time = (time()-$v['create_time'])/60;
        $v['time'] = ceil($time);
        $getRevies['result'][$k] = $v;
        
      }
      $page = ceil($getRevies['page_sum']/$getRevies['page_size']);//查看更多评论处理
      $getRevies['index'] = $pageIndex;
      $getRevies['page'] = $page;
     // print_r($getRevies);die;
    $json = json_encode($getRevies);
    //print_r($json);die;
    echo $json;
  }


  //点赞
  public function caseUp(){
    $id = I('id','','int');

  }
}