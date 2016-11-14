<?php
namespace Home\Controller;
use Think\Controller;
header("Content-type:text/html; charset=utf-8");
class CaseController extends BaseController{
    private $page_size = 10;
    public function index(){
        $this->sutraCase();
    }
    /*
     * 公用...
     * @param   [array]     $nameplate     [案例类型(路牌)]
     *                      $getcity       [城市列表]
     *                      category       [搜索框下的关键字]
* */
     function parent (){
         $getcity=array('used'=>1);//开通地区
         $nameplate = get_nameplate();
         $getcity=get_city($getcity);
         $sort=array('page_size'=>'5');
         $category = get_category($sort);
         $this->assign('category',$category);
         $this->assign('nameplate',$nameplate);
         $this->assign('getcity',$getcity);
         $this->assign('secMenu','case');
    }

    /*
     * 经典案例
     * @param   keywords  [案例关键字]
     *          pId       [案例类型ID]
     *          cId       [案例地区ID]
     *          sutracase [案例列表接口]
     *          totalPage [分页总页数]
     * */
    public function sutraCase(){
        $keywords=I('keywords');
        $goodsCategoryId=I('goodsCategoryId');
        $cId=I('cId');
        $data=array(
            'page_index'=>I('page'),
            'cityId'=>$cId,
            'goodsCategoryId'=>$goodsCategoryId,
            'keywords'=>$keywords,
            'page_size'=>$this->page_size
        );
        $this->parent();
        $sutracase = get_curl_info(CASES_LIST,$data);
        if($sutracase['code']=='999'){
            $data=array(
                'page_index'=>I('page'),
                'cityId'=>$cId,
                'goodsCategoryId'=>$goodsCategoryId,
                'title'=>$keywords,
                'page_size'=>$this->page_size
            );
            $sutracase = get_curl_info(CASES_LIST,$data);
        }
        /*案例类型标签显示*/
        $catName = '';
        $cityName = '';
        if($goodsCategoryId > 0 OR $cId > 0){
            $category=get_nameplate();
            foreach($category as $k => $v){
                if($v['id'] == $goodsCategoryId ){
                    $catName = $v['cat_name'];
                    break;
                }
            }
            $city=get_city();
            foreach($city as $k => $v){
                if($v['id'] == $cId ){
                    $cityName = $v['name'];
                    break;
                }
            }
        }
        $this->assign('cat_name',$catName);
        $this->assign('city_name',$cityName);
        $this->assign('sutracase',$sutracase);
        $this->assign('goodsCategoryId',$goodsCategoryId);
        $this->assign('keywords',$keywords);
        $this->assign('cId',$cId);

        $this->assign('params', 'keywords=' .$keywords. '&goodsCategoryId=' .$goodsCategoryId. '&cId=' .$cId);
        $this->display('sutraCase');
    }

    /*
     *优选服务(二级联动及搜索)
     * @param    openCitys  [区域列表]
     *           goods     [商品列表]
     *           pid       [省份ID]
     *          openCity   [城市接口]
     *          goods      [商品接口]
     * */
    public function getCity(){
        $pid=I('pId');
        $data=array('used'=>1,"pid"=>$pid);
        $openCitys=get_city($data);
        $province=array('province_id'=>$pid,'page_size'=>6);
        $goods=get_curl_info(GOODS,$province);
        $good=$goods['result'];
        $result=array('goods'=>$good,'openCitys'=>$openCitys);
        echo json_encode($result);
    }
    /*
     * 优先服务(城市搜索)
     * @param   cid    [城市ID]
     *          goods  [商品列表]
     * */
    public function goodsSeek(){
        $cid=I('cId');
        $goods=array('city_id'=>$cid,'page_size'=>6);
        $goods=get_curl_info(GOODS,$goods);
        echo json_encode($goods);
    }
    /*
     * 案例详情
     * @param   caseDetails     [案例详情接口]
     * */
    public function caseDetails(){
        $data=array('id'=>I('id'));
        $caseDetails=get_curl_info(CASE_DETAILS,$data);
        $this->parent();
        $this->assign('caseDetails',$caseDetails['result']);
        $this->display();
    }

}

