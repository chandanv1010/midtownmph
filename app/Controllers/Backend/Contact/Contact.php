<?php 
namespace App\Controllers\Backend\Contact;
use App\Controllers\BaseController;

class Contact extends BaseController{
	protected $data;
	
	public function __construct(){
		$this->data = [];
		$this->data['module'] = 'contact';
	}

	public function index($page = 1){
		
		helper(['mypagination']);
		$page = (int)$page;
		$perpage = ($this->request->getGet('perpage')) ? $this->request->getGet('perpage') : 20;
		$where = $this->condition_where();
		$keyword = $this->condition_keyword();

		$config['total_rows'] = $this->AutoloadModel->_get_where([
			'select' => 'tb1.id',
			'table' => $this->data['module'].' as tb1',
			'keyword' => $keyword,
			'where' => $where,
			'join' => [
				[
					'contact_translate as tb2','tb1.id = tb2.objectid AND tb2.module = "contact" AND tb2.language = \''.$this->currentLanguage().'\'','inner'
				],
				
			],
			'group_by' => 'tb1.id',
			'count' => TRUE,
		]);
		if($config['total_rows'] > 0){
			$config = pagination_config_bt(['url' => 'backend/contact/contact/index','perpage' => $perpage], $config);

			$this->pagination->initialize($config);
			$this->data['pagination'] = $this->pagination->create_links();


			$totalPage = ceil($config['total_rows']/$config['per_page']);
			$page = ($page <= 0)?1:$page;
			$page = ($page > $totalPage)?$totalPage:$page;
			$page = $page - 1;
			$this->data['contactList'] = $this->AutoloadModel->_get_where([
				'select' => 'tb1.id, tb1.order, tb2.fullname, tb1.phone, tb1.email, tb2.content,tb2.address, tb1.created_at, tb2.title, tb1.seen',
				'table' => $this->data['module'].' as tb1',
				'where' => $where,
				'keyword' => $keyword,
				'join' => [
					[
						'contact_translate as tb2','tb1.id = tb2.objectid AND tb2.module = "contact" AND tb2.language = \''.$this->currentLanguage().'\'','inner'
					],
				],
				'limit' => $config['per_page'],
				'start' => $page * $config['per_page'],
				'order_by'=> 'tb1.seen asc,tb1.id desc',
				'group_by' => 'tb1.id'
			], TRUE);
		}
		$this->data['template'] = 'backend/contact/contact/index';
		return view('backend/dashboard/layout/home', $this->data);
	}

	public function reply($id = 0){
		$session = session();
		$id = (int)$id;
		$flag = $this->AutoloadModel->_update([
			'table' => 'contact',
			'data' => ['seen' => 1],
			'where' => [
				'id' => $id
			],
		]);
		$explode = explode('_', $this->data['module']);
		$this->data['data'] = $this->AutoloadModel->_get_where([
			'select' => ' id',
			'table'  => 'contact',
			'where'  => ['id' => $id,'deleted_at' => 0]
		]);
		$session = session();
		if(!isset($this->data['data']) || is_array($this->data['data']) == false || count($this->data['data']) == 0){
			$session->setFlashdata('message-danger', 'Bản ghi không tồn tại');
 			return redirect()->to(BASE_URL.'backend/contact/contact/index');
		}
		
		$this->data['contact'] = $this->AutoloadModel->_get_where([
			'select' => ' tb2.title, tb2.content, tb2.fullname, tb1.email, tb1.phone, tb2.address, tb1.created_at',
			'table'  => 'contact as tb1',
			'join' => [
				[
					'contact_translate as tb2','tb2.objectid = tb1.id AND tb2.module = "contact" AND tb2.language = \''.$this->currentLanguage().'\'','inner'
				],
			],
			'where'  => ['tb1.id' => $id,'tb1.deleted_at' => 0]
		]);
		
		$this->data['template'] = 'backend/contact/contact/reply';
		return view('backend/dashboard/layout/popup', $this->data);
	}

	

	private function validation(){
	
		$validate = [
			
			
			'replycontent' => 'required',
		];
		$errorValidate = [
			'replycontent' => [
				'required' => 'Bạn chưa nhập nội dung trả lời!',
			],
			
		];
		return [
			'validate' => $validate,
			'errorValidate' => $errorValidate,
		];
	}

	private function condition_where(){
		$where = [];
		$deleted_at = $this->request->getGet('deleted_at');
		if(isset($deleted_at)){
			$where['tb1.deleted_at'] = $deleted_at;
		}else{
			$where['tb1.deleted_at'] = 0;
		}

		return $where;
	}

	private function condition_keyword($keyword = ''): string{
		if(!empty($this->request->getGet('keyword'))){
			$keyword = $this->request->getGet('keyword');
			$keyword = '(fullname LIKE \'%'.$keyword.'%\' OR address LIKE \'%'.$keyword.'%\' OR email LIKE \'%'.$keyword.'%\' OR phone LIKE \'%'.$keyword.'%\')';
		}
		return $keyword;
	}

	private function store($objectid = 0, $param = []){
		helper(['text']);
		$store = [
 			'content' => $this->request->getPost('replycontent'),
 			'objectid' => $objectid,
 			'deleted_at' => 0,
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

}
