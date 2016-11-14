<?php
namespace Home\Controller;
use Think\Controller;
class PublicController extends BaseController {
    public function header(){
        $telphone = '400-0101-456';
        var_dump($telphone);
        $this->assign('telphone', $telphone);
        $this->display();
    }
     function _initialize(){
        if(count($_SESSION)>1){
            if($_SESSION['enter']){
                //$token=$_SESSION['enter']['result']['token'];
                $session=$_SESSION['enter']['result']['userinfo']['nick_name'];
                if($session==''){
                    $session=$_SESSION['enter']['result']['userinfo']['mobile'];
                    $session=substr($session,7);
                }
                //print_r($session);die;
                //$this->assign('token',$_SESSION['enter']['result']['token']);
                $this->assign('through_department_id', $_SESSION['enter']['result']['userinfo']['through_department_id']);
                $this->assign('session',$session);
            }

        }else{
            $session = '';
            $this->assign('through_department_id','');
            $this->assign('session',$session);
        }
        $cmsList=get_curl_info(CMS_LIST);
        $cmsList=$cmsList['result'];

        $count=count($cmsList);
        if($count > 6){
            $cmsList = array($cmsList[0],$cmsList[3],$cmsList[4],$cmsList[5],$cmsList[6],$cmsList[7]);
        }else{
            $cmsList = $cmsList;
        }
        $this->assign('cmsList',$cmsList);

}

   /*关于我们*/
    public function about_us(){
        $data=array(
            'code'=>'about_us'
        );
        $aboutUs = get_curl_info(CMS,$data);
        //print_r($aboutUs);die;
        $this->assign('aboutUs', $aboutUs['result']);
        $this->assign('secMenu', 'about');
        $this->display('about');
    }
    /*招贤纳士*/
    public function recruit(){
        $data=array(
            'code'=>'recruit'
        );
        $recruit = get_curl_info(CMS,$data);
        //print_r($aboutUs);die;
        $this->assign('recruit', $recruit['result']);
        $this->assign('secMenu', 'careers');
        $this->display('careers');
    }
    /*联系我们*/
    public function contact_us(){
        $this->assign('secMenu', 'contactUs');
        $this->display('contactUs');
    }

    /*
     * 我要投诉
     * */
    public function complain(){
        if(I('get.')){
            $type = I('type') - 1;
            $data=array(
                'type' => $type ,
                'content' => I('content')
                );
            if(session('userInfo')){
                $token = session('userInfo.token');   
            }else{
                $this->display('Passport/login');
                exit();
            }
            $data['token'] = $token;
            $result=get_curl_info(FEEDBACK,$data);
            if($result['code'] == 1000){
                $this->success('反馈成功');
                exit();
            }
        }
        $this->assign('secMenu', '');
        $this->display('complaint');
    }

    /*业务合作*/
    public function cooperate_entrance(){
        $data=array(
            'code'=>'cooperate_entrance'
        );
        $cooperateEntrance = get_curl_info(CMS,$data);
        $this->assign('cooperateEntrance', $cooperateEntrance['result']);
        $this->assign('secMenu', 'business');
        $this->display('business');
    }
    /*
     * 关于俺们
     * */
    public  function about_andlvshi(){
        $this->assign('secMenu','aboutTheAns');
        $this->display('aboutTheAns');
    }



    /*
* 律师会所合作入口
* */
    public function boutiqueLawFirm(){
        $this->assign('secMenu', 'homePage');
        $this->display('chamber');
    }



    /*
     * 看看俺们都是谁
     * */

    public function WhoIsTheAns(){

        $this->assign('secMenu', 'homePage');
        $this->display('whoIsTheAns');
    }


    //用户协议
    public function register(){
        $title = $content = '用户协议';
        $data = array("id"=>13);
        $re = get_curl_info(GET_PROTOCOL,$data);
        $content = $re['result']['content'];
        $this->assign('title', $title);
        $this->assign('content', $content);
        $this->assign('secMenu','');
        $this->display('register');
    }
}