<?php
namespace App\Controller;
use Think\Controller;
class GuideController extends Controller {
    public function index(){
        $fromName = array('a001','a002');
        $from=I('from');
        $ios=C('IOS_URL');
        $url=C('APP_URL')."andlvshi.apk";
        $dn=C('APP_URL')."andlvshi$from.apk";
        if(!remote_file_exists($dn)){            
            $dn=$url;
        }      
        
        $this->assign('dn',$dn);
        $this->assign('url',$url);
        $this->assign('ios',$ios);
        $this->assign('from',$from);
        $this->display();
    }
    public function guide(){
        $from=I('from');
        $this->assign('from',$from);
        $this->display();
    }
}