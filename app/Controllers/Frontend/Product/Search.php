<?php
namespace App\Controllers\Frontend\Product;
use App\Controllers\FrontendController;

class Search extends FrontendController{

    protected $data;

    public function __construct(){
        $this->data = [];
    }

    public function index(){

        $currencyJson = isset($_COOKIE[AUTH_CURRENCY]) ? $_COOKIE[AUTH_CURRENCY] : null;

        $currency = json_decode(urldecode($currencyJson), true);
        $rate = (float) $currency['rate'];
        

        $query = '';
        $param['catalogueid'] = $this->request->getGet('catalogueid');
        $param['area_from'] = $this->request->getGet('area_from');
        $param['type'] = $this->request->getGet('type');
        $param['area_to'] = $this->request->getGet('area_to');
        $param['keyword'] = $this->request->getGet('escape_str');
        $param['bed'] = $this->request->getGet('bed');
        $param['attr'] = $this->request->getGet('attr');
        $param['price_from'] = (int)$this->request->getGet('price_from');
        $param['price_to'] = (int)$this->request->getGet('price_to');
        $param['productid'] = $this->request->getGet('productid');
        $param['keyword'] = $this->request->getGet('keyword');
        if(isset($param['attr']) && is_array($param['attr']) && count($param['attr'])){
            foreach ($param['attr'] as $key => $value) {
                if($value == 0){
                    unset($param['attr'][$key]);
                }
            }

            $param['attr'] = array_values($param['attr']);
        }

        if ($rate > 0) {
            if ($param['price_from'] > 0) {
                $param['price_from'] = (int) ($param['price_from'] / $rate);
            }

            if ($param['price_to'] > 0) {
                $param['price_to'] = (int) ($param['price_to'] / $rate);
            }
        }


        if($param['type'] == 2){
            $query = $query.'(`tb3`.`attributeid` = 27 OR `tb3`.`attributeid` = 28)';
        }
        if($param['type'] == 1){
            $query = $query.'(`tb3`.`attributeid` = 29 OR `tb3`.`attributeid` = 30)';
        }

        if($param['type'] == 0){
            $query = $query.'(`tb3`.`attributeid` = 27 OR `tb3`.`attributeid` = 28 OR `tb3`.`attributeid` = 29 OR `tb3`.`attributeid` = 30)';
        }


        if(isset($param['attr']) && is_array($param['attr']) && count($param['attr'])){
            $dem = 0;
            $query = ($query== '' ? '': $query.' AND ');
            $dem_count = 4;
            foreach ($param['attr'] as $key => $value) {
                $query = $query.'`tb'.$dem_count.'`.`attributeid` = '.$value;
                if(count($param['attr']) != $dem +1  ){
                    $query = $query.'` AND ';
                }
                $dem++;
                $dem_count++;
            }
        }

        if(isset($param['bed']) && !empty($param['bed'])){
            $param['bed'] = (int)$param['bed'];
            $query = ($query== '' ? '': $query.' AND ').'`tb1`.`bed` = '.$param['bed'];
        }

        if(isset($param['catalogueid']) && !empty($param['catalogueid'])){
            $param['catalogueid'] = (int)$param['catalogueid'];
            $query = ($query== '' ? '': $query.' AND ').'  `tb1`.`catalogueid` = '.$param['catalogueid'];
        }
        if(isset($param['area_from']) && $param['area_from'] > 0 && $param['area_to'] && $param['area_to'] > 0 && $param['area_from'] < $param['area_to']){
            $query = ($query== '' ? '': $query.' AND ').'  tb1.area >= '.$param['area_from'].' AND tb1.area <= '.$param['area_to'].' ';
        }
        if(isset($param['price_from']) && $param['price_from'] > 0 && $param['price_to'] && $param['price_to'] > 0 && $param['price_from'] < $param['price_to']){
            $query = ($query== '' ? '': $query.' AND ').'  tb1.price >= '.$param['price_from'].' AND tb1.price <= '.$param['price_to'].' ';
        }


        if(isset($param['productid']) &&  $param['productid'] != ''){
            $query = ($query== '' ? '': $query.' AND ').'  `tb1`.`productid` = "'.$param['productid'].'"';
        }
        if(isset($param['keyword']) &&  $param['keyword'] != ''){
            $query = ($query== '' ? '': $query.' AND ').'  (tb2.title LIKE \'%'.$param['keyword'].'%\')';
        }

        $product = $this->AutoloadModel->_get_where([
            'select' => 'tb1.id, tb2.title, tb2.canonical, tb1.status, tb2.description, tb1.image, tb1.price, tb1.extra, tb1.catalogueid, tb1.area, tb1.bed, tb1.status',
            'table' => 'product as tb1',
            'join' => [
                [
                    'product_translate as tb2','tb1.id = tb2.objectid','inner'
                ],
                [
                    'attribute_relationship as tb3', 'tb1.id = tb3.objectid AND tb3.module = "product"', 'left'
                ],
                [
                    'attribute_relationship as tb4', 'tb1.id = tb4.objectid AND tb4.module = "product"', 'left'
                ],
                [
                    'attribute_relationship as tb5', 'tb1.id = tb5.objectid AND tb5.module = "product"', 'left'
                ]
            ],
            'query' => $query,
            'where' => [
                'tb1.publish' => 1,
                'tb1.deleted_at' => 0,
                'tb2.module' => 'product',
                'tb2.language' => $this->currentLanguage(),
            ],
            'order_by' => 'order desc, id desc',
            'group_by' => 'tb1.id'
        ], TRUE);

        // pre($product);


        $extra_id = [];
        if(isset($product) && is_array($product) && count($product)){
            foreach($product as $key =>$val){
                // $product[$key]['status'] = json_decode($val['status'], true);

                if($val['extra'] != ''){
                    $product[$key]['extra'] = json_decode($val['extra'], true);
                }
                if(isset($product[$key]['extra']) && is_array($product[$key]['extra']) && count($product[$key]['extra'])){
                    foreach($product[$key]['extra'] as $keyChild =>$valChild){
                        $extra_id[] = $valChild;
                    }
                }
            }
        }

        $this->data['extra_list'] = $this->AutoloadModel->_get_where([
            'select' => 'tb1.id, tb2.title, tb2.canonical, tb2.description,  tb1.image,  ',

            'table' => 'product_tag as tb1',
            'where_in' => $extra_id,
            'where_in_field' => 'tb1.id',
            'join' =>  [
                    [
                        'product_translate as tb2','tb1.id = tb2.objectid AND tb2.module = "product_tag" AND tb2.language = \''.$this->currentLanguage().'\' ','inner'
                    ]
                ],
            'where' => ['tb1.deleted_at' => 0]
        ],true);
        $this->data['meta_title'] = lang('app.SEARCH_PROPERTY');
        $this->data['general'] = $this->general;
        $this->data['meta_image'] =  $this->general['homepage_logo'];
        $this->data['product'] = $product;
        $this->data['template'] = 'frontend/product/search/index';
        return view('frontend/homepage/layout/home', $this->data);
    }

}
