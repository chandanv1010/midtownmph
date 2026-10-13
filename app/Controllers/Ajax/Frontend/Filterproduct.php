<?php
namespace App\Controllers\Ajax\Frontend;
use App\Controllers\FrontendController;

class Filterproduct extends FrontendController{
    public function __construct(){

    }

    public function render_product($page = 1){
        helper(['mypagination']);
        $param['cat'] = $this->request->getPost('cat');
        $param['catalogue'] = $this->request->getPost('catalogue');
        $param['brand'] = $this->request->getPost('brand');
        $param['array'] = $this->request->getPost('array');
        $param['star'] = $this->request->getPost('star');
        $param['price'] = $this->request->getPost('price');
        $param['page'] = $this->request->getPost('page');
        $param['url'] = $this->request->getPost('url');
        $param['module'] = $this->request->getPost('module');
        $param['moduleid'] = $this->request->getPost('moduleid');
        $explode = explode('_', $param['module']);
        // print_r($param); exit;
        if (isset($explode[0]) && $explode[0] == 'brand') {
            $explode[0] = 'product';
            $param['module'] = 'product';
            $param['brand'] = [$param['moduleid']];
        }
        $importSQL = $this->create_query($param);
        $flag  = $this->AutoloadModel->_get_where([
            'select' => 'tb1.id, tb1.viewed, tb1.productid, tb1.image,tb1.price, tb1.price_promotion, tb2.title, tb2.canonical, tb2.meta_title, tb2.meta_description, tb2.description, tb2.content',
            'table' => $explode[0].' as tb1',
            'join' => $importSQL['join'],
            'where' => [
                'tb1.deleted_at' => 0,
                'tb1.publish' => 1,
            ],
            'query' => $importSQL['query'],
            'group_by' => 'tb1.id',
            'order_by' => 'tb1.catalogueid asc'
        ],TRUE);

        $html = 'Không tìm thấy kết quả !';
        $page = (int)$param['page'];
        $config['base_url'] = $param['url'];
        $config['base_url'] = str_replace('.html', '', $config['base_url']);
        $config['per_page'] = 10;
        $config['total_rows'] = count($flag);
        if(count($flag) > 0){
            $config = pagination_frontend(['url' => $config['base_url'],'perpage' => $config['per_page']], $config, $page);
            $this->pagination->initialize($config);
            $pagination = $this->pagination->create_links();
            $totalPage = ceil($config['total_rows']/$config['per_page']);
            $page = ($page <= 0)?1:$page;
            $page = ($page > $totalPage)?$totalPage:$page;
            if($page >= 2){
                $canonical = $config['base_url'].'/trang-'.$page.HTSUFFIX;
            }
            $page = $page - 1;
            $flag  = $this->AutoloadModel->_get_where([
                'select' => 'tb1.id, tb1.viewed, tb1.productid, tb2.module, tb1.image, tb1.album ,tb1.price, tb1.price_promotion,tb2.title, tb2.canonical, tb2.meta_title, tb2.meta_description, tb2.description, tb2.content, tb1.rate, tb1.checkin, tb1.star',
                'table' => $explode[0].' as tb1',
                'join' => $importSQL['join'],
                'where' => [
                    'tb1.deleted_at' => 0,
                    'tb1.publish' => 1,
                ],
                'query' => $importSQL['query'],
                'limit' => $config['per_page'],
                'start' => $page * $config['per_page'],
                'group_by' => 'tb1.id',
                'order_by' => 'tb1.catalogueid asc'
            ],TRUE);
            if(isset($flag) && is_array($flag) && count($flag)){
                foreach ($flag as $key => $value) {
                    // $flag[$key]['description'] = base64_decode($value['description']);
                    // $flag[$key]['content'] = base64_decode($value['content']);
                    $flag[$key]['description'] = $value['description'];
                    $flag[$key]['content'] = $value['content'];
                }
            }

            $data = '';
            $data = array(
                'productList' => $flag,
                'style' => 'grid-3',
            );
            $html = view('frontend/homepage/core/productList', $data, ['saveData' => true]);

        }
        return json_encode([
            'html' => base64_encode($html),
            'pagination' => (isset($pagination) ? $pagination : '')
        ]);die();
    }
    public function detail_item(){
        $id = $this->request->getPost('id');
        $catid = $this->request->getPost('catid');
        $where = [
            'tb1.publish' => 1,
            'tb2.module' => 'article',
            'tb2.language' => $this->currentLanguage(),
            'tb1.id' => $id,
        ];
        if(!empty($catid)){
            $where['tb1.catalogueid'] = $catid;
        }
        $data = $this->AutoloadModel->_get_where([
            'select' => 'tb1.id, tb1.album, tb2.description, tb2.title',
            'table' => 'article as tb1',
            'join' => [
                ['article_translate as tb2','tb2.objectid = tb1.id', 'inner'],
            ],
            'where' => $where,
        ], false);
        $html_select = '';

        if($catid != 0){
            $articleList = $this->AutoloadModel->_get_where([
                'select' => 'tb1.id, tb2.title',
                'table' => 'article as tb1',
                'join' => [
                    ['article_translate as tb2','tb2.objectid = tb1.id', 'inner'],
                ],
                'where' => [
                    'tb1.publish' => 1,
                    // 'tb1.delete_at' => 0,
                    'tb2.module' => 'article',
                    'tb2.language' => $this->currentLanguage(),
                    'tb1.catalogueid' => $catid,
                ],
            ], true);

            $artList = [];
            if(isset($articleList) && is_array($articleList) && count($articleList)){
                $artList =  convert_array_no_select([
                    'data' => $articleList,
                    'field' => 'id',
                    'value' => 'title',
                ]);
            }
            if(isset($artList) && is_array($artList) && count($artList)){
                $dem = 0;
                foreach ($artList as $key => $value) {
                    $html_select = $html_select .' <option value="'.$key.'" '.($id == $key ? 'selected=""' : (!isset($data) || !is_array($data) || count($data) == 0 ? ($dem == 0 ? 'selected' : '') : '')).'>'.$value.'</option> '; 
                    if($dem == 0)    $where['tb1.id'] = $key;
                    $dem ++;
                }
                $where['tb1.catalogueid'] = $catid;
                $data = $this->AutoloadModel->_get_where([
                    'select' => 'tb1.id, tb1.album, tb2.description, tb2.title',
                    'table' => 'article as tb1',
                    'join' => [
                        ['article_translate as tb2','tb2.objectid = tb1.id', 'inner'],
                    ],
                    'where' => $where,
                ], false);
            }
        }

        
        $html = '';
        $class_1 = '';
        $class_2 = '';
        if( isset($data) && is_array($data) && count($data)){
            $data['album'] = json_decode($data['album']);
            $data['description'] = base64_decode($data['description']);
            if( isset($data['album']) && is_array($data['album']) && count($data['album'])){
                $class_1 = 'dt_overview_sw_sm_1_'.$data['id'].'';
                $class_2 = 'dt_overview_sw_1_'.$data['id'].'';
                $html= $html.'<div class="panel-slide mb30">';
                    $html= $html.'<div class="swiper-container mySwiper2 '.$class_2.'">';
                        $html= $html.'<div class="swiper-wrapper">';
                             foreach($data['album'] as $keyA => $valA){
                            $html= $html.'<div class="swiper-slide">';
                                $html= $html.'<div class="img-cover image">';
                                    $html= $html.'<img src="'.$valA.'" alt="'.$data['title'].'">';
                                $html= $html.'</div>';
                            $html= $html.'</div>';
                            }
                        $html= $html.'</div>';
                    $html= $html.'</div>';
                $html= $html.'<div class="description">';
                    $html= $html.$data['description'];
                $html= $html.'</div>';
            }
        }
        echo json_encode([
            'html'=>$html,
            'html_select'=> $html_select ,
            'class_1'=>$class_1,
            'class_2'=>$class_2,
        ]);die();
    }

    private function create_query($param = []){
        $find = [];
        $querySQL = '';
        $explode = explode('_', $param['module']);
        if(isset($param['cat']) && is_array($param['cat']) && count($param['cat'])){
            $find['location'] = $this->find_by_location($param);
        }
        if(isset($param['catalogue']) && is_array($param['catalogue']) && count($param['catalogue'])){
            $find['catalogue'] = $this->find_by_catalogue($param);
        }
        if(isset($param['brand']) && is_array($param['brand']) && count($param['brand'])){
            $find['brand'] = $this->find_by_brand($param);
        }
        if(isset($param['star']) && is_array($param['star']) && count($param['star'])){
            $find['star'] = $this->find_by_star($param);
        }
        if(isset($param['price']) && is_array($param['price']) && count($param['price'])){
            $find['price'] = $this->find_by_price($param);
        }
        if(isset($param['array']) && is_array($param['array']) && count($param['array'])){
            $find['attribute'] = $this->find_by_attribute($param);
        }
        if(isset($find) && is_array($find) && count($find)){
            $count = 1;
            foreach ($find as $key => $value) {
                $querySQL = $querySQL.$value.(($count == count($find) ? '' : ' AND '));
                $count++;
            }
        }
        $join = $this->query_join($find, $explode[0], $param);

        return [
            'query' => $querySQL,
            'join' => $join
        ];
    }

    // Tìm kiếm vị trí
    //
    private function find_by_location($param = []){
        $explode = explode('_', $param['module']);
        $query = '( ';
        foreach ($param['cat'] as $key => $value) {
            $query = $query.(($key == 0) ? '' : 'OR').' location_relationship.catalogueid = \''.$value.'\' ';
        }
        $query = $query.' )';
        return $query;
    }

    private function find_by_brand($param = []){
        $explode = explode('_', $param['module']);
        $query = '( ';
        foreach ($param['brand'] as $key => $value) {
            $query = $query.(($key == 0) ? '' : 'OR ').'tb1.brandid = \''.$value.'\' ';
        }
        $query = $query.' )';
        return $query;
    }

    private function find_by_star($param = []){
        $explode = explode('_', $param['module']);
        $query = '( ';
        foreach ($param['star'] as $key => $value) {
            $query = $query.(($key == 0) ? '' : 'OR ').'tb1.star = \''.$value.'\' ';
        }
        $query = $query.' )';
        return $query;
    }

    private function find_by_catalogue($param = []){
        $explode = explode('_', $param['module']);
        $query = '( ';
        foreach ($param['catalogue'] as $key => $value) {
            $query = $query.(($key == 0) ? '' : 'OR ').'tb1.catalogueid = \''.$value.'\' ';
        }
        $query = $query.' )';
        return $query;
    }

    // Tìm kiếm giá

    private function find_by_price($param = []){
        $explode = explode('_', $param['module']);
        $query = '( ';
        foreach ($param['price'] as $key => $value) {
            $price_explode = explode('-', $value);
            $query = $query.(($key == 0) ? '' : ' OR ').(($price_explode[0] == 'min') ? '( ' : '( tb1.price >= '.$price_explode[0]).(($price_explode[1] == 'max') ? ' )' : (($price_explode[0] == 'min') ? '' : ' AND ').' tb1.price <= '.$price_explode[1].' )');
        }
        $query = $query.' )';
        return $query;
    }

    // Tìm kiếm theo thuộc tính

    private function find_by_attribute($param = []){
        $query = '';
        if(isset($param['array']) && is_array($param['array']) && count($param['array'])){
            foreach ($param['array'] as $key => $value) {
                foreach ($value as $keyChild => $valChild) {
                    $flag[$valChild['name']][] = $valChild['value'];
                }
                // $query = $query.(($key == 0) ? '' : 'OR').' attribute_relationship.attributeid = \''.$value.'\' ';
            }
            $index = 100;
            $count = 0;
            foreach ($flag as $key => $value) {
                $query = $query.(($count != 0) ? ' AND ' : '').' ( ';

                foreach ($value as $keyChild => $valChild) {
                    $query = $query.' tb'.$index.'.attributeid =  '.$valChild.' OR ';
                }
                $query = substr( $query,  0, strlen($query) -3 );
                $query = $query.' ) ';
                $count++;
                $index ++;
            }
        }
        return $query;
    }


    private function query_join($param = [], $module = '', $data = []){
        $join = [
            [
                $module.'_translate as tb2','tb1.id = tb2.objectid AND tb2.module = \''.$module.'\' AND tb2.language = \''.$this->currentLanguage().'\' ','inner'
            ]
        ];
        $param_join = [];
        if(isset($param) && is_array($param) && count($param)){
            foreach ($param as $key => $value) {
                if($key == 'location'){
                    $param_join = [
                        'location_relationship', 'tb1.id = location_relationship.objectid AND location_relationship.module ="location_catalogue" AND location_relationship.attribute = "end"', 'inner'
                    ];
                    array_push($join,$param_join );
                }

            }
        }
        $query = '';
        $json = [];
        if(isset($data['array']) && is_array($data['array']) && count($data['array'])){
            foreach ($data['array'] as $key => $value) {
                foreach ($value as $keyChild => $valChild) {
                    $flag[$valChild['name']][] = $valChild['value'];
                }
            }
            $index = 100;
            foreach ($flag as $key => $value) {
                foreach ($value as $keyChild => $valChild) {
                    $json = ['attribute_relationship as tb'.$index, 'tb1.id = tb'.$index.'.objectid ', 'inner'];
                    array_push($join,$json );
                }
                $index++;
            }
            $join = array_unique($join, SORT_REGULAR);
            $array = [];
            $dem = 0;
            foreach ($join as $key => $value) {
                $array[$dem] = $value;
                $dem++;
            }
            return $array;
        }
        return $join;
    }
}
