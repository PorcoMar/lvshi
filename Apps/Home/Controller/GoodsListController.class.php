<?php
namespace Home\Controller;
use Think\Controller;
class GoodsListController extends BaseController {
    private $page_size = 10;
    /*
     * 公用部分
     */
    public function parent(){
        //获取所在城市
        $data1=array('used'=>1, 'limit'=>6);
        $openCitys=get_city($data1);
        $count=count($openCitys);
        if($count>6){
            $openCitys=array($openCitys[0],$openCitys[1],$openCitys[2],$openCitys[3],$openCitys[4],$openCitys[5]);
        }else{
            $openCitys=$openCitys;
        }
        //获取开通的所有的省级地区
        $provinces=get_province($data1);
        //获取路牌
        $sign=get_nameplate();
        $this->assign('provinces',$provinces);//所在城市
        $this->assign('openCitys',$openCitys);//开通城市
        $this->assign('signList',$sign);//服务项目

    }
    /**
     * 分类商品列表
     */
    public function index(){

        $this->parent();
        //获取参数id
        if(isset($_GET['id'])&& !empty($_GET['id'])){
            $id = I('id');
            $_SESSION['cat_id'] = $id;//将分类id保存到session中
        }else{
            $id = $_SESSION['cat_id'];
        }

        //获取省
        if(isset($_GET['province_id'])&& !empty($_GET['province_id'])){
            $provinces_id = I('province_id');
            $_SESSION['province_id']=$provinces_id;
        }else{
            $provinces_id = $_SESSION['province_id'];
        }
        //获取市
        if(isset($_GET['city_id'])&& !empty($_GET['city_id'])){
            $city_id = I('city_id');
            $_SESSION['city_id'] = $city_id;
        }else{
            $city_id = $_SESSION['city_id'];
        }
        //获取当前页面
        if(isset($_GET['pageIndex'])&& !empty($_GET['pageIndex'])){
            $pageIndex = I('pageIndex');
        }else{
            $pageIndex = '1';
        }
        //获取对应分类的产品
        $data=array('cat_id'=>$id,'member_cat_id'=>'','page_size'=>$this->page_size,'page_index'=>$pageIndex,'type'=>'null','province_id'=>$provinces_id,'city_id'=>$city_id);
         unset($_SESSION['province_id']);
         unset($_SESSION['city_id']);
        $goods=get_curl_info(GOODS,$data);
        //获取服务律师表
        foreach($goods['result'] as $k=>$v){
            $lawyer = get_curl_info(LAWYER_LIST,array('goods_id'=>$v['id']));
            //获取律师标签
            foreach($lawyer['result'] as $key=>$value){
                $lawyer_tag = get_curl_info(LAWYER_TAG,array('lawyer_id'=>$value['id']));
                if(isset($lawyer_tag['result'])){
                    $lawyer['result'][$key]['tag'] = $lawyer_tag;
                }else{
                    $lawyer['result'][$key]['tag'] ="";
                }
            }
            if(isset($lawyer['result'])){
                $lawyerList = $lawyer['result'];
                $count_lawyer = count($lawyer['result']);
            }else{
                $lawyerList ='';
                $count_lawyer ='0';
            }
            $goods['result'][$k]['lawyer'] = $lawyerList;
            $goods['result'][$k]['total'] = $count_lawyer;
        }

        $this->assign('goods',$goods['result']);//产品数据
        $this->assign('page_sum',$goods['page_sum']);
        $this->assign('page_num',ceil(($goods['page_sum']/$goods['page_size'])+1));
        $this->assign('page_size',$goods['page_size']);
        $this->assign('page_Index',$goods['page_Index']);
        $this->assign('cat',$id);//右侧栏目
        $this->assign('secMenu','lawyer');
        $this->display();
    }
    /**
     * 商品明细
     */
    public function detail(){
        $goodId=I('id');
        //获取案例详情
        $data=array('id'=>$goodId);
        $dataLawyer=array('goods_id'=>$goodId,'sortOrder'=>2,'page_index'=>1,'page_size'=>$this->page_size);
        $goodsDetail=get_curl_info(GOODS_DETAIL,$data);
        $goodsDetail=$goodsDetail['result'];
        //商品下的律师
        $goodsLawyer =get_curl_info(LAWYER_LIST,$dataLawyer);
        //商品下的服务保障
        $goodsService =get_curl_info(GOODS_GUARANTEE,$data);

        //商品下的用户评论 接口没有
        //相关案列
        $relationGoods =get_curl_info(CASE_RELATED);
        //热门问答
        $questionList = get_curl_info(HOOT_QUESTION);
        $this->assign('goodsDetail',$goodsDetail);
        $this->assign('goodsLawyer',$goodsLawyer);
        $this->assign('pageNum',ceil(($goodsLawyer['page_sum']/$goodsLawyer['page_size'])));
        $this->assign('goodsService',$goodsService);
        $this->assign('relationGoods',$relationGoods);
        $this->assign('questionList',$questionList);
        $this->display();
    }

    /**
     * 获取所在城市
     */
    public function getCity(){
        $pid=I('pId');
        $data=array('used'=>1,'pid'=>$pid);
        $openCity=get_curl_info(GET_CITY,$data);
        $openCitys=$openCity['result'];
        $this->successJson($openCitys);
    }
    /**
     * 根据城市查询数据ajax
     */
    public function findCity(){
         $id = $_SESSION['cat_id'];
         $provinces_id = I('province');
         $city_id = I('city');
         $data=array('cat_id'=>$id,'member_cat_id'=>'','page_size'=>$this->page_size,'page_index'=>1,'type'=>'null','province_id'=>$provinces_id,'city_id'=>$city_id);
         $goods=get_curl_info(GOODS,$data);
        foreach($goods['result'] as $k=>$v){
            $lawyer = get_curl_info(LAWYER_LIST,array('goods_id'=>$v['id']));
            //获取律师标签
            foreach($lawyer['result'] as $key=>$value){
                $lawyer['result'][$key]['star'] = intval($value['star']);
                $lawyer_tag = get_curl_info(LAWYER_TAG,array('lawyer_id'=>$value['id']));
                if(isset($lawyer_tag['result'])){
                    $lawyer['result'][$key]['tag'] = $lawyer_tag;
                }else{
                    $lawyer['result'][$key]['tag'] ="";
                }
            }
            if(isset($lawyer['result'])){
                $lawyerList = $lawyer['result'];
                $count_lawyer = count($lawyer['result']);
            }else{
                $lawyerList ='';
                $count_lawyer ='0';
            }

            $goods['result'][$k]['lawyer'] = $lawyerList;
            $goods['result'][$k]['total'] = $count_lawyer;
        }
        //格式化
        foreach($goods['result'] as $k=>$v){
           $service_effect=html_entity_decode($v['service_effect']);
            if($v['sell_price']==''){
                $goods['result'][$k]['sell_price']=0;
            }else{
                $goods['result'][$k]['sell_price']=$v['sell_price'];
            }
            if($v['service_way_name']==''){
                $goods['result'][$k]['service_way_name']="";
            }else{
                $goods['result'][$k]['service_way_name']=$v['service_way_name'];
            }
            $goods['result'][$k]['service_effect']=$service_effect;
        }
       // print_r($goods);die;
        echo json_encode($goods);
    }
}