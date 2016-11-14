<?php
/**
 * 一些常用的函数
 * 命名方式 多个单词用规划用"_"链接
 *
 */
define('BASE_URL', 'http://p.biyunapp.com/andlvshi/api/index.php/home/');
define('SENDSMS', BASE_URL . 'passport/sendSms');//获取短信验证码
define('LOGIN', BASE_URL . 'passport/login');//登入
define('CHARACTER', BASE_URL . 'Member/character');//选择角色