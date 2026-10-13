<?php
namespace App\Controllers\Frontend\Lease;
use App\Controllers\FrontendController;

class Catalogue extends FrontendController{

    protected $data;

    public function __construct(){
        $this->data = [];
        $this->data['module'] = 'lease_catalogue';
    }

    public function index($id = 0, $page = 1){
        helper(['mypagination']);
        $id = (int)$id;
        $this->data['moduleid'] = $id;
        $session = session();
        $module_extract = explode("_", $this->data['module']);
        $detailCatalogue = $this->AutoloadModel->_get_where([
            'select' => ' tb1.id,tb1.lft, tb1.rgt, tb1.level, tb1.parentid, tb1.image, tb1.album, tb2.title, tb2.canonical,  tb2.content, tb2.description, tb2.meta_title, tb2.meta_description',
            'table' => $this->data['module'].' as tb1',
            'join' => [
                [
                    $module_extract[0].'_translate as tb2','tb2.module = \''.$this->data['module'].'\' AND tb2.objectid = tb1.id AND tb2.language = \''.$this->currentLanguage().'\'', 'inner'
                ]
            ],
            'where' => [
                'tb1.deleted_at' => 0,
                'tb1.publish' => 1,
                'tb1.id' => $id
            ]
        ]);

        $this->data['detailCatalogue'] = $detailCatalogue;

        $dt_catalogue = get_object_catalogue([
            'module' => $this->data['module'],
            'where' => [
                'tb1.id' => $detailCatalogue['id'],
            ],  
            'children' => [
                'flag' => true,
                'limit' => 2,
                'post' => true,
                'post_limit' => 9,
            ],
            'post' => [
                'flag' => false,
                // 'limit' => 0,
            ],
        ]);

        // print_r($dt_catalogue); exit;
        $this->data['dt_catalogue'] = $dt_catalogue;

        $breadcrumb = $this->AutoloadModel->_get_where([
            'select' => 'tb1.lft, tb1.rgt, tb1.id, tb1.parentid, tb1.album, tb2.title, tb2.canonical',
            'table' => $this->data['module'].' as tb1',
            'join' => [
                [
                    $module_extract[0].'_translate as tb2','tb2.module = \''.$this->data['module'].'\' AND tb2.objectid = tb1.id AND tb2.language = \''.$this->currentLanguage().'\'', 'inner'
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
        $perpage = ($this->request->getGet('perpage')) ? $this->request->getGet('perpage') : 26;
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
                        'lease_translate as tb3','tb1.id = tb3.objectid AND tb3.module = "lease" AND tb3.language = \''.$this->currentLanguage().'\' ','inner'
                    ]
                ],
            'count' => TRUE
        ]);
        // prE($config['total_rows']);
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
            $this->data['leaseList'] = $this->AutoloadModel->_get_where([
                'select' => 'tb1.id,tb1.viewed,tb1.leaseid, tb1.image,tb1.price,tb1.rate, tb1.price_promotion,  tb1.album, tb3.title, tb3.canonical, tb3.meta_title, tb3.meta_description, tb3.module, tb3.description, tb3.content, tb5.title as cat_title, tb3.*',
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
                        'lease_translate as tb3','tb1.id = tb3.objectid AND tb3.module = "lease" AND tb3.language = \''.$this->currentLanguage().'\' ','inner'
                    ],
                    [
                        'lease_catalogue as tb4','tb1.catalogueid = tb4.id','left'
                    ],
                    [
                        'lease_translate as tb5','tb4.id = tb5.objectid AND tb5.module = "lease_catalogue" AND tb5.language = \''.$this->currentLanguage().'\' ','left'
                    ],
                ],
                'limit' => $config['per_page'],
                'start' => $page * $config['per_page'],
                'order_by'=> 'tb1.order desc, tb1.id desc',
                'group_by' => 'tb1.id'
            ], TRUE);
            // pre($this->data['leaseList']);
        }
        

        $this->data['meta_title'] = (!empty( $this->data['detailCatalogue']['meta_title'])? $this->data['detailCatalogue']['meta_title']: $this->data['detailCatalogue']['title']).$seoPage;
        $this->data['meta_description'] = (!empty( $this->data['detailCatalogue']['meta_description'])? $this->data['detailCatalogue']['meta_description']:cutnchar(strip_tags( $this->data['detailCatalogue']['description']), 300)).$seoPage;
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

        if (isset($dt_catalogue['children']) && check_array($dt_catalogue['children'])) {
            $this->data['template'] = 'frontend/lease/catalogue/cat_child';
        }else{
            $this->data['template'] = 'frontend/lease/catalogue/index';
        }
        return view('frontend/homepage/layout/home', $this->data);
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
                        'lease_translate as tb3','tb1.id = tb3.objectid AND tb3.language = \''.$this->currentLanguage().'\' AND tb3.module = "lease_catalogue"','inner'
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
