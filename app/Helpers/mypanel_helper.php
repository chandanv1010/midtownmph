<?php
use App\Models\AutoloadModel;

if (! function_exists('get_panel')){
    function get_panel( $param = []){
        $model = new AutoloadModel();
        $object = $model->_get_where([
           'select' => 'keyword, title, description, locate, module, catalogue, type_data, canonical, image',
            'table' => 'website_panel',
            'where' => ['language' => $param['language'],'locate' => $param['locate'], 'deleted_at' => 0 ]
        ],TRUE);
        if(isset($object) &&  is_array($object)  && count($object)){
            foreach ($object as $key => $value) {
                $module_explode = explode("_", $value['module']);
                $select  = '';$select_cat='';
                if($module_explode[0] == 'tour' || $module_explode[0] == 'product'){
                    $select = 'tb1.price, tb1.price_promotion';
                    $select_cat = 'tb3.price, tb3.price_promotion';
                }

                if($module_explode[0] == 'tour'){
                    $select = $select . ', tb1.time_end, tb2.start_at, tb2.end_at,';
                    $select_cat = $select_cat.',tb3.time_end, tb2.start_at, tb2.end_at';
                }
                if(isset($module_explode[1]) && $module_explode[1] != ''){
                    $object[$key]['data'] = panel_catalogue($object, $value, $select_cat, $module_explode, $param, $key);
                }else if($value['module'] == '0'){
                    $object[$key]['data'] = [];
                }else{
                	$object[$key]['data'] = panel_post($object, $value, $select, $module_explode, $param, $key);
                }
            }
            // render data
            $temp = [];
            foreach ($object as $key => $value) {
                $temp[$value['keyword']] = $value;
            }
            return $temp;
        }

        return $object;
        
    }
}

if (! function_exists('panel_post')){
    function panel_post($object = [], $value = [], $select = '', $module_explode = [], $param = [], $key = ''){
        $model = new AutoloadModel();
        $iframe = '';
        if($module_explode[0] == 'media'){
            $iframe = 'tb2.iframe,';
        }else if($module_explode[0] == 'brand'){
            $select = $select.', MIN(tb55.price_promotion) as min_price_promo, MIN(tb55.price) as min_price';
        }
        if($value['module'] == 'product'){
            $select = $select.',tb1.rate';
        }
        $value['catalogue'] = json_decode($value['catalogue']);

        $join[] = [
            $module_explode[0].'_translate as tb2','tb1.id = tb2.objectid AND tb2.language = \''.$param['language'].'\' AND tb2.module = \''.$module_explode[0].'\'','inner'
        ];
        if ($module_explode[0] == 'brand') {
            $join[] = [
                'product as tb55','tb1.id = tb55.brandid','left'
            ];
        }

        $data = $model->_get_where([
            'select' => 'tb1.id, tb2.module, tb1.image, tb1.catalogueid,tb2.description, tb1.album,'.$iframe.$select.',  tb2.title, tb2.meta_description, tb2.canonical',
            'table' => $module_explode[0].' as tb1',
            'join' => $join,
            'where' => ['tb1.deleted_at' => 0, 'tb1.publish' => 1],
            'where_in_field' => 'tb1.id',
            'where_in' => $value['catalogue'],
            'limit' => 8,
            'order_by' => 'tb1.order desc, tb1.created_at desc'
        ],TRuE);
        if(isset($data) &&  is_array($data)  && count($data)){
            foreach ($data as $keyData => $valData) {
                $data[$keyData]['description'] = base64_decode($valData['description']);
                $data[$keyData]['canonical'] = write_url($valData['canonical']);
                $data[$keyData]['album'] = json_decode($valData['album']);
                 if($select != ''){
                    $data[$keyData]['price'] = number_format($valData['price'],0,',','.');
                    $data[$keyData]['price_promotion'] = number_format((float)$valData['price_promotion'],0,',','.');
                }
                if(isset($data[$keyData]['image']) && $data[$keyData]['image'] != ''){
                    $data[$keyData]['avatar'] = $data[$keyData]['image'];
                }else{
                    $data[$keyData]['avatar'] = $data[$keyData]['album'][0];
                }
            }
        }

        return $data;
    }
}


if (! function_exists('panel_catalogue')){
    function panel_catalogue($object = [], $value = [], $select_cat = '', $module_explode = [], $param = [], $key = ''){
        $model = new AutoloadModel();
        $type = json_decode($value['type_data']);
        $data = [];
        if(isset($type[1]) && $type[1] != ''){
        	$data = post_and_cat($object, $value, $select_cat, $module_explode, $param, $key);
        }
        if(!isset($type[1]) && $type[0] == 'only_cat'){
        	$data = only_cat($object, $value, $select_cat, $module_explode, $param, $key);
        }
        if(!isset($type[1]) && $type[0] == 'only_post'){
        	$data = only_post($object, $value, $select_cat, $module_explode, $param, $key);
        }
        if(!isset($type[1]) && $type[0] == 'normal'){
            $data = only_select_cat($object, $value, $select_cat, $module_explode, $param, $key);
        }

        return $data;
    }
}


if (! function_exists('recursive')){
    function recursive($array = '', $parentid = 0){
        $temp = [];
        if(isset($array) && is_array($array) && count($array)){
            foreach($array as $key => $val){
                if($val['parentid'] == $parentid){
                    $temp[] = $val;
                    if(isset($temp) && is_array($temp) && count($temp)){
                        foreach($temp as $keyTemp => $valTemp){
                            $temp[$keyTemp]['children'] = recursive($array, $valTemp['id']);
                        }
                    }

                }
            }
        }
        return $temp;
    }
}

if (! function_exists('post_and_cat')){
    function post_and_cat($object = [], $value = [], $select_cat = '', $module_explode = [], $param = [], $key = ''){
        $model = new AutoloadModel();
        $data = only_cat($object, $value, $select_cat, $module_explode, $param, $key);
        $recursive = recursive($data);
        $id = [];
        foreach ($data as $key => $value) {
        	$id[$key] = $value['id'];
        	$data[$key]['post'] = [];
        }
        if($module_explode[0] == 'product'){
            $select_cat = $select_cat.',tb3.rate';
        }

        $id_list = [];
        if(isset($recursive) && is_array($recursive) && count($recursive)){
            foreach($recursive as $key => $val){
                if(isset($val['children']) && is_array($val['children']) && count($val['children'])){
                    foreach($val['children'] as $keyS => $valS){
                        $id_list[] = $valS['id'];
                    }
                }
            }
        }
        $mang = $model->_get_where([
            'select' => 'tb3.title, tb1.id, tb1.catalogueid, tb3.module, tb3.canonical, tb1.image, tb1.album, tb3.content, tb3.description, tb1.price, tb1.price_promotion',
            'table' => 'product as tb1',
            'join' => [
                [
                    'product_catalogue as tb2', 'tb2.id = tb1.catalogueid', 'inner'
                ],
                [
                    'product_translate as tb3', 'tb3.objectid = tb1.id', 'inner'
                ]
            ],
            'where' => [
                'tb1.publish' => 1,
                'tb1.deleted_at' => 0,
                'tb2.deleted_at' => 0,
                'tb3.module' => 'product',
            ],
            'where_in' => $id_list,
            'where_in_field' => 'tb1.catalogueid',
            'query' => '
                (
                    SELECT COUNT(*)
                    FROM product as t
                    WHERE tb1.catalogueid = t.catalogueid
                    AND tb1.id <= t.id AND tb1.deleted_at = 0
                ) <= 4
            ',
            'order_by' => 'id desc'
        ], TRUE);
        if(isset($mang) &&  is_array($mang)  && count($mang)){
            foreach ($mang as $keymang => $valmang) {
                $mang[$keymang]['canonical'] = $valmang['canonical'];
                $mang[$keymang]['album'] = json_decode($valmang['album']);
                $mang[$keymang]['content'] = strip_tags(base64_decode($valmang['content']));
                // $mang[$keymang]['description'] = strip_tags(base64_decode($valmang['description']));
                if($select_cat != ''){
                    // $mang[$keymang]['price'] = number_format($valmang['price'],0,',','.');
                    // $mang[$keymang]['price_promotion'] = number_format((float)$valmang['price_promotion'],0,',','.');
                }
                if(isset($mang[$keymang]['image']) && $mang[$keymang]['image'] != ''){
                    $mang[$keymang]['avatar'] = $mang[$keymang]['image'];
                }else{
                    $mang[$keymang]['avatar'] = $mang[$keymang]['album'][0];
                }
            }
        }
        if(isset($recursive) && is_array($recursive) && count($recursive)){
            foreach ($recursive as $key => $value) {
                if(isset($value['children']) && is_array($value['children']) && count($value['children'])){
                    foreach($value['children'] as $keyC => $valC){
                        foreach ($mang as $keyMang => $valueMang) {
                    		if($valC['id'] == $valueMang['catalogueid']){
                    			$recursive[$key]['children'][$keyC]['post'][] = $valueMang;
                    		}
                    	}
                    }
                }

            }
        }
        return $recursive;
    }
}

if (! function_exists('only_post')){
    function only_post($object = [], $value = [], $select_cat = '', $module_explode = [], $param = [], $key = ''){
        $model = new AutoloadModel();
        // $value['catalogue'] = json_decode($value['catalogue']);
        $data = only_cat($object, $value, $select_cat, $module_explode, $param, $key);
        foreach ($data as $key => $value) {
            $id[$key] = $value['id'];
            $data[$key]['post'] = [];
        }

        if($module_explode[0] == 'product'){
            $select_cat = $select_cat.',tb3.rate';
        }else if($module_explode[0] == 'media'){
            $select_cat = 'tb2.iframe';
        }else if($module_explode[0] == 'brand'){
            $select_cat = $select_cat.', tb2.slogan, tb2.description, MIN(tb55.price_promotion) as min_price_promo, MIN(tb55.price) as min_price';
        }

        $join[] = [
            $module_explode[0].'_translate as tb2','tb2.module = \''.$module_explode[0].'\' AND tb2.objectid = tb1.objectid AND tb2.language = \''.$param['language'].'\'','inner'
        ];
        $join[] = [
            $module_explode[0].' as tb3', 'tb1.objectid = tb3.id','inner'
        ];
        if ($module_explode[0] == 'brand') {
            $join[] = [
                'product as tb55','tb1.id = tb55.brandid','left'
            ];
        }

        $data = $model->_get_where([
            'select' => 'tb1.catalogueid, tb3.id, tb2.module, '.$select_cat.', tb2.title,tb2.description, tb2.content, tb2.canonical, tb3.album, tb3.image',
            'table' => 'object_relationship as tb1',
            'join' => $join,
            'where' => ['tb1.module' => $module_explode[0], 'tb3.deleted_at' => 0, 'tb3.publish' => 1],
            'where_in_field' => 'tb1.catalogueid',
            'where_in' => $id,
            'group_by' => 'tb3.id',
            'limit' => 8,
            'order_by' => 'tb3.order desc, tb3.created_at desc'
        ],TRuE);
        if(isset($data) &&  is_array($data)  && count($data)){
            foreach ($data as $keyData => $valData) {
                $data[$keyData]['canonical'] = $valData['canonical'];
                $data[$keyData]['album'] = json_decode($valData['album']);
                $data[$keyData]['content'] = strip_tags(base64_decode($valData['content']));
                // $data[$keyData]['description'] = strip_tags(base64_decode($valData['description']));
                if($select_cat != '' && $module_explode[0] == 'product'){
                    $data[$keyData]['price'] = number_format($valData['price'],0,',','.');
                    $data[$keyData]['price_promotion'] = number_format($valData['price_promotion'],0,',','.');
                }
                if(isset($data[$keyData]['image']) && $data[$keyData]['image'] != ''){
                    $data[$keyData]['avatar'] = $data[$keyData]['image'];
                }else{
                    $data[$keyData]['avatar'] = $data[$keyData]['album'][0];
                }
            }
        }
        return $data;
    }
}

if (! function_exists('only_cat')){
    function only_cat($object = [], $value = [], $select_cat = '', $module_explode = [], $param = [], $key = ''){
        $model = new AutoloadModel();
        $value['catalogue'] = json_decode($value['catalogue']);
        $data = $model->_get_where([
            'select' => 'id, lft, rgt',
            'table' => $module_explode[0].'_catalogue',
            'where' => [
                'deleted_at' => 0
            ],
            'where_in_field' => 'id',
            'where_in' => $value['catalogue'],
            'group_by' => 'id'
        ],TRuE);
        $query  =  query_get_cat($data);
        $child = $model->_get_where([
            'select' => 'tb1.id,  tb2.title, tb2.canonical, tb1.image, tb2.description, tb1.parentid, tb1.created_at',
            'table' => $module_explode[0].'_catalogue as tb1',
            'join' =>[
                [
                    $module_explode[0].'_translate as tb2','tb2.module = \''.$module_explode[0].'_catalogue\' AND tb2.objectid = tb1.id AND tb2.language = \''.$param['language'].'\'','inner'
                ]
            ],
            'where' => [
            	'tb1.deleted_at' => 0,
            	'tb1.publish' => 1
            ],
            'query' =>  $query,
            'group_by' => 'tb1.id',
            'order_by' => 'tb1.order desc, tb1.id asc'
        ],TRuE);
        if(isset($child) &&  is_array($child)  && count($child)){
            foreach ($child as $keyData => $valData) {
                $child[$keyData]['canonical'] = $valData['canonical'];
                // $child[$keyData]['description'] = base64_decode($valData['description']);
                if($child[$keyData]['image'] == ''){
                    $child[$keyData]['image'] = 'public/not-found.png';
                }
            }
        }
        return $child;
    }
}

if (! function_exists('only_select_cat')){
    function only_select_cat($object = [], $value = [], $select_cat = '', $module_explode = [], $param = [], $key = ''){
        $model = new AutoloadModel();
        $value['catalogue'] = json_decode($value['catalogue']);
        $data = $model->_get_where([
            'select' => 'id, lft, rgt',
            'table' => $module_explode[0].'_catalogue',
            'where' => [
                'deleted_at' => 0
            ],
            'where_in_field' => 'id',
            'where_in' => $value['catalogue'],
            'group_by' => 'id'
        ],TRuE);
        $query = '';
        foreach ($data as $key => $value) {
            $query = $query.'( tb1.lft = \''.$value['lft'].'\' AND tb1.rgt = \''.$value['rgt'].'\' AND tb1.deleted_at = 0)';
            if($key < count($data)-1){
                $query = $query.' OR ';
            }
        }
        $child = $model->_get_where([
            'select' => 'tb1.id,  tb2.title, tb2.canonical, tb1.image, tb2.description',
            'table' => $module_explode[0].'_catalogue as tb1',
            'join' =>[
                [
                    $module_explode[0].'_translate as tb2','tb2.module = \''.$module_explode[0].'_catalogue\' AND tb2.objectid = tb1.id AND tb2.language = \''.$param['language'].'\'','inner'
                ]
            ],
            'where' => [
                'tb1.deleted_at' => 0,
                'tb1.publish' => 1
            ],
            'query' =>  $query,
            'group_by' => 'tb1.id',
            'order_by' => 'tb1.order desc, tb1.id asc'
        ],TRuE);
        if(isset($child) &&  is_array($child)  && count($child)){
            foreach ($child as $keyData => $valData) {
                $child[$keyData]['canonical'] = write_url($valData['canonical']);
                $child[$keyData]['description'] = base64_decode($valData['description']);
                if($child[$keyData]['image'] == ''){
                    $child[$keyData]['image'] = 'public/not-found.png';
                }
            }
        }
        return $child;
    }
}


if (! function_exists('query_get_cat')){
    function query_get_cat($param = []){
    	$query = '';
    	foreach ($param as $key => $value) {
    		$query = $query.'( tb1.lft >= \''.$value['lft'].'\' AND tb1.rgt <= \''.$value['rgt'].'\' AND tb1.deleted_at = 0)';
    		if($key < count($param)-1){
    			$query = $query.' OR ';
    		}
    	}
        return $query;
    }
}


?>
