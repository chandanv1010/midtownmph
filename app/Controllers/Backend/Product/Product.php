<?php
namespace App\Controllers\Backend\Product;
use App\Controllers\BaseController;
use App\Libraries\Nestedsetbie;

class Product extends BaseController{
	protected $data;
	public $nestedsetbie;


	public function __construct(){
		$this->data = [];
		$this->data['module'] = 'product';
		$this->nestedsetbie = new Nestedsetbie(['table' => $this->data['module'].'_catalogue','language' => $this->currentLanguage()]);
	}

	public function index($page = 1){
		$session = session();
		$flag = $this->authentication->check_permission([
			'routes' => 'backend/product/product/index'
		]);
		if($flag == false){
 			$session->setFlashdata('message-danger', 'Bạn không có quyền truy cập vào chức năng này!');
 			return redirect()->to(BASE_URL.'backend/dashboard/dashboard/index');
		}
		$this->data['attribute_list'] = $this->AutoloadModel->_get_where([
			'select' => 'tb1.id, tb2.title',
			'table' => 'attribute as tb1',
			'join' => [
				[
					'attribute_translate as tb2','tb1.id = tb2.objectid AND tb2.module = "attribute" AND tb2.language = \''.$this->currentLanguage().'\'','inner'
				]
			],
			'where' => [
				'tb1.deleted_at' => 0,
				'tb1.publish' => 1
			],
			'order_by' => 'tb2.title asc'
		],true);
		$this->data['code'] = $this->AutoloadModel->_get_where([
			'select' => 'id, suffix, prefix, module, num0',
			'table' => 'id_general',
			'where' => ['module' => $this->data['module']]
		]);
		helper(['mypagination']);
		$page = (int)$page;
		$perpage = ($this->request->getGet('perpage')) ? $this->request->getGet('perpage') : 20;
		$where = $this->condition_where();
		$keyword = $this->condition_keyword();
		$join = $this->condition_join();
		$catalogue = $this->condition_catalogue();
		$config['total_rows'] = $this->AutoloadModel->_get_where([
			'select' => 'tb1.id',
			'table' => $this->data['module'].' as tb1',
			'join' => $join,
			'where_in' => $catalogue['where_in'],
			'where_in_field' => $catalogue['where_in_field'],
			'keyword' => $keyword,
			'where' => $where,
			'count' => TRUE
		]);

		if($config['total_rows'] > 0){
			$config = pagination_config_bt(['url' => 'backend/product/product/index','perpage' => $perpage], $config, $page);

			$this->pagination->initialize($config);
			$this->data['pagination'] = $this->pagination->create_links();
			$totalPage = ceil($config['total_rows']/$config['per_page']);
			$page = ($page <= 0)?1:$page;
			$page = ($page > $totalPage)?$totalPage:$page;
			$page = $page - 1;


			$languageDetact = $this->detect_language();
			$this->data['productList'] = $this->AutoloadModel->_get_where([
				'select' => 'tb1.id, tb1.catalogueid as cat_id, tb1.code, tb1.price,tb1.order, tb1.price_promotion, tb1.bar_code, tb1.model, tb1.brandid, tb1.album,  tb2.catalogueid, tb1.publish, tb3.title as product_title, tb1.catalogue, tb2.objectid, tb3.content, tb3.sub_title, tb3.sub_content, tb3.canonical, tb3.meta_title, tb3.meta_description, tb3.made_in, tb4.title as cat_title ,'.((isset($languageDetact['select'])) ? $languageDetact['select'] : ''),
				'table' => $this->data['module'].' as tb1',
				'where' => $where,
				'where_in' => $catalogue['where_in'],
				'where_in_field' => $catalogue['where_in_field'],
				'keyword' => $keyword,
				'join' => $join,
				'limit' => $config['per_page'],
				'start' => $page * $config['per_page'],
				'order_by'=> 'tb1.id desc',
				'group_by' => 'tb1.id'
			], TRUE);
		}
		// pre($this->data['productList']);

		$this->data['dropdown'] = $this->nestedsetbie->dropdown();
		$this->data['template'] = 'backend/product/product/index';
		return view('backend/dashboard/layout/home', $this->data);
	}

	public function create(){
		$session = session();
		$flag = $this->authentication->check_permission([
			'routes' => 'backend/product/product/create'
		]);
		if($flag == false){
 			$session->setFlashdata('message-danger', 'Bạn không có quyền truy cập vào chức năng này!');
 			return redirect()->to(BASE_URL.'backend/dashboard/dashboard/index');
		}
		$this->data['attribute_list'] = $this->get_attribute_list();

		$this->data['get_canonical'] = $this->get_canonical();
		$this->data['check_code'] = $this->AutoloadModel->_get_where([
			'select' => 'code,objectid',
			'table' => 'id_general',
			'where' => ['module' => $this->data['module']],
		]);

		if(!isset($this->data['check_code']) && !is_array($this->data['check_code'])){
			$session->setFlashdata('message-danger', 'Bạn chưa tạo phần cấu hình chung cho mã Sản phẩm!');
 			return redirect()->to(BASE_URL.'backend/product/product/index');
		}else{

			$this->data['export_brand'] = $this->export_brand();
			$this->data['productid'] = convert_code($this->data['check_code']['code'], $this->data['module']);
			if($this->request->getMethod() == 'post'){
				$validate = $this->validation();
				if($this->validate($validate['validate'], $validate['errorValidate'])){
					$sub_content = $this->request->getPost('sub_content');
			 		$insert = $this->store(['method' => 'create']);

			 		$resultid = $this->AutoloadModel->_insert([
			 			'table' => $this->data['module'],
			 			'data' => $insert,
			 		]);

			 		if($resultid > 0){
			 			$storeLanguage = $this->storeLanguage($resultid);
			 			$storeLanguage = $this->convert_content($sub_content, $storeLanguage);
						$this->AutoloadModel->_insert([
							'table' => 'product_translate',
							'data' => $storeLanguage
						]);
				 		$this->AutoloadModel->_update([
	 						'table' => 'id_general',
	 						'data' => [
	 							'objectid' => $this->data['check_code']['objectid'] + 1
	 						],
	 						'where' => ['module' => $this->data['module']]
	 					]);

		 				insert_router([
				 			'method' => 'create',
				 			'id' => $resultid,
				 			'language' => $this->currentLanguage(),
				 			'module' => $this->data['module'],
				 			'router' => $this->request->getPost('router'),
			 				'canonical' => slug($this->request->getPost('canonical'))
				 		]);
				 		$this->insert_attribute($resultid);
		 				$this->insert_tags($resultid);
	 					$flag = $this->create_relationship($resultid);
			 			$this->nestedsetbie->Get('level ASC, order ASC');
						$this->nestedsetbie->Recursive(0, $this->nestedsetbie->Set());
						$this->nestedsetbie->Action();
			 			$session->setFlashdata('message-success', 'Tạo Sản phẩm Thành Công! Hãy tạo danh mục tiếp theo.');
	 					return redirect()->to(BASE_URL.'backend/product/product/create');
			 		}
		        }else{
		        	$this->data['validate'] = $this->validator->listErrors();
		        }
			}
		}

		$this->data['fixWrapper'] = 'fix-wrapper';
		$this->data['dropdown'] = $this->nestedsetbie->dropdown();
		$this->data['method'] = 'create';
		$this->data['template'] = 'backend/product/product/create';
		return view('backend/dashboard/layout/home', $this->data);
	}

	public function update($id = 0){
		$id = (int)$id;
		$session = session();
		$flag = $this->authentication->check_permission([
			'routes' => 'backend/product/product/update'
		]);
		if($flag == false){
 			$session->setFlashdata('message-danger', 'Bạn không có quyền truy cập vào chức năng này!');
 			return redirect()->to(BASE_URL.'backend/dashboard/dashboard/index');
		}
		$this->data['export_brand'] = $this->export_brand();
		$this->data[$this->data['module']] = $this->get_data_module($id);
		if(isset($this->data[$this->data['module']]['sub_album']) && $this->data[$this->data['module']]['sub_album'] != ''){
			$this->data['sub_album'] = $this->rewrite_album($this->data[$this->data['module']]);
		}

		if($this->data[$this->data['module']] == false){
			$session->setFlashdata('message-danger', 'Sản phẩm không tồn tại!');
 			return redirect()->to(BASE_URL.'backend/product/product/index');
		}
		$this->data['get_canonical'] = $this->get_canonical();
		$this->data['tags'] = $this->get_tags($id);
		$this->data['attribute_list'] = $this->get_attribute_list();

		if($this->request->getMethod() == 'post'){
			$validate = $this->validation();
			if ($this->validate($validate['validate'], $validate['errorValidate'])){
				$sub_content = $this->request->getPost('sub_content');
		 		$update = $this->store(['method' => 'update']);
		 		$updateLanguage = $this->storeLanguage($id);
		 		$updateLanguage = $this->convert_content($sub_content, $updateLanguage);
		 		$flag = $this->AutoloadModel->_update([
		 			'table' => $this->data['module'],
		 			'where' => ['id' => $id],
		 			'data' => $update
		 		]);

		 		if($flag > 0){
		 			$this->AutoloadModel->_update([
			 			'table' => 'product_translate',
			 			'where' => ['objectid' => $id, 'module' => $this->data['module'],'language' => $this->currentLanguage()],
			 			'data' => $updateLanguage
			 		]);
			 		$this->insert_attribute($id);
			 		$flag = $this->create_relationship($id);
		 			insert_router([
		 				'method' => 'update',
		 				'id' => $id,
		 				'language' => $this->currentLanguage(),
		 				'module' => $this->data['module'],
		 				'router' => $this->request->getPost('router'),
		 				'canonical' => slug($this->request->getPost('canonical'))
		 			]);
		 			$this->nestedsetbie->Get('level ASC, order ASC');
					$this->nestedsetbie->Recursive(0, $this->nestedsetbie->Set());
					$this->nestedsetbie->Action();
	 				$this->insert_tags($id);
		 			$session->setFlashdata('message-success', 'Cập Nhật Sản phẩm Thành Công!');
 					return redirect()->to(BASE_URL.'backend/product/product/index');
		 		}

	        }else{
	        	$this->data['validate'] = $this->validator->listErrors();
	        }
		}

		$this->data['fixWrapper'] = 'fix-wrapper';
		$this->data['dropdown'] = $this->nestedsetbie->dropdown();
		$this->data['method'] = 'update';
		$this->data['template'] = 'backend/product/product/update';
		return view('backend/dashboard/layout/home', $this->data);
	}

	public function delete($id = 0){
		$session = session();
		$flag = $this->authentication->check_permission([
			'routes' => 'backend/product/product/delete'
		]);
		if($flag == false){
 			$session->setFlashdata('message-danger', 'Bạn không có quyền truy cập vào chức năng này!');
 			return redirect()->to(BASE_URL.'backend/dashboard/dashboard/index');
		}
		$id = (int)$id;
		$this->data[$this->data['module']] = $this->get_data_module($id);
		if($this->data[$this->data['module']] == false){
			$session->setFlashdata('message-danger', 'Sản phẩm không tồn tại!');
 			return redirect()->to(BASE_URL.'backend/product/product/index');
		}

		if($this->request->getPost('delete')){

			$flag = $this->AutoloadModel->_update([
				'table' => $this->data['module'],
				'data' => ['deleted_at' => 1],
				'where' => [
					'id' => $id
				]
			]);
			delete_router($id,$this->data['module'], $this->currentLanguage());

			$session = session();
			if($flag > 0){
				$this->nestedsetbie->Get('level ASC, order ASC');
				$this->nestedsetbie->Recursive(0, $this->nestedsetbie->Set());
				$this->nestedsetbie->Action();
	 			$session->setFlashdata('message-success', 'Xóa bản ghi thành công!');
			}else{
				$session->setFlashdata('message-danger', 'Có vấn đề xảy ra, vui lòng thử lại!');
			}
			return redirect()->to(BASE_URL.'backend/product/product/index');
		}

		$this->data['template'] = 'backend/product/product/delete';
		return view('backend/dashboard/layout/home', $this->data);
	}

	 public function preview($id = 0){
        $id = (int)$id;
        $session = session();
        $flag = $this->authentication->check_permission([
			'routes' => 'backend/product/product/preview'
		]);
		if($flag == false){
 			$session->setFlashdata('message-danger', 'Bạn không có quyền truy cập vào chức năng này!');
 			return redirect()->to(BASE_URL.'backend/dashboard/dashboard/index');
		}
        $module_extract = explode("_", $this->data['module']);
        $this->data['object'] = $this->AutoloadModel->_get_where([
            'select' => 'tb1.id,tb1.price,tb1.sub_album, tb1.price_promotion,tb1.catalogueid, tb1.viewed, tb1.album, tb1.image, tb2.title, tb2.canonical,tb2.sub_album_title, tb2.meta_title, tb2.sub_title, tb2.sub_content, tb1.productid, tb2.meta_description,  tb2.description, tb2.content, tb1.bar_code, tb1.model',
            'table' => $module_extract[0].' as tb1',
            'where' => [
                'tb1.deleted_at' => 0,
                'tb1.publish' => 1,
                'tb1.id' => $id
            ],
            'join' => [
                [
                    'product_translate as tb2','tb1.id = tb2.objectid AND tb2.module = "product" AND tb2.language = \''.$this->currentLanguage().'\' ','inner'
                ]
            ],
        ]);

        if(!isset($this->data['object']) && is_array($this->data['object']) == false && count($this->data['object']) == 0){
            $session->setFlashdata('message-danger', 'Bài viết không tồn tại!');
            return redirect()->to(BASE_URL.'backend/product/product/index');
        }
        $this->data['sub_album'] = $this->rewrite_album($this->data['object']);
        $this->data['object'] = $this->convert_data($this->data['object']);
        // prE($this->data['object']);
        $this->data['template'] = 'backend/product/product/preview';
		return view('backend/dashboard/layout/popup', $this->data);
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
        if(isset($param['price']) && $param['price'] != ''){
            $param['price'] = number_format($param['price'],0,',','.');
        }
        if(isset($param['price_promotion']) && $param['price_promotion'] != ''){
            $param['price_promotion'] = number_format($param['price_promotion'],0,',','.');
        }

        return $param;
    }

	private function get_tags($id = ''){
		$tags = $this->AutoloadModel->_get_where([
			'select' => '*',
			'table' => 'tags',
			'where' => [
				'module' => $this->data['module'],
				'language' => $this->currentLanguage(),
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
		$text = '';
		$count = 0;
		foreach ($array as $key => $value) {
			$text = $text. $value['title'];
			if($count + 1 < count($array)) $text=$text.', ';
			$count++;
		}

		return $text;
	}

	private function insert_tags($id = ''){
		$tags = $this->request->getPost('tags');


		$tags_explode = explode(',', $tags);
		$array_tag = [];
		foreach ($tags_explode as $key => $value) {
			array_push($array_tag, ['title' => $value]);
		}
		foreach ($array_tag as $key => $value) {
			$array_tag[$key]['keyword'] = slug($array_tag[$key]['title']);
			$array_tag[$key]['module'] = $this->data['module'];
			$array_tag[$key]['language'] = $this->currentLanguage();
			$array_tag[$key]['objectid'] = $id;
		}
		$store_create = [];
		$store_update = [];
		$get_tags = $this->AutoloadModel->_get_where([
			'select' => '*',
			'table' => 'tags',
			'where' => [
				'module' => $this->data['module'],
				'language' => $this->currentLanguage()
			]
		], true);
		if(isset($get_tags) && is_array($get_tags) && count($get_tags)){
			foreach ($get_tags as $key => $value) {
				foreach ($array_tag as $keyTag => $valueTag) {
					if($value['keyword'] == $valueTag['keyword']){
						$get_tags[$key]['objectid'] = json_decode($value['objectid']);
						array_push($get_tags[$key]['objectid'], $valueTag['objectid']);
						array_push($store_update ,[
							'keyword' => $value['keyword'],
							'objectid' => json_encode(array_unique($get_tags[$key]['objectid']))
						]);
						unset($array_tag[$keyTag]);
					}
				}
			}
		}

		if($store_update != []){
			$this->AutoloadModel->_update_batch([
				'table' => 'tags',
				'data' => $store_update,
				'field' => 'keyword'
			]);
		}

		if($array_tag != []){
			if(isset($array_tag) && is_array($array_tag) && count($array_tag)){
				foreach ($array_tag as $key => $value) {
					unset($array_tag[$key]['objectid']);
					$array_tag[$key]['objectid']= [];
					array_push($array_tag[$key]['objectid'], $value['objectid']);
					$array_tag[$key]['objectid'] = json_encode($array_tag[$key]['objectid']);
				}
			}
			$this->AutoloadModel->_create_batch([
	 			'table' => 'tags',
	 			'data' => $array_tag
	 		]);
		}

		return $array_tag;
	}

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
		$priceFrom = $this->request->getGet('priceFrom');
		$priceFrom = str_replace('.', '', $priceFrom);
		$priceFrom = (float)$priceFrom;
		$priceTo = $this->request->getGet('priceTo');
		$priceTo = str_replace('.', '', $priceTo);
		$priceTo = (float)$priceTo;
		if(isset($priceFrom) && $priceFrom != 0){
			$where['tb1.price >='] = $priceFrom;
		}
		if(isset($priceTo) && $priceTo != 0){
			$where['tb1.price <='] = $priceTo;
		}

		$attr = $this->request->getGet('attr');
		if(isset($attr) && $attr != 0){
			$where['tb5.attributeid'] = $attr;
		}
		return $where;
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

	public function get_attribute_list(){
		$attr = $this->AutoloadModel->_get_where([
			'select' => 'tb1.id, tb1.catalogueid, tb2.title , tb3.title as cat_title, tb2.module_primary ',
			'table' => 'attribute as tb1',
			'join' => [
				[
					'attribute_translate as tb2','tb1.id = tb2.objectid AND tb2.module = "attribute"  AND tb2.language = \''.$this->currentLanguage().'\' ','inner'
				],
				[
					'attribute_translate as tb3','tb1.catalogueid = tb3.objectid AND tb3.module = "attribute_catalogue" AND tb3.language = \''.$this->currentLanguage().'\' ','inner'
				],
			],
			'where' => [
				'tb1.publish' => 1,
				'tb1.deleted_at' => 0
			],
			'group_by' => 'tb1.id',
			'order_by' => 'tb2.title asc'
		],true);

		$attr_list = [];
		if(isset($attr) && is_array($attr) && count($attr)){
			foreach ($attr as $key => $value) {
			    $attr_list[$value['catalogueid']] = [
			    	'title' => $value['cat_title'],
			    	'id' => $value['catalogueid'],
			    	'status' => $value['module_primary'],
			    	'data' => []
			    ];
			}
			if(isset($attr_list) && is_array($attr_list) && count($attr_list)){
				foreach ($attr_list as $key => $value) {
					foreach ($attr as $keyChild => $valueChild) {
						if($valueChild['catalogueid'] == $key){
							$attr_list[$key]['data'][] = $valueChild;
						}
					}
				}
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

	private function create_relationship($objectid = 0, $catalogue = []){
		if($this->request->getPost('catalogue') != ''){
			$catalogue = $this->request->getPost('catalogue');
		}
		$catalogueid = $this->request->getPost('catalogueid');
		$extra = $this->request->getPost('extra');
		$relationshipId = 	array_unique(array_merge($catalogue, [$catalogueid]));
		$this->AutoloadModel->_delete([
			'table' => 'object_relationship',
			'where' => [
				'module' => $this->data['module'],
				'objectid' => $objectid
			]
		]);
		$this->AutoloadModel->_delete([
			'table' => 'object_relationship',
			'where' => [
				'module' => 'product_tag',
				'objectid' => $objectid
			]
		]);
		$insert = [];
		if(isset($extra) && is_array($extra) && count($extra)){
			foreach($extra as $key => $val){
				$insert[] = array(
					'objectid' => $objectid,
					'catalogueid' => $val,
					'module' => 'product_tag',
				);
			}
		}
		if(isset($relationshipId) && is_array($relationshipId) && count($relationshipId)){
			foreach($relationshipId as $key => $val){
				$insert[] = array(
					'objectid' => $objectid,
					'catalogueid' => $val,
					'module' => $this->data['module'],
				);
			}
		}


		if(isset($insert) && is_array($insert) && count($insert)){
			$flag = $this->AutoloadModel->_create_batch([
				'data' => $insert,
				'table' => 'object_relationship'
			]);
		}

		return $flag;
	}

	private function condition_keyword($keyword = ''): string{
		if(!empty($this->request->getGet('keyword'))){
			$keyword = $this->request->getGet('keyword');
			$keyword = '(tb3.title LIKE \'%'.$keyword.'%\' OR tb1.productid LIKE \'%'.$keyword.'%\' OR tb1.code LIKE \'%'.$keyword.'%\')';
		}
		return $keyword;
	}

	private function condition_join(){
		$join = [
			[
				'object_relationship as tb2', 'tb1.id = tb2.objectid AND tb2.module = \''.$this->data['module'].'\' ', 'inner'
			],
			[
				'product_translate as tb3','tb1.id = tb3.objectid AND tb3.module = "product" AND tb3.language = \''.$this->currentLanguage().'\' ','inner'
			],
			[
				'product_translate as tb4','tb1.catalogueid = tb4.objectid AND tb4.module = "product_catalogue" AND tb3.language = \''.$this->currentLanguage().'\' ','inner'
			],
		];
		$attr = $this->request->getGet('attr');
		if(isset($attr) && $attr != 0){
			$join[] = [
				'attribute_relationship as tb5', 'tb1.id = tb5.objectid AND tb2.module = \''.$this->data['module'].'\' ', 'inner'
			];
		}

		return $join;
	}

	private function storeLanguage($objectid = 0){
		helper(['text']);
		$store = [
			'objectid' => $objectid,
			'title' => validate_input($this->request->getPost('title')),
			'canonical' => slug($this->request->getPost('canonical')),
			'made_in' => $this->request->getPost('made_in'),
			'sub_album_title' => json_encode($this->request->getPost('sub_album_title'),TRUE),
			'content' => base64_encode($this->request->getPost('content')),
			'description' => base64_encode($this->request->getPost('description')),
			'meta_title' => validate_input($this->request->getPost('meta_title')),
			'meta_description' => validate_input($this->request->getPost('meta_description')),
			'language' => $this->currentLanguage(),
			'module' => $this->data['module'],
 			
 			'address' => $this->request->getPost('address'),
		];

		return $store;
	}

	private function store($param = []){
		helper(['text']);
		$catalogue = $this->request->getPost('catalogue');
		$extra = $this->request->getPost('extra');
		$attributeid = $this->request->getPost('attributeid');
		$productid_original = $this->request->getPost('productid_original');
		if($productid_original == '' || $productid_original != $this->request->getPost('productid')){
			$productid_original = $this->request->getPost('productid');
		}
		if(isset($catalogue) && is_array($catalogue) && count($catalogue)){
			foreach($catalogue as $key => $val){
				if($val == (int)$this->request->getPost('catalogueid')){
					unset($catalogue[$key]);
				}
			}
		}
		if(isset($catalogue) && is_array($catalogue) && count($catalogue)){
			$catalogue = array_values($catalogue);
		}
		if(isset($extra) && is_array($extra) && count($extra)){
			$extra = array_values($extra);
		}
		if(isset($attributeid) && is_array($attributeid) && count($attributeid)){
			$attributeid = array_values($attributeid);
		}
		$album = $this->request->getPost('album');
		$image = '';
		if(isset($album) && is_array($album) && count($album)){
			$image = $album[0];
		}else{
			$image = 'public/not-found.png';
		}
		$price = $this->request->getPost('price');
		$price = str_replace('.', '', $price);
		$price = (float)$price;
		$price_promotion = $this->request->getPost('promotion_price');
		$price_promotion = str_replace('.', '', $price_promotion);
		$price_promotion = (float)$price_promotion;
		$time = str_replace("/","-",$this->request->getPost('time_end'));
 		$end = gettime($time, 'datetime');
		$store = [
 			'catalogueid' => (int)$this->request->getPost('catalogueid'),
 			'catalogue' => json_encode($catalogue),
 			'extra' => json_encode($extra),
 			'productid' => $this->request->getPost('productid'),
 			'code' => $this->request->getPost('code'),
 			'productid_original' => $productid_original,
 			'status' => json_encode($this->request->getPost('status')),
 			'brandid' => json_encode($this->request->getPost('brandid')),
 			'info' => json_encode($this->request->getPost('info')),
 			'album' => json_encode($this->request->getPost('album'), TRUE),
 			'publish' => $this->request->getPost('publish'),
 			'sub_album' => json_encode($this->request->getPost('sub_album'), TRUE),
 			'map' => $this->request->getPost('map'),
 			'price' => $price,
 			'image' => $image,
 			'bath' => $this->request->getPost('bath'),
 			'bed' => $this->request->getPost('bed'),
 			'area' => $this->request->getPost('area'),
 			'time_end' => $end,
 			'price_promotion' => $price_promotion,
 			'bar_code' => $this->request->getPost('bar_code'),
 			'model' => $this->request->getPost('model'),
 			'img_sub' => $this->request->getPost('img_sub'),
 			'qr_code' => $this->request->getPost('qr_code'),
 			'out_of_stock' => 0,
 			'video' => $this->request->getPost('video'),
 			'vr' => $this->request->getPost('vr'),
 			'vr_3d' => $this->request->getPost('vr_3d'),
 		];
 		if($param['method'] == 'create' && isset($param['method'])){
 			$store['created_at'] = $this->currentTime;
 			$store['rate'] = 5;
 			$store['userid_created'] = $this->auth['id'];

 		}else{
 			$store['updated_at'] = $this->currentTime;
 			$store['userid_updated'] = $this->auth['id'];
 		}

 		// print_r($store); exit;
 		return $store;
	}

	private function insert_router($param = []){
		helper(['text']);
		$view = view_cells($this->data['module']);
		$data = [
			'canonical' => slug($this->request->getPost('canonical')),
			'module' => $this->data['module'],
			'objectid' => $param['id'],
			'language' => $this->currentLanguage(),
			'view' => $view
		];
 		if($param['method'] == 'create' && isset($param['method'])){
 			$insertRouter = $this->AutoloadModel->_insert([
	 			'table' => 'router',
	 			'data' => $data,
	 		]);
 		}else{
			$this->AutoloadModel->_delete([
				'table' => 'router',
				'where' => ['objectid' => $param['id'], 'module' => $this->data['module']],
			]);

			$insertRouter = $this->AutoloadModel->_insert([
	 			'table' => 'router',
	 			'data' => $data,
	 		]);
 		}
 		return true;
	}

	private function detect_language(){
		$languageList = $this->AutoloadModel->_get_where([
			'select' => 'id, canonical',
			'table' => 'language',
			'where' => ['publish' => 1,'deleted_at' => 0,'canonical !=' => $this->currentLanguage()]
		], TRUE);


		$select = '';
		$i = 3;
		if(isset($languageList) && is_array($languageList) && count($languageList)){
			foreach($languageList as $key => $val){
				$select = $select.'(SELECT COUNT(objectid) FROM product_translate WHERE product_translate.objectid = tb1.id AND product_translate.module = "product" AND product_translate.language = "'.$val['canonical'].'") as '.$val['canonical'].'_detect, ';
				$i++;
			}
		}


		return [
			'select' => $select,
		];
	}

	private function convert_content($content = [], $store = []){
		$count_1 = 0;
		$count_2 = 0;
		if($content != []){
			foreach ($content['title'] as $key => $value) {
	 			$title[] = $content['title'][$count_1];
	 			$count_1++;
	 		}
	 		foreach ($content['title'] as $key => $value) {
	 			$description[] = $content['description'][$count_2];
	 			$count_2++;
	 		}
	 		$title = base64_encode(json_encode($title));
	 		$description = base64_encode(json_encode($description));
	 		$store['sub_title'] = $title;
	 		$store['sub_content'] = $description;
			return $store;
		}else{
			return $store;
		}
	}

	private function export_brand(){
		$brand = $this->AutoloadModel->_get_where([
			'select' => 'tb1.id, tb2.title',
			'table' => 'brand as tb1',
			'join' =>  [
				[
					'brand_translate as tb2','tb1.id = tb2.objectid AND tb2.module = "brand" AND tb2.language = \''.$this->currentLanguage().'\' ','inner'
				],
			],
			'where' => [
				'tb1.deleted_at' => 0,
				'tb1.publish' => 1
			]
		],TRUE);
		$new_array= [];
		$new_array = convert_array([
			'text' => 'Thương hiệu',
			'data' => $brand,
			'field'=> 'id',
			'value' => 'title'
		]);

		return $new_array;
	}
	private function insert_attribute($id = 0){
		$status = $this->request->getPost('status');
		$info = $this->request->getPost('info');
		$array_merge = $status + $info;

		$this->AutoloadModel->_delete([
			'table' => 'attribute_relationship',
			'where' => [
				'objectid' => $id
			]
		]);
		if(isset($array_merge) && is_array($array_merge) && count($array_merge)){
			foreach ($array_merge as $key => $value) {
			    if($value == 0){
			    	unset($array_merge[$key]);
			    }
			}
		}
		$store = [];
		if(isset($array_merge) && is_array($array_merge) && count($array_merge)){
			foreach ($array_merge as $key => $value) {
		    	$store[] = [
		    		'objectid' => $id,
		    		'module' => $this->data['module'],
		    		'attributeid' => $value
		    	];
			}
		}
		if(isset($store) && is_array($store) && count($store)){
			$this->AutoloadModel->_create_batch([
				'table' => 'attribute_relationship',
				'data' => $store
			]);
		}
	}


	private function version($id = '', $method = ''){
		$title_key = $this->request->getPost('title_version');
		$version_type = $this->request->getPost('version_type');
		if($version_type == 'normal'){
			$get = [
				'attribute_catalogue' => $this->request->getPost('attribute_catalogue'),
				'attribute' => $this->request->getPost('attribute'),
				'attribute1' => $this->request->getPost('attribute1'),
				'attribute2' => $this->request->getPost('attribute2'),
				'attribute3' => $this->request->getPost('attribute3'),
				'checked' => $this->request->getPost('checked'),
				'img_version' => $this->request->getPost('img_version'),
				'title_version' => $title_key,
				'barcode_version' => $this->request->getPost('barcode_version'),
				'model_version' => $this->request->getPost('model_version'),
				'price_version' => $this->request->getPost('price_version'),
				'code_version' => $this->request->getPost('code_version'),
			];
		}else if($version_type == 'canonical'){
			$get = [
				'attribute_catalogue' => $this->request->getPost('attribute_catalogue'),
				'attribute' => $this->request->getPost('attribute'),
				'attribute1' => $this->request->getPost('attribute1'),
				'attribute2' => $this->request->getPost('attribute2'),
				'attribute3' => $this->request->getPost('attribute3'),
				'checked' => $this->request->getPost('checked'),
				'title_version' => $title_key,
				'objectid' => $this->request->getPost('objectid'),
				'canonical_version' => $this->request->getPost('canonical_version'),
				'code_version' => $this->request->getPost('code_version'),
			];
		}
		$flag = insert_version($get , $id, $this->currentLanguage(), $method, $this->data['module'],$version_type);

		if(isset($get['attribute_catalogue']) && $get['attribute1'] == [] && count($get['attribute_catalogue'])){
			$flag = insert_attribute_relationship($get , $id, $this->currentLanguage(), $method, $this->data['module'],$version_type);
		}else{
			$flag = insert_attribute_relationship([] , $id, $this->currentLanguage(), $method, $this->data['module'],$version_type);
		}

		return $flag;
	}

	private function get_data_version($id = ''){
		$flag = $this->AutoloadModel->_get_where([
			'select' => 'id, objectid, content, attribute, attribute_catalogue, checked, type',
			'table' => 'product_version',
			'where' => ['objectid' => $id]
		],TRUE);
		foreach ($flag as $key => $value) {

			$flag[$key]['content'] = json_decode($flag[$key]['content'], TRUE);
		}

		return $flag;
	}

	private function attribute_relationship($id){
		$attribute = $this->request->getPost('attribute');
		$attr = [];

		$this->AutoloadModel->_delete([
			'table' => 'attribute_relationship',
			'where' => [
				'objectid' => $id,
				'module' => $this->data['module'],
			]
		]);
		if(isset($attribute) && is_array($attribute) && count($attribute)){
			foreach ($attribute as $key => $value) {
				foreach ($value as $keyChild => $valChild) {
					array_unshift($attr, $valChild);
				}
			}
			$insert = [];
			foreach ($attr as $key => $value) {
				$insert[] = [
					'objectid' => $id,
					'attributeid' => $value,
					'module' => $this->data['module'],
				];
			}

			$this->AutoloadModel->_create_batch([
				'table' => 'attribute_relationship',
				'data' => $insert
			]);
		}
		return true;
	}

	public function condition_catalogue(){
		$catalogueid = $this->request->getGet('catalogueid');
		$id = [];
		if($catalogueid > 0){
			$catalogue = $this->AutoloadModel->_get_where([
				'select' => 'tb1.id, tb1.lft, tb1.rgt, tb3.title',
				'table' => $this->data['module'].'_catalogue as tb1',
				'join' =>  [
					[
						'product_translate as tb3','tb1.id = tb3.objectid AND tb3.language = \''.$this->currentLanguage().'\' ','inner'
					],
									],
				'where' => ['tb1.id' => $catalogueid],
			]);

			$catalogueChildren = $this->AutoloadModel->_get_where([
				'select' => 'id',
				'table' => $this->data['module'].'_catalogue',
				'where' => ['lft >=' => $catalogue['lft'],'rgt <=' => $catalogue['rgt']],
			], TRUE);

			$id = array_column($catalogueChildren, 'id');
		}
		return [
			'where_in' => $id,
			'where_in_field' => 'tb2.catalogueid'
		];
	}
	public function get_canonical(){
		$flag = $this->AutoloadModel->_get_where([
			'select' => 'tb1.id, tb2.canonical, tb2.title',
			'table' => $this->data['module'].' as tb1',
			'join' => [
				[
					$this->data['module'].'_translate as tb2','tb1.id = tb2.objectid','inner'
				]
			],
			'where' => [
				'tb1.publish' => 1,
				'tb1.deleted_at' => 0,
				'tb2.language' => $this->currentLanguage(),
				'tb2.module' => $this->data['module']
			]
		],TRUE);
		$array= [
			'0' => '-- Chọn sản phẩm cần nối link --'
		];
		foreach ($flag as $key => $value) {
			$abc = [
				$value['id'] => $value['title']
			];
			$array = $array + $abc;
		}
		return $array;
	}

	private function get_data_module($id = 0){
		$session = session();
		$flag = $this->AutoloadModel->_get_where([
			'select' => 'tb1.id,tb1.code, tb1.catalogue, tb1.time_end,tb1.sub_album,tb2.sub_album_title,tb2.video, tb1.bar_code, tb1.brandid, tb1.catalogueid, tb1.model, tb1.price_promotion, tb1.price, tb1.productid, tb1.id, tb1.id, tb1.id, tb2.title, tb2.objectid, tb2.sub_title, tb2.sub_content, tb2.description, tb2.canonical,  tb2.content, tb2.meta_title, tb2.meta_description, tb1.album, tb1.publish, tb1.productid_original, tb1.img_sub, tb1.qr_code, tb1.link_tiki, tb1.link_shopee, tb1.link_laz, tb1.link_sendo, tb1.out_of_stock, tb1.best_seller, tb2.address, tb1.map, tb2.*,tb1.video, tb1.vr, tb1.extra, tb1.vr_3d, tb1.status, tb1.info, tb1.bath, tb1.bed, tb1.area',
			'table' => $this->data['module'].' as tb1',
			'join' =>  [
					[
						'product_translate as tb2','tb1.id = tb2.objectid AND tb2.module = \''.$this->data['module'].'\' AND tb2.language = \''.$this->currentLanguage().'\' ','inner'
					]
				],
			'where' => ['tb1.id' => $id,'tb1.deleted_at' => 0]
		]);
		if(!isset($flag) || is_array($flag) == false || count($flag) == 0){
 			return false;
		}else{
			$flag['content'] = base64_decode($flag['content']);
			$flag['description'] = base64_decode($flag['description']);
			$flag['sub_title'] = json_decode(base64_decode($flag['sub_title']));
			$flag['sub_content'] = json_decode(base64_decode($flag['sub_content']));
			$flag['status'] = json_decode($flag['status'],true);
			$flag['info'] = json_decode($flag['info'],true);
		}
		return $flag;
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

	private function validation(){
		$validate = [
			'title' => 'required',
			'price' => 'required',
			'canonical' => 'required|check_router['.$this->data['module'].']',
			'catalogueid' => 'is_natural_no_zero',
		];
		$errorValidate = [
			'title' => [
				'required' => 'Bạn phải nhập tên Sản phẩm!'
			],
			'price' => [
				'required' => 'Bạn phải nhập giá Sản phẩm!'
			],
			'canonical' => [
				'required' => 'Bạn phải nhập giá trị cho trường đường dẫn!',
				'check_router' => 'Đường dẫn đã tồn tại, vui lòng chọn đường dẫn khác!',
			],
			'catalogueid' => [
				'is_natural_no_zero' => 'Bạn Phải chọn danh mục cha cho Sản phẩm!',
			],
		];

		return [
			'validate' => $validate,
			'errorValidate' => $errorValidate,
		];
	}


}
