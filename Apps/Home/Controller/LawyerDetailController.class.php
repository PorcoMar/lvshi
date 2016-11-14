<?php
namespace Home\Controller;
use Think\Controller;
class LawyerDetailController extends BaseController {

    public function index(){

        //获取律师详细信息
        $mid = I("member_id");
        $data=array('mid'=>$mid);
        $data1=array('lawyer_id'=>$mid);
        $data2=array('member_id'=>$mid);
        $lawyer = get_curl_info(LAWYER_MSG,$data);
        //print_r($lawyer);die;
        $lawyer=$lawyer['result'];
        $lawyer['style']=ceil(floor($lawyer['star']) + $lawyer['star']);


        //获取律师案例
        $lawyerCase =  get_curl_info(LAWYER_CASE,$data);
        if($lawyerCase['code']==1000){
            $caseNum = count($lawyerCase['result']);
        }else{
            $caseNum = 0 ;
            $lawyerCase = 0 ;
        }
       
        //获取律师服务
        $lawyerService = get_curl_info(LAWYER_SERVICE,$data1);
        if($lawyerService['code'] == 1000){
             $serverNum = count($lawyerService['result']);
         }else{
            $serverNum = 0 ;
            $lawyerService = 0;
         }

        //获取律师回答
        $lawyerAnswer = get_curl_info(LAWYER_ANSWER,$data2);
        if($lawyerAnswer['code'] == 1000){
            $answerNum = count($lawyerAnswer['result']);
        }else{
            $answerNum = 0;
            $lawyerAnswer = 0;
        }

        //var_dump($lawyerAnswer);die;
        //获取用户评价
        $lawyerComment = get_curl_info(LAWYER_COMMENT,$data1);
        if($lawyerComment['code']==1000){
            foreach($lawyerComment['result'] as $k=>$v){
                $lawyerComment['result'][$k]['create_time'] = date('Y-m-d',time($v['create_time']));
            } 
            $commentNum = count($lawyerComment['result']);
        }else{
            $commentNum = 0;
             $lawyerComment = 0;
        }
        
        //经典案例
        $goodTop = get_curl_info(GOODS_TOP); 
        $this->assign('lawyer',$lawyer);
        $this->assign('lawyerCase',$lawyerCase);
        $this->assign('caseNum',$caseNum);
        $this->assign('lawyerService',$lawyerService);
        $this->assign('serverNum',$serverNum );
        $this->assign('lawyerAnswer',$lawyerAnswer );
        $this->assign('answerNum',$answerNum );
        $this->assign('lawyerComment',$lawyerComment);
        $this->assign('commentNum',$commentNum );
        $this->assign('goodTop ',$goodTop);
        $this->assign('secMenu', 'lawyer');
        $this->display();
    }
}