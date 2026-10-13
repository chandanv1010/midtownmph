<?php
namespace App\Controllers\Frontend\Lease;
use App\Controllers\FrontendController;

class Lease extends FrontendController{

    protected $data;
    protected $_dt_select;

    public function __construct(){
        $this->data = [];
        $this->data['module'] = 'lease';
    }

    public function index($id = 0, $page = 1){
        helper(['mypagination']);
        $id = (int)$id;
        $this->data['moduleid'] = $id;
        $session = session();
        $module_extract = explode("_", $this->data['module']);
        $keyword = $this->condition_keyword();

        $this->_dt_select = ', tb2.address, tb2.map, tb2.direction, tb2.bath, tb2.bed, tb2.area, tb2.floor, tb2.type, tb2.indoor_status, tb2.law, tb2.foreign, tb2.sell_status';


        $object = $this->AutoloadModel->_get_where([
            'select' => 'tb1.id,tb1.price,tb1.sub_album, tb1.price_promotion,tb1.catalogueid, tb1.viewed, tb1.album, tb1.image, tb1.userid_created,
                tb2.title, tb2.canonical,tb2.sub_album_title, tb2.meta_title, tb2.sub_title,tb2.video, tb2.sub_content, tb1.leaseid, tb2.meta_description,  tb2.description, tb2.content, tb1.bar_code, tb1.model, tb1.brandid
            '.$this->_dt_select,
            'table' => $module_extract[0].' as tb1',
            'where' => [
                'tb1.deleted_at' => 0,
                'tb1.publish' => 1,
                'tb1.id' => $id
            ],
            'join' => [
                [
                    'lease_translate as tb2','tb1.id = tb2.objectid AND tb2.module = "lease" AND tb2.language = \''.$this->currentLanguage().'\' ','inner'
                ],
            ],
        ]);
        $this->data['object'] = $object;

        if (check_array($object)) {
            // thông tin người tạo
            $user_created = $this->AutoloadModel->_get_where([
                'select' => 'tb1.*',
                'table' => 'user as tb1',
                'where' => [
                    'tb1.id' => $object['userid_created'],
                ],
            ]);

            $this->data['user_created'] = $user_created;

            // tiện nghi khách sạn
            $brandid = json_decode($object['brandid'], true);
            if (check_array($brandid)) {
                $brand_list = $this->AutoloadModel->_get_where([
                    'select' => 'tb1.image, tb2.title',
                    'table' => 'brand as tb1',
                    'join' => [
                        [
                            'brand_translate as tb2','tb2.module = \'brand\' AND tb2.objectid = tb1.id AND tb2.language = \''.$this->currentLanguage().'\'', 'inner'
                        ]
                    ],
                    'where' => [
                        'tb1.deleted_at' => 0,
                        'tb1.publish' => 1,
                    ],
                    'where_in' => $brandid,
                    'where_in_field' => 'tb1.id',
                ], true);
                $this->data['brand_list'] = $brand_list;
            }
        }
        

        //khối mở rộng

        $this->data['wholesale_list'] = $this->get_list_wholesale($id);
        // prE($this->data['wholesale_list']);
        // if(isset($this->data['object']) && is_array($this->data['object']) && count($this->data['object'])){
        //     $session->setFlashdata('message-danger', 'Bài viết không tồn tại!');
        //     return redirect()->to(BASE_URL);
        // }
        $this->data['sub_album'] = $this->rewrite_album($this->data['object']);
        $this->data['object'] = $this->convert_data($this->data['object']);

        $this->data['detailCatalogue'] = $this->AutoloadModel->_get_where([
            'select' => ' tb1.id,tb1.lft, tb1.rgt, tb1.level, tb1.parentid, tb1.image,  tb2.title, tb2.canonical,  tb2.content, tb2.description, tb2.meta_title, tb2.meta_description',
            'table' => $this->data['module'].'_catalogue as tb1',
            'join' => [
                [
                    $module_extract[0].'_translate as tb2','tb2.module = \''.$this->data['module'].'_catalogue\' AND tb2.objectid = tb1.id AND tb2.language = \''.$this->currentLanguage().'\'', 'inner'
                ]
            ],
            'where' => [
                'tb1.deleted_at' => 0,
                'tb1.publish' => 1,
                'tb1.id' => $this->data['object']['catalogueid']
            ]
        ]);

        if(!isset($this->data['detailCatalogue']) || is_array($this->data['detailCatalogue']) == false && count($this->data['detailCatalogue']) == 0){
            $session->setFlashdata('message-danger', 'Bản ghi không tồn tại!');
            return redirect()->to(BASE_URL);
        }

        $this->data['child'] = $this->AutoloadModel->_get_where([
           'select' => 'tb1.id,  tb2.title, tb2.canonical',
           'table' => $module_extract[0].'_catalogue as tb1',
           'join' =>[
               [
                   $module_extract[0].'_translate as tb2','tb2.module = \''.$module_extract[0].'_catalogue\' AND tb2.objectid = tb1.id AND tb2.language = \''.$this->currentLanguage().'\'','inner'
               ]
           ],
           'where' => [
               'tb1.deleted_at' => 0,
               'tb1.publish' => 1,
               'tb1.lft >=' => $this->data['detailCatalogue']['lft'],
               'tb1.rgt <=' => $this->data['detailCatalogue']['rgt'],
           ],
           'group_by' => 'tb1.id',
           'order_by' => 'tb1.order desc, tb1.id desc'
        ],TRuE);
        $id = [];
        if(isset($this->data['child']) &&  is_array($this->data['child'])  && count($this->data['child'])){
            foreach ($this->data['child'] as $keyData => $valData) {
                array_push($id,$valData['id']);
            }
        }

        // print_r($module_extract); exit;

        $this->data['lease_general'] = $this->lease_general($module_extract, $id);
        $this->data['breadcrumb'] = $this->breadcrumb($module_extract);
         $this->data['category'] = $this->category();

        if(isset($this->data['lease_general']) && is_array($this->data['lease_general']) && count($this->data['lease_general'])){
            foreach ($this->data['lease_general'] as $key => $value) {
                if($value['id'] == $this->data['object']['id']){
                    unset($this->data['lease_general'][$key]);
                }else{
                    $this->data['lease_general'][$key]['description']= $value['description'];
                    $this->data['lease_general'][$key]['content']= $value['content'];
                }
            }
        }

        $cookie = $this->set_cookie($id, $this->data['object']);

        $this->data['meta_title'] = (!empty( $this->data['object']['meta_title']) ? $this->data['object']['meta_title']: $this->data['object']['title']);
        $this->data['meta_description'] = (!empty( $this->data['object']['meta_description'])? $this->data['object']['meta_description']:cutnchar(strip_tags( $this->data['object']['description']), 300));
        $this->data['meta_image'] = !empty( $this->data['object']['image'])?base_url( $this->data['object']['image']):((isset($this->data['object']['album'][0])) ? $this->data['object']['album'][0] : '');

        $config['base_url'] = write_url($this->data['object']['canonical'], FALSE, TRUE);
        if(!isset($this->data['canonical']) || empty($this->data['canonical'])){
            $this->data['canonical'] = $config['base_url'].HTSUFFIX;
        }
        $this->data['rate'] = $this->data_rate([
            'canonical' => $this->data['object']['canonical'],
            'module' => $this->data['module']
        ]);

        $this->data['general'] = $this->general;
        $this->data['template'] = 'frontend/lease/lease/index';
        return view('frontend/homepage/layout/home', $this->data);
    }
    private function lease_general($module_extract = [], $id = []){
        $lease_general = $this->AutoloadModel->_get_where([
            'select' => 'tb1.catalogueid, tb3.id, tb3.price, tb3.price_promotion, tb3.image, tb2.module, tb3.rate, tb3.album ,tb3.bar_code,       tb2.title,tb2.description, tb2.content, tb2.canonical,  tb2.meta_title, tb2.meta_description
            '.$this->_dt_select,
            'table' => 'object_relationship as tb1',
            'join' =>[
                [
                    $module_extract[0].'_translate as tb2','tb2.module = \''.$module_extract[0].'\' AND tb2.objectid = tb1.objectid AND tb2.language = \''.$this->currentLanguage().'\'','inner'
                ],
                [
                    $module_extract[0].' as tb3', 'tb1.objectid = tb3.id AND tb3.deleted_at = 0','inner'
                ]
            ],
            'where' => ['tb1.module' => $module_extract[0]],
            'where_in_field' => 'tb1.catalogueid',
            'where_in' => $id,
            'group_by' => 'tb3.id',
            'order_by' => 'tb3.order desc, tb3.id desc'
         ],TRuE);

        return $lease_general;
    }
    private function category(){
        $category = $this->AutoloadModel->_get_where([
            'select' => 'tb1.id, tb2.canonical, tb2.title',
            'table' => 'lease_catalogue as tb1',
            'join' => [
                ['lease_translate as tb2', 'tb2.objectid = tb1.id', 'inner']
            ],
            'where' => [
                'tb1.publish' => 1,
                'tb1.level' => 2,
                'tb2.module' => 'lease_catalogue'
            ]
        ], TRUE);
        return $category;
    }
    private function breadcrumb($module_extract = []){
        $breadcrumb = $this->AutoloadModel->_get_where([
            'select' => 'tb1.lft, tb1.rgt, tb1.id, tb1.parentid,  tb2.title, tb2.canonical',
            'table' => $this->data['module'].'_catalogue as tb1',
            'join' => [
                [
                    $module_extract[0].'_translate as tb2','tb2.module = \''.$this->data['module'].'_catalogue\' AND tb2.objectid = tb1.id AND tb2.language = \''.$this->currentLanguage().'\'', 'inner'
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
        return $breadcrumb;
    }

    private function get_list_wholesale($id = 0){
        $check = $this->AutoloadModel->_get_where([
            'select' => 'number_start, number_end, price',
            'table' => 'lease_wholesale',
            'where' => ['objectid' => $id, 'module' => $this->data['module']]
        ]);
        if(isset($check) && is_array($check) && count($check)){
            $array = [
                'number_start' => json_decode($check['number_start']),
                'number_end' => json_decode($check['number_end']),
                'price_wholesale' => json_decode($check['price']),
            ];
            $data = [];
            foreach ($array as $key => $value) {
                foreach ($value as $keyChild => $valChild) {
                    $data[$keyChild][$key] = $valChild;
                }
            }
            return $data;
        }

    }

    private function data_rate($param = []){
        $full = $this->AutoloadModel->_get_where([
            'select' => 'fullname, comment, rate, created_at, phone, email, id, created_at, publish, url, module, image, album',
            'table' => 'comment',
            'where' => [
                'deleted_at' => 0,
                'module' => $param['module'],
                'url' => $param['canonical'],
                'parentid' => 0
            ],
            'order_by' => 'created_at asc'
        ], TRUE);
        $calculator = [
            '1' => 0,
            '2' => 0,
            '3' => 0,
            '4' => 0,
            '5' => 0,
        ];
        $rate = [];
        if(isset($full) && is_array($full) && count($full)){
            foreach ($full as $key => $value) {
                $full[$key]['comment'] = base64_decode($value['comment']);
                foreach ($calculator as $keyCal => $valueCal) {
                    if($value['rate'] == $keyCal && $value['publish'] == 1){
                        $calculator[$keyCal] = $calculator[$keyCal] + 1;
                    }
                }
            }
            foreach ($full as $key => $value) {
               if($value['publish'] == 1){
                    array_push($rate, $value);
                }
            }
        }
        $sum_star = 0;
        $sum_comment = 0;
        foreach ($calculator as $key => $value) {
            $sum_star = $sum_star + ($key * $value);
            $sum_comment = $sum_comment + $value;
        }
        if($sum_comment == 0){
            $result['total'] = 0;
        }else{
            $result['total'] = round($sum_star/$sum_comment,1);
        }
        $result['sum'] = $sum_comment;
        $result['all_comment'] = $full;
        $result['comment_publish_1'] = $rate;
        $result['calculator'] = $calculator;
        return $result;
    }

    private function condition_keyword($keyword = ''): string{
        if(!empty($this->request->getGet('keyword'))){
            $keyword = $this->request->getGet('keyword');
            $keyword = '(title LIKE \'%'.$keyword.'%\')';
        }
        return $keyword;
    }

    private function set_cookie($id = 0, $param = []){
        $idList = [];
        if(!isset($_COOKIE['COUNT_'.$this->data['module']]) || empty($_COOKIE['COUNT_'.$this->data['module']])){
            array_push($idList, $id);
            setcookie('COUNT_'.$this->data['module'], json_encode($idList), time() + 1*24*3600, "/");
            $cookie = $this->AutoloadModel->_update([
                'table' => $this->data['module'],
                'where' => [
                    'id' => $id,
                    'deleted_at' => 0,
                    'publish' => 1
                ],
                'data' => [
                    'viewed' => $param['viewed'] + 1
                ]
            ]);
        }else{
            $getCookie = $this->request->getCookie('COUNT_'.$this->data['module']);
            $getCookie = json_decode($getCookie);
            $count = 0;
            foreach ($getCookie as $key => $value) {
                if($id == $value){
                    $count++;
                }
            }
            if($count == 0){
                array_push($getCookie, $id);
                setcookie('COUNT_'.$this->data['module'], json_encode($getCookie), time() + 1*24*3600, "/");
                $cookie = $this->AutoloadModel->_update([
                    'table' => $this->data['module'],
                    'where' => [
                        'id' => $id,
                        'deleted_at' => 0,
                        'publish' => 1
                    ],
                    'data' => [
                        'viewed' => $param['viewed'] + 1
                    ]
                ]);
            }
        }
        return true;
    }

    private function rewrite_album($param = []){
        $sub_album = json_decode($param['sub_album'],TRUE);
        $sub_album_title = json_decode($param['sub_album_title'],TRUE);
        $album = [];
        if(isset($sub_album) && is_array($sub_album) && count($sub_album)){
            foreach ($sub_album as $key => $value) {
                foreach ($sub_album_title as $keyTitle => $valTitle) {
                    if($key == $keyTitle){
                        $album[$key]['title'] = $valTitle;
                        $album[$key]['album'] = $value;
                    }
                }
            }

        }
        return $album;
    }

    private function convert_data($param = []){
        if(isset($param['album']) && $param['album'] != ''){
            $param['album'] = json_decode($param['album']);
        }
        if(isset($param['info']) && $param['info'] != ''){
            $param['info'] = json_decode($param['info'], TRUE);
        }
        if(isset($param['description']) && $param['description'] != ''){
            $param['description'] = validate_input(base64_decode($param['description']));
        }
        if(isset($param['content']) && $param['content'] != ''){
            $param['content'] = validate_input(base64_decode($param['content']));
        }
        if(isset($param['sub_content']) && $param['sub_content'] != ''){
            $param['sub_content'] = json_decode(base64_decode($param['sub_content']));
        }
        if(isset($param['sub_title']) && $param['sub_title'] != ''){
            $param['sub_title'] = json_decode(base64_decode($param['sub_title']));
        }
        // if(isset($param['price']) && $param['price'] != ''){
        //     $param['price'] = number_format($param['price'],0,',','.');
        // }
        // if(isset($param['price_promotion']) && $param['price_promotion'] != ''){
        //     $param['price_promotion'] = number_format($param['price_promotion'],0,',','.');
        // }

        return $param;
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
                        'lease_translate as tb3','tb1.id = tb3.objectid AND tb3.language = \''.$this->currentLanguage().'\' AND tb3.module = "lease"','inner'
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
