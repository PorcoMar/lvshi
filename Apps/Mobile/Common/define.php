<?php
/**
 * 一些常用的函数
 * 命名方式 多个单词用规划用"_"链接
 * 
 */
//

define('BASE_URL', 'http://api.andlvshi.com/index.php/home/');
//案例列表
define('CASE_LIST', BASE_URL.'CaseCategory/casePagngSeek');
//路牌
define('NAMEPLATE_LIST', BASE_URL.'Category/nameplate');
//城市列表
define('City_LIST', BASE_URL.'common/getCityList');
//案例详情
define('CASE_ONE',BASE_URL.'CaseCategory/caseDetail');
//案例评论
define('REVICES_LIST', BASE_URL.'CaseCategory/caseDetails');

//商品分类列表
define('GOODSCATEGORY_LIST',BASE_URL.'Category/goodsCategoryList');
//商品列表
define('GOODS_LIST', BASE_URL.'Category/goodsList');
//商品详情
define('GOODSDETAILS', BASE_URL.'Category/goodsDetails');

//律师列表
define('LAWYER_LIST', BASE_URL.'Category/lawyerList');
//律师详情
define('LAWYERDETAILS_LIST',BASE_URL.'Lawyer/getLawyerProfile');
//我的服务
define('MYSERVICE_LIST',BASE_URL.'Category/myService');
//律师评价
define('LAWYERVIEWS_LIST',BASE_URL.'Lawyer/getLawyerComment');
//我的案例
define('MYCASE_LIST',BASE_URL.'caseCategory/lawyerCase');
//点赞

define('TEMPLATE_LIST', BASE_URL . 'Doc/DocDemolist');//文书模板列表
define('TEMPLATE_DETAIL', BASE_URL . 'Doc/detail');//文书模板详情
define('DOC_DOWNLOAD', BASE_URL . 'Doc/downloadDoc');//文书下载数目增1
define('TEMPLATE_TO_INSTRUMENTS', BASE_URL . 'Doc/getMyDocByDemoId');//模板转化为文书
define('MY_DOC_LIST', BASE_URL . 'Doc/MyInstruments');//我的文书列表
define('MY_DOC_DETAIL', BASE_URL . 'Doc/myDetail');//文书详情
define('MY_DOC_UPDATE', BASE_URL . 'Doc/myUpdate');//修改文书
define("GET_PROTOCOL",BASE_URL .'Cms/contentCategory');//从CMS端获得协议
define('REGISTER', BASE_URL . 'passport/register');//注册
define('SENDSMS', BASE_URL . 'passport/sendSms');//获取短信验证码