<?php
namespace App\Controllers\Frontend\Tag;
use App\Controllers\FrontendController;

class Tag extends FrontendController{

    protected $data;

    public function __construct(){
        $this->data = [];
        $this->data['module'] = 'product_catalogue';
    }

    public function index($id = 0, $page = 1){
        helper(['mypagination']);
        $id = (int)$id;
        $session = session();
        $module_extract = explode("_", $this->data['module']);
       
        // if(isset($this->data['detailCatalogue']) && is_array($this->data['detailCatalogue']) && count($this->data['detailCatalogue'])){
        //     $session->setFlashdata('message-danger', 'Danh mục không tồn tại!');
        //     return redirect()->to(BASE_URL);
        // }
        

        $seoPage = '';
        $page = (int)$page;
        $perpage = ($this->request->getGet('perpage')) ? $this->request->getGet('perpage') : 10;
        $keyword = $this->request->getGet('keyword');
        $catalogue = $this->condition_catalogue($id);
        $tags = $this->AutoloadModel->_get_where([
            'select' => 'objectid',
            'table' => 'tags',
            'where' => [
                'keyword' => $keyword
            ]
        ]);
        $config['total_rows'] = 0;
        if(isset($tags['objectid']) && $tags['objectid'] != ''){
            $object = json_decode($tags['objectid']);
            $config['total_rows'] = count($object);
        }
        $config['base_url'] = write_url('tag', FALSE, TRUE);
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
            $this->data['productList'] = $this->AutoloadModel->_get_where([
                'select' => 'tb1.id,tb1.viewed,tb1.productid, tb1.image,tb1.price,tb1.rate, tb1.price_promotion,  tb1.album, tb3.title, tb3.canonical, tb3.meta_title, tb3.meta_description, tb3.module, tb3.description, tb3.content, tb5.title as cat_title, tb3.*, tb1.extra, tb1.area, tb1.bed',
                'table' => 'product as tb1',
                'where' => [
                    'tb1.deleted_at' => 0,
                    'tb1.publish' => 1
                ],
                'where_in' => $object,
                'where_in_field' => 'tb1.id',
                'join' => [
                    [
                        'object_relationship as tb2', 'tb1.id = tb2.objectid AND tb2.module = \''.$module_extract[0].'\' ', 'inner'
                    ],
                    [
                        'product_translate as tb3','tb1.id = tb3.objectid AND tb3.module = "product" AND tb3.language = \''.$this->currentLanguage().'\' ','inner'
                    ],
                    [
                        'product_catalogue as tb4','tb1.catalogueid = tb4.id','left'
                    ],
                    [
                        'product_translate as tb5','tb4.id = tb5.objectid AND tb5.module = "product_catalogue" AND tb5.language = \''.$this->currentLanguage().'\' ','left'
                    ],
                ],
                'limit' => $config['per_page'],
                'start' => $page * $config['per_page'],
                'order_by'=> $order_by,
                'group_by' => 'tb1.id'
            ], TRUE);
            // pre($this->data['productList']);
        }
        $this->data['meta_title'] = 'Tìm kiếm theo Tags';
        $this->data['meta_description'] = 'Tìm kiếm theo Tags';
        $this->data['meta_image'] = !empty( $this->data['detailCatalogue']['image'])?base_url( $this->data['detailCatalogue']['image']):'';

        if(!isset($this->data['canonical']) || empty($this->data['canonical'])){
            $this->data['canonical'] = $config['base_url'].HTSUFFIX;
        }

        $this->data['slide_banner'] = get_slide([
            'keyword' => 'slide-banner',
            'language' => $this->currentLanguage(),
            'output' => 'html',
            'type' => 'uikit',
            'limit' => 1
        ]);
        $this->data['general'] = $this->general;

        $this->data['template'] = 'frontend/product/catalogue/extra';
        return view('frontend/homepage/layout/home', $this->data);
    }

    private function condition_keyword($keyword = ''): string{
        if(!empty($this->request->getGet('keyword'))){
            $keyword = $this->request->getGet('keyword');
            $keyword = '(tb1.keyword LIKE \'%'.$keyword.'%\')';
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
                        'product_translate as tb3','tb1.id = tb3.objectid AND tb3.language = \''.$this->currentLanguage().'\' AND tb3.module = "product_catalogue"','inner'
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
