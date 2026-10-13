<?php


global $g_currency;

$g_currency = !empty($_COOKIE[AUTH_CURRENCY])? json_decode($_COOKIE[AUTH_CURRENCY], true): [];

use App\Models\AutoloadModel;
use App\Controllers\BaseController;
use App\Controllers\FrontendController;

if (! function_exists('fe_currentLanguage')){
    function fe_currentLanguage(){
        // $FrontendController = new FrontendController();
        return request()->getLocale();
    }
}

if (! function_exists('be_currentLanguage')){
    function be_currentLanguage(){
        $BaseController = new BaseController();
        return $BaseController->currentLanguage();
    }
}

if (! function_exists('count_contact')){
    function count_contact(){
        $model = new AutoloadModel();
        $count  = $model->_get_where([
        	'select' => 'tb1.id',
			'table' => 'contact as tb1',
			'where' => [
				'tb1.deleted_at' => 0,
				'tb1.seen' => 0
			],
			'join' => [
				[
					'contact_translate as tb2','tb1.id = tb2.objectid AND tb2.module = "contact" ','inner'
				],

			],
			'count' => TRUE,
        ]);
        return $count;
    }
}

/*
    tb1 as module
    tb2 as module_translate
*/
if(!function_exists('dt_get_query_module')){
    function dt_get_query_module($module = 'article_catalogue') {
        $query = 'tb1.id, tb1.image, tb1.created_at
            , tb2.title, tb2.canonical, tb2.description, tb2.content, tb2.meta_title, tb2.meta_description
        ';
        $module_explode = explode('_', $module);
        // if module_catalogue
        if (count($module_explode) > 1) {
            $query .= ', tb1.lft, tb1.rgt, tb1.parentid, tb1.level, tb1.icon';
        }
        if ($module == 'product') {
            $query .= ', tb1.price, tb1.price_promotion, tb1.area, tb1.bed, tb1.extra';
        }else if ($module == 'media_catalogue') {
            $query .= ', tb2.iframe';
        }else if ($module == 'media') {
            $query .= ', tb2.iframe';
        }

        if ($module == 'currency') {
            $query = 'tb1.*, tb2.title, tb2.slug';
        }

        return $query;
    }
}

if(!function_exists('get_object_catalogue')){
    function get_object_catalogue($param = []) {
        $model = new AutoloadModel();

        $module = ($param['module']) ?? 'article_catalogue';
        $language = ($param['language']) ?? fe_currentLanguage();

        $post = ($param['post']['flag'])?? FALSE;
        $limit_post = ($param['post']['limit'])?? 100;

        $children = ($param['children']['flag'])?? FALSE;
        $children_post = ($param['children']['post'])?? FALSE;

        $limit_children = ($param['children']['limit'])?? 100;
        $limit_children_post = ($param['children']['post_limit'])?? 100;

        $where = [
            'tb1.deleted_at' => 0,
            'tb1.publish' => 1,
            // 'tb2.language' => request()->getLocale(),
        ];

        $where = isset($param['where']) ? array_merge($param['where'], $where) : $where;
        $module_extract = explode("_", $module);
        $detailCatalogue = $model->_get_where([
            'select' => dt_get_query_module($module),
            'table' => $module.' as tb1',
            'join' => [
                [
                    $module_extract[0].'_translate as tb2','tb2.module = \''.$module.'\' AND tb2.objectid = tb1.id AND tb2.language = \''.$language.'\'', 'inner'
                ]
            ],
            'where' => $where,
        ]);

        if (check_array($detailCatalogue)) {
            if ($children == true) {
                $cat_child = $model->_get_where([
                    'select' => dt_get_query_module($module),
                    'table' => $module.' as tb1',
                    'join' => [
                        [
                            $module_extract[0].'_translate as tb2','tb1.id = tb2.objectid AND tb2.module = "'.$module.'" AND tb2.language = \''.$language.'\' ','inner'
                        ]
                    ],
                    'where' => [
                        'tb1.deleted_at' => 0,
                        'tb1.publish' => 1,
                        'tb1.lft > ' => $detailCatalogue['lft'],
                        'tb1.rgt < ' => $detailCatalogue['rgt'],
                    ],
                    'limit' => $limit_children,
                    'order_by'=> 'tb1.order desc, tb1.id desc',
                ], true);

                if ($children_post == true) {
                    foreach ($cat_child as $key => $val) {
                        $catalogue = dt_condition_catalogue([
                            'catalogueid' => $val['id'],
                            'module' => $module,
                        ]);
                        $cat_child[$key]['post'] = $model->_get_where([
                            'select' => dt_get_query_module($module_extract[0]),
                            'table' => $module_extract[0].' as tb1',
                            'where' => [
                                'tb1.deleted_at' => 0,
                                'tb1.publish' => 1
                            ],
                            'where_in' => $catalogue['where_in'],
                            'where_in_field' => $catalogue['where_in_field'],
                            'join' => [
                                [
                                    $module_extract[0].'_translate as tb2','tb1.id = tb2.objectid AND tb2.module = "'.$module_extract[0].'" AND tb2.language = \''.$language.'\' ','inner'
                                ],
                                [
                                    'object_relationship as tb3', 'tb1.id = tb3.objectid AND tb3.module = \''.$module_extract[0].'\' ', 'inner'
                                ],

                            ],
                            'limit' => $limit_children_post,
                            'order_by'=> 'tb1.order desc, tb1.id desc',
                            'group_by' => 'tb1.id'
                        ], TRUE);
                    }
                }

                $detailCatalogue['children'] = $cat_child;
            }

            // print_r($detailCatalogue); exit;
            if ($post == true) {
                $catalogue = dt_condition_catalogue([
                    'catalogueid' => $detailCatalogue['id'],
                    'module' => $module,
                    'language' => $language,
                ]);

                $detailCatalogue['post'] = $model->_get_where([
                    'select' => dt_get_query_module($module_extract[0]),
                    'table' => $module_extract[0].' as tb1',
                    'where' => [
                        'tb1.deleted_at' => 0,
                        'tb1.publish' => 1
                    ],
                    'where_in' => $catalogue['where_in'],
                    'where_in_field' => $catalogue['where_in_field'],
                    'join' => [
                        [
                            $module_extract[0].'_translate as tb2','tb1.id = tb2.objectid AND tb2.module = "'.$module_extract[0].'" AND tb2.language = \''.$language.'\' ','inner'
                        ],
                        [
                            'object_relationship as tb3', 'tb1.id = tb3.objectid AND tb3.module = \''.$module_extract[0].'\' ', 'inner'
                        ],

                    ],
                    'limit' => $limit_post,
                    'order_by'=> 'tb1.order desc, tb1.id desc',
                    'group_by' => 'tb1.id'
                ], TRUE);
            }
        }

        return $detailCatalogue;
    }
}

if (! function_exists('dt_condition_catalogue')){
    function dt_condition_catalogue($param = []){
        $model = new AutoloadModel();

        $catalogueid = ($param['catalogueid']) ?? 0;
        $module = ($param['module']) ?? '';
        $language = ($param['language']) ?? fe_currentLanguage();

        $id = [];
        $module_extract = explode("_", $module);
        if($catalogueid > 0){
            $catalogue = $model->_get_where([
                'select' => dt_get_query_module($module_extract[0].'_catalogue'),
                'table' => $module_extract[0].'_catalogue as tb1',
                'join' =>  [
                    [
                        $module_extract[0].'_translate as tb2','tb1.id = tb2.objectid AND tb2.language = \''.$language.'\' AND tb2.module = "'.$module.'"','inner'
                    ],
                ],
                'where' => [
                    'tb1.id' => $catalogueid
                ],
            ]);

            $catalogueChildren = $model->_get_where([
                'select' => 'id',
                'table' => $module_extract[0].'_catalogue',
                'where' => [
                    'deleted_at' => 0,
                    'publish' => 1,
                    'lft >=' => $catalogue['lft'],
                    'rgt <=' => $catalogue['rgt'],
                ],
            ], TRUE);

            $id = array_column($catalogueChildren, 'id');
        }
        return [
            'where_in' => $id,
            'where_in_field' => 'tb3.'.(($param['where_in_field'])?? 'catalogueid'),
        ];
    }
}

if (! function_exists('currentLanguage')){
    function currentLanguage(){
        $FrontendController = new FrontendController();
        return $FrontendController->currentLanguage();
    }
}


if (! function_exists('dt_render_currency')){
    function dt_render_currency($price = 0){

        $_price = get_price_convert([
            'price_init' => $price,
        ]);

        return $_price;

    	// $price = number_format($price);
    	// return $price.' '.CURRENCY;
    }
}
if (! function_exists('dt_render_currency')){
    function dt_render_currency_lease($price = 0){
    	$price = number_format($price);
    	return $price.' '.CURRENCY.'/'.PER;
    }
}

if (! function_exists('get_data')){
	function get_data(array $param = []){
		$model = new AutoloadModel();

		$where = [];
		if(isset($param['where'])){
			$where = $param['where'];
		}
	 	$object = $model->_get_where([
            'select' => $param['select'],
            'table' => $param['table'],
            'where' => $where,
            'order_by' => $param['order_by']
        ], TRUE);
	 	return $object;
	}
}


if(!function_exists('cal_percent')){
    function cal_percent($param = []){
		if($param['sum'] > 0){
			$result_present = $param['present']/$param['sum'] * 100;
	       	$result_ago = $param['ago']/$param['sum'] * 100;
	       	$result = $result_present - $result_ago;
	        return round($result,2);
		}else{
			return 0;
		}

    }
}

if (! function_exists('delete_router')){
	function delete_router($id = '', $module = '', $language = ''){
		$model = new AutoloadModel();

	 	$object = $model->_update([
            'table' => 'router',
            'data' => [
            	'canonical' => '',
            ],
            'where' => [
            	'language' => $language,
            	'objectid' => $id,
            	'module' => $module,
            ],
        ]);
	 	return $object;
	}
}

if (! function_exists('delete_catalogue')){
	function delete_catalogue(array $param = []){
		$model = new AutoloadModel();
		$module = explode('_',  $param['module']);
		$flag = 0;
		$catid = $model->_get_where([
			'select' => 'id',
			'table' => $param['module'],
           	'where' => [
           		'lft >=' => $param['lft'],
				'rgt <=' => $param['rgt'],
				'deleted_at' => 0
           	]
		], TRUE);
		$router  = $catid;
		if(isset($catid) && is_array($catid) && count($catid)){
			foreach ($catid as $key => $value) {
				$catid[$key]['deleted_at'] = 1;
			}

			$flag = $model->_update_batch([
				'table' => $param['module'],
				'data' => $catid,
				'field' => 'id'
			]);
			foreach ($router as $key => $value) {
				$model->_update([
		            'table' => 'router',
		            'data' => [
		            	'canonical' => ''
		            ],
		            'where' => [
		            	'objectid' => $value['id'],
		            	'language' => $param['language'],
		            	'module' => $param['module']
		            ]
		        ]);
			}
			if($flag > 0){
				$id = [];
				foreach ($catid as $key => $value) {
					$id[] = $value['id'];
				}
				$store = $model->_get_where([
					'select' => 'id',
					'table' => $module[0],
		           	'where_in' => $id,
		           	'where_in_field' => 'catalogueid',
		           	'where' => [
		           		'deleted_at' => 0
		           	]
				],TRUE);
				$router_blog = $store;
				if(isset($store) && is_array($store) && count($store)){
					foreach ($store as $key => $value) {
						$store[$key]['deleted_at'] = 1;
					}
					$result = $model->_update_batch([
						'table' => $module[0],
						'data' => $store,
						'field' => 'id'
					]);
					foreach ($router_blog as $key => $value) {
						$model->_update([
				            'table' => 'router',
				            'data' => [
				            	'canonical' => ''
				            ],
				            'where' => [
				            	'objectid' => $value['id'],
				            	'language' => $param['language'],
				            	'module' => $module[0]
				            ]
				        ]);
					}
				}
			}
		}
		return $flag;
	}
}

if (! function_exists('check_type_canonical')){
	function check_type_canonical($language = ''){
		$model = new AutoloadModel();

	 	$object = $model->_get_where([
            'select' => 'content',
            'table' => 'system_translate',
            'where' => [
            	'language' => $language,
            	'keyword' => 'website_canonical'
            ],
        ]);
	 	return $object;
	}
}

if (! function_exists('get_attribute_catalogue')){
	function get_attribute_catalogue($language, $module){
		$model = new AutoloadModel();

	 	$catalogue = $model->_get_where([
            'select' => ' title, canonical, objectid',
            'table' => 'attribute_translate',
            'where' => [
            	'language' => $language,
            	'module' => 'attribute_catalogue',
            	'module_primary' => $module
            ],
        ], TRUE);
        // pre($catalogue);
	 	return $catalogue;
	}
}

if (! function_exists('get_attribute')){
	function get_attribute($language){
		$model = new AutoloadModel();
	 	$data = $model->_get_where([
            'select' => 'tb1.id, tb1.catalogueid, tb2.title, tb2.canonical',
            'table' => 'attribute as tb1',
           	'join' => [
           		[
           			'attribute_translate as tb2','tb1.id = tb2.objectid AND tb2.language = \''.$language.'\' AND tb2.module = "attribute"','inner'
           		]
           	]
        ], TRUE);
	 	return $data;
	}
}

if (! function_exists('separateArray')){
	function separateArray($param= [], $target=[]){
		$data=[];
		for ($i = 0; $i < count($param);$i++){
			if (isset($param[$i]))
				for ($j = 0; $j < count($target);$j++){
					$data[$i][$target[$j]] = $param[$i][$target[$j]];
				}
			}
		return $data;
	}
}
if(!function_exists('get_id_create_batch')){
	function get_id_create_batch(int $firstid = 0, int $length = 0){
		$data[] = $firstid;
		for($i = 1 ; $i < $length; $i++){
			$data[] = $firstid + $i;
		}

		return $data;
	}
}

if (! function_exists('count_object')){
	function count_object(array $param = []){
		$model = new AutoloadModel();

		$catalogueid = $param['catalogueid'];

		$id = [];
		if($catalogueid > 0){
			$catalogue = $model->_get_where([
				'select' => 'id, lft, rgt, title',
				'table' => $param['module'].'',
				'where' => ['id' => $catalogueid],
			]);

			$catalogueChildren = $model->_get_where([
				'select' => 'id',
				'table' => $param['module'].'',
				'where' => ['lft >=' => $catalogue['lft'],'rgt <=' => $catalogue['rgt']],
			], TRUE);

			$id = array_column($catalogueChildren, 'id');
		}

		$count = 0;
		$module = explode('_',  $param['module']);
		if(isset($id) && is_array($id)  && count($id)){
			$count = $model->_get_where([
				'select' => 'tb1.id',
				'table' => current($module).' as tb1',
				'where' => [
					'tb1.deleted_at' => 0,
					'tb1.publish' => 1,
				],
				'where_in' => $id,
				'where_in_field' => 'tb2.catalogueid',
				'join' => [
					[
						'object_relationship as tb2', 'tb1.id = tb2.objectid AND tb2.module = \''.current($module).'\' ', 'inner'
					],
					[
						'user as tb3','tb1.userid_created = tb3.id','inner'
					]
				],
				'group_by' => 'tb1.id',
				'count' => TRUE
			]);
		}


		return $count;

	}
}

if (! function_exists('convert_code')){
	function convert_code($code = '', $module =''){
		$model = new AutoloadModel();

		$code_Explode = explode('-',  $code );
		$code = (int)'1'.$code_Explode[1];
		$id = $model->_get_where([
			'select' => 'objectid',
			'table' => 'id_general',
			'where' => ['module' => $module]
		]);
		$code  = $code + $id['objectid'];
		$code = substr($code, 1);
		$code = $code_Explode[0].'-'.$code.'-'.$code_Explode[2];
		return $code;
	}
}

if (! function_exists('get_catalogue_object')){
	function get_catalogue_object(array $param = []){
		$model = new AutoloadModel();
		$object = $model->_get_where([
		  	'select' => 'tb1.id, tb4.title',
            'table' => $param['module'].'_catalogue as tb1',
            'join' => [
        		[
					$param['module'].'_translate as tb4','tb1.id = tb4.objectid AND tb4.module = \''.$param['module'].'_catalogue'.'\' AND tb4.language = \''.be_currentLanguage().'\'','inner'
				]
			],
            'where' => ['tb1.deleted_at' => 0],
            'where_in' => $param['catalogue'],
            'where_in_field' => 'tb1.id',
            'order_by' => 'tb4.title asc'
		], TRUE);
		return $object;

	}
}


if (! function_exists('language_switcher')){
	/**
	 * Sinh cac the <a> cua nut chuyen ngon ngu.
	 *
	 * Truoc day moi noi dat nut deu viet cung ba the <a> cho vi/en/tq, kem ba
	 * bien $active_vi/$active_en/$active_tq. Rieng header.php lap lai hai lan
	 * (ban desktop va ban mobile), offcanvas.php mot lan nua - tong cong bon
	 * cho phai sua moi khi them mot ngon ngu, va quen mot cho la nguoi dung
	 * khong bam sang ngon ngu do duoc.
	 *
	 * Nay danh sach lay tu bang `language` (admin bat/tat bang cot publish),
	 * con nhan va tooltip lay tu khoa _LANG_LABEL / _LANG_TITLE trong chinh
	 * file ngon ngu tuong ung. Them ngon ngu = them hang trong admin + them
	 * thu muc app/Language/<ma>/, khong dung toi markup.
	 *
	 * @param string $currentLocale Ma ngon ngu dang xem, de danh dau 'active'.
	 * @return string HTML cac the <a>, hoac chuoi rong neu khong co du lieu.
	 */
	function language_switcher(string $currentLocale = ''): string
	{
		$languages = get_all_language();
		if (!is_array($languages) || !count($languages)) {
			return '';
		}

		// Chi hien ngon ngu da khai bao trong App::$supportedLocales. Thieu
		// buoc nay thi mot hang thua trong bang `language` se sinh ra link 404.
		$supported = config('App')->supportedLocales;

		$html = '';
		foreach ($languages as $row) {
			$code = $row['canonical'] ?? '';
			if ($code === '' || !in_array($code, $supported, true)) {
				continue;
			}

			$html .= '<a href="' . base_url($code) . '"'
				. ' title="' . esc(lang('app._LANG_TITLE', [], $code), 'attr') . '"'
				. ' class="' . (($code === $currentLocale) ? 'active' : '') . '">'
				. esc(lang('app._LANG_LABEL', [], $code))
				. '</a>';
		}

		return $html;
	}
}
if (! function_exists('flag_by_code')){
	/**
	 * Doi danh sach ngon ngu thanh bang tra cuu: ma ngon ngu => anh co.
	 *
	 * Truoc day cac view ban dich lay co theo VI TRI trong mang:
	 *     $flag[0] cho 'vi', $flag[1] cho 'en', $flag[2] cho 'tq'
	 * kem mot chuoi ternary long nhau de chon. Cach do hong theo hai kieu:
	 *   - Them ngon ngu moi (kr, jp) thi khong co nhanh nao khop, co bien mat.
	 *   - Doi thu tu hay xoa mot hang trong bang `language` thi lech het co.
	 *
	 * Tra theo ma ngon ngu thi khong phu thuoc thu tu, va them bao nhieu ngon
	 * ngu cung khong phai sua lai view.
	 *
	 * @param array $languageList Ket qua truy van bang `language`, moi phan tu
	 *                            can co khoa 'canonical' va 'image'.
	 * @return array Mang dang ['vi' => 'anh-co-vi.png', 'kr' => '...']
	 */
	function flag_by_code($languageList): array
	{
		if (!is_array($languageList)) {
			return [];
		}

		$map = [];
		foreach ($languageList as $row) {
			if (isset($row['canonical'])) {
				$map[$row['canonical']] = $row['image'] ?? '';
			}
		}

		return $map;
	}
}
if (! function_exists('get_list_language')){
	function get_list_language(array $param = []){
		$model = new AutoloadModel();

		$language = $model->_get_where([
			'select' => 'id, canonical, image',
			'table' => 'language',
			'where' => ['publish' => 1,'canonical !=' => $param['currentLanguage']]
		], TRUE);

		return $language;
	}
}
if (! function_exists('get_all_language')){
	function get_all_language(){
		$model = new AutoloadModel();

		$language = $model->_get_where([
			'select' => 'id, canonical, image',
			'table' => 'language',
			'where' => ['publish' => 1]
		], TRUE);

		return $language;
	}
}


if (! function_exists('get_full_language')){
	function get_full_language(array $param = []){
		$model = new AutoloadModel();

		$language = $model->_get_where([
			'select' => 'id, canonical, image',
			'table' => 'language',
			'where' => ['publish' => 1]
		], TRUE);

		return $language;
	}
}


if (! function_exists('check_id_exist')){
	function check_id_exist($module = ''){
		$model = new AutoloadModel();

		$count = $model->_get_where([
			'table' => 'id_general',
			'where' => ['module' => $module],
			'count' => TRUE
		], TRUE);

		return $count;
	}
}


if (! function_exists('check_attribute')){
	function check_attribute($canonical = '', $module = ''){
		$model = new AutoloadModel();
		$moduleExplode = explode('_',  $module);
		$count = $model->_get_where([
			'table' => 'attribute_translate',
			'where' => ['module' => $module, 'canonical' => $canonical],
			'count' => TRUE
		], TRUE);

		return $count;
	}
}

if (! function_exists('object_menu')){
	function object_menu($module = '',$translate = 0, $language = ''){
		$model = new AutoloadModel();
		$moduleExplode = explode('_',  $module);
		if(isset($params['translate']) && $params['translate'] == 0){
			$ObjectList = $model->_get_where([
				'select' => 'title, canonical, id',
				'table' => $module,
				'order_by' => 'created_at desc',
				'limit' => 5
			],TRUE);
		}else{
			if(isset($moduleExplode[1])){
				$ObjectList = $model->_get_where([
					'select' => 'tb1.title, tb1.id, tb1.canonical, tb1.module, tb2.parentid as catalogueid, tb1.objectid as objectid',
					'table' => $moduleExplode[0].'_translate as tb1',
					'join' => [
						[
							$module.' as tb2', 'tb2.id = tb1.objectid', 'inner'
						],
					],
					'where' => [
						'tb1.module' => $module,
						'tb1.language' => $language,
						'tb2.deleted_at' => 0
					],
					'order_by' => 'tb1.created_at desc',
					'limit' => 5
				],TRUE);
			}else{
				$ObjectList = $model->_get_where([
					'select' => 'tb1.title, tb1.objectid as objectid, tb1.canonical, tb1.module, tb2.catalogueid as catalogueid',
					'table' => $moduleExplode[0].'_translate as tb1',
					'join' => [
						[
							$module.' as tb2', 'tb2.id = tb1.objectid', 'inner'
						],
					],
					'where' => [
						'tb1.module' => $module,
						'tb1.language' => $language,
						'tb2.deleted_at' => 0
					],
					'order_by' => 'tb1.created_at desc',
					'limit' => 5
				],TRUE);
			}
		}
		return $ObjectList;
	}
}


if (! function_exists('silo')){
	function silo($id = '', $canonical = '', $module = '', $catid = '', $lang = ''){
	    $model = new AutoloadModel();
		$moduleExplode = explode('_',  $module);
	    if($catid == 0){
	        $category = $model->_get_where([
	            'select' => 'id, lft, rgt',
	            'table' => $moduleExplode[0].'_catalogue',
	            'where' => ['parentid' => $catid, 'id' => $id]
	        ]);
	       	$allCategory = $model->_get_where([
	            'select' => 'objectid, canonical, module',
	            'table' => $moduleExplode[0].'_translate',
	            'where' => [
	            	$moduleExplode[0].'_translate.id' => $category['id'],
	            	'module' => $moduleExplode[0].'_catalogue',
	            	'language' => $lang
	            ],
	            'join' => [
	            	[
	            		$moduleExplode[0].'_catalogue', $moduleExplode[0].'_catalogue.id = '.$category['id'].'','inner'
	            	]
	            ],
	            'order_by' => $moduleExplode[0].'_catalogue.lft asc',
	        ], TRUE);
	        $url = '';
	        foreach($allCategory as $key => $val){
	            $url = $url.$val['canonical'].(($key + 1 < count($allCategory)) ? '/' : '');
	        }

	    }else{
	        $category = $model->_get_where([
	            'select' => 'id, lft, rgt, parentid',
	            'table' => $moduleExplode[0].'_catalogue',
	            'where' => ['id' => $catid]
	        ]);
	        if($category['parentid'] != 0){
	        	$abc = $model->_get_where([
		            'select' => 'id, lft, rgt, parentid',
		            'table' => $moduleExplode[0].'_catalogue',
		            'where' => ['id' => $category['parentid']]
		        ]);
	        }
			$allCategory = $model->_get_where([
	            'select' => 'tb2.objectid, tb2.canonical, tb2.module',
	            'table' => $moduleExplode[0].'_catalogue as tb1',
	            'join' => [
	            	[
	            		$moduleExplode[0].'_translate as tb2', 'tb2.objectid = tb1.id AND tb2.language = \''.$lang.'\' AND tb2.module = \''.$moduleExplode[0].'_catalogue\'','inner'
	            	]
	            ],
	            'where' => [
	                'tb1.lft >=' => $abc['lft'],
	                'tb1.lft <=' => $category['lft'],
	                'tb1.rgt <=' => $abc['rgt'],
	                'tb1.rgt >=' => $category['rgt'],
	            ],
	            'order_by' => 'tb1.lft asc',
	        ], TRUE);
	        $url = '';

	        foreach($allCategory as $key => $val){
	        	if($val['canonical'] == $canonical){
	        		$url = $url.$val['canonical'].(($key + 1 < count($allCategory)) ? '/' : '');
	        		$canonical = '';
	        	}else{
	            	$url = $url.$val['canonical'].'/';
	        	}
	        }

	        $url = $url.$canonical;
	    }
	        // pre($url);
	    return $url;
	}
}

if(!function_exists('recursive')){
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

if (!function_exists('dt_menu_recursive')) {
	function dt_menu_recursive($param = [], $extend = []){
		$html = '';
		$class = isset($extend['class'])? $extend['class'] : 'dt_dropdown_main';
		$level = isset($extend['level'])? $extend['level'] : 0;
		$_class_subs = isset($extend['_class_subs'])? $extend['_class_subs'] : '';

		if(isset($param) && count($param) && is_array($param)){
			$html .= '<ul class="uk-list '.$class.' '.$_class_subs.'">';
				foreach ($param as $key => $val) {
					if (isset($val['level']) && $val['level'] > $level) {
						$_class_subs = 'subs';
					}
					$title = $val['title'];
					$href = write_url($val['canonical'], true, false);

					$flag_child = 0;
					if(isset($val['children']) && count($val['children']) && is_array($val['children'])){
						$flag_child = 1;
					}

					$html .= '<li style="position:relative;">';
						$not_child = ($flag_child == 0)? 'not-child' : '';
						$html .= '<a class="'.$not_child.'" href="'.$href.'" title="'.$title.'">'.$title.'</a>';
						if($flag_child == 1){
							$html .= '<span class="dt_menu_recursive_btn"><i class="fa fa-plus" aria-hidden="true"></i></span>';
							$html .= dt_menu_recursive($val['children'], [
								'class' => $class,
								'_class_subs' => $_class_subs,
							]);
						}
					$html .= '</li>';
				}
			$html .= '</ul>';
		}

		return $html;
	}
}

if (! function_exists('get_general')){
    function get_general($lang = 'vi' ){
        $model = new AutoloadModel();
        $object = $model->_get_where([
           'select' => 'keyword, content',
            'table' => 'system_translate',
            'where' => ['language' => $lang]
        ],TRUE);
        $data= [];
        if(isset($object) &&  is_array($object)  && count($object)){
            foreach ($object as $key => $value) {
                $data[$value['keyword']] = $value['content'];
            }
        }

        return $data;
    }
}
if (!function_exists('dt_get_same_key_value')) {
    function dt_get_same_key_value($object = [], $flag = 'value'){
        $result = [];
        if (check_array($object)) {
            if ($flag == 'key') {
                foreach ($object as $key => $val) {
                    $result[$key] = $key;
                }
            }else{
                foreach ($object as $key => $val) {
                    $result[$val] = $val;
                }
            }
        }
        return $result;
    }
}

// get_currency_rate: phục vụ cho cookie
/*
    IN:
        currency_id: tiền tệ đang chọn
    OUT:
        rate: tỉ lệ chuyển đổi
*/


if(!function_exists('get_currency_rate')){
    function get_currency_rate($param = []){
        $model = new AutoloadModel();

        $result = $model->_get_where([
            'select' => '*',
            'table' => 'currency',
            'where' => [
                'default' => 1,
            ],
        ]);

        $default_id = isset($result['id'])? (int)$result['id'] : 0;
        $currency_id = isset($param['currency_id'])? (int)$param['currency_id'] : 0;


        if ($default_id == $currency_id && $currency_id != 0) {
            return 1;
        }

        $result = $model->_get_where([
            'select' => 'convert_rate',
            'table' => 'currency_relationship',
            'where' => [
                'default_id' => $default_id,
                'convert_id' => $currency_id,
            ],
        ]);

        $rate = isset($result['convert_rate'])? $result['convert_rate']: 1;

        return $rate;
    }
}


if(!function_exists('get_currency')){
    function get_currency($where = []){
        $model = new AutoloadModel();

        $join = [
            [
                'currency_translate as tb2','tb1.id = tb2.objectid AND tb2.module = "currency" AND tb2.language = \''.be_currentLanguage().'\' ','inner'
            ],
        ];

        $result = $model->_get_where([
            'select' => 'tb1.*, tb2.title',
            'table' => 'currency as tb1',
            'where' => $where,
            'join' => $join,
            'order_by' => 'tb1.default desc, tb2.title asc',
        ], true);

        return $result;
    }
}

// update currency backend
if(!function_exists('get_currency_relationship')){
    function get_currency_relationship($where = []){
        $model = new AutoloadModel();

        $module = 'currency';
        $module_extract = explode("_", $module);
        $language = be_currentLanguage();
        $join = [
            [
                $module_extract[0].'_translate as tb2','tb2.module = \''.$module.'\' AND tb2.objectid = tb1.id AND tb2.language = \''.$language.'\'', 'inner'
            ]
        ];

        $result = $model->_get_where([
            'select' => dt_get_query_module($module),
            'table' => 'currency as tb1',
            'join' => $join,
            'where' => $where,
        ]);


        if (check_array($result)) {
            $join[] = [
                'currency_relationship as tb3', 'tb3.convert_id = tb1.id AND tb3.default_id = '.$result['id'], 'left'
            ];

            $result['relationship'] = $model->_get_where([
                'select' => 'tb1.id, tb2.title, tb3.convert_rate',
                'table' => 'currency as tb1',
                'where' => [
                    'tb1.id !=' => $result['id'],
                ],
                'join' => $join,
                'order_by' => 'tb1.order desc',
            ], true);
        }

        return $result;
    }
}

if (!function_exists('get_data_module')) {
    function get_data_module($param = []){
        $model = new AutoloadModel();

        $module = ($param['module'])?? '';
        $language = ($param['language'])?? fe_currentLanguage();
        $keyword = ($param['keyword'])?? '';
        $where = ($param['where'])?? [];

        $query = ($param['query'])?? '';
        $order_by = ($param['order_by'])?? 'tb1.order desc';

        $module_explode = explode('_',$module);

        $flag = $model->_get_where([
            'select' => dt_get_query_module($module),
            'table' => $module.' as tb1',
            'query' => $query,
            'where' => $where,
            'join' =>  [
                [
                    $module_explode[0].'_translate as tb2','tb1.id = tb2.objectid AND tb2.module = \''.$module.'\' AND tb2.language = \''.$language.'\' ','inner'
                ]
            ],
            'keyword' => $keyword,
            'order_by' => $order_by,
        ], true);

        return $flag;
    }
}

if (!function_exists('dt_search_link')) {
    function dt_search_link($keyword = ''){
        return SEARCH.'?keyword='.$keyword;
    }
}

// print_r($g_currency); exit;
// price frontend
/*
    IN:
        rate: tỉ lệ chuyển đổi (lấy từ cookie)
    OUT:
        price: giá sau chuyển đổi
*/
if(!function_exists('get_price_convert')){
    function get_price_convert($param = [], $number_format = true){
        $price_init = isset($param['price_init'])? $param['price_init'] : 0;
        $rate = isset($GLOBALS['g_currency']['rate'])? $GLOBALS['g_currency']['rate'] : 1;
        // $rate = (isset($_COOKIE[AUTH_CURRENCY])) ? $_COOKIE[AUTH_CURRENCY] : '';

        // echo CURRENCY;die();

        $currency_title = isset($GLOBALS['g_currency']['title'])? $GLOBALS['g_currency']['title'] : CURRENCY;


        $decimal = (slug($currency_title) == 'vnd')? 0:0;
        if ($number_format == true) {
            $price = number_format($rate*$price_init, $decimal).' '.$currency_title;
        }else{
            $price = $rate*$price_init;
        }

        return $price;
    }
}


// rating
if(!function_exists('get_detail_rating')){
    function get_detail_rating($param = []){
        $model = new AutoloadModel();

        $objectid = ($param['objectid'])?? 0;
        $module = ($param['module'])?? '';
        $language = ($param['language'])?? 'vi';

        $join = [
            [
                'rating_relationship as tb2','tb1.id = tb2.rating_id','left'
            ],
        ];

        $where = [
            'publish' => 1,
            'objectid' => $objectid,
            'module' => $module,
            'language' => $language,
        ];

        $result = $model->_get_where([
            'select' => 'tb1.*',
            'table' => 'rating as tb1',
            'where' => $where,
            'join' => $join,
            'order_by' => 'tb1.module asc',
        ]);

        return $result;
    }
}

// static rating: tke
if(!function_exists('dt_static_rating')){
    function dt_static_rating($param = []){
        $model = new AutoloadModel();

        $objectid = ($param['objectid'])?? 0;
        $module = ($param['module'])?? '';
        $language = ($param['language'])?? 'vi';

        $result = get_detail_rating([
            'module' => $module,
            'objectid' => $objectid,
            'language' => $language,
        ]);

        if (check_array($result)) {
            $where = [
                'rating_id' => $result['id'],
            ];

            $result['static'] = $model->_get_where([
                'select' => 'tb1.*',
                'table' => 'rating_relationship as tb1',
                'where' => $where,
            ], true);
            $result['detail'] = $model->_get_where([
                'select' => 'SUM(rating) as total_point, COUNT(rating_id) as num_rate, round(SUM(rating)/COUNT(rating_id),1) as average',
                'table' => 'rating_relationship as tb1',
                'where' => $where,
            ]);
        }

        // print_r($result); exit;


        return $result;
    }
}

// upload file
if(!function_exists('upload_file')){
    function upload_file($param = []){
        $frontend_auth = isset($param['frontend_auth'])? $param['frontend_auth']: [];
        $flag = isset($param['flag'])? $param['flag']: TRUE;

        if ($flag == TRUE) {
            // file trống
            $_return = array(
                'flag' => 1,
                'message' => 'Bạn chưa chọn file. Vui lòng thử lại !',
            );
        }else{
            $_return = array(
                'flag' => 0,
                'message' => '',
            );
        }

        if (isset($_FILES) && isset($_FILES['file']['name']) && !empty($_FILES['file']['name'])) {
            $_return = array(
                'flag' => 0,
                'message' => '',
            );

            $time = gmdate('Y-m-d', time() + 7*3600);

            $folder_upload = (isset($frontend_auth['folder_upload']) && $frontend_auth['folder_upload'] != '')? $frontend_auth['folder_upload']: $time; // name of folder upload
            // File upload configuration
            $targetDir = TARGET_DIR.$folder_upload.'/';
            $allowTypes = explode('|', ALLOW_TYPES);
            $maxfilesize = MAX_SIZE*1024; // => B
            $_mfs = MAX_SIZE/1024; // MB

            // print_r($allowTypes); exit;

            $images = '';

            $image_name = $_FILES['file']['name'];
            $tmp_name   = $_FILES['file']['tmp_name'];
            $size       = $_FILES['file']['size']; // BYTES => MB
            $type       = $_FILES['file']['type'];
            $error      = $_FILES['file']['error'];

            // File upload path
            $fileName = basename($_FILES['file']['name']);
            $targetFilePath = $targetDir . $fileName;

            // Check whether file type is valid
            $fileType = pathinfo($targetFilePath, PATHINFO_EXTENSION);
            if(in_array($fileType, $allowTypes)){
                if($size > $maxfilesize){
                    $message = "Không được upload file lớn hơn $_mfs MB.";

                    $_return = array(
                        'flag' => 1,
                        'message' => $message,
                    );

                    return $_return;

                }else{
                    if (!file_exists($targetDir)) {
                        mkdir($targetDir, 0777, true);
                    }

                    if(move_uploaded_file($tmp_name, $targetFilePath)){
                        $file = $targetFilePath;

                        $_return = array(
                            'flag' => 0,
                            'message' => 'Upload file thành công !',
                            'file' => $file,
                        );
                    }else{
                        $_return = array(
                            'flag' => 1,
                            'message' => 'Tải file lên thất bại !',
                        );
                    }
                }
            }else{
                $_return = array(
                    'flag' => 1,
                    'message' => 'Hệ thống chỉ cho phép upload file '.str_replace('|',',',ALLOW_TYPES).'. Vui lòng thử lại !',
                );
            }
        }
        return $_return;
    }
}
