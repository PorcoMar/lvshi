<?php
/**
 * 一些常用的函数
 * 命名方式 多个单词用规划用"_"链接
 *
 */
define('BASE_URL', 'http://api.andlvshi.com/');
define('SENDSMS', BASE_URL . 'passport/sendSms');//获取短信验证码
define('LOGIN', BASE_URL . 'passport/login');//登入
define('LOGOUT', BASE_URL . 'passport/logout');//退出登入
define('REGISTER', BASE_URL . 'passport/register');//注册
define('FORGET', BASE_URL . 'passport/forgetPass');//找回密码
define('CHANGE_PWD', BASE_URL . 'passport/editPass');//修改密码
define('GET_USER_INFO', BASE_URL . 'passport/getTokenUserInfo');//获取用户信息
define('MODIFY_MY_INFO', BASE_URL . 'passport/completions');//补全信息
define('UPLOAD', BASE_URL . 'Upload/upload');//上传类

define('UPLOAD_BASE64', BASE_URL . 'Upload/base64Upload');//base64上传
define('HELP_LIST', BASE_URL . 'Help/helpList');//获取求助列表
define('HELP_POOL', BASE_URL . 'Help/lawyerHelpList');//我的求助池
define('CASES_LIST', BASE_URL . 'caseCategory/casePagngSeek');//获取案例列表
define('SIGNS', BASE_URL . 'Category/nameplate');//获取路牌标志
define('GET_CITY', BASE_URL . 'Common/getCityList');//获取城市
define('GET_PROVINCE', BASE_URL . 'Common/getProvinceList');//获取所有省份
define('GOODS_CATE', BASE_URL . 'Category/goodsCategoryList');//获取商品分类
define('NAMEPLATE', BASE_URL . 'Category/nameplate');//路牌
define('GETCITY', BASE_URL . 'Common/getCityList');//城市列表
define('PRAIRIES', BASE_URL . 'Common/getProvinceList');//省份列表
define('DISTRICT', BASE_URL . 'Common/getDistrictList');//区域列表
define('CASE_PAGNGSEEK', BASE_URL . 'CaseCategory/casePagngSeek');//案例分页
define('CASE_DETAILS', BASE_URL . 'caseCategory/caseDetail');//案例详情
define('MATTER', BASE_URL . 'AskCategory/questionList');//问题
define('ASK', BASE_URL . 'AskCategory/questionList');//问题
define('ASK_DETAIL', BASE_URL . 'AskCategory/question');//问题详情
define('GOODS', BASE_URL . 'Category/goodsList');//商品列表
define('COMMIT',BASE_URL.'AskCategory/askQuestion');//提交问题
define('ANSWER',BASE_URL.'AskCategory/askAnswer'); //回答问题
define('ADOPT_QUESTION', BASE_URL . 'AskCategory/adoptQuestion');//采纳回答
define('MY_ANSWER_LIST', BASE_URL . 'AskCategory/lawyerAnswerList');//我的回答列表
define('GOODS_TOP', BASE_URL . 'Category/goodTop');//问题
define('ADOPT',BASE_URL . 'AskCategory/adoptQuestion');//采纳问题
define('ASKED',BASE_URL .'AskCategory/questions');//追问问题

define('CASE_RELATED', BASE_URL . 'CaseCategory/correlation');//相关案例
define('TEMPLATE_LIST', BASE_URL . 'Doc/DocDemolist');//文书模板列表
define('TEMPLATE_DETAIL', BASE_URL . 'Doc/detail');//文书模板详情
define('TEMPLATE_TO_INSTRUMENTS', BASE_URL . 'Doc/getMyDocByDemoId');//模板转化为文书
define('MY_INSTRUMENTS', BASE_URL . 'Doc/MyInstruments');//我的文书列表
define('DOWNLOAD_THE_TOTAL_NUMBER', BASE_URL . 'Doc/documentDownload');//下载总次数
define('INSTRUMENTS_DETAIL', BASE_URL . 'Doc/myDetail');//文书详情
define('INSTRUMENTS_UPDATE', BASE_URL . 'Doc/myUpdate');//修改文书
define('INSTRUMENTS_DROP', BASE_URL . 'Doc/myDelete');//删除文书
define('DOC_DOWNLOAD',BASE_URL . 'Doc/downloadDoc');//下载文书后数目加一
define('LAWYER_LIST', BASE_URL . 'Category/lawyerList');//获取对应律师列表
define('MEMBER_VERIFY', BASE_URL . 'Passport/memberVerify');//实名认证
define('LAWYER_VERIFY', BASE_URL . 'Lawyer/lawyerVerify');//律师认证
define('MY_MESSAGE', BASE_URL . 'NotifyMessage/myMessage');//消息列表

define('LAWYER_ORDERS', BASE_URL . 'OrderLawyer/orderList');//律师的订单
define('LAWYER_REVIEW', BASE_URL . 'Lawyer/lawyerComment');//评价律师
define('MEMBER_ORDERS', BASE_URL . 'OrderMember/orderList');//普通用户的订单
define('LAWYER_ORDER_DETAIL', BASE_URL . 'OrderLawyer/orderDetail');//律师订单详细信息
define('MEMBER_ORDER_DETAIL', BASE_URL . 'OrderMember/orderDetail');//普通用户订单详情
define('MEMBER_ORDER_CANCEL', BASE_URL . 'OrderMember/cancelOrder');//普通用户取消详情
define('LAWYER_ORDER_CANCEL', BASE_URL . 'OrderLawyer/cancelOrder');//律师取消详情
define('LAWYER_ORDER_FIRM', BASE_URL . 'OrderMember/lawyerFirmOrder');//律师确定订单
define('LAWYER_ORDER_FINISH', BASE_URL . 'OrderLawyer/finishOrder');//律师完成服务

define('LAWYER_MSG', BASE_URL . 'Lawyer/getLawyerProfile');//获取对应律师详细资料
define ('CREATE_DELETE',BASE_URL.'OrderMember/delOrder');//删除订单
define('LAWYER_CASE', BASE_URL . 'caseCategory/lawyerCase');//我的案例(律师)
define('LAWYER_SERVICE', BASE_URL . 'Category/myService');//我的服务(律师)
define('LAWYER_COMMENT', BASE_URL . 'Lawyer/getLawyerComment');//我的用户评价(律师)
define('LAWYER_ANSWER', BASE_URL . 'AskCategory/lawyerAnswerList');//我的回答(律师)
define('GOODS_DETAIL', BASE_URL . 'Category/goodsDetails');//商品详情页
define('LAWYER_TAG', BASE_URL . 'Lawyer/getLawyerTag');//律师标签
define('LAWYER_QUOTE', BASE_URL . 'Category/lawyerQuotedPrice');//商品报价
define('QUOTE', BASE_URL . 'Category/goodsModify');//修改商品报价
define('LAWYER_DELETE', BASE_URL . 'Category/deleteGoods');//律师删除服务
define('LAWYER_TYPE', BASE_URL . 'Lawyer/personalLawyer');//获取公益/金牌/贴身律师
define('GOODS_GUARANTEE', BASE_URL . 'Category/ServiceGuarantee');//商品下的服务保障
define('HELP', BASE_URL . 'Help/help');//我的求助
define('HELP_DETAIL', BASE_URL . 'Help/helpDetails');//求助明细
define('HELP_CLOSE', BASE_URL . 'Help/closeHelp');//关闭求助
define('CREATE_ORDER', BASE_URL . 'OrderMember/createOrder');//创建订单
define('LAWYER_DIRECT', BASE_URL . 'DirectNumber/throughRelation');//我的直达号
define('LAWYER_GROUP', BASE_URL . 'DirectNumber/throughGroup');//我的直达号.
define('CMS', BASE_URL . 'Cms/contentCategory');//CMS
define('CMS_LIST', BASE_URL . 'Cms/categoryList');//CMS分类列表
define("GET_PROTOCOL",BASE_URL .'Cms/contentCategory');//从CMS端获得协议
define ('FEEDBACK',BASE_URL.'complaints/feedback');//我要投诉
define ('TRIPLICITIES',BASE_URL.'Tipartite/trilateral');//第三方登入
define ('EDIT_MOBILE',BASE_URL.'passport/editMobile');//修改绑定的手机号
define ('BINDING_MOBILE',BASE_URL.'Passport/bindingMobile');//绑定新手机号
define ('LAWYER_QUOTED',BASE_URL.'Category/getLawyerQuoted');//获取律师报价及律师资料
define ('CASES_LAWYER',BASE_URL.'Cms/articleList');//文章列表
define ('CONTENT_DETAIL',BASE_URL.'Cms/contentCategory');//文章列表
define ('CASES_DETAIL',BASE_URL.'Cms/articleDetails');//文章明细
define ('LAWYER_TEST',BASE_URL.'Lawyer/lawyerList');//律师列表
define ('LAWYER_TOP',BASE_URL.'AskCategory/rankingLawyerList');//律师排行
define ('LAWYER_DETAIL',BASE_URL.'Lawyer/getLawyerProfile');//律师详情
define ('CASE_LIST',BASE_URL.'caseCategory/casePagngSeek');//案例列表
define ('CASE_DETAIL',BASE_URL.'caseCategory/caseDetail');//案例详情
define ('COMMENT_LIST',BASE_URL.'Cms/commentList');//评论列表
define ('COMMENT_DETAIL',BASE_URL.'Cms/comment');//评论
define ('AGREEBACK',BASE_URL.'complaints/feedback');//反馈意见
define ('INSTRUMENT_LIST',BASE_URL.'Doc/MyInstruments');//我的文书
define ('AUTO_LIST',BASE_URL.'Doc/DocDemolist');//自动文书