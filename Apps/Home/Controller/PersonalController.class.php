<?php
/**
 * Created by IntelliJ IDEA.
 * User: wuxf
 * Date: 16-5-9
 * Time: 下午11:24
 */

namespace Home\Controller;

use Think\Controller;

header("Content-type:text/html; charset=utf-8");

class PersonalController extends BaseController
{
    private $token;
    private $page_size=5;
    /**
     * PersonalController constructor.
     * @desc 访问控制,只有session有效用户可用
     */
    function __construct()
    {
        parent::__construct();
        $token = $_SESSION['enter']['result']['token'];
        if (!$token) {
            if (IS_AJAX) {
                $this->ajaxReturn(array('code' => '901', 'msg' => 'token获取失败,请先登录'));
            } else {
                $this->redirect('/Passport/index', array(), 0, '请先登录');
            }
        }
        $session=$_SESSION['enter']['result']['userinfo']['nick_name'];
        if($session==''){
            $session=$_SESSION['enter']['result']['userinfo']['mobile'];
            $session=substr($session,7);
        }
        $this->assign('secMenu','');
        $this->token = $token;

        $this->assign('lawyer', $_SESSION['enter']['result']['userinfo']['lawyer']);
        $this->assign('session', $session);
        $this->assign('secMenu','');
    }

    /**
     * @desc 个人中心
     */
    public function index()
    {
       // print_r($_SESSION);die;
        $user_info = get_curl_info(GET_USER_INFO, array('token' => $this->token));
        $user_info = $user_info['result'];
        if($user_info['nick_name']==''){
            $user_info['nick_name']=substr($user_info['mobile'],7);
        }
        if (IS_AJAX) {
            $params = I('post.val');
            $params['token'] = $this->token;
            $this->ajaxReturn(get_curl_info(MODIFY_MY_INFO, $params));
        }
        $this->assign('user', $user_info);
        $this->display();
    }

    /*
     * @desc 我的服务
     *
     *      serve   [服务列表]
     * */
    public function serve(){
        $data=array(
            'page_index' => I('get.page'),
            'page_size'=>$this->page_size,
            'token'=>$this->token
        );
        //print_r($data);die;
        $serve=get_curl_info(LAWYER_SERVICE,$data);
        //print_r($serve);die;
        foreach($serve['result'] as $k=>$v){
            if($v['service_ex_time']==0){
                $serve['result'][$k]['serviceExTime']='';
            }else{
                $serve['result'][$k]['serviceExTime']=date('Y-m-d',$v['service_ex_time']);
            }
        }
        $editor = I('get.key', 0);
        $this->assign('editor', $editor);
        $this->assign('serve',$serve);
        $this->display('serve');
    }

    /*
     *@desc 删除服务
     * */
    public function omitServe(){
        if (IS_AJAX) {
            $data=array(
                'token'=>$this->token,
                'ids'=>I('id')
            );
            $deleteService=get_curl_info(LAWYER_DELETE,$data);
            $this->ajaxReturn($deleteService);
        }
    }

    /*
    * @dase 编辑服务
    * @param   goodsDetail [商品详情]
    *
    * */
    public function redactServe(){
        $id=I('id');
        $sell_prict=I('sell_prict');
        $service_ex_time=I('service_ex_time');
        $remark=I('remark');
        $data=array('id'=>$id);
        $goodsDetail=get_curl_info(GOODS_DETAIL,$data);
        //print_r($goodsDetail);die;
        $goodsDetail=$goodsDetail['result'];
        $goodsDetail['servicingTime']=date('Y-m-d',$goodsDetail['expiration_time']);
        $this->assign('id',$id);
        $this->assign('sell_prict',$sell_prict);
        $this->assign('service_ex_time',$service_ex_time);
        $this->assign('remark',$remark);
        $this->assign('goodsDetail',$goodsDetail);
        $this->display('redactServe');
    }


    /*
     * @dase 新增服务列表
     * @param   openCitys   [城市]
     *          sign        [服务项目]
     *          goods       [商品列表]
     * */
    public function newlyServe(){
        $data=array('used'=>1, 'limit'=>6);
        $openCitys=get_city($data);
        $sign=get_nameplate();
        $signId=I('signsId');
        $city=I('city');
        $data=array(
            'cat_id'=>$signId,
            'city_id'=>$city,
            'page_index'=>I('page'),
            'page_size' => $this->page_size
        );
        $goods=get_curl_info(GOODS,$data);
        if($goods['code']!='999'){
            foreach($goods['result'] as $k=>$v){
                if($v['expiration_time']==0){
                    $goods['result'][$k]['servicingTime']='';
                }else{
                    $goods['result'][$k]['servicingTime']=date('Y-m-d',$v['expiration_time']);
                }
            }
        }
        $this->assign('goods',$goods);
        $this->assign('params','signsId='.$signId.'&city='.$city);
        $this->assign('cid',I('city'));
        $this->assign('signsId',I('signsId'));
        $this->assign('signList',$sign);
        $this->assign('openCitys',$openCitys);
        $this->display('newlyServe');
    }

    /*新增服务报价*/
    public function redactServes(){
        $data=array(
            'goodsId'=>I('id'),
            'token'=>$this->token,
            'price'=>I('quote'),
            'service_ex_time'=>strtotime(date('Y-m-d')),
        );
        $quote=get_curl_info(LAWYER_QUOTE,$data);
        if($quote && $quote['code'] == 1000){
            $data['status']  = 1;
            $this->ajaxReturn($data);
            exit;
        }elseif($quote['code'] == 1001){
            $data['status']  = 2;
            $this->ajaxReturn($data);
            exit;
        }else{
            $data['status']  = 0;
            $this->ajaxReturn($data);
            exit;
        }
    }

    /*
     * 修改报价
     * */
    public function amendQuote(){
        $data=array(
            'goodsId'=>I('id'),
            'token'=>$this->token,
            'price'=>I('quote'),
            'service_ex_time'=>I('start'),
            'remark'=>I('content'),
        );
        $quote=get_curl_info(QUOTE,$data);
        if($quote && $quote['code'] == 1000){
            $data['status']  = 1;
            $this->ajaxReturn($data);
            exit;
        }else{
            $data['status']  = 0;
            $data['msg']  = '修改报价失败';
            $this->ajaxReturn($data);
            exit;
        }
    }
    /**
     * @desc 律师认证,js验证的提示,待完善
     */
    public function lawyerVerify()
    {
        if (IS_AJAX) {
            $params = I('post.val');
            $params['token'] = $this->token;

            $params['gender'] =$_SESSION['enter']['result']['userinfo']['gender'];
            //$lawyerVerify=get_curl_info($params['type'] == -1 ? MEMBER_VERIFY : LAWYER_VERIFY, $params);
            $lawyerVerify=get_curl_info(LAWYER_VERIFY, $params);
            $this->ajaxReturn($lawyerVerify);
        }
        $nameplate = get_nameplate();
        $this->assign('nameplate', $nameplate);
        $this->display('lawyer_verify');
    }

    /**
     * @desc 经纪人认证,js验证的提示,待完善
     */
    public function brokerVerify()
    {
        if (IS_AJAX) {
            $params = I('post.val');
            $params['token'] = $this->token;
            //$params['gender'] = session('userInfo.userinfo')['gender'];
            $params['gender'] = session('userInfo.gender');
            if($params['type'] == 1){
                $this->ajaxReturn( get_curl_info(MEMBER_VERIFY,$params));
            }
           // $this->ajaxReturn(get_curl_info($params['type'] == -1 ? MEMBER_VERIFY : LAWYER_VERIFY, $params));
        }
        $nameplate = get_nameplate();
        $this->assign('nameplate', $nameplate);
        $this->display('broker_verify');
    }

    /**
     * @desc 帐户管理,已完成
     */
    public function changePwd()
    {
        if (IS_AJAX) {
            $data = I('post.data');
            $params['token'] = $this->token;
            $params['oldCode'] = md5($data['oldCode']);
            $params['newCode'] = md5($data['newCode']);
            $this->ajaxReturn(get_curl_info(CHANGE_PWD, $params));
        }
        $this->display('change_pwd');
    }

    public function verifyPhoneCode()
    {
        $this->display('verify_phone_code');
    }

    /**
     * @desc 通知,已完成待数据测试
     */
    public function notice()
    {
        $params = array(
            'to_member_id' => session('userInfo.mid'),
            'type' => 0,
            'page_Index' => I('page', 0),
            'page_size' => I('size', 10)
        );
        $msgList = get_curl_info(MY_MESSAGE, $params);
        $this->assign('msgList', $msgList);
        $this->display();
    }
    /**
     * @desc 我的合同
     */
    public function contract()
    {
        $params =array('token'=>$this->token);
        $list = get_curl_info(INSTRUMENT_LIST, $params);
        $this->display('my_contract');
    }
    /**
     * @desc 我的问题列表
     */
    public function question()
    {
        $questions = get_curl_info(ASK, array(
            'token' => $this->token,
            'is' => 1,
            'member_id' => session('userInfo.mid'),
            'page_size' => 10,
            'page_index' => I('get.page', 1)
        ));
        if(isset($questions['result'])){
            foreach ($questions['result'] as $key => $value) {
                if(count($value['answer'])){
                    $questions['result'][$key]['content'] = $value['answer'][0]['content'];
                }else{
                    $questions['result'][$key]['content'] = '';
                }
        
            }

        }
        
        //var_dump($questions);die;
        $this->assign('questions', $questions);
        $this->display('my_question');
    }

    /**
     * @desc 问题详情
     */
    public function questionDetail()
    {
        $question = get_curl_info(ASK_DETAIL, array(
            'id' => I('key', 0),
            'page_size' => 10,
            'page_index' => I('get.page', 1)
        ));
        if (!count($question['result']) || $question['code'] == 999) {
            $this->error('查看的问题不存在!');
        }
        $this->assign('params', I('key', 0));
        $this->assign('question', $question['result']);
        $this->display('my_question_detail');
    }

    /**
     * @desc 采纳回答
     */
    public function adoptQuestion()
    {
        if (IS_AJAX) {
            $params = I('post.val');
            $this->ajaxReturn(get_curl_info(ADOPT_QUESTION, $params));
        }
    }

    /**
     * @desc 我的订单 等待数据测试,接口完善
     */
    public function orders()
    {
        $data= array(
            'token' => $this->token,
            //'valid' => I('get.val'),
            'determined' => I('con'),//待确定
            'pay_status' => I('pay'),//待支付
            'wait_comment' => I('wcm'),//待评价
            'service'=>I('sev'),//待服务
            'order_type' => 2,
            'page_index' => I('get.page'),
            'page_size' => 10
        );
        $orderList = get_curl_info(MEMBER_ORDERS,$data);
        //print_r($orderList);die;
        $this->assign('step', $this->getOrderStatus());
        //分页的url参数
        $this->assign('params','con='.I('con',0).'&pay='.I('pay',0).'&sev='.I('sev',0).'&wcm='.I('wcm',0));
        $this->assign('order_list', $orderList);
        $this->display('my_orders');
    }

    /**
     * @desc 订单详情 等待数据测试
     */
    public function orderDetail()
    {
        $data=array(
            'order_id' => I('get.key', 0),
            'order_type' => I('get.type', 1)
        );
        $order_info = get_curl_info( MEMBER_ORDER_DETAIL,$data);
        $this->assign('order', $order_info['result']);
        if(I('list')=='discuss'){
            //订单评论
            $this->assign('order_id', $order_info['result']['order_id']);
            $this->assign('lawyer_id', $order_info['result']['lawyer_id']);
            $this->display('my_orders_evaluate');
        }else{
            //订单详情
            $this->display('my_orders_detail');
        }
    }

    /**
     * @desc 订单评论
     */
    public function evaluate()
    {
        $user_info = session('userInfo');
        $data=array(
            'token'=>$user_info['token'],
            'content'=>I('content'),
            'lawyer_id'=>I('lawyer_id'),
            'order_id'=>I('order_id'),
            'star'=>I('star')
        );
        $review=get_curl_info(LAWYER_REVIEW,$data);
        if($review['code']==1000){
            $data['status']  = 1;
            $data['msg']  = $review['msg'];
            $this->ajaxReturn($data);
            exit;
        }else{
            $data['status']  = 0;
            $data['msg']  = $review['msg'];
            $this->ajaxReturn($data);
            exit;
        }
    }


    /*我的接单列表*/
    public function lawyerOrders()
    {
        $data= array(
            'token' => $this->token,
            //'valid' => I('get.val'),
            'determined' => I('con'),//待确定
            'pay_status' => I('pay'),//待支付
            'wait_comment' => I('wcm'),//待评价
            'service'=>I('sev'),//待服务
            'order_type' => 2,
            'page_index' => I('get.page'),
            'page_size' => 10
        );
        $orderList = get_curl_info(LAWYER_ORDERS,$data);
        //print_r($orderList);die;
        $this->assign('step', $this->getOrderStatus());
        //分页的url参数
        $this->assign('params','con='.I('con',0).'&pay='.I('pay',0).'&sev='.I('sev',0).'&wcm='.I('wcm',0));
        $this->assign('order_list', $orderList);
        $this->display('my_lawyerOrders');
    }
    /**
     * @desc 接单详情 等待数据测试
     */
    public function lawyerDetail()
    {
        $data=array(
            'order_id' => I('get.key', 0),
            'order_type' => I('get.type', 1)
        );
        $order_info = get_curl_info( LAWYER_ORDER_DETAIL,$data);
        $this->assign('order', $order_info['result']);
        if(I('list')=='discuss'){
            //订单评论
            $this->display('my_orders_evaluate');
        }else{
            //订单详情
            $this->display('my_orders_lawyerDetail');
        }
    }

    /*
    * @desc 删除订单
    * */
    public function delete(){
        $data = array(
            'token' => $this->token,
            'order_id' => I('id'),
        );
        $delect= get_curl_info(CREATE_DELETE,$data);
        //print_r($delect);die;
        if($delect['code']==1000){
            $data['status']  = 1;
            $this->ajaxReturn($data);
            exit;
        }else{
            $data['status']  = 0;
            $this->ajaxReturn($data);
            exit;
        }
    }

    /*
     * 普通取消订单
     * */

    public function cancelOrder(){
        $data = array(
            'token' => $this->token,
            'order_id' => I('id'),
        );
        $cance= get_curl_info(MEMBER_ORDER_CANCEL,$data);
        if($cance['code']==1000){
            $data['status']  = 1;
            $this->ajaxReturn($data);
            exit;
        }
    }
    /*
     * 律师取消订单
     * */
    public function lawyerCancelOrder(){
        $data = array(
            'token' => $this->token,
            'order_id' => I('id'),
        );
        //print_r($data);die;
        $cance= get_curl_info(LAWYER_ORDER_CANCEL,$data);
        if($cance['code']==1000){
            $data['status']  = 1;
            $this->ajaxReturn($data);
            exit;
        }
    }

    /*
     * 律师确定订单
     * */
    public function awyerFirmOrder(){
        $data = array(
            'token' => $this->token,
            'order_id' => I('id'),
        );
        $firm= get_curl_info(LAWYER_ORDER_FIRM,$data);
        if($firm['code']==1000){
            $data['status']  = 1;
            $this->ajaxReturn($data);
            exit;
        }
    }

    /*
     * 律师完成服务
     * */
    public function awyerFinishOrder(){
        $data = array(
            'token' => $this->token,
            'order_id' => I('id'),
        );

        $finish= get_curl_info(LAWYER_ORDER_FINISH,$data);
        if($finish['code']==1000){
            $data['status']  = 1;
            $this->ajaxReturn($data);
            exit;
        }
    }

    /**
     * @desc 我的回答列表 MY_ANSWER_LIST
     */
    public function reply()
    {
        $data = array(
            'token' => $this->token,
            'page_size' => 5,
            'page_index' => I('get.page', 1)
        );
        $result = get_curl_info(MY_ANSWER_LIST,$data);
        //var_dump($result);die;
        $this->assign('myreply',$result);
        $this->display('my_reply');
    }

    /**
     * @desc 我的文书,
     */
    public function instrument()
    {
        $params = array(
            'token' => $this->token,
            'page_index' => I('get.page'),
            'page_size' => 10
        );
        $instruments = get_curl_info(MY_INSTRUMENTS, $params);
        $editor = I('get.key', 0);
        //print_r($instruments);die;
        $this->assign('editor', $editor);
        $this->assign('docsList', $instruments);
        $this->display();
    }

    /**
     * @desc 修改文书
     */
    public function updateInstrument()
    {
        $res = get_curl_info(INSTRUMENTS_UPDATE, I('post.val'));
        $this->ajaxReturn($res);
    }

    /**
     * @desc 删除文书
     */
    public function delInstrument()
    {
        if (IS_AJAX) {
            $params = array(
                'token' => $this->token,
                'ids' => I('post.id')
            );
           // print_r($params);die;
            $this->ajaxReturn(get_curl_info(INSTRUMENTS_DROP, $params));
        }
    }

    /**
     * @desc 生成文书
     * $word    调用方法
     * ob_flush 数据从PHP的缓冲中释放出来
     * flush    把不在缓冲中的或者说是被释放出来的数据发送到浏览器
     */
    public function download()
    {
        $word = new \Org\Util\HtmlToWord();
        $word->start();
        $word_name = trim(I('post.title')) . '.doc';
        $id = I('id');
        $data = array(
            'id' => $id,
            'type' => 2);
        get_curl_info(DOC_DOWNLOAD,$data);
        echo $_POST['content'];
        $word->save($word_name);
        header('Content-type:application/word');
        header('Content-Disposition: attachment; filename=' . $word_name . '');
        //readfile($wordname);
        ob_flush();//每次执行前刷新缓存
        flush();
    }
    /*
     * 我的文书详情
     * */
    public function docToInstrument()
    {
        $params['id'] = I('post.id');
        $res = get_curl_info(INSTRUMENTS_DETAIL, $params);
        if ($res['code'] == 1000 && !empty($res['data']['content'])) {
            $res['data']['content'] = htmlspecialchars_decode($res['data']['content']);
        } elseif ($res['code'] == 1000 && !empty($res['result']['content'])) {
            $res['result']['content'] = html_entity_decode($res['result']['content']);
            $res['result']['content'] = htmlspecialchars_decode($res['result']['content']);
            $res['data'] = $res['result'];
        }
        $this->ajaxReturn($res);
    }

    /**
     * @desc 上传
     */
    public function upload()
    {
        if (IS_AJAX) {
            $image_info = getimagesize($_FILES['file']['tmp_name']);
            $base64_image_content = "data:{$image_info['mime']};base64," . chunk_split(base64_encode(file_get_contents($_FILES['file']['tmp_name'])));
            $params = array('token' => session('userInfo.token'), 'type' => I('post.type', 0), 'base64' => $base64_image_content);
            $this->ajaxReturn(get_curl_info(UPLOAD_BASE64, $params));
        }
    }

    /**
     * @desc 获取订单状态
     */
    protected function getOrderStatus()
    {
        $params = array(
            'confirm' => I('get.con'),
            'pay_status' => I('get.pay'),
            'service_status' => I('get.sev'),
            'wait_comment' => I('get.wcm')
        );
        $step = implode('', $params);
        //print_r($step);die;
        switch ($step) {
            case '1000':
                return 1;//待确认
            case '0100':
                return 2;//待支付
            case '0010':
                return 3;//待服务
            case '0001':
                return 4;//待评价
            case '1111':
                return 5;//已完成
            default:
                return 0;//全部
        }
    }

    /*
     * @desc 我的求助池 找不到我的求助、
     *
     * @param   Help    [律师回答的求助列表]
     * */
    public function HelpPool(){
        $data=array(
            'page_index'=>I('page'),
            'page_size' => $this->page_size,
            'token'=>$this->token
        );
        $Help= get_curl_info(HELP_POOL, $data);
        $this->assign('help',$Help);
        $this->display('HelpPool');
    }

    /*
     * 我的求助(会员的求助列表)
     * */
    public function myHelpList(){
        $data=array(
            'token'=>$this->token,
            'page_index'=>I('page'),
            'page_size' => $this->page_size
        );
        $Help= get_curl_info(HELP_LIST, $data);
       // print_r($Help);die;
        $this->assign('help',$Help);
        $this->display('my_help_list');
    }

    /*
     * 更换手机页面
     * */
    public function ChangeThePhone(){
        $user_info = get_curl_info(GET_USER_INFO, array('token' => $this->token));
        $user_info = $user_info['result']['mobile'];
        $this->assign('mobile', $user_info);
        $this->display('changeThePhone');
    }
    /*
     * 确认修改手机
     * */
    public function alterMobile(){
        $rawMobile=I('rawMobile');
        //print_r($rawMobile);die;
        if($rawMobile==''){
            $data=array(
                'token'=>$this->token,
                'mobile'=>I('mobile'),
                'code'=>I('code')
            );
            //print_r($data);die;
            $enroll=get_curl_info(BINDING_MOBILE,$data);

        }else{
            echo 11;
            $data=array(
                'old_mobile'=>$rawMobile,
                'mobile'=>I('mobile'),
                'code'=>I('code')
            );
            $enroll=get_curl_info(EDIT_MOBILE,$data);
        }
        //print_r($enroll);die;
        if($enroll['code']=='1000'){
            $data['status']  = 1;
            $data['msg']=$enroll['msg'];
            $this->ajaxReturn($data);
        }else{
            $data['status']  = 0;
            $data['msg']=$enroll['msg'];
            $this->ajaxReturn($data);
        }
    }
}