<?php
namespace App\Controllers\Backend\Tour;
use App\Controllers\BaseController;
use App\Libraries\Nestedsetbie;

class Tour extends BaseController{
	protected $data;
	public $nestedsetbie;


	public function __construct(){
		$this->data = [];
		$this->data['module'] = 'tour';
		$this->nestedsetbie = new Nestedsetbie(['table' => $this->data['module'].'_catalogue','language' => $this->currentLanguage()]);

	}

	public function index($page = 1){
		$session = session();
		$flag = $this->authentication->check_permission([
			'routes' => 'backend/tour/tour/index'
		]);
		if($flag == false){
 			$session->setFlashdata('message-danger', 'Bạn không có quyền truy cập vào chức năng này!');
 			return redirect()->to(BASE_URL.'backend/dashboard/dashboard/index');
		}
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
		$config['total_rows'] = $this->AutoloadModel->_get_where([
			'select' => 'tb1.id',
			'table' => $this->data['module'].' as tb1',
			'join' => [
				[
					'object_relationship as tb2', 'tb1.id = tb2.objectid AND tb2.module = \''.$this->data['module'].'\' ', 'inner'
				],
				[
					'tour_translate as tb3','tb1.id = tb3.objectid AND tb3.module = "tour" AND tb3.language = \''.$this->currentLanguage().'\' ','inner'
				],
				[
					'tour_translate as tb4','tb1.catalogueid = tb4.objectid AND tb4.module = "tour_catalogue" AND tb4.language = \''.$this->currentLanguage().'\' ','inner'
				],

			],
			'keyword' => $keyword,
			'where' => $where,
			'count' => TRUE
		]);

		if($config['total_rows'] > 0){
			$config = pagination_config_bt(['url' => 'backend/tour/tour/index','perpage' => $perpage], $config);

			$this->pagination->initialize($config);
			$this->data['pagination'] = $this->pagination->create_links();
			$totalPage = ceil($config['total_rows']/$config['per_page']);
			$page = ($page <= 0)?1:$page;
			$page = ($page > $totalPage)?$totalPage:$page;
			$page = $page - 1;

			$catalogue = $this->condition_catalogue();
			$languageDetact = $this->detect_language();
			$this->data['tourList'] = $this->AutoloadModel->_get_where([
				'select' => 'tb1.id, tb1.time_end, tb1.catalogueid as cat_id, tb1.price,tb1.order, tb1.price_promotion, tb1.album,  tb2.catalogueid, tb1.publish, tb3.title as tour_title, tb1.catalogue, tb2.objectid, tb3.content, tb3.sub_title, tb3.sub_content, tb3.canonical, tb3.meta_title, tb3.meta_description,  tb4.title as cat_title ,'.((isset($languageDetact['select'])) ? $languageDetact['select'] : ''),
				'table' => $this->data['module'].' as tb1',
				'where' => $where,
				'where_in' => $catalogue['where_in'],
				'where_in_field' => $catalogue['where_in_field'],
				'keyword' => $keyword,
				'join' => [
					[
						'object_relationship as tb2', 'tb1.id = tb2.objectid AND tb2.module = \''.$this->data['module'].'\' ', 'inner'
					],
					[
						'tour_translate as tb3','tb1.id = tb3.objectid AND tb3.module = "tour" AND tb3.language = \''.$this->currentLanguage().'\' ','inner'
					],
					[
						'tour_translate as tb4','tb1.catalogueid = tb4.objectid AND tb4.module = "tour_catalogue" AND tb4.language = \''.$this->currentLanguage().'\' ','inner'
					],

				],
				'limit' => $config['per_page'],
				'start' => $page * $config['per_page'],
				'order_by'=> 'tb1.order desc, tb1.id desc',
				'group_by' => 'tb1.id'
			], TRUE);
			// pre($this->data['tourList'] );
		}

		$this->data['dropdown'] = $this->nestedsetbie->dropdown();
		$this->data['template'] = 'backend/tour/tour/index';
		return view('backend/dashboard/layout/home', $this->data);
	}

	public function create(){
		$session = session();
		$flag = $this->authentication->check_permission([
			'routes' => 'backend/tour/tour/create'
		]);
		if($flag == false){
 			$session->setFlashdata('message-danger', 'Bạn không có quyền truy cập vào chức năng này!');
 			return redirect()->to(BASE_URL.'backend/dashboard/dashboard/index');
		}
		$this->data['attribute_catalogue'] = get_attribute_catalogue($this->currentLanguage(), $this->data['module']);
		$this->data['location_end'] = $this->location('end')['select'];
		$this->data['location_start'] = $this->location('start')['select'];
		$this->data['get_canonical'] = $this->get_canonical();
		$this->data['check_code'] = $this->AutoloadModel->_get_where([
			'select' => 'code,objectid',
			'table' => 'id_general',
			'where' => ['module' => $this->data['module']],
		]);
		if(!isset($this->data['check_code']) && !is_array($this->data['check_code'])){
			$session->setFlashdata('message-danger', 'Bạn chưa tạo phần cấu hình chung cho mã Chuyến du lịch!');
 			return redirect()->to(BASE_URL.'backend/tour/tour/index');
		}else{
			$this->data['export_brand'] = $this->export_brand();
			$this->data['tourid'] = convert_code($this->data['check_code']['code'], $this->data['module']);
			if($this->request->getMethod() == 'post'){



				


				$validate = $this->validation();
				if($this->validate($validate['validate'], $validate['errorValidate'])){
					$sub_content = $this->request->getPost('sub_content');
					$schedule = $this->request->getPost('schedule');
			 		$insert = $this->store(['method' => 'create']);

			 		$resultid = $this->AutoloadModel->_insert([
			 			'table' => $this->data['module'],
			 			'data' => $insert,
			 		]);

			 		if($resultid > 0){
			 			$storeLanguage = $this->storeLanguage($resultid);
			 			$storeLanguage = $this->convert_content($sub_content, $storeLanguage);
						$this->version($resultid, 'create');
						$this->AutoloadModel->_insert([
							'table' => 'tour_translate',
							'data' => $storeLanguage
						]);
				 		$this->AutoloadModel->_update([
	 						'table' => 'id_general',
	 						'data' => [
	 							'objectid' => $this->data['check_code']['objectid'] + 1
	 						],
	 						'where' => ['module' => $this->data['module']]
	 					]);

		 				$this->insert_router(['method' => 'create','id' => $resultid]);
	 					$flag = $this->create_relationship($resultid);
	 					$this->insert_location([
			 				'id' => $resultid,
			 				'end' => $this->request->getPost('end_at'),
			 				'start' => $this->request->getPost('start_at'),
			 			]);
			 			$this->attribute_relationship($resultid);
		 				$this->insert_tags($resultid);
			 			$this->nestedsetbie->Get('level ASC, order ASC');
						$this->nestedsetbie->Recursive(0, $this->nestedsetbie->Set());
						$this->nestedsetbie->Action();
			 			$session->setFlashdata('message-success', 'Tạo Chuyến du lịch Thành Công! Hãy tạo danh mục tiếp theo.');
	 					return redirect()->to(BASE_URL.'backend/tour/tour/create');
			 		}
		        }else{
		        	$this->data['validate'] = $this->validator->listErrors();
		        }
			}
		}

		$this->data['fixWrapper'] = 'fix-wrapper';
		$this->data['dropdown'] = $this->nestedsetbie->dropdown();
		$this->data['method'] = 'create';
		$this->data['template'] = 'backend/tour/tour/create';
		return view('backend/dashboard/layout/home', $this->data);
	}

	public function update($id = 0){
		$session = session();
		$flag = $this->authentication->check_permission([
			'routes' => 'backend/tour/tour/update'
		]);
		if($flag == false){
 			$session->setFlashdata('message-danger', 'Bạn không có quyền truy cập vào chức năng này!');
 			return redirect()->to(BASE_URL.'backend/dashboard/dashboard/index');
		}
		$id = (int)$id;
		$this->data['export_brand'] = $this->export_brand();
		$this->data['get_canonical'] = $this->get_canonical();
		$this->data['attribute_catalogue'] = get_attribute_catalogue($this->currentLanguage(), $this->data['module']);
		$this->data['location_end'] = $this->location('end')['select'];
		$this->data['location_start'] = $this->location('start')['select'];
		$this->data['tags'] = $this->get_tags($id);
		
		$this->data[$this->data['module']] = $this->get_data_module($id);
		if($this->data[$this->data['module']] == false){
			$session->setFlashdata('message-danger', 'Chuyến du lịch không tồn tại!');
 			return redirect()->to(BASE_URL.'backend/tour/tour/index');
		}
		// pre($this->data[$this->data['module']]);
		if(isset($this->data[$this->data['module']]['sub_title']) && is_array($this->data[$this->data['module']]['sub_title']) && count($this->data[$this->data['module']]['sub_title'])){
			$this->data['sub_album'] = $this->rewrite_album($this->data[$this->data['module']]);
		}

		$this->data['version'] = $this->get_data_version($id);
		// pre($this->data['version']);

		$this->data['schedule_list'] = $this->get_list_schedule($id);

		if($this->request->getMethod() == 'post'){
			
			$validate = $this->validation();
			if ($this->validate($validate['validate'], $validate['errorValidate'])){
				$sub_content = $this->request->getPost('sub_content');
				$schedule = $this->request->getPost('schedule');
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
			 			'table' => 'tour_translate',
			 			'where' => ['objectid' => $id, 'module' => $this->data['module'],'language' => $this->currentLanguage()],
			 			'data' => $updateLanguage
			 		]);
			 		if($schedule != []){
						$this->insert_schedule($schedule, $this->data['module'],'update', $id);
			 		}
			 		$flag = $this->create_relationship($id);
					$this->version($id, 'update');
		 			$this->insert_router(['method' => 'update','id' => $id]);
		 			$this->insert_location([
		 				'id' => $id,
		 				'end' => $this->request->getPost('end_at'),
		 				'start' => $this->request->getPost('start_at'),
		 			]);
		 			$this->insert_tags($id);
		 			$this->attribute_relationship($id);
		 			$this->nestedsetbie->Get('level ASC, order ASC');
					$this->nestedsetbie->Recursive(0, $this->nestedsetbie->Set());
					$this->nestedsetbie->Action();

		 			$session->setFlashdata('message-success', 'Cập Nhật Chuyến du lịch Thành Công!');
 					return redirect()->to(BASE_URL.'backend/tour/tour/index');
		 		}

	        }else{
	        	$this->data['validate'] = $this->validator->listErrors();
	        }
		}

		$this->data['fixWrapper'] = 'fix-wrapper';
		$this->data['dropdown'] = $this->nestedsetbie->dropdown();
		$this->data['method'] = 'update';
		$this->data['template'] = 'backend/tour/tour/update';
		return view('backend/dashboard/layout/home', $this->data);
	}

	public function delete($id = 0){
		$session = session();
		$flag = $this->authentication->check_permission([
			'routes' => 'backend/tour/tour/delete'
		]);
		if($flag == false){
 			$session->setFlashdata('message-danger', 'Bạn không có quyền truy cập vào chức năng này!');
 			return redirect()->to(BASE_URL.'backend/dashboard/dashboard/index');
		}
		$id = (int)$id;
		$this->data[$this->data['module']] = $this->get_data_module($id);
		if($this->data[$this->data['module']] == false){
			$session->setFlashdata('message-danger', 'Chuyến du lịch không tồn tại!');
 			return redirect()->to(BASE_URL.'backend/tour/tour/index');
		}


		if($this->request->getPost('delete')){
			$_id = $this->request->getPost('id');

			$flag = $this->AutoloadModel->_update([
				'table' => $this->data['module'],
				'data' => ['deleted_at' => 1],
				'where' => [
					'id' => $_id
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
			return redirect()->to(BASE_URL.'backend/tour/tour/index');
		}

		$this->data['template'] = 'backend/tour/tour/delete';
		return view('backend/dashboard/layout/home', $this->data);
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

		return $where;
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

	private function create_relationship($objectid = 0, $catalogue = []){
		if($this->request->getPost('catalogue') != ''){
			$catalogue = $this->request->getPost('catalogue');
		}
		$catalogueid = $this->request->getPost('catalogueid');
		$relationshipId = 	array_unique(array_merge($catalogue, [$catalogueid]));
		$this->AutoloadModel->_delete([
			'table' => 'object_relationship',
			'where' => [
				'module' => $this->data['module'],
				'objectid' => $objectid
			]
		]);
		$insert = [];
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
			$keyword = '(tb3.title LIKE \'%'.$keyword.'%\')';
		}
		return $keyword;
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

	private function storeLanguage($objectid = 0){
		helper(['text']);
		$sub_album['title'] = $this->request->getPost('sub_album_title');
		$store = [
			'objectid' => $objectid,
			'title' => validate_input($this->request->getPost('title')),
			'start_at' => $this->request->getPost('start_at'),
			'end_at' => $this->request->getPost('end_at'),
			'sub_album_title' => json_encode($this->request->getPost('sub_album_title'),TRUE),
			'info' => json_encode($this->request->getPost('info'),TRUE),
			'number_days' => $this->request->getPost('number_days'),
			'video' => $this->request->getPost('video'),
			'day_start' => $this->request->getPost('day_start'),
			'canonical' => slug($this->request->getPost('canonical')),
			'content' => base64_encode($this->request->getPost('content')),
			'description' => base64_encode($this->request->getPost('description')),
			'meta_title' => validate_input($this->request->getPost('meta_title')),
			'meta_description' => validate_input($this->request->getPost('meta_description')),
			'language' => $this->currentLanguage(),
			'module' => $this->data['module'],
		];
		return $store;
	}

	private function store($param = []){
		helper(['text']);
		$catalogue = $this->request->getPost('catalogue');
		$attributeid = $this->request->getPost('attributeid');
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
 			'tourid' => $this->request->getPost('tourid'),
 			'time_end' => $end,
 			'image' => $image,
 			'album' => json_encode($this->request->getPost('album'), TRUE),
 			'sub_album' => json_encode($this->request->getPost('sub_album'), TRUE),
 			'publish' => $this->request->getPost('publish'),
 			'price' => $price,
 			'price_promotion' => $price_promotion,
 		];
 		if($param['method'] == 'create' && isset($param['method'])){
 			$store['created_at'] = $this->currentTime;
 			$store['userid_created'] = $this->auth['id'];

 		}else{
 			$store['updated_at'] = $this->currentTime;
 			$store['userid_updated'] = $this->auth['id'];
 		}
 		return $store;
	}

	private function insert_router($param = []){
		helper(['text']);
		$view = view_cells($this->data['module']);
		$data = [
			'canonical' => $this->request->getPost('canonical'),
			'module' => $this->data['module'],
			'objectid' => $param['id'],
			'language' => $this->currentLanguage(),
			'view' => $view
		];
 		if(isset($param['method']) &&  $param['method'] == 'create' ){
 			$insertRouter = $this->AutoloadModel->_insert([
	 			'table' => 'router',
	 			'data' => $data,
	 		]);
 		}else{
 			$this->AutoloadModel->_update([
	 			'table' => 'router',
	 			'where' => ['objectid' => $param['id'], 'module' => $this->data['module'], 'language' => $this->currentLanguage()],
	 			'data' => [
	 				'canonical' => $data['canonical']
	 			]
	 		]);
 		}
 		return true;
	}

	private function location($attr = ''){
		$data = $this->AutoloadModel->_get_where([
			'select' => 'tb1.id,  tb1.catalogueid,  tb2.title, tb2.keyword,  tb1.userid_updated, tb1.publish, tb1.created_at, tb1.updated_at ',
			'table' => 'location as tb1',
			'join' =>  [
				[
					'location_translate as tb2','tb1.id = tb2.objectid AND tb2.module = "location"   AND tb2.language = \''.$this->currentLanguage().'\'AND tb2.attribute = \''.$attr.'\' ','inner'
				],
			],
			'where' => [
				'tb1.deleted_at' => 0,
				'tb1.publish' => 1
			],
		], TRUE);
		$flag = [
			0 => (($attr == 'start') ? 'Chọn điểm khởi hành' : 'Chọn điểm kết thúc')
		];

		if(isset($data) && is_array($data) && count($data)){
			foreach ($data as $key => $value) {
				$array[$value['id']] = $value['title'];
			}
		}
		$flag = $flag+$array;


 		return [
 			'select' => $flag,
 			'array' => $data
 		];
	}

	private function insert_location($param = []){
		$this->AutoloadModel->_delete([
			'table' => 'location_relationship',
			'where' => [
				'objectid' => $param['id'],
			]
		]);
		$insert[] = [
			'objectid' => $param['id'],
			'catalogueid' => $param['end'],
			'attribute' => 'end',
			'module' => 'location'
		];
		$insert[] = [
			'objectid' => $param['id'],
			'catalogueid' => $param['start'],
			'attribute' => 'start',
			'module' => 'location'
		];
		foreach ($insert as $key => $value) {
			$select = $this->AutoloadModel->_get_where([
				'select' => 'tb1.catalogueid, tb1.id',
				'table' => 'location as tb1',
				'where' => [
					'tb1.id' => $value['catalogueid'],
					'tb1.publish' => 1,
					'tb1.deleted_at' => 0
				]
			]);
			$insert[] = [
				'objectid' => $param['id'],
				'catalogueid' => $select['catalogueid'],
				'attribute' => $value['attribute'],
				'module' => 'location_catalogue'
			];
		}

		$flag = $this->AutoloadModel->_create_batch([
			'table' => 'location_relationship',
			'data' => $insert
		]);
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
				$select = $select.'(SELECT COUNT(objectid) FROM tour_translate WHERE tour_translate.objectid = tb1.id AND tour_translate.module = "tour" AND tour_translate.language = "'.$val['canonical'].'") as '.$val['canonical'].'_detect, ';
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

	private function export_brand(){
		$brand = $this->AutoloadModel->_get_where([
			'select' => 'tb2.id, tb2.title',
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
		foreach ($brand as $key => $value) {
			$new_array[$value['id']] = $value['title'];
		}

		return $new_array;
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
		if($get['attribute1'] != []){
			$flag = insert_version($get , $id, $this->currentLanguage(), $method, $this->data['module'],$version_type);
		}else{
			$flag = insert_version([] , $id, $this->currentLanguage(), $method, $this->data['module'],$version_type);
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

	private function get_data_module($id = 0){
		$flag = $this->AutoloadModel->_get_where([
			'select' => 'tb1.id,tb1.time_end,tb1.sub_album, tb1.catalogue,tb1.catalogueid,tb1.image,  tb1.price_promotion, tb1.price, tb1.tourid, tb1.id, tb2.title, tb2.objectid,tb2.info, tb2.video, tb2.sub_title, tb2.sub_content, tb2.description, tb2.canonical,  tb2.content,tb2.sub_album_title, tb2.meta_title, tb2.meta_description,tb2.day_start, tb2.number_days, tb1.album, tb1.publish, tb2.start_at, tb2.end_at',
			'table' => $this->data['module'].' as tb1',
			'join' =>  [
					[
						'tour_translate as tb2','tb1.id = tb2.objectid AND tb2.module = \''.$this->data['module'].'\' AND tb2.language = \''.$this->currentLanguage().'\' ','inner'
					]
				],
			'where' => ['tb1.id' => $id,'tb1.deleted_at' => 0]
		]);
		if(!isset($flag) || is_array($flag) == false || count($flag) == 0){
 			return false;
		}else{
			$flag['content'] = base64_decode(validate_input($flag['content']));
			$flag['description'] = base64_decode(validate_input($flag['description']));
			$flag['sub_title'] = json_decode(base64_decode($flag['sub_title']));
			$flag['sub_content'] = json_decode(base64_decode($flag['sub_content']));
			$flag['info'] = json_decode($flag['info'],TRUE);
		}

		return $flag;
	}

	private function get_data_version($id = ''){
		$flag = $this->AutoloadModel->_get_where([
			'select' => 'id, objectid, content, attribute, attribute_catalogue, checked, type',
			'table' => 'tour_version',
			'where' => ['objectid' => $id]
		],TRUE);
		foreach ($flag as $key => $value) {

			$flag[$key]['content'] = json_decode($flag[$key]['content'], TRUE);
		}

		return $flag;
	}

	private function insert_schedule(array $param = [], $module = '', $method = '' , $id = ''){
		$new_array = [];
		$module_explode = explode("_", $module);
		foreach ($param as $key => $value) {
			foreach ($param['schedule_start'] as $keyChild => $valChild) {
				if($param['schedule_start'][$keyChild] == '' && $param['schedule_to'][$keyChild] == '' && $param['schedule_price'][$keyChild] == ''){
					unset($param['schedule_start'][$keyChild]);
					unset($param['schedule_to'][$keyChild]);
					unset($param['schedule_price'][$keyChild]);
				}
			}
		}
		$start = json_encode($param['schedule_start']);
		$end = json_encode($param['schedule_to']);
		$price = json_encode($param['schedule_price']);
		
		$store = [
			'objectid' => $id,
			'module' => $module,
			'schedule_start' => $start,
			'schedule_to' => $end,
			'price' => $price
		];
		if($method =='create'){
			$flag = $this->AutoloadModel->_insert([
				'table' => $module_explode[0].'_schedule',
				'data' => $store
			]);
		}else{
			$flag = $this->AutoloadModel->_update([
				'table' => $module_explode[0].'_schedule',
				'data' => $store,
				'where' => [
					'objectid' => $id,
					'module' => $module
				]
			]);

			if($flag == ''){
				$flag = $this->AutoloadModel->_insert([
					'table' => $module_explode[0].'_schedule',
					'data' => $store
				]);
			}
		}

	 	return $flag;
	}

	private function get_list_schedule($id = 0){
		$check = $this->AutoloadModel->_get_where([
			'select' => 'schedule_start, schedule_to, price',
			'table' => 'tour_schedule',
			'where' => ['objectid' => $id]
		]);
		$data = [];

		if(isset($check) && is_array($check) && count($check)){
			$array = [
				'schedule_start' => json_decode($check['schedule_start']),
				'schedule_to' => json_decode($check['schedule_to']),
				'price_schedule' => json_decode($check['price']),
			];
			foreach ($array as $key => $value) {
				foreach ($value as $keyChild => $valChild) {
					$data[$keyChild][$key] = $valChild;
				}
			}
		}
		return $data;
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
						'tour_translate as tb3','tb1.id = tb3.objectid AND tb3.language = \''.$this->currentLanguage().'\' ','inner'
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
			'0' => '-- Chọn Tour cần nối link --'
		];
		foreach ($flag as $key => $value) {
			$abc = [
				$value['id'] => $value['title']
			];
			$array = $array + $abc;
		}
		return $array;
	}

	private function validation(){
		$validate = [
			'title' => 'required',
			'number_days' => 'required',
			'day_start' => 'required',
			'price' => 'required',
			'tourid' => 'required|check_id['.$this->data['module'].']',
			'canonical' => 'required|check_router['.$this->data['module'].']',
			'catalogueid' => 'is_natural_no_zero',
			'start_at' => 'is_no_0',
			'end_at' => 'is_no_0',
		];
		$errorValidate = [
			'title' => [
				'required' => 'Bạn phải nhập tên Chuyến du lịch!'
			],
			'number_days' => [
				'required' => 'Bạn phải nhập số ngày Tour!'
			],
			'day_start' => [
				'required' => 'Bạn phải nhập Ngày khởi hành!'
			],
			'tourid' => [
				'required' => 'Bạn phải nhập mã Chuyến du lịch!',
				'check_id' => 'Mã Chuyến du lịch đã tồn tại, vui lòng chọn mã Chuyến du lịch khác!',
			],
			'price' => [
				'required' => 'Bạn phải nhập giá Chuyến du lịch!'
			],
			'canonical' => [
				'required' => 'Bạn phải nhập giá trị cho trường đường dẫn!',
				'check_router' => 'Đường dẫn đã tồn tại, vui lòng chọn đường dẫn khác!',
			],
			'catalogueid' => [
				'is_natural_no_zero' => 'Bạn Phải chọn danh mục cha cho Chuyến du lịch!',
			],
			'start_at' => [
				'is_no_0' => 'Bạn Phải chọn điểm xuất phát!',
			],
			'end_at' => [
				'is_no_0' => 'Bạn Phải chọn điểm kết thúc!',
			],
		];

		return [
			'validate' => $validate,
			'errorValidate' => $errorValidate,
		];
	}


}
