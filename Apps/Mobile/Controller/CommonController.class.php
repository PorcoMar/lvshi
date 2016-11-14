<?php
namespace Mobile\Controller;
use Think\Controller;

class CommonController extends BaseController{
    /**
     * @desc 注册协议
     */
    public function register(){
        $title = $content = '注册协议';
        $data = array("id"=>13);
        $re = get_curl_info(GET_PROTOCOL,$data);
        $content = $re['result']['content'];
        $this->assign('title', $title);
        $this->assign('content', $content);
        $this->display('register');
    }

    /**
     * @desc 认证协议
     */
    public function certify(){
        $title = $content = '认证协议';
        $data = array("id"=>11);
        $re = get_curl_info(GET_PROTOCOL,$data);
        $content = $re['result']['content'];
        $this->assign('title', $title);
        $this->assign('content', $content);
        $this->display('certify');
    }

    /**
     * @desc 合作入口
     */
    public function cooperate(){
        $title = $content = '合作入口';
        
        $this->assign('title', $title);
        $this->assign('content', $content);
        $this->display('cooperate');
    }

    /**
     * @desc 看看俺们是谁
     */
    public function look(){
        $title = $content = '看看俺们是谁';
        
        $this->assign('title', $title);
        $this->assign('content', $content);
        $this->display('look');
    }


     /**
     * @desc 支付协议
     */
    public function pay(){
        $title = $content = '支付协议';
        $data = array("id"=>10);
        $re = get_curl_info(GET_PROTOCOL,$data);
        $content = $re['result']['content'];
        $this->assign('title', $title);
        $this->assign('content', $content);
        $this->display('register');
    }

     /**
     * @desc 服务协议
     */
    public function service(){
        $title = $content = '服务协议';
        $data = array("id"=>12);
        $re = get_curl_info(GET_PROTOCOL,$data);
        $content = $re['result']['content'];
        $this->assign('title', $title);
        $this->assign('content', $content);
        $this->display('register');
    }

    /**
     * @desc 积分说明
     */
    public function creditRule(){
        $title = $content = '积分说明';
        $this->display('rule');
    }

    /**
     * @desc 律师积分说明
     */
    public function lawyerCreditRule(){
        $title = $content = '积分说明';
        $this->display('rulelawyer');
    }

    /**
     * @desc 红包说明
     */
    public function voucher(){
        $title = $content = '红包说明';
        $data = array("id"=>12);
        $re = get_curl_info(GET_PROTOCOL,$data);
        $content = $re['result']['content'];
        $this->assign('title', $title);
        $this->assign('content', $content);
        $this->display('voucher');
    }
}