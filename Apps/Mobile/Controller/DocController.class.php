<?php
/**
 * Created by IntelliJ IDEA.
 * User: wuxf
 * Date: 16-5-9
 * Time: 下午20:03
 */

namespace Mobile\Controller;

use Think\Controller;

header("Content-type:text/html; charset=utf-8");

class DocController extends BaseController
{
    private $token = '';

    /**
     * @desc 文书模板列表
     */
    public function index()
    {
        $catId = I('get.catId', 0, 'int');
        $type = I('get.type', 0, 'int');
        $page = I('page');
        if($type == 1){
                $page = $page + 1;
        }
        if($type==2){
                $page = $page - 1;
        }    
        $params = array(
            'title' => I('get.key'),
            'goods_cat_id' => $catId,
            'page_index' => $page,
            'page_size' => C('PAGE_SIZE')
        );
        $categoryName = '';
        $list = get_curl_info(TEMPLATE_LIST, $params);
        //var_dump($list);die;
        $page = $list['page_Index'];
        if($list['code'] == 999){
            $pageCount = 1 ;
        }else{
            $pageCount = ceil($list['page_sum']/$list['page_size']);
        }
       
        $list = isset($list['result']) ? $list['result'] : array();
        $category = get_nameplate();
        if ($catId > 0 && $category) {
            foreach ($category as $key => $value) {
                if ($value['id'] == $catId) {
                    $categoryName = $value['cat_name'];
                }
            }
        }
//        $from = I('from');
//        $token = I('token');
        $this->assign('pageCount',$pageCount);
        $this->assign('page',$page);
//        $this->assign('token', $token);
//        $this->assign('from',$from);
        $this->assign('catId',$catId);
        $this->assign('category', $category);
        $this->assign('categoryName', $categoryName);
        $this->assign('docsList', $list);
        $this->assign('type', I('get.type'));
        $this->assign('key', I('get.key'));
        $this->assign('params', 'type=' . I('get.type') . '&key=' . I('get.key'));
        $this->display();
    }

    /**
     * @desc 文书模板详情
     */
    public function demoDetail()
    {
        $params = array(
            'id' => I('get.id', 0)
        );
        $token = I('token');
        $result = get_curl_info(TEMPLATE_DETAIL, $params);
        // var_dump(TEMPLATE_DETAIL);die;
        if (empty($result) || $result['code'] == 999) {
            $this->error('查看的模板不存在!');
        }
        //$this->assign('token', $this->token);
        $from = I('from');
        $this->assign('from',$from);
        $this->assign('token', $token);
        $this->assign('data', $result['result']);
        $this->display();
    }

    /**
     * @desc 编辑文书,需要token
     */
    public function editDetail()
    {
        $token=I('token');
        $id=I('get.id');
        $params['id'] = $id;
        $params['token'] = $token;
        $res = get_curl_info(TEMPLATE_TO_INSTRUMENTS, $params);
        if ($res['code'] == 1000 && !empty($res['data']['content'])) {
            $res['data']['content'] = html_entity_decode($res['data']['content']);
            $res['data']['content'] = htmlspecialchars_decode($res['data']['content']);
        } elseif ($res['code'] == 1000 && !empty($res['result']['content'])) {
            $res['result']['content'] = html_entity_decode($res['result']['content']);
            $res['result']['content'] = htmlspecialchars_decode($res['result']['content']);
            $res['data'] = $res['result'];
        } elseif ($res['code'] != 1000) {
            $this->error('请先登录');
        }
        $from = I('from');
        $this->assign('from',$from);
        $this->assign('token',$token);
        $this->assign('id',$id);
        $this->assign('data', $res['data']);
        $this->display('editDetail');
    }

    /**
     * @desc 下载文书
     */
    public function download()
    {
        $word = new \Org\Util\HtmlToWord();
        $word->start();
        $word_name = trim(I('post.title')) . '.doc';
        echo $_POST['content'];
        //echo $_POST['id'];die;
        $id =I('id');
        $data =  array(
            'id' =>$id,
            'type' => 2
            );
        //下载后文书的下载数目自己加一
        get_curl_info(DOC_DOWNLOAD,$data);
        //var_dump($re);die;
        $word->save($word_name);
        header('Content-type:application/word');
        header('Content-Disposition: attachment; filename=' . $word_name . '');
        //readfile($wordname);
        ob_flush();//每次执行前刷新缓存
        flush();
    }

    /**
     * @desc 我的文书详情
     */
    public function myDetail()
    {
        $params['id'] = I('get.id');
        $result = get_curl_info(MY_DOC_DETAIL, $params);
        if (empty($result) || $result['code'] == 999) {
            $this->error('查看的文书不存在!');
        }
        $from = I('from');
        $token = I('token');
        $this->assign('from',$from);
        $this->assign('token', $token);
        $this->assign('data', $result['result']);
        $this->display();
    }
    public function updateDetail(){
        $params['id'] = I('get.id');
        $res = get_curl_info(MY_DOC_DETAIL, $params);
        if ($res['code'] == 1000 && !empty($res['data']['content'])) {
            $res['data']['content'] = html_entity_decode($res['data']['content']);
            $res['data']['content'] = htmlspecialchars_decode($res['data']['content']);
        } elseif ($res['code'] == 1000 && !empty($res['result']['content'])) {
            $res['result']['content'] = html_entity_decode($res['result']['content']);
            $res['result']['content'] = htmlspecialchars_decode($res['result']['content']);
            $res['data'] = $res['result'];
        } elseif ($res['code'] != 1000) {
            $this->error('请先登录');
        }
        $token = I('token');
        $from = I('from');
        $this->assign('token',$token);
        $this->assign('from',$from);
        $this->assign('data', $res['result']);
        $this->display('updateDetail');
    }
    /**
     * @desc 修改文书
     */
    public function updateInstrument()
    {

        $res = get_curl_info(MY_DOC_UPDATE, I('post.val'));
        $this->ajaxReturn($res);
    }

    /**
     * @desc 我的文书,需要token
     */
    public function myDoc()
    {
        $type = I('get.type', 0, 'int');
        $page = I('page');
        if($type == 1){
                $page = $page + 1;
        }
        if($type==2){
                $page = $page - 1;
        }    
        $params = array(
            'token' => I('token'),
            'page_index' =>$page,
            'page_size' => C('PAGE_SIZE')
            
        );
        $instruments = get_curl_info(MY_DOC_LIST, $params);
        $page = $instruments['page_Index'];
        if($instruments['code'] == 999){
            $pageCount = 1;
        }else{
            $pageCount = ceil($instruments['page_sum']/$instruments['page_size']);
        }
        $from = I('from');
        $token = I('token');
        $this->assign('page',$page);
        $this->assign('pageCount',$pageCount);
        $this->assign('token',$token);
        $this->assign('from',$from);
        $this->assign('docsList', $instruments);
        $this->display();
    }


    //将模板转化为我的文书
    function write(){
        $data['id'] = I('id');
        $data['token'] = I('token');
        $from = I('from');
        $res = get_curl_info(TEMPLATE_TO_INSTRUMENTS, $data);
        if($res['code'] != 1000){
            $this->error('请先登录');
        }else{
           $this->redirect('Doc/myDoc', array('token' => I('token'),'from' => $from));
        }
    }

}