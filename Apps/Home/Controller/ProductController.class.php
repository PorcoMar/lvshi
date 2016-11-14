<?php
namespace Home\Controller;
use Think\Controller;
class ProductController extends BaseController {
     private $page_size = 10;
    /*
     * 公用部分
     * @param   openCitys   [获取所在城市]
     *          provinces   [获取所在省份]
     *          sign        [获取路牌]
   */
    public function parent(){
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
        $this->assign('provinces',$provinces);
        $this->assign('openCitys',$openCitys);
        $this->assign('signList',$sign);

    }
    /*
     * 找律师
     * @param   goods           [商品列表]
     *          page_index      [当前页]
     *          pId             [省份Id]
     *          cId             [城市Id]
     *          signsId         [路牌标签]
     *          member_cat_id   [会员口分类ID]
     *          $province       [获取开通省份]
     *          $navCates       [获取会员口分类]
     *
     * */
    public function index(){
        $this->parent();
        $page_index = I('page');
        $cId=I('cid');
        $pId=I('pid');
        $signsId=I('signsId');
        $member_cat_id=I('member_cat_id');
        $data=array(
            'cat_id'=>$signsId,
            'member_cat_id'=>$member_cat_id,
            'type'=>I('type'),
            'province_id'=>$pId,
            'city_id'=>$cId,
            'page_index'=>$page_index,
            'page_size' => $this->page_size
        );
        $provinceName = '';
        if($pId>0) {
            $data1=array('used'=>1, 'limit'=>4);
            $province=get_province($data1);
            foreach($province as $k => $v){
                if($v['id'] == $pId ){
                    $provinceName = $v['name'];
                    break;
                }
            }
        }
        $navCates = get_goods_member_category();
        $goods=get_curl_info(GOODS,$data);
        if($goods['code']==1000){
            $goods= $this->call($goods);
            $this->assign('pageIndex',$goods['page_Index']);//当前页
            $this->assign('goods',$goods);//产品数据
        }else{
            $this->assign("goods",0);
        }
        $this->assign('provinceName',$provinceName);  
        $this->assign('cid',$cId);
        $this->assign('pid',$pId);
        $this->assign('signsId',$signsId);
        $this->assign('secMenu','lawyer');
        $this->assign('navCates',$navCates);
        $this->assign('member_cat_id',$member_cat_id);
        $this->assign('params','type='.I('type').'&member_cat_id='.$member_cat_id. '&cid=' .$cId. '&signsId=' .$signsId.'&pid=' .$pId);
        $this->display();
    }
    /*
     * 商品详情
     * @param   sortOrder   [ ' '按照默认排序 ,1按照价格排序 ,2按照接单量排序 , 3按照评价数排序]
     *          goodsDetail [获取问题详情]
     *          goodsLawyer [商品下律师]
     * */
    public function detail(){
        $goodId=I('id');
        $data=array('id'=>$goodId);
        $dataLawyer=array(
            'page_index'=>I('page'),
            'page_size' => 3,/*$this->page_size*/
            'goods_id'=>$goodId,
            'sortOrder'=>2
        );
        $goodsDetail=get_curl_info(GOODS_DETAIL,$data);
        $goodsDetail=$goodsDetail['result'];
        $goodsLawyer =get_curl_info(LAWYER_LIST,$dataLawyer);
        if($goodsLawyer['code'] == 1000){
            foreach($goodsLawyer['result'] as $k=>$v){
                $goodsLawyer['result'][$k]['style']=ceil(floor($v['star']) + $v['star']);
                if(!$k['tag_name']){
                  $goodsLawyer['result'][$k]['tag_name'] = 0;
                }
            }

        }
        
        //商品下的用户评论 接口没有

        $this->assign('params', 'id=' .$goodId);
        $this->assign('goodsDetail',$goodsDetail);
        $this->assign('goodsLawyer',$goodsLawyer);
        $this->assign('secMenu','lawyer');
        $this->display();
    }


    /*
     *           lawyer      [产品下的律师]
     *          lawyer_tag  [律师标签]
     *          service_effect  [格式化标签]
     *          count_lawyer    [产品下律师总数]
     * */
    public function call($goods){
        if($goods['code']==1000){
            foreach($goods['result'] as $k=>$v){
                $lawyer = get_curl_info(LAWYER_LIST,array('goods_id'=>$v['id']));
                //获取律师标签
                if($lawyer['code']==1000){
                    foreach($lawyer['result'] as $key=>$value){
                        $lawyer['result'][$key]['style']=ceil(floor($value['star']) + $value['star']);

                        /*$lawyer_tag = get_curl_info(LAWYER_TAG,array('lawyer_id'=>$value['id']));
                        if(isset($lawyer_tag['result'])){
                             $lawyer['result'][$key]['tag'] = $lawyer_tag;
                         }else{
                             $lawyer['result'][$key]['tag'] ="";
                         }*/
                        if($value['law_office_name']==''){
                            $lawyer['result'][$key]['law_office_name']="";
                        }else{
                            $lawyer['result'][$key]['law_office_name']=$value['law_office_name'];
                        }
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
                if($v['description']==''){
                    $goods['result'][$k]['description']="";
                }else{
                    $goods['result'][$k]['description']=$v['description'];
                }
                $goods['result'][$k]['service_effect']=$service_effect;
            }

        }else{
            $goods = '';
        }
        return $goods;
    }

}