<?php
namespace App\Controllers\Frontend\Search;
use App\Controllers\FrontendController;

class Search extends FrontendController{

    protected $data;

    public function __construct(){
        $this->data = [];
        $this->data['module'] = 'product';
    }

    private function rent_catalogue(){
        $rent = $this->AutoloadModel->_get_where([
            'select' => 'tb1.id, tb2.title, tb1.lft, tb1.rgt',
            'table' => 'product_catalogue as tb1',
            'join' => [
                ['product_translate as tb2','tb2.objectid = tb1.id','inner']
            ],
            'where' => [
                'tb1.publish' => 1,
                'tb1.deleted_at' => 0,
                'tb2.language' => $this->currentLanguage(),
                'tb2.module' => $this->data['module'].'_catalogue',
                'tb1.id' => $this->rent_id(),
            ]
        ]);
        return $rent;
    }
    private function rent_id(){
        return 18;
    }

    public function index($page = 1){
        $articleList = get_data_module([
            'module' => 'article',
            'language' => $this->currentLanguage(),
            'keyword' => $this->condition_keyword(),
            'where' => [
                'tb1.publish' => 1,
                'tb1.deleted_at' => 0,
            ]
        ]);


        $query = '';
        $query_bed = '';
        $query_area = '';
        $query_price = '';

        if (isset($_GET['bed']) && $_GET['bed'] != '') {
            $_param_bed = explode('-', $_GET['bed']);
            if (check_array($_param_bed)) {
                foreach ($_param_bed as $key => $val) {
                    $query_bed .= '(tb1.bed = \''.trim($val).'\') OR ';
                }
            }
        }

        // print_r($query_bed); exit;

        if (!empty($query_bed) && strlen($query_bed) > 4) {
            $query_bed = '('.substr($query_bed, 0, -4).')';
        }

        // print_r($query_bed); exit;

        if (isset($_GET['area']) && $_GET['area'] != '') {

            // print_r($_GET['area']); exit;
            $_param_area = explode('|', $_GET['area']);

            if (check_array($_param_area)) {
                foreach ($_param_area as $key => $val) {
                    $_sub_param_area = explode('-', $val);

                    $min_area = ltrim($_sub_param_area[0]);
                    $max_area = ltrim((($_sub_param_area[1])?? ''));

                    if (!empty($min_area) && !empty($max_area)) {
                        $query_area .= '(tb1.area >= '.$min_area.' AND tb1.area <= '.$max_area.') OR ';
                    }else if (!empty($min_area) && empty($max_area)) {
                        $query_area .= '(tb1.area >= '.$min_area.') OR ';
                    }
                    // $query .= '(tb1.area = '.$val.') OR ';
                }
            }
        }

        if (!empty($query_area) && strlen($query_area) > 4) {
            $query_area = '('.substr($query_area, 0, -4).')';
        }

        if (isset($_GET['price']) && $_GET['price'] != '') {

            // print_r($_GET['price']); exit;
            $_param_price = explode('|', $_GET['price']);

            if (check_array($_param_price)) {
                foreach ($_param_price as $key => $val) {
                    $_sub_param_price = explode('-', $val);

                    $min_price = ltrim($_sub_param_price[0]);
                    $max_price = ltrim((($_sub_param_price[1])?? ''));

                    if (!empty($min_price) && !empty($max_price)) {
                        $query_price .= '(tb1.price >= '.$min_price.' AND tb1.price <= '.$max_price.') OR ';
                    }else if (!empty($min_price) && empty($max_price)) {
                        $query_price .= '(tb1.price >= '.$min_price.') OR ';
                    }
                    // $query .= '(tb1.price = '.$val.') OR ';
                }
            }
        }

        if (!empty($query_price) && strlen($query_price) > 4) {
            $query_price = '('.substr($query_price, 0, -4).')';
        }

        // print_r($query_price); exit;

        $query .= !empty($query_bed)? $query_bed.' AND':'';
        $query .= !empty($query_area)? $query_area.' AND':'';
        $query .= !empty($query_price)? $query_price.' AND':'';

        if (!empty($query) && strlen($query) > 4) {
            $query = substr($query, 0, -4);
        }

        $order_by = 'tb1.price asc';
        $productList = get_data_module([
            'module' => 'product',
            'language' => $this->currentLanguage(),
            'keyword' => $this->condition_keyword(),
            'query' => $query,
            'where' => [
                'tb1.publish' => 1,
                'tb1.deleted_at' => 0,
            ],
            'order_by' => $order_by,
        ]);

        $total_rows_prd = (check_array($productList))? count($productList): 0;
        $this->data['total_rows_prd'] = $total_rows_prd;

        $this->data['articleList'] = $articleList;
        $this->data['productList'] = $productList;

        $this->data['meta_title'] = 'Tìm kiếm';
        $this->data['meta_description'] = 'Kết quả tìm kiếm';
        $this->data['og_type'] = 'website';
        $this->data['general'] = $this->general;
        if (isset($_GET['filter']) && $_GET['filter'] == 'sell') {

            $this->data['template'] = 'frontend/search/sell';

        }else if (isset($_GET['filter']) && $_GET['filter'] == 'lease') {

            $this->data['template'] = 'frontend/search/lease';

        }else{
            $this->data['template'] = 'frontend/search/index';
        }


        return view('frontend/homepage/layout/home', $this->data);
    }

    // public function condition_catalogue(){
    //     $catalogueid = $this->request->getGet('catalogueid');
    //     $id = [];
    //     if($catalogueid > 0){
    //         $catalogue = $this->AutoloadModel->_get_where([
    //             'select' => 'tb1.id, tb1.lft, tb1.rgt, tb3.title',
    //             'table' => $this->data['module'].'_catalogue as tb1',
    //             'join' =>  [
    //                 [
    //                     'product_translate as tb3','tb1.id = tb3.objectid AND tb3.language = \''.$this->currentLanguage().'\' ','inner'
    //                 ],
    //                                 ],
    //             'where' => ['tb1.id' => $catalogueid],
    //         ]);
    //         pre($catalogue);

    //         $catalogueChildren = $this->AutoloadModel->_get_where([
    //             'select' => 'id',
    //             'table' => $this->data['module'].'_catalogue',
    //             'where' => ['lft >=' => $catalogue['lft'],'rgt <=' => $catalogue['rgt']],
    //         ], TRUE);

    //         $id = array_column($catalogueChildren, 'id');
    //     }
    //     return [
    //         'where_in' => $id,
    //         'where_in_field' => 'tb2.catalogueid'
    //     ];

    // }

    private function condition_where(){
        $where = [];

        $publish = $this->request->getGet('publish');
        if(isset($publish)){
            $where['tb1.publish'] = $publish;
        }

        $deleted_at = $this->request->getGet('deleted_at');
        if(isset($deleted_at)){
            $where['tb1.deleted_at'] = $deleted_at;
        }else{
            $where['tb1.deleted_at'] = 0;
        }

        return $where;
    }

    private function condition_keyword($keyword = ''): string{
        if(!empty($this->request->getGet('keyword'))){
            $keyword = $this->request->getGet('keyword');
            $keyword = '(tb2.title LIKE \'%'.$keyword.'%\' )';
        }
        return $keyword;
    }
}
