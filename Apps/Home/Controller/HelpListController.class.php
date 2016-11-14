<?php
namespace Home\Controller;
use Think\Controller;
class HelpListController extends BaseController {
    private $page_size = 10;
    /*
     * 求助池首页
     * @param   openCitys   [获取所有城市]
     *          provinces   [获取所有省级]
     *          sign        [获取求助分类]
     *          $lawyer     [判断是否为律师 0(不是)1(是)]
     * */
    public function index(){
        $data=array('used'=>1, 'limit'=>6);
        $openCitys=get_city($data);
        $count=count($openCitys);
        if($count>6){
            $openCitys=array($openCitys[0],$openCitys[1],$openCitys[2],$openCitys[3],$openCitys[4],$openCitys[5]);
        }else{
            $openCitys=$openCitys;
        }
        $provinces=get_province($data);
        $sign=get_nameplate();
        $lawyer=$_SESSION['enter']['result']['userinfo']['lawyer'];
        if($lawyer!=1){
            $dataType=array(
                //'token'=>session('userInfo.token'),
                'token'=>$_SESSION['enter']['result']['token'],
                'page_size' => $this->page_size,
                'page_index'=>I('page',1)

            );
        }else{
            //求助类型（分页条件）
            $dataType=array(
                'category_id'=>I('category'),
                'province_id'=>I('pid'),
                'city_id'=>I('cid'),
                'page_size' => $this->page_size,
                'page_index'=>I('page',1)

            );
        }
        $catName = '';
        $cityName = '';
        if(I('category') > 0 OR I('cid') > 0 OR I('pid')>>0){
            $category=get_nameplate();
            foreach($category as $k => $v){
                if($v['id'] == I('category') ){
                    $catName = $v['cat_name'];
                    break;
                }
            }
            $city=get_city();
            foreach($city as $k => $v){
                if($v['id'] == I('cid') ){
                    $cityName = $v['name'];
                    break;
                }
            }
        }

        $helpList =get_curl_info(HELP_LIST,$dataType);
        //print_r($helpList);die;
        $this->assign('catName',$catName);
        $this->assign('cityName',$cityName);
        $this->assign('lawyer',$lawyer);
        $this->assign('category',I('category'));//求助类型搜索
        $this->assign('pid',I('pid'));//省份搜索
        $this->assign('cid',I('cid'));//城市搜索
        $this->assign('helpList',$helpList);//求助列表
        $this->assign('provinces',$provinces);//所在城市
        $this->assign('openCitys',$openCitys);//开通城市
        $this->assign('params', 'category=' . I('category'). '&pid=' . I('pid'). '&cid=' . I('cid'));
        $this->assign('sign',$sign);
        $this->assign('secMenu','seekHelp');
        $this->display();
    }
    /**
     * 求助明细
     */
    public function detail(){
         $id = I('id');
         $data = array('id'=>$id);
         $helpDetail =get_curl_info(HELP_DETAIL,$data);
         $helpDetail['result']['status'] = $helpDetail['result']['status']=='2'?'完成':($helpDetail['result']['status']=='1'?'审核通过':($helpDetail['result']['status']=='0'?'审核中':($helpDetail['result']['status']=='-1'?'审核未通过':'过期')));
        foreach($helpDetail['result']['lawyerList'] as $k=>$v){
            $helpDetail['result']['lawyerList'][$k]['style']=ceil(floor($v['star']) + $v['star']);
        }

        if(isset($_SESSION) && !empty($_SESSION)){
             if($_SESSION['userInfo']['userinfo']['practitioner']=='0' && $_SESSION['userInfo']['userinfo']['lawyer']=='0' && $_SESSION['userInfo']['userinfo']['broker']=='0'){
                 $helpDetail['result']['user_status'] = 0;
             }else{
                 $helpDetail['result']['user_status'] = 1;
             }
         }else{
             $helpDetail['result']['user_status'] = 0;
         }
        //print_r($helpDetail);die;

         $this->assign('helpDetail',$helpDetail);
         $this->assign('lawyerArr',$helpDetail['result']['lawyerList']);
         $this->assign('secMenu','seekHelp');
         $this->display();
    }
    /**
     * 关闭求助
     */
    public function closeHelp(){
        $id = I('id');
        $data = array('id'=>$id);
        $helpClose =get_curl_info(HELP_CLOSE,$data);
        echo json_encode($helpClose);
    }

    /*
     * 省份搜索
    * @param   openCity ['获取开通城市']
     * */
    public function getCity(){
        $pid=I('pId');
        $data=array('used'=>1,'pid'=>$pid);
        $openCity=get_city($data);
        $result=array('openCity'=>$openCity);
        echo json_encode($result);
    }

}