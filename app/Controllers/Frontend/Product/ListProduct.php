<?php
namespace App\Controllers\Frontend\Product;
use App\Controllers\FrontendController;

class ListProduct extends FrontendController{

    protected $data;

    public function __construct(){
        $this->data = [];
        $this->data['module'] = 'product';
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
        $productList = get_data_module([
            'module' => 'product',
            'language' => $this->currentLanguage(),
            'keyword' => $this->condition_keyword(),
            'where' => [
                'tb1.publish' => 1,
                'tb1.deleted_at' => 0,
            ]
        ]);

        // echo 123;die();

        $this->data['articleList'] = $articleList;
        $this->data['productList'] = $productList;
            
        $this->data['meta_title'] = 'Tìm kiếm';
        $this->data['meta_description'] = 'Kết quả tìm kiếm';
        $this->data['og_type'] = 'website';
        $this->data['meta_image'] =  $this->general['homepage_logo'];

        $this->data['general'] = $this->general;
        if (isset($_GET['filter']) && $_GET['sell']) {
            
        }
        $this->data['template'] = 'frontend/product/list/index';
        // $this->data['template'] = 'frontend/product/catalogue/index';
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
            $keyword = '(tb2.title LIKE \'%'.$keyword.'%\')';
        }
        return $keyword;
    }
}
