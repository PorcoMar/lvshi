<?php
namespace Home\Widget;
use Think\Controller;
class WebWidget extends Controller {
    /*
    * 案例页面右侧菜单
    * 调用方式：{:W('Web/rightMenu')}
    */
    public function rightMenu(){
        $caseParam = array('page_size'=>5);
        $askParam = array('page_size'=>5);
        $caseList = get_curl_info(CASE_RELATED, $caseParam);//查询案例列表
        $askList = get_curl_info(ASK, $askParam);//查询问答列表
        $this->assign('caseList',$caseList);
        $this->assign('askList',$askList);
        $this->display('Widget:rightMenu');
    }

    /*
    *案例底部优先服务
    * 调用方式：{:W('Web/serveMenu')}
    *优选服务(二级联动及搜索)
    *  @param   [array]    $prairies       [省份列表]
    *                      $goods          [商品列表]
    * */
    public function serveMenu(){
        $prairies=array('used'=>1,'num'=>6);
        $goods=array('page_size'=>6);
        $prairies=get_curl_info(GET_PROVINCE,$prairies);
        $goods=get_curl_info(GOODS,$goods);
        $this->assign('prairies',$prairies);
        $this->assign('goods',$goods);
        $this->display('Widget:serveMenu');
    }

    /*
     *金牌律师
     * 调用方式：{:W('Web/lawyerMenu')}
     * */
    public function lawyerMenu(){
        //获取金牌律师
        $dataType = array('type'=>'gold');
        $lawyerGold =get_curl_info(LAWYER_TYPE,$dataType);
        //print_r($lawyerGold);die;
        //获取所在城市
        $data1=array('used'=>1, 'limit'=>6);
        $openCitys=get_city($data1);
        $count=count($openCitys);
        if($count>6){
            $openCitys=array($openCitys[0],$openCitys[1],$openCitys[2],$openCitys[3],$openCitys[4],$openCitys[5]);
        }else{
            $openCitys=$openCitys;
        }
        foreach($lawyerGold['result'] as $k=>$v){
            $lawyerGold['result'][$k]['style']=ceil(floor($v['star']) + $v['star']);
        }
        //获取开通的所有的省级地区
        $provinces=get_province($data1);
        $this->assign('lawyerGold',$lawyerGold);//金牌律师
        $this->assign('openCitys',$openCitys);//开通城市
        $this->assign('provinces',$provinces);//所在城市
        $this->display('Widget:lawyerMenu');
    }
    /*
     *路牌
     * 调用方式：{:W('Web/guideBoardMenu')}
     * */
    public function guideBoardMenu(){
        //获取路牌
        $sign=get_nameplate();
        $leftSign=array($sign[0],$sign[2],$sign[4],$sign[6]);//左边路牌
        $rightSign=array($sign[1],$sign[3],$sign[5],$sign[7]);//右边路牌
        $this->assign('leftSign',$leftSign);//左侧栏目
        $this->assign('rigntSign',$rightSign);//右侧栏目
        $this->display('Widget:guideBoardMenu');
    }
}