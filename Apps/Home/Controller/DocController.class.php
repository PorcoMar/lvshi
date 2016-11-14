<?php
/**
 * Created by IntelliJ IDEA.
 * User: wuxf
 * Date: 16-5-9
 * Time: 下午20:03
 */

namespace Home\Controller;

use Think\Controller;

header("Content-type:text/html; charset=utf-8");

class DocController extends BaseController
{

    private $doc_page_size = 10;

    /**
     * @desc 文书模板列表
     */
    public function index()
    {
        $params = array(
            'title' => I('get.key'),
            'goods_cat_id' => I('get.type'),
            'page_index' => I('get.page', 1),
            'page_size' => $this->doc_page_size
        );
        $list = get_curl_info(TEMPLATE_LIST, $params);
        //print_r($list);die;
        $data=array('type'=>1);
        $down = get_curl_info(DOWNLOAD_THE_TOTAL_NUMBER,$data);
        $down = str_split($down['result']['sum']);
        //print_r($down);die;
        $nameplate=get_nameplate();
        $this->assign('down',$down);
        $this->assign('secMenu','writ');
        $this->assign('nameplate',$nameplate);
        $this->assign('docsList', $list);
        $this->assign('type', I('get.type'));
        $this->assign('key', I('get.key'));
        $this->assign('params', 'type=' . I('get.type') . '&key=' . I('get.key'));
        $this->display();
    }

    /**
     * @desc 文书模板详情
     */
    public function detail()
    {
        $params = array(
            'id' => I('get.id', 0)
        );
        $result = get_curl_info(TEMPLATE_DETAIL, $params);
        if (!count($result) || $result['code'] == 999) {
            $this->error('查看的模板不存在!');
        }
        if ($result['result']['dowland_num'] == '') {
            $result['result']['dowland_num'] = 0;
        } else {
            $result['result']['dowland_num'] = $result['result']['dowland_num'];
        }
        // print_r($result);die;
        $this->assign('secMenu','writ');
        $this->assign('detail_info', $result['result']);
        $this->display();
    }

    /**
     * @desc 模板转化成文书
     * htmlspecialchars_decode  把预定义的HTML实体转换为字符
     * 提示：此处可以少一个if判断但HTML接收数据要更改
     */
    public function docToInstrument()
    {

        $params['id'] = I('post.id');
        //$params['token'] = session('userInfo.token');
        $params['token'] = $_SESSION['enter']['result']['token'];
        $res = get_curl_info(TEMPLATE_TO_INSTRUMENTS, $params);
        if ($res['code'] == 1000 && !empty($res['data']['content'])) {
            $res['data']['content'] = htmlspecialchars_decode($res['data']['content']);
        } elseif ($res['code'] == 1000 && !empty($res['result']['content'])) {
            $res['result']['content'] = html_entity_decode($res['result']['content']);
            $res['result']['content'] = htmlspecialchars_decode($res['result']['content']);
            $res['data'] = $res['result'];
        }
        $this->ajaxReturn($res);
    }

}