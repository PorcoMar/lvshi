<?php

return array(
	//支付宝配置参数
	'alipay_config'=>array(
		//合作身份者ID，签约账号，以2088开头由16位纯数字组成的字符串，查看地址：https://b.alipay.com/order/pidAndKey.htm
		'partner' =>'2088221455960778',   //这里是你在成功申请支付宝接口后获取到的PID；
		//收款支付宝账号，以2088开头由16位纯数字组成的字符串，一般情况下收款账号就是签约账号
		// 'seller_user_id'	=> 'andlvshi@gmw.cn',
		'seller_id' => '2088221455960778',//'andlvshi@gmw.cn',
		//商户的私钥,此处填写原始私钥，RSA公私钥生成：https://doc.open.alipay.com/doc2/detail.htm?spm=a219a.7629140.0.0.nBDxfy&treeId=58&articleId=103242&docType=1
		'private_key_path'	=> VENDOR_PATH.DIRECTORY_SEPARATOR.'Alipay'.DIRECTORY_SEPARATOR.'key'.DIRECTORY_SEPARATOR.'rsa_private_key.pem', 
		//支付宝的公钥，查看地址：https://b.alipay.com/order/pidAndKey.htm
		'ali_public_key_path' => VENDOR_PATH.DIRECTORY_SEPARATOR.'Alipay'.DIRECTORY_SEPARATOR.'key'.DIRECTORY_SEPARATOR.'rsa_public_key.pem',

//		 'key'=>'MIGfMA0GCSqGSIb3DQEBAQUAA4GNADCBiQKBgQDDI6d306Q8fIfCOaTXyiUeJHkrIvYISRcc73s3vF1ZT7XN8RNPwJxo8pWaJMmvyTn9N4HQ632qJBVHf8sxHi/fEsraprwCtzvzQETrNRwVxLO5jVmRGi60j8Ue1efIlzPXV9je9mkjzOmdssymZkh2QhUrCmZYI/FCEa3/cNMW0QIDAQAB',//'8066iwfyofXXXXXXXXXXXXXXXXXX',//这里是你在成功申请支付宝接口后获取到的Key
		//签名方式
		'sign_type'   => strtoupper('RSA'), 
		//字符编码格式 目前支持 gbk 或 utf-8
		'input_charset'=> strtolower('utf-8'), 
			
		// 退款日期 时间格式 yyyy-MM-dd HH:mm:ss
		//date_default_timezone_set('PRC');//设置当前系统服务器时间为北京时间，PHP5.1以上可使用。
		// $alipay_config['refund_date']=date("Y-m-d H:i:s",time()),
		
		//ca证书路径地址，用于curl中ssl校验
		//请保证cacert.pem文件在当前文件夹目录中
		'cacert'    => VENDOR_PATH.DIRECTORY_SEPARATOR.'Alipay'.DIRECTORY_SEPARATOR.'key'.DIRECTORY_SEPARATOR.'cacert.pem',
		
		//访问模式,根据自己的服务器是否支持ssl访问，若支持请选择https；若不支持请选择http
		'transport'    => 'http', 
		
		// 支付类型 ，无需修改
		'payment_type' => "1", 
		
		// 产品类型，无需修改
		'service' => "create_direct_pay_by_user", 
		//↓↓↓↓↓↓↓↓↓↓ 请在这里配置防钓鱼信息，如果没开通防钓鱼功能，为空即可 ↓↓↓↓↓↓↓↓↓↓↓↓↓↓↓
		
		// 防钓鱼时间戳  若要使用请调用类文件submit中的query_timestamp函数
		'anti_phishing_key' => "",
		
		// 客户端的IP地址 非局域网的外网IP地址，如：221.0.0.1
		'exter_invoke_ip' => "",
		//		//这里是异步通知页面url，提交到项目的Pay控制器的notifyurl方法；
		'notify_url'=>'http://www.biyunapp.com/andlvshi/api/payback/notifyurl',
//
//		//这里是异步通知页面url,退款通知
		'refund_notify_url'=>'http://www.biyunapp.com/andlvshi/api/pay/notifyl',
	
	),

//	'alipay'   =>array(
//		//这里是卖家的支付宝账号，也就是你申请接口时注册的支付宝账号
//		'seller_email'=>'andlvshi@gmw.cn',
//
//		//这里是异步通知页面url，提交到项目的Pay控制器的notifyurl方法；
//		'notify_url'=>'http://www.biyunapp.com/andlvshi/api/payback/notifyurl',
//
//		//这里是异步通知页面url,退款通知
//		'refund_notify_url'=>'http://www.biyunapp.com/andlvshi/api/pay/notifyl',
//
//		//这里是页面跳转通知url，提交到项目的Pay控制器的returnurl方法；
//		'return_url'=>'http://www.biyunapp.com/andlvshi/api/Pay/returnurl',
//
//		//支付成功跳转到的页面，我这里跳转到项目的User控制器，myorder方法，并传参payed（已支付列表）
//		// 'successpage'=>'User/myorder?ordtype=payed',
//
//		//支付失败跳转到的页面，我这里跳转到项目的User控制器，myorder方法，并传参unpay（未支付列表）
//		// 'errorpage'=>'User/myorder?ordtype=unpay',
//	),
);