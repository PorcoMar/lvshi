<?php
return array(
	//'配置项'=>'配置值'
	'DEFAULT_THEME' => 'default',//使用的模板
	/*'SHOW_PAGE_TRACE' => true, //显示页面trace信息*/
	'LOAD_EXT_FILE' => 'define',
	'Sms_ValidDate' => '600',//短信有效期。单位秒
	'Token_ValidDate' => '72000',//token有效期。单位秒
	'CACHE_SHORT_TIME' => '60', //短时缓存时间，单位秒
	'CACHE_LOGIN_TIME' => '8640000',//一天 //长时缓存时间，单位秒
	'URL_HTML_SUFFIX' => '',
	'appKey'	=> 'ik1qhw091mcwp',
	'appSecret'		=>	'NUHPGnZlJksYUH',
	'MAP_AK'	=>	'3ly2EIQhvHuD5LUyZbY2qiNc',//百度地图AK
	'LOAD_EXT_CONFIG' => 'alipay',
	'WEB_URL'	=>	'http://www.andlvshi.com',//域名
	'PAGE_SIZE' => 10, //每页默认显示条数
	'method' 					=> 'POST',
	'url' 						=> 'http://msg.umeng.com/api/send',
	'ios_app_key'				=> '5704baec67e58e68710003301111',
	'ios_app_master_secret' 	=> 'c6e0154035545104a306b05ebf70ae61',
	'and_app_key' 				=> '570c9d33e0f55a571e000029',
	'and_app_master_secret' 	=> '4r6rlbfqsnuugi7b9vrqq9lrmyqnizh4',
	//微信
	'App_id'					=>'wxffee9ed6dc5ef9e6',
	'AppSecret'					=>'b7ed8e9d4104a82ddc8782510167c7af',
	'WX_NOTIFY_URL'             => 'http://www.andlvshi.com/index.php/Index/trilateral',
	//qq登入
	'QQ_AUTH'                 	=> array(
		'APP_ID'         		=> '101332342', //你的QQ互联APPID
		'APP_KEY'   			=> '17b102a16aaf6ee66f9923e5b061b0a5',
		'SCOPE'          		=> 'get_user_info,get_repost_list,add_idol,add_t,del_t,add_pic_t,del_idol',
		'CALLBACK'       		=> 'http://www.web.com/index.php/Tripartite/callback/type/qq',
	),
	//微博登入
	'SINA_AUTH'                 => array(
		'APP_ID'         		=> '225538476',
		'APP_KEY'   			=> '14c55d4656374cc1e392e20558b8c987',
		'CALLBACK'       		=> 'http://www.web.com/index.php/Tripartite/callback/type/sina',
	),


);