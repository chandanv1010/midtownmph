<?php
namespace App\Controllers\Frontend\Product;
use App\Controllers\FrontendController;

class Catalogue extends FrontendController{

    protected $data;

    public function __construct(){
        $this->data = [];
        $this->data['module'] = 'product_catalogue';
    }

    public function index($id = 0, $page = 1){


        helper(['mypagination']);
        $id = (int)$id;
        $this->data['moduleid'] = $id;
        $session = session();
        $module_extract = explode("_", $this->data['module']);
        $detailCatalogue = $this->AutoloadModel->_get_where([
            'select' => ' tb1.id,tb1.lft, tb1.rgt, tb1.level, tb1.parentid, tb1.image, tb1.album, tb2.title, tb2.canonical,  tb2.content, tb2.description, tb2.meta_title, tb2.meta_description, tb1.video_top_id, tb1.video_bottom_id',
            'table' => $this->data['module'].' as tb1',
            'join' => [
                [
                    $module_extract[0].'_translate as tb2','tb2.module = \''.$this->data['module'].'\' AND tb2.objectid = tb1.id AND tb2.language = \''.$this->locale.'\'', 'inner'
                ]
            ],
            'where' => [
                'tb1.deleted_at' => 0,
                'tb1.publish' => 1,
                'tb1.id' => $id
            ]
        ]);
        $extra_id = [];
        $this->data['detailCatalogue'] = $detailCatalogue;
        $dt_catalogue = get_object_catalogue([
            'module' => $this->data['module'],
            'where' => [
                'tb1.id' => $detailCatalogue['id'],
            ],
            'children' => [
                'flag' => true,
                'limit' => 10,
                'post' => true,
                'post_limit' => 9,
            ],
            'post' => [
                'flag' => false,
                // 'limit' => 0,
            ],
        ]);

        $this->data['children'] = $this->AutoloadModel->_get_where([
            'select' => 'tb1.id, tb2.title, tb2.canonical',
            'table' => 'product_catalogue as tb1',
            'join' => [
                ['product_translate as tb2','tb2.objectid = tb1.id','inner']
            ],
            'where' => [
                'tb1.publish' => 1,
                'tb1.deleted_at' => 0,
                'tb2.module' => 'product_catalogue',
                'tb2.language' => $this->locale,
                'tb1.parentid' => $id
            ],
            'order_by' => 'tb1.order asc',
            'group_by' => 'tb1.id'
        ], TRUE);


        if(isset($this->data['children']) && is_array($this->data['children']) && count($this->data['children'])){
            foreach($this->data['children'] as $key =>$val){
                $this->data['children'][$key]['post'] = $this->AutoloadModel->_get_where([
                    'select' => 'tb1.id, tb2.title, tb2.canonical, tb1.image, tb2.description, tb1.price, tb1.area, tb1.bed, tb1.extra, tb1.status',
                    'table' => 'product as tb1',
                    'join' => [
                        ['product_translate as tb2','tb2.objectid = tb1.id','inner']
                    ],
                    'where' => [
                        'tb1.publish' => 1,
                        'tb1.deleted_at' => 0,
                        'tb2.module' => 'product',
                        'tb1.catalogueid' => $val['id'],
                        'tb2.language' => $this->locale,
                    ],
                    'limit' => 9,
                    'order_by' => 'tb1.order desc, tb1.id desc'
                ], TRUE);
            }
        }
        if(isset($this->data['children']) && is_array($this->data['children']) && count($this->data['children'])){
            foreach($this->data['children'] as $key =>$val){
                if(isset($val['post']) && is_array($val['post']) && count($val['post'])){
                    foreach($val['post'] as $keyChild =>$valChild){
                        if($valChild['extra'] != ''){
                            $this->data['children'][$key]['post'][$keyChild]['extra'] = json_decode($valChild['extra'], true);
                        }
                        if(isset($this->data['children'][$key]['post']['extra']) && is_array($this->data['children'][$key]['post']['extra']) && count($this->data['children'][$key]['post']['extra'])){
                            foreach($this->data['children'][$key]['post']['extra'] as $keyExtra =>$valExtra){
                                $extra_id[] = $valExtra;
                            }
                        }
                    }
                }
            }
        }
        // print_r($dt_catalogue); exit;
        $this->data['dt_catalogue'] = $dt_catalogue;

        $breadcrumb = $this->AutoloadModel->_get_where([
            'select' => 'tb1.lft, tb1.rgt, tb1.id, tb1.parentid, tb1.album, tb2.title, tb2.canonical',
            'table' => $this->data['module'].' as tb1',
            'join' => [
                [
                    $module_extract[0].'_translate as tb2','tb2.module = \''.$this->data['module'].'\' AND tb2.objectid = tb1.id AND tb2.language = \''.$this->locale.'\'', 'inner'
                ]
            ],
            'where' => [
                'tb1.deleted_at' => 0,
                'tb1.publish' => 1,
                'tb1.lft <=' => $this->data['detailCatalogue']['lft'],
                'tb1.rgt >=' => $this->data['detailCatalogue']['rgt'],
            ],
            'order_by' => 'tb1.lft asc'
        ], TRUE);

        $this->data['breadcrumb'] = $breadcrumb;
        $seoPage = '';
        $page = (int)$page;
        $perpage = ($this->request->getGet('perpage')) ? $this->request->getGet('perpage') : 1000;
        $keyword = $this->condition_keyword();
        $catalogue = $this->condition_catalogue($id);
        $config['total_rows'] = $this->AutoloadModel->_get_where([
            'select' => 'tb1.id',
            'table' => $module_extract[0].' as tb1',
            'keyword' => $keyword,
            'where_in' => $catalogue['where_in'],
            'where_in_field' => $catalogue['where_in_field'],
            'where' => [
                'tb1.deleted_at' => 0,
                'tb1.publish' => 1
            ],
            'join' => [
                    [
                        'object_relationship as tb2', 'tb1.id = tb2.objectid AND tb2.module = \''.$module_extract[0].'\' ', 'inner'
                    ],
                    [
                        'product_translate as tb3','tb1.id = tb3.objectid AND tb3.module = "product" AND tb3.language = \''.$this->locale.'\' ','inner'
                    ]
                ],
            'count' => TRUE
        ]);
        // pre($config['total_rows']);
        $config['base_url'] = write_url($this->data['detailCatalogue']['canonical'], FALSE, TRUE);
        if($config['total_rows'] > 0){
            $config = pagination_frontend(['url' => $config['base_url'],'perpage' => $perpage], $config);
            $this->pagination->initialize($config);
            $this->data['pagination'] = $this->pagination->create_links();

            $totalPage = ceil($config['total_rows']/$config['per_page']);
            $page = ($page <= 0)?1:$page;
            $page = ($page > $totalPage)?$totalPage:$page;
            if($page >= 2){
                $this->data['canonical'] = $config['base_url'].'/trang-'.$page.HTSUFFIX;
            }
            $page = $page - 1;

            $order_by = 'tb1.order desc, tb1.id desc';

            $_filter = ($_GET['filter'])?? '';

            if (!empty($_filter)) {
                $_filter_explode = explode('|' , $_filter);

                // print_r($_filter_explode); exit;

                if (count($_filter_explode) > 1) {
                    $order_by = 'tb1.'.$_filter_explode[0].' '.$_filter_explode[1];
                }
            }
            // print_r($_get); exit;

            $this->data['productList'] = $this->AutoloadModel->_get_where([
                'select' => 'tb1.id,tb1.viewed,tb1.productid,tb1.bed, tb1.area, tb1.image,tb1.price,tb1.rate, tb1.status, tb1.price_promotion,  tb1.album, tb3.title, tb3.canonical, tb3.meta_title, tb3.meta_description, tb3.module, tb3.description, tb3.content, tb5.title as cat_title, tb3.*, tb1.extra',
                'table' => $module_extract[0].' as tb1',
                'where' => [
                    'tb1.deleted_at' => 0,
                    'tb1.publish' => 1
                ],
                'where_in' => $catalogue['where_in'],
                'where_in_field' => $catalogue['where_in_field'],
                'keyword' => $keyword,
                'join' => [
                    [
                        'object_relationship as tb2', 'tb1.id = tb2.objectid AND tb2.module = \''.$module_extract[0].'\' ', 'inner'
                    ],
                    [
                        'product_translate as tb3','tb1.id = tb3.objectid AND tb3.module = "product" AND tb3.language = \''.$this->locale.'\' ','inner'
                    ],
                    [
                        'product_catalogue as tb4','tb1.catalogueid = tb4.id','left'
                    ],
                    [
                        'product_translate as tb5','tb4.id = tb5.objectid AND tb5.module = "product_catalogue" AND tb5.language = \''.$this->locale.'\' ','left'
                    ],
                ],
                'limit' => $config['per_page'],
                'start' => $page * $config['per_page'],
                'order_by'=> $order_by,
                'group_by' => 'tb1.id'
            ], TRUE);


            if(isset($this->data['productList']) && is_array($this->data['productList']) && count($this->data['productList'])){
                foreach($this->data['productList'] as $key =>$val){
                    if($val['extra'] != ''){
                        $this->data['productList'][$key]['extra'] = json_decode($val['extra'], true);
                    }
                    if(isset($this->data['productList'][$key]['extra']) && is_array($this->data['productList'][$key]['extra']) && count($this->data['productList'][$key]['extra'])){
                        foreach($this->data['productList'][$key]['extra'] as $keyChild =>$valChild){
                            $extra_id[] = $valChild;
                        }
                    }
                }
            }

        }

        $this->data['meta_title'] = (!empty( $this->data['detailCatalogue']['meta_title'])? $this->data['detailCatalogue']['meta_title']: $this->data['detailCatalogue']['title']).$seoPage;
        $this->data['meta_description'] = (!empty( $this->data['detailCatalogue']['meta_description'])? $this->data['detailCatalogue']['meta_description']:cutnchar(strip_tags( base64_decode(html_entity_decode($this->data['detailCatalogue']['description']))), 300)).$seoPage;
        $this->data['meta_image'] = !empty( $this->data['detailCatalogue']['image']) ? base_url( $this->data['detailCatalogue']['image']): $this->general['homepage_logo'];

        $this->data['meta_description'] = html_entity_decode($this->data['meta_description']);


        if(!isset($this->data['canonical']) || empty($this->data['canonical'])){
            $this->data['canonical'] = $config['base_url'].HTSUFFIX;
        }

        $this->data['extra_list'] = $this->AutoloadModel->_get_where([
            'select' => 'tb1.id, tb2.title, tb2.canonical, tb2.description,  tb1.image,  ',

            'table' => 'product_tag as tb1',
            'where_in' => $extra_id,
            'where_in_field' => 'tb1.id',
            'join' =>  [
                    [
                        'product_translate as tb2','tb1.id = tb2.objectid AND tb2.module = "product_tag" AND tb2.language = \''.$this->locale.'\' ','inner'
                    ]
                ],
            'where' => ['tb1.deleted_at' => 0],
            /*
             * PHAI co order_by.
             *
             * Khong co thi MySQL tra ve theo thu tu nao tuy no, va thu tu do
             * DOI GIUA CAC LAN TAI TRANG. Do duoc tren trang that: goi /kr/
             * bon lan lien tiep thi hai muc "shophouse" va "penthouse" doi
             * cho nhau o lan thu hai.
             *
             * Hai the product_tag deu co `order` = 0 nen khong co gi phan
             * dinh; them `tb1.id` lam tieu chi phu de thu tu co dinh.
             *
             * Dung cung quy uoc voi cho khac trong ma nguon:
             * mypanel_helper.php dong 363 va 413.
             */
            'order_by' => 'tb1.order desc, tb1.id asc',
        ],true);


        $this->data['slide_banner'] = get_slide([
            'keyword' => 'slide-banner',
            'language' => $this->locale,
            'output' => 'html',
            'type' => 'uikit',
            'limit' => 1
        ]);
        $this->data['general'] = $this->general;

        $rent = $this->rent_catalogue();
        $this->data['rent_flag'] = 0;
        if($detailCatalogue['lft'] >= $rent['lft'] && $detailCatalogue['rgt'] <= $rent['rgt']){
            $this->data['rent_flag'] = 1;
        }

        if (isset($dt_catalogue['children']) && check_array($dt_catalogue['children'])) {
            $this->data['template'] = 'frontend/product/catalogue/cat_child';
        }else{
            $this->data['template'] = 'frontend/product/catalogue/index';
        }


        return view('frontend/homepage/layout/home', $this->data);
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
                'tb2.language' => $this->locale,
                'tb2.module' => $this->data['module'],
                'tb1.id' => $this->rent_id(),
            ]
        ]);
        return $rent;
    }
    private function rent_id(){
        return 18;
    }

    private function condition_keyword($keyword = ''): string{
        if(!empty($this->request->getGet('keyword'))){
            $keyword = $this->request->getGet('keyword');
            $keyword = '(tb3.title LIKE \'%'.$keyword.'%\')';
        }
        return $keyword;
    }

    public function condition_catalogue($catalogueid = 0){
        $id = [];
        $module_extract = explode("_", $this->data['module']);
        if($catalogueid > 0){
            $catalogue = $this->AutoloadModel->_get_where([
                'select' => 'tb1.id, tb1.lft, tb1.rgt, tb3.title',
                'table' => $module_extract[0].'_catalogue as tb1',
                'join' =>  [
                    [
                        'product_translate as tb3','tb1.id = tb3.objectid AND tb3.language = \''.$this->locale.'\' AND tb3.module = "product_catalogue"','inner'
                    ],
                ],
                'where' => ['tb1.id' => $catalogueid],
            ]);
            $catalogueChildren = $this->AutoloadModel->_get_where([
                'select' => 'id',
                'table' => $module_extract[0].'_catalogue',
                'where' => ['lft >=' => $catalogue['lft'],'rgt <=' => $catalogue['rgt']],
            ], TRUE);

            $id = array_column($catalogueChildren, 'id');
        }
        return [
            'where_in' => $id,
            'where_in_field' => 'tb2.catalogueid'
        ];

    }
}
