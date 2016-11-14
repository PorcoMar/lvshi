<?php
namespace Home\Controller;
use Think\Controller;
header('content-type:text/html; charset=utf-8');
class DownloadController extends BaseController {
    private $page_index = 1;
    private $page_size = 50;

    //意见反馈
    public function a011(){
        if (isMobileBrower()) {
            //$this->redirect('http://m.andlvshi.com/index.php/app/login/?from=a011');
            header("location: http://m.andlvshi.com/index.php/app/login/?from=a011");
            return;
        }
        $this->display();
    }
}