<?php
namespace Home\Controller;
use Think\Controller;
header("Content-type:text/html; charset=utf-8");
class ConsultController extends BaseController{
	private $page_size = 4;
    public function index(){
    	 $params = array(
            'title' => I('get.key'),
            'page_index' => I('get.page', 1),
            'page_size' => $this->page_size
        );
        //获取所有问题
        $key = I('key');
        $this->assign('key',$key);
    	$answer = get_curl_info(ASK,$params);
        $this->assign('params', 'key=' .I('key'));
        $this->assign('answerList',$answer);
        $this->assign('secMenu','refer');
        $this->display();
    }


    //我要提问
    public function ask(){ 
        //获取用户token
        //print_r($_SESSION['enter']['result']['token']);die;
        $token= $_SESSION['enter']['result']['token'];
        if(I('description')) {
            $description=I('description');
            $catId=I('catId');
            $data=array(
                'token' => $token,
                'description' => $description,
                'cat_id' => $catId
            );
            $skip=get_curl_info(COMMIT,$data);
            $question= $skip['result'];
            $questionId = $question['id'];
            //提问问题成功后跳转到这个问题的详情页面
            if($skip && $skip['code'] == 1000){
                $data['status']  = 1;
                $data['msg'] = $questionId;
                $this->ajaxReturn($data);
                exit;
            }else{
                $data['status']  = 0;
                $data['msg'] = '发送失败!';
                $this->ajaxReturn($data);
                exit;
            }

        }
        //获取问题类型
        $nameplate = get_nameplate();
        $this->assign('secMenu','refer');
        $this->assign('nameplate',$nameplate);
        $this->display();
    }


    //问题详情
    public function askdetail(){
        $id = I('id');
        $data = array('id'=>$id);
        $askDetail = get_curl_info(ASK_DETAIL,$data);
        //var_dump($askDetail['result']);die;
        $askDetails = $askDetail['result'];
        $answers= $askDetail['result']['askAnswer'];
        //echo $askDetails['answers'];
        if($askDetails['answers']>0){
            foreach ($answers as $k => $v) {
                if(!empty($v['comment'])){
                    //给是否追问赋值  1代表该问题被追问 2代表问题没有被追问
                    $answers[$k]['status'] =  1;
                    $num = count($v['comment']);
                    $answers[$k]['num'] =  $num;
                    
                }else{
                    $answers[$k]['status'] =  2;
                    $answers[$k]['num'] =  0;

                }
            }
        } 
        $replys = array();
        foreach ($answers as $ks => $vs) {
                $replys[] = $vs['member_id'];    
            }   
        $otheranswer =array();
        $adopt = '';
        if($askDetails['adopt']){
            $askDetails['answers']=$askDetails['answers']-1; 
            foreach ($answers as $key => $value) {
                if($value['adopt']==1){
                    $adopt = $value;
                }else{
                    $otheranswer[] = $value;
                }
                
            }
        }
        $userinfo = session('userInfo');
        $userInfo = $userinfo['userinfo'];
        $userId = $userInfo['mid'];
        if(in_array($userId, $replys)){
            $state = 1;
            $this->assign('state',$state);
        }
        //style等于1时可以回答问题  等于2是普通用户
        if($userInfo['lawyer']>0 || $userInfo['practitioner']>0 || $userInfo['broker']>0){
            $style = 1;
        }else{
            $style = 2;
        }
        $this->assign('adopt',$adopt);
        $this->assign('style',$style);
        $this->assign('userId',$userId);
        $this->assign('answers',$answers);
        $this->assign('otheranswers',$otheranswer);
        $this->assign('askDetail',$askDetails);
        $this->assign('secMenu','refer');
        $this->display();
    }


    //回答问题
    public function  answer(){
        $token= session('userInfo.token');
        $token=$_SESSION['enter']['result']['token'];
        $Id=I('id');
        $content=I('content');
        $data=array(
            'token' => $token,
            'content' => $content,
            'question_id' => $Id
        );
        $enter=get_curl_info(ANSWER,$data);
        if($enter && $enter['code'] == 1000){
            $data['status']  = 1;
            $this->ajaxReturn($data);
            exit;
        }else{
            $data['status']  = 0;
            $data['msg'] = '发送失败!';
            $this->ajaxReturn($data);
            exit;
        }
    } 

    //采纳问题
    public function adoptProblem(){
        $id = I('id');
        $data=array('id'=>$id);
        $enter=get_curl_info(ADOPT,$data);
        if($enter && $enter['code'] == 1000){
            $data['status']  = 1;
            $this->ajaxReturn($data);
            exit;
        }else{
            $data['status']  = 0;
            $data['msg'] = '采纳失败!';
            $this->ajaxReturn($data);
            exit;
        }
    }


    // 追问问题
    public function asked(){
        $answerId = I('answerId');
        $content = I('content') ;
        $token= session('userInfo.token');
        $token=$_SESSION['enter']['result']['token'];
        $time = time();
        $data=array(
            'token' => $token,
            'content' => $content,
            'answer_id' => $answerId ,
            'create_time' => $time
        );
        $enter=get_curl_info(ASKED,$data);
        if($enter && $enter['code'] == 1000){
            $data['status']  = 1;
            $this->ajaxReturn($data);
            exit;
        }else{
            $data['status']  = 0;
            $data['msg'] = '发送失败!';
            $this->ajaxReturn($data);
            exit;
        }

    }



    //追答问题
        public function reply(){
            $answerId = I('answerId');
            $content = I('content') ;
            $token= session('userInfo.token');
            $token=$_SESSION['enter']['result']['token'];
            $time = time();
            $data=array(
                'token' => $token,
                'content' => $content,
                'answer_id' => $answerId ,
                'create_time' => $time
            );
            $enter=get_curl_info(ASKED,$data);
            if($enter && $enter['code'] == 1000){
                $data['status']  = 1;
                $this->ajaxReturn($data);
                exit;
            }else{
                $data['status']  = 0;
                $data['msg'] = '发送失败!';
                $this->ajaxReturn($data);
                exit;
            }

        }

}