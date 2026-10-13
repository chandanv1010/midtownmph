<?php
namespace App\Controllers\Frontend\Product;
use App\Controllers\FrontendController;

class Product extends FrontendController{

    protected $data;
    protected $_dt_select;

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
                'tb2.language' => $this->locale,
                'tb2.module' => $this->data['module'].'_catalogue',
                'tb1.id' => $this->rent_id(),
            ]
        ]);
        return $rent;
    }
    private function rent_id(){
        return 18;
    }

    public function index($id = 0, $page = 1){
        helper(['mypagination']);
        $id = (int)$id;
        $this->data['moduleid'] = $id;
        $session = session();
        $module_extract = explode("_", $this->data['module']);
        $keyword = $this->condition_keyword();

        $this->_dt_select = ', tb2.address, map, bath,bed,area, status, info';

        $object = $this->AutoloadModel->_get_where([
            'select' => 'tb1.id,tb1.price,tb1.sub_album, tb1.price_promotion,tb1.catalogueid, tb1.viewed, tb1.album, tb1.image, tb1.userid_created, tb2.title, tb2.canonical,tb2.sub_album_title, tb2.meta_title, tb2.sub_title, tb2.sub_content, tb1.productid, tb2.meta_description, tb2.description, tb2.content, tb1.bar_code, tb1.model, tb1.brandid, tb1.video, tb1.vr,  tb1.vr_3d'.$this->_dt_select,
            'table' => $module_extract[0].' as tb1',
            'where' => [
                'tb1.deleted_at' => 0,
                'tb1.publish' => 1,
                'tb1.id' => $id
            ],
            'join' => [
                [
                    'product_translate as tb2','tb1.id = tb2.objectid AND tb2.module = "product" AND tb2.language = \''.$this->locale.'\' ','inner'
                ],
            ],
        ]);

        $this->data['tags'] = $this->get_tags($id);
        if(!isset($object) || is_array($object) == false || count($object) == 0 ){
            $session->setFlashdata('message-danger', 'Bản ghi không tồn tại!');
            header('location:'.BASE_URL);die();
        }
        $object['status'] = json_decode($object['status'],true);
        $object['info'] = json_decode($object['info'],true);

        $list_attr = $object['status'] + $object['info'];
        $this->data['attrList'] = $this->get_attribute_list($list_attr);
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
        }

        $brandid = json_decode($this->data['object']['brandid'], TRUE);
        $this->data['brand']['indoor'] = [];
        $this->data['brand']['outdoor'] = [];
        if(isset($brandid['indoor']) && is_array($brandid['indoor']) && count($brandid['indoor'])){
            $this->data['brand']['indoor'] = $this->AutoloadModel->_get_where([
                'select' => 'tb1.id, tb2.title, tb1.image',
                'table' => 'brand as tb1',
                'join' => [
                    ['brand_translate as tb2','tb1.id = tb2.objectid','inner'],
                ],
                'where' => [
                    'tb1.publish' => 1,
                    'tb1.deleted_at' => 0,
                    'tb2.module' => 'brand',
                    'tb2.language' => $this->locale,
                ],
                'where_in' => $brandid['indoor'],
                'where_in_field' => 'tb1.id',
            ], TRUE);
        }

        if(isset($brandid['outdoor']) && is_array($brandid['outdoor']) && count($brandid['outdoor'])){
            $this->data['brand']['outdoor'] = $this->AutoloadModel->_get_where([
                'select' => 'tb1.id, tb2.title, tb1.image',
                'table' => 'brand as tb1',
                'join' => [
                    ['brand_translate as tb2','tb1.id = tb2.objectid','inner'],
                ],
                'where' => [
                    'tb1.publish' => 1,
                    'tb1.deleted_at' => 0,
                    'tb2.module' => 'brand',
                    'tb2.language' => $this->locale,
                ],
                'where_in' => $brandid['outdoor'],
                'where_in_field' => 'tb1.id',
            ], TRUE);
        }




        //khối mở rộng

        // $this->data['wholesale_list'] = $this->get_list_wholesale($id);
        // prE($this->data['wholesale_list']);
        // if(isset($this->data['object']) && is_array($this->data['object']) && count($this->data['object'])){
        //     $session->setFlashdata('message-danger', 'Bài viết không tồn tại!');
        //     return redirect()->to(BASE_URL);
        // }

        $this->data['sub_album'] = $this->rewrite_album($this->data['object']);
        $this->data['object'] = $this->convert_data($this->data['object']);



        $this->data['detailCatalogue'] = $this->AutoloadModel->_get_where([
            'select' => ' tb1.id,tb1.lft, tb1.rgt, tb1.level, tb1.parentid, tb1.image, tb2.title, tb2.canonical,  tb2.content, tb2.description, tb2.meta_title, tb2.meta_description',
            'table' => $this->data['module'].'_catalogue as tb1',
            'join' => [
                [
                    $module_extract[0].'_translate as tb2','tb2.module = \''.$this->data['module'].'_catalogue\' AND tb2.objectid = tb1.id AND tb2.language = \''.$this->locale.'\'', 'inner'
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
            header('location:'.BASE_URL);
        }



        $this->data['child'] = $this->AutoloadModel->_get_where([
           'select' => 'tb1.id,  tb2.title, tb2.canonical',
           'table' => $module_extract[0].'_catalogue as tb1',
           'join' =>[
               [
                   $module_extract[0].'_translate as tb2','tb2.module = \''.$module_extract[0].'_catalogue\' AND tb2.objectid = tb1.id AND tb2.language = \''.$this->locale.'\'','inner'
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
        ], true);
        $id = [];

        if(isset($this->data['child']) &&  is_array($this->data['child'])  && count($this->data['child'])){
            foreach ($this->data['child'] as $keyData => $valData) {
                array_push($id,$valData['id']);
            }
        }

        // print_r($module_extract); exit;




        $this->data['product_general'] = $this->product_general($module_extract, $id, $object['price']);
        $this->data['breadcrumb'] = $this->breadcrumb($module_extract);
         $this->data['category'] = $this->category();

        if(isset($this->data['product_general']) && is_array($this->data['product_general']) && count($this->data['product_general'])){
            foreach ($this->data['product_general'] as $key => $value) {
                if($value['id'] == $this->data['object']['id']){
                    unset($this->data['product_general'][$key]);
                }else{
                    $this->data['product_general'][$key]['description']= $value['description'];
                    $this->data['product_general'][$key]['content']= $value['content'];
                }
            }
        }

        $viewed = $object['viewed'] + 1;
        $a = $this->AutoloadModel->_update([
            'table' => 'product',
            'where' => [
                'id' => $object['id'],
                'deleted_at' => 0,
                'publish' => 1
            ],
            'data' => [
                'viewed' => $viewed
            ]
        ]);


        // $cookie = $this->set_cookie($id, $this->data['object']);

        $this->data['meta_title'] = (!empty( $this->data['object']['meta_title']) ? htmlspecialchars_decode(html_entity_decode($this->data['object']['meta_title'])): htmlspecialchars_decode(html_entity_decode($this->data['object']['title'])));
        $this->data['meta_description'] = (!empty( $this->data['object']['meta_description'])? $this->data['object']['meta_description']:cutnchar(strip_tags( $this->data['object']['description']), 300));
        $this->data['meta_image'] = !empty( $this->data['object']['image'])?base_url( $this->data['object']['image']):((isset($this->data['object']['album'][0])) ? $this->data['object']['album'][0] : $this->general['homepage_logo']);


        $config['base_url'] = write_url($this->data['object']['canonical'], FALSE, TRUE);
        if(!isset($this->data['canonical']) || empty($this->data['canonical'])){
            $this->data['canonical'] = $config['base_url'].HTSUFFIX;
        }
        $this->data['rate'] = $this->data_rate([
            'canonical' => $this->data['object']['canonical'],
            'module' => $this->data['module']
        ]);

        $rent = $this->rent_catalogue();
        $this->data['rent_flag'] = 0;
        $detailCatalogue = $this->data['detailCatalogue'];
        if($detailCatalogue['lft'] >= $rent['lft'] && $detailCatalogue['rgt'] <= $rent['rgt']){
            $this->data['rent_flag'] = 1;
        }

        $this->data['general'] = $this->general;
        $this->data['template'] = 'frontend/product/product/index';
        return view('frontend/homepage/layout/home', $this->data);
    }

    public function get_attribute_list($where = []){
        if(isset($where) && is_array($where) && count($where)){
            $attr = $this->AutoloadModel->_get_where([
                'select' => 'tb1.id, tb1.catalogueid, tb2.title , tb3.title as cat_title, tb2.module_primary , tb4.image',
                'table' => 'attribute as tb1',
                'join' => [
                    [
                        'attribute_translate as tb2','tb1.id = tb2.objectid AND tb2.module = "attribute"  AND tb2.language = \''.$this->locale.'\' ','inner'
                    ],
                    [
                        'attribute_translate as tb3','tb1.catalogueid = tb3.objectid AND tb3.module = "attribute_catalogue" AND tb3.language = \''.$this->locale.'\' ','inner'
                    ],
                    [
                        'attribute_catalogue as tb4','tb1.catalogueid = tb4.id AND tb4.deleted_at = 0  AND tb4.publish = 1','inner'
                    ],
                ],
                'where_in' => $where,
                'where_in_field' => 'tb1.id',
                'where' => [
                    'tb1.publish' => 1,
                    'tb1.deleted_at' => 0
                ],
                'group_by' => 'tb1.id',
                'order_by' => 'tb2.title asc'
            ],true);
        }

        $attr_list = [];
        if(isset($attr) && is_array($attr) && count($attr)){
            foreach ($attr as $key => $value) {
                $attr_list[$value['catalogueid']] = [
                    'title' => $value['cat_title'],
                    'id' => $value['catalogueid'],
                    'status' => $value['module_primary'],
                    'image' => $value['image'],
                    'value' => $value['title']
                ];
            }

        }
        $list = [];
        if(isset($attr_list) && is_array($attr_list) && count($attr_list)){
            foreach ($attr_list as $key => $value) {
                if($value['status'] == 'product'){
                    $list['product'][] = $value;
                }else{
                    $list['status'][] = $value;
                }
            }
        }
        return $list;
    }

    private function get_tags($id = ''){
        $tags = $this->AutoloadModel->_get_where([
            'select' => '*',
            'table' => 'tags',
            'where' => [
                'module' => $this->data['module'],
                'language' => $this->locale,
            ]
        ],true);
        $array = [];
        if(isset($tags) && is_array($tags) && count($tags)){
            foreach ($tags as $key => $value) {
                $tags[$key]['objectid'] = json_decode($value['objectid']);
            }
            foreach ($tags as $key => $value) {
                foreach ($value['objectid'] as $keytag => $valuetag) {
                    if($valuetag == $id){
                        array_push($array, $value);
                    }
                }
            }
        }

        return $array;
    }

    private function product_general($module_extract = [], $id = [], $price = 0){
        $product_general = $this->AutoloadModel->_get_where([
            'select' => 'tb1.catalogueid, tb3.id, tb3.price, tb3.price_promotion, tb3.image, tb2.module, tb3.rate, tb3.album ,tb3.bar_code, tb2.title,tb2.description, tb2.content, tb2.canonical,  tb2.meta_title, tb2.meta_description, tb3.productid
            '.$this->_dt_select,
            'table' => 'object_relationship as tb1',
            'join' =>[
                [
                    $module_extract[0].'_translate as tb2','tb2.module = \''.$module_extract[0].'\' AND tb2.objectid = tb1.objectid AND tb2.language = \''.$this->locale.'\'','inner'
                ],
                [
                    $module_extract[0].' as tb3', 'tb1.objectid = tb3.id AND tb3.deleted_at = 0','inner'
                ]
            ],
            'where' => [
                'tb1.module' => $module_extract[0],
                'tb3.price <' => $price + 1000,
                'tb3.price >' => $price - 1000,
            ],
            'group_by' => 'tb3.id',
            'order_by' => 'tb3.order desc, tb3.id desc'
         ],TRuE);


        return $product_general;
    }
    private function category(){
        $category = $this->AutoloadModel->_get_where([
            'select' => 'tb1.id, tb2.canonical, tb2.title',
            'table' => 'product_catalogue as tb1',
            'join' => [
                ['product_translate as tb2', 'tb2.objectid = tb1.id', 'inner']
            ],
            'where' => [
                'tb1.publish' => 1,
                'tb1.level' => 2,
                'tb2.module' => 'product_catalogue'
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
                    $module_extract[0].'_translate as tb2','tb2.module = \''.$this->data['module'].'_catalogue\' AND tb2.objectid = tb1.id AND tb2.language = \''.$this->locale.'\'', 'inner'
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
            'table' => 'product_wholesale',
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
                if(isset($sub_album_title) && is_array($sub_album_title) && count($sub_album_title)){
                    foreach ($sub_album_title as $keyTitle => $valTitle) {
                        if($key == $keyTitle){
                            $album[$key]['title'] = $valTitle;
                            $album[$key]['album'] = $value;
                        }
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
                        'product_translate as tb3','tb1.id = tb3.objectid AND tb3.language = \''.$this->locale.'\' AND tb3.module = "product"','inner'
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
