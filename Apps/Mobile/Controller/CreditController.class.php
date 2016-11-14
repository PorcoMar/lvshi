<?php

namespace Mobile\Controller;
use Think\Controller;

class CreditController extends BaseController
{
    private $token = '';

    /**
     * @desc 
     */
    public function index(){

        $this->display();
    }

    /**
     * @desc 积分规则
     */
    public function rule(){

        $this->display();
    }

    
}