<?php
namespace Home\Controller;
use Think\Controller;
class PayController extends BaseController {
	public function _initialize()
    {
        
        
    }
    public function index(){
        if(IS_POST || IS_GET){
            $orderId = I('id', '');
            if(empty($orderId)){
                exit('参数错误');
            }
            $order = $this->_getOrderById($orderId);
            $order=$order['result'];            
            //生成微信二维码
            $wxurl = $this->_wxpay($order);

            $this->assign('url', $wxurl);
            $this->assign('data', $order);
        }
        $this->display();
    }

    public function dopay(){
        if(!IS_POST){
            exit('');
        }
        $payWay = I('post.payway', null);
        $orderId = I('post.orderId', null);
        $order = $this->_getOrderById($orderId);
        $order=$order['result'];
        if($payWay == 'alipay'){
            $this->_alipay($order);
        }elseif($payWay == 'wxpay'){
            $this->_wxpay($order);
        }
    }

    /**
     * @description 微信支付
     * 
     */
    public function _alipay($order){
        vendor('Alipay.Corefunction');
        // vendor('Alipay.Function');
        vendor('Alipay.Notify');        
        vendor('Alipay.RSA');
        vendor('Alipay.Submit');
        $alipayConfig = C('alipay_config');
        $parameter = array(
            "service"       => $alipayConfig['service'],
            "partner"       => $alipayConfig['partner'],
            "seller_id"  => $alipayConfig['seller_id'],
            "payment_type"  => $alipayConfig['payment_type'],
            "notify_url"    => $alipayConfig['notify_url'],
            "return_url"    => $alipayConfig['return_url'],
            
            "anti_phishing_key"=>$alipayConfig['anti_phishing_key'],
            "exter_invoke_ip"=>$alipayConfig['exter_invoke_ip'],
            "out_trade_no"  => $order['order_id'],
            "subject"   => $order['goods_list'][0]['goods_name'],
            "total_fee" => $order['payable_amount'],
            "body"  => '',
            "_input_charset"    => trim(strtolower($alipayConfig['input_charset']))
            //其他业务参数根据在线开发文档，添加参数.文档地址:https://doc.open.alipay.com/doc2/detail.htm?spm=a219a.7629140.0.0.kiX33I&treeId=62&articleId=103740&docType=1
            //如"参数名"=>"参数值"
        );
        // exit('1');
        //建立请求
        $alipaySubmit = new \AlipaySubmit($alipayConfig);
        $html_text = $alipaySubmit->buildRequestForm($parameter,"get", "确认");
        echo $html_text;
        // $this->display();
    }

    /**
     * @description 微信支付
     * 
     */
    public function _wxpay($order)
    {
        //引入WxPay
        vendor('Wxpay.Api');
        vendor('Wxpay.Data');
        //使用统一支付接口
       ini_set('date.timezone','Asia/Shanghai');
        //模式二
        /**
         * 流程：
         * 1、调用统一下单，取得code_url，生成二维码
         * 2、用户扫描二维码，进行支付
         * 3、支付完成之后，微信服务器会通知支付成功
         * 4、在支付成功通知中需要查单确认是否真正支付成功（见：notify.php）
         */
        $fee=$order['payable_amount']*100;
        $trade_no=I('order_no');
        $goodsId=I('goods_id');
        $ip = get_client_ip(1);
        //echo $fee;die;
        $input = new \WxPayUnifiedOrder();
        $input->SetBody($order['goods_list'][0]['goods_name']);//商品描述
        $input->SetAttach('');//附加数据
        $input->SetOut_trade_no($order['order_id']);
        $input->SetTotal_fee($fee);//商品金额
        $input->SetTime_start(date("YmdHis"));
        $input->SetTime_expire(date("YmdHis", time() + 600));
        $input->SetGoods_tag("test");//商品标记
        $input->SetNotify_url(C('WX_NOTIFY_URL'));
        $input->SetTrade_type("NATIVE");
        $input->SetProduct_id($order['goods_list'][0]['id']);//商品ID

        $input->SetSpbill_create_ip($ip);
        //var_dump($input);die;
        $result = \WxPayApi::unifiedOrder($input);
        //$result = $notify->GetPayUrl($input);
        $url = $result["code_url"];
        // $this->assign('url',$url);
        // $this->display();
        return $url;
    }

    /**
     *
     *
     */
    public function _getOrderById($id){
        //查询订单
        $data['order_id'] = $id;
        $data['token'] = $token = session('userInfo.token');
        $order = get_curl_info(MEMBER_ORDER_DETAIL,$data);
        if($order['code'] != 1000){
            exit('订单不存在');
        }
        return $order;
    }

}