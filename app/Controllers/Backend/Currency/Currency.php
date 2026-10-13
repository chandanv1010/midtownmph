<?php
namespace App\Controllers\Backend\Currency;
use App\Controllers\BaseController;
use App\Libraries\Nestedsetbie;

class Currency extends BaseController{
	protected $data;
	public $nestedsetbie;


	public function __construct(){
		$this->data = [];
		$this->data['module'] = 'currency';
	}

	public function index($page = 1){
		$session = session();
		$flag = $this->authentication->check_permission([
			'routes' => 'backend/currency/currency/index'
		]);
		if($flag == false){
 			$session->setFlashdata('message-danger', 'Bạn không có quyền truy cập vào chức năng này!');
 			return redirect()->to(BASE_URL.'backend/dashboard/dashboard/index');
		}

		$join = $this->join();

		$where = [
			'tb1.deleted_at' => 0,
		];

		helper(['mypagination']);
		$page = (int)$page;
		$perpage = ($this->request->getGet('perpage')) ? $this->request->getGet('perpage') : 20;
		$config['total_rows'] = $this->AutoloadModel->_get_where([
			'select' => 'tb1.id, tb2.title',
			'table' => $this->data['module'].' as tb1',
			'where' => $where,
			'join' => $join,
			'count' => TRUE,
		]);

		if($config['total_rows'] > 0){
			$config = pagination_config_bt(['url' => 'backend/currency/currency/index','perpage' => $perpage], $config);

			$this->pagination->initialize($config);
			$this->data['pagination'] = $this->pagination->create_links();


			$totalPage = ceil($config['total_rows']/$config['per_page']);
			$page = ($page <= 0)?1:$page;
			$page = ($page > $totalPage)?$totalPage:$page;
			$page = $page - 1;
			$languageDetact = $this->detect_language();
			$this->data['currencyList'] = $this->AutoloadModel->_get_where([
				'select' => 'tb1.id, tb2.title as currency_title, tb1.publish, tb1.order, tb5.fullname as creator, tb1.created_at, tb1.updated_at, tb1.default, '.((isset($languageDetact['select'])) ? $languageDetact['select'] : ''),
				'table' => $this->data['module'].' as tb1',
				'where' => $where,
				'join' => $join,
				'limit' => $config['per_page'],
				'start' => $page * $config['per_page'],
				'order_by'=> 'tb1.order desc, tb1.id desc',
				'group_by' => 'tb1.id'
			], TRUE);
		}
		$this->data['template'] = 'backend/currency/currency/index';
		return view('backend/dashboard/layout/home', $this->data);
	}

	public function create(){
		$session = session();

		$default = 0;
		$currency = get_currency();
		$data['currency'] = $currency;

		if (!check_array($currency)) {
			$default = 1;
		}
		if($this->request->getMethod() == 'post'){
			$validate = $this->validation();
			if($this->validate($validate['validate'], $validate['errorValidate'])){
		 		$insert = $this->store(['method' => 'create']);
		 		$insert['default'] = $default;

		 		$resultid = $this->AutoloadModel->_insert([
		 			'table' => $this->data['module'],
		 			'data' => $insert,
		 		]);

		 		if($resultid > 0){
		 			$storeLanguage = $this->storeLanguage($resultid);
		 			$insertid = $this->AutoloadModel->_insert([
			 			'table' => 'currency_translate',
			 			'data' => $storeLanguage,
			 		]);
	 				$flag = $this->create_relationship([
	 					'id' => $resultid,
	 				]);
	 				if($flag > 0){
	 					$session->setFlashdata('message-success', 'Khởi tạo Thành Công !');
 						return redirect()->to(BASE_URL.'backend/currency/currency/index');
	 				}else{
	 					$session->setFlashdata('message-danger', 'Có vấn đề xảy ra, vui lòng thử lại!');
	 					return redirect()->to(BASE_URL.'backend/currency/currency/index');
	 				}
		 		}
	        }else{
	        	$this->data['validate'] = $this->validator->listErrors();
	        }
		}
		$this->data['fixWrapper'] = 'fix-wrapper';
		$this->data['method'] = 'create';
		$this->data['template'] = 'backend/currency/currency/create';
		return view('backend/dashboard/layout/home', $this->data);
	}

	public function update($id = 0){
		$id = (int)$id;
		$select = $this->select($this->data['module']);
		$join = $this->join();

		$this->data[$this->data['module']] = $this->AutoloadModel->_get_where([
			'select' => $select,
			'table' => $this->data['module'].' as tb1',
			'join' => $join,
			'where' => ['tb1.id' => $id,'tb1.deleted_at' => 0],
		]);

		$session = session();
		if(!isset($this->data[$this->data['module']]) || is_array($this->data[$this->data['module']]) == false || count($this->data[$this->data['module']]) == 0){
			$session->setFlashdata('message-danger', 'Tiền tệ không tồn tại');
 			return redirect()->to(BASE_URL.'backend/currency/currency/index');
		}

		$currency_convert = get_currency_relationship([
			'tb1.id' => $id,
		]);

		// print_r($currency_convert); exit;

		$this->data['currency_convert'] = $currency_convert;

		if($this->request->getMethod() == 'post'){
			$validate = $this->validation();
			if ($this->validate($validate['validate'], $validate['errorValidate'])){
		 		$update = $this->store(['method' => 'update']);
		 		$updateLanguage = $this->storeLanguage($id);

		 		$flag = $this->AutoloadModel->_update([
		 			'table' => $this->data['module'],
		 			'where' => ['id' => $id],
		 			'data' => $update
		 		]);

		 		if($flag > 0){
		 			$flagLang = $this->AutoloadModel->_update([
			 			'table' => $this->data['module'].'_translate',
			 			'where' => ['objectid' => $id, 'module' => 'currency', 'language' => $this->currentLanguage()],
			 			'data' => $updateLanguage,
			 		]);
			 		$flag = $this->create_relationship([
			 			'id' => $id,
			 		]);
			 		
		 			$session->setFlashdata('message-success', 'Cập Nhật Tiền tệ Thành Công!');
 					return redirect()->to(BASE_URL.'backend/currency/currency/index');
		 		}

	        }else{
	        	$this->data['validate'] = $this->validator->listErrors();
	        }
		}
		$this->data['fixWrapper'] = 'fix-wrapper';
		$this->data['method'] = 'update';
		$this->data['template'] = 'backend/currency/currency/update';
		return view('backend/dashboard/layout/home', $this->data);
	}

	public function delete($id = 0){

		$id = (int)$id;
		$this->data[$this->data['module']] = $this->AutoloadModel->_get_where([
			'select' => 'tb1.id, tb4.title ',
			'table' => $this->data['module'].' as tb1',
			'join' => [
					[
						'object_relationship as tb2', 'tb1.id = tb2.objectid AND tb2.module = \''.$this->data['module'].'\' ', 'inner'
					],
					[
						'user as tb3','tb1.userid_created = tb3.id','inner'
					],
					[
						'currency_translate as tb4','tb1.id = tb4.objectid AND tb4.module="currency" AND tb4.language = \''.$this->currentLanguage().'\' ','inner'
					]
				],
			'where' => ['tb1.id' => $id,'tb1.deleted_at' => 0]
		]);
		$session = session();
		if(!isset($this->data[$this->data['module']]) || is_array($this->data[$this->data['module']]) == false || count($this->data[$this->data['module']]) == 0){
			$session->setFlashdata('message-danger', 'Tiền tệ không tồn tại');
 			return redirect()->to(BASE_URL.'backend/currency/currency/index');
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
	 			$session->setFlashdata('message-success', 'Xóa bản ghi thành công!');
			}else{
				$session->setFlashdata('message-danger', 'Có vấn đề xảy ra, vui lòng thử lại!');
			}
			return redirect()->to(BASE_URL.'backend/currency/currency/index');
		}

		$this->data['template'] = 'backend/currency/currency/delete';
		return view('backend/dashboard/layout/home', $this->data);
	}

	private function condition_where(){
		$where = [];
		
		$where['tb1.publish'] = ($this->request->getGet('publish'))?? 1;
		$where['tb1.deleted_at'] = ($this->request->getGet('deleted_at'))?? 0;

		return $where;
	}

	private function condition_keyword($keyword = ''): string{
		if(!empty($this->request->getGet('keyword'))){
			$keyword = $this->request->getGet('keyword');
			$keyword = '(tb3.title LIKE \'%'.$keyword.'%\')';
		}
		return $keyword;
	}

	private function storeLanguage($objectid = 0){
		helper(['text']);
		$store = [
			'objectid' => $objectid,
			'title' => validate_input($this->request->getPost('title')),
			'language' => $this->currentLanguage(),
			'module' => $this->data['module'],
		];
		return $store;
	}

	private function store($param = []){
		helper(['text']);
		$store = [
 			'publish' => $this->request->getPost('publish'),
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


	private function detect_language(){
		$languageList = $this->AutoloadModel->_get_where([
			'select' => 'id, canonical',
			'table' => 'language',
			'where' => ['publish' => 1,'deleted_at' => 0,'canonical !=' =>  $this->currentLanguage()]
		], TRUE);

		$select = '';

		$i = 3;
		if(isset($languageList) && is_array($languageList) && count($languageList)){
			foreach($languageList as $key => $val){
				$select = $select.'(SELECT COUNT(objectid) FROM currency_translate WHERE currency_translate.objectid = tb1.id AND currency_translate.module = "currency" AND  currency_translate.language = "'.$val['canonical'].'") as '.$val['canonical'].'_detect, ';
				$i++;
			}
		}
		return [
			'select' => $select,
		];
	}

	private function validation(){
		$param = [
			'title' => 'Tiền tệ',
		];

		$validate = [
			'title' => 'required|_CheckCurrency',
		];
		$errorValidate = [
			'title' => [
				'required' => 'Bạn phải nhập vào trường '.$param['title'],
				'_CheckCurrency' => $param['title'].' đã tồn tại',
			],
		];
		return [
			'validate' => $validate,
			'errorValidate' => $errorValidate,
		];
	}

	private function join(){
		$join = [
			[
				'currency_translate as tb2','tb1.id = tb2.objectid AND tb2.module = "currency" AND tb2.language = \''.$this->currentLanguage().'\' ','inner'
			],
			[
				'user as tb5','tb1.userid_created = tb5.id','inner'
			],
		];

		return $join;
	}

	private function select($module = ''){
		$select = dt_get_query_module($module);

		return $select;
	}

	private function create_relationship($param = []){
		$id = ($param['id'])?? 0;
		// del currency_relationship
		$this->AutoloadModel->_delete(array(
			'where' => array(
				'default_id' => $id
			),
			'table' => 'currency_relationship',
		));

		$_insert_batch = [];
		$_convert = $this->request->getPost('convert');
		if (check_array($_convert)) {
			foreach ($_convert as $key => $value) {
				$_insert_batch[] = [
					'default_id' => $id,
					'convert_id' => $key,
					'convert_rate' => str_replace(',','',$value),
				];
			}
		}
		// tạo currency_relationship
		$this->AutoloadModel->_create_batch(array(
			'table' => 'currency_relationship',
			'data' => $_insert_batch,
		));
	}


}
