<?php
namespace App\Controllers\Backend\Article;
use App\Controllers\BaseController;
use App\Libraries\Nestedsetbie;

class Article extends BaseController{
	protected $data;
	public $nestedsetbie;


	public function __construct(){
		$this->data = [];
		$this->data['module'] = 'article';
		$this->data['module2'] = 'article_catalogue';
		$this->nestedsetbie = new Nestedsetbie(['table' => $this->data['module'].'_catalogue','language' => $this->currentLanguage()]);

	}

	public function index($page = 1){
		$session = session();
		$flag = $this->authentication->check_permission([
			'routes' => 'backend/article/article/index'
		]);
		if($flag == false){
 			$session->setFlashdata('message-danger', 'Bạn không có quyền truy cập vào chức năng này!');
 			return redirect()->to(BASE_URL.'backend/dashboard/dashboard/index');
		}

		helper(['mypagination']);
		$page = (int)$page;
		$perpage = ($this->request->getGet('perpage')) ? $this->request->getGet('perpage') : 20;
		$where = $this->condition_where();
		$keyword = $this->condition_keyword();
		$catalogue = $this->condition_catalogue();
		$config['total_rows'] = $this->AutoloadModel->_get_where([
			'select' => 'tb1.id, tb3.title',
			'table' => $this->data['module'].' as tb1',
			'keyword' => $keyword,
			'where' => $where,
			'where_in' => $catalogue['where_in'],
			'where_in_field' => $catalogue['where_in_field'],
			'join' => [
				[
					'object_relationship as tb2', 'tb1.id = tb2.objectid AND tb2.module = \''.$this->data['module'].'\' ', 'inner'
				],
				[
					'article_translate as tb3','tb1.id = tb3.objectid AND tb3.module = "article" AND tb3.language = \''.$this->currentLanguage().'\' ','inner'
				],
				[
					'article_translate as tb4','tb1.catalogueid = tb4.objectid AND tb4.module = "article_catalogue" AND tb4.language = \''.$this->currentLanguage().'\' ','inner'
				],
				[
					'user as tb5','tb1.userid_created = tb5.id','inner'
				],
			],
			'group_by' => 'tb1.id',
			'count' => TRUE,
		]);
		if($config['total_rows'] > 0){
			$config = pagination_config_bt(['url' => 'backend/article/article/index','perpage' => $perpage], $config);

			$this->pagination->initialize($config);
			$this->data['pagination'] = $this->pagination->create_links();


			$totalPage = ceil($config['total_rows']/$config['per_page']);
			$page = ($page <= 0)?1:$page;
			$page = ($page > $totalPage)?$totalPage:$page;
			$page = $page - 1;
			$languageDetact = $this->detect_language();
			$this->data['articleList'] = $this->AutoloadModel->_get_where([
				'select' => 'tb1.id,  tb1.catalogueid as cat_id,tb1.image,tb1.viewed, tb1.order,tb1.created_at,  tb1.album,  tb2.catalogueid, tb1.publish, tb3.title as article_title, tb1.catalogue, tb2.objectid, tb3.content,  tb3.canonical, tb3.meta_title, tb3.meta_description,  tb4.title as cat_title ,tb4.objectid as cat_id, tb5.fullname as creator,'.((isset($languageDetact['select'])) ? $languageDetact['select'] : ''),
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
						'article_translate as tb3','tb1.id = tb3.objectid AND tb3.module = "article" AND tb3.language = \''.$this->currentLanguage().'\' ','inner'
					],
					[
						'article_translate as tb4','tb1.catalogueid = tb4.objectid AND tb4.module = "article_catalogue" AND tb4.language = \''.$this->currentLanguage().'\' ','inner'
					],
					[
						'user as tb5','tb1.userid_created = tb5.id','inner'
					],
				],
				'limit' => $config['per_page'],
				'start' => $page * $config['per_page'],
				'order_by'=> 'tb1.order desc, tb1.id desc',
				'group_by' => 'tb1.id'
			], TRUE);
		}
		$this->data['dropdown'] = $this->nestedsetbie->dropdown();
		$this->data['template'] = 'backend/article/article/index';
		return view('backend/dashboard/layout/home', $this->data);
	}

	public function create(){
		$session = session();
		if($this->request->getMethod() == 'post'){
			$validate = $this->validation();
			if ($this->validate($validate['validate'], $validate['errorValidate'])){
		 		$insert = $this->store(['method' => 'create']);

		 		$resultid = $this->AutoloadModel->_insert([
		 			'table' => $this->data['module'],
		 			'data' => $insert,
		 		]);

		 		if($resultid > 0){
		 			$storeLanguage = $this->storeLanguage($resultid);
		 			$insertid = $this->AutoloadModel->_insert([
			 			'table' => 'article_translate',
			 			'data' => $storeLanguage,
			 		]);
		 			$this->insert_router(['method' => 'create','id' => $resultid]);
		 			$this->insert_tags($resultid);
	 				$flag = $this->create_relationship($resultid);
	 				if($flag > 0){
	 					$session->setFlashdata('message-success', 'Tạo Bài Viết Thành Công! Hãy tạo danh mục tiếp theo.');
 						return redirect()->to(BASE_URL.'backend/article/article/index');
	 				}else{
	 					$session->setFlashdata('message-danger', 'Có vấn đề xảy ra, vui lòng thử lại!');
	 					return redirect()->to(BASE_URL.'backend/article/article/index');
	 				}
		 		}
	        }else{
	        	$this->data['validate'] = $this->validator->listErrors();
	        }
		}
		$this->data['dropdown'] = $this->nestedsetbie->dropdown();
		$this->data['fixWrapper'] = 'fix-wrapper';
		$this->data['method'] = 'create';
		$this->data['template'] = 'backend/article/article/create';
		return view('backend/dashboard/layout/home', $this->data);
	}

	public function update($id = 0){
		$id = (int)$id;
		$this->data[$this->data['module']] = $this->AutoloadModel->_get_where([
			'select' => 'tb1.id, tb4.title, tb4.canonical, tb4.description, tb4.content, tb4.meta_title, tb4.meta_description, tb1.catalogueid, tb1.image, tb1.album, tb1.publish, tb1.catalogue, tb1.files',
			'table' => $this->data['module'].' as tb1',
			'join' => [
					[
						'article_translate as tb4','tb1.id = tb4.objectid AND tb4.module = "article" AND tb4.language = \''.$this->currentLanguage().'\' ','inner'
					]
				],
			'where' => ['tb1.id' => $id,'tb1.deleted_at' => 0]
		]);

		$this->data['tags'] = $this->get_tags($id);
		$session = session();
		if(!isset($this->data[$this->data['module']]) || is_array($this->data[$this->data['module']]) == false || count($this->data[$this->data['module']]) == 0){
			$session->setFlashdata('message-danger', 'Bài Viết không tồn tại');
 			return redirect()->to(BASE_URL.'backend/article/article/index');
		}

		$this->data[$this->data['module']]['description'] = base64_decode($this->data[$this->data['module']]['description']);
		$this->data[$this->data['module']]['content'] = base64_decode($this->data[$this->data['module']]['content']);

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
			 			'where' => ['objectid' => $id, 'module' => 'article', 'language' => $this->currentLanguage()],
			 			'data' => $updateLanguage,
			 		]);
			 		$flag = $this->create_relationship($id);
			 		$this->insert_tags($id);
			 		if($updateLanguage['canonical'] != $this->data[$this->data['module']]['canonical']){
		 				$this->insert_router(['method' => 'update','id' => $id]);

			 		}
		 			$session->setFlashdata('message-success', 'Cập Nhật Bài Viết Thành Công!');
 					return redirect()->to(BASE_URL.'backend/article/article/index');
		 		}

	        }else{
	        	$this->data['validate'] = $this->validator->listErrors();
	        }
		}
		$this->data['dropdown'] = $this->nestedsetbie->dropdown();
		$this->data['fixWrapper'] = 'fix-wrapper';
		$this->data['method'] = 'update';
		$this->data['template'] = 'backend/article/article/update';
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
						'article_translate as tb4','tb1.id = tb4.objectid AND tb4.module="article" AND tb4.language = \''.$this->currentLanguage().'\' ','inner'
					]
				],
			'where' => ['tb1.id' => $id,'tb1.deleted_at' => 0]
		]);
		$session = session();
		if(!isset($this->data[$this->data['module']]) || is_array($this->data[$this->data['module']]) == false || count($this->data[$this->data['module']]) == 0){
			$session->setFlashdata('message-danger', 'Bài Viết không tồn tại');
 			return redirect()->to(BASE_URL.'backend/article/article/index');
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
			return redirect()->to(BASE_URL.'backend/article/article/index');
		}

		$this->data['template'] = 'backend/article/article/delete';
		return view('backend/dashboard/layout/home', $this->data);
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

	public function condition_catalogue(){
		$catalogueid = $this->request->getGet('catalogueid');
		$id = [];
		if($catalogueid > 0){
			$catalogue = $this->AutoloadModel->_get_where([
				'select' => 'tb1.id, tb1.lft, tb1.rgt, tb4.title',
				'table' => $this->data['module'].'_catalogue as tb1',
				'join' =>  [
					[
						'article_translate as tb4','tb1.id = tb4.objectid AND tb4.module="article_catalogue" AND tb4.language = \''.$this->currentLanguage().'\' ','inner'
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

	private function condition_where(){
		$where = [];
		$publish = $this->request->getGet('publish');
		if(isset($publish)){
			$where['tb1.publish'] = $publish;
		}else{
			$where['tb1.publish'] = 1;
		}

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
			$keyword = '(tb3.title LIKE \'%'.$keyword.'%\')';
		}
		return $keyword;
	}

	private function storeLanguage($objectid = 0){
		helper(['text']);
		$store = [
			'objectid' => $objectid,
			'title' => validate_input($this->request->getPost('title')),
			'canonical' => slug($this->request->getPost('canonical')),
			'description' => base64_encode($this->request->getPost('description')),
			'content' => base64_encode($this->request->getPost('content')),
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

		$store = [
 			'catalogueid' => (int)$this->request->getPost('catalogueid'),
 			'catalogue' => json_encode($catalogue),
 			'image' => $this->request->getPost('image'),
 			'album' => json_encode($this->request->getPost('album'), TRUE),
 			'publish' => $this->request->getPost('publish'),
 			'files' => $this->request->getPost('files'),
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
 			$this->AutoloadModel->_update([
	 			'table' => 'router',
	 			'where' => ['objectid' => $param['id'], 'module' => $this->data['module'], 'language' => $this->currentLanguage()],
	 			'data' => $data
	 		]);
 		}
 		return true;
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
				$select = $select.'(SELECT COUNT(objectid) FROM article_translate WHERE article_translate.objectid = tb1.id AND article_translate.module = "article" AND  article_translate.language = "'.$val['canonical'].'") as '.$val['canonical'].'_detect, ';
				$i++;
			}
		}


		return [
			'select' => $select,
		];

	}

	private function validation(){
		$validate = [
			'title' => 'required',
			'canonical' => 'required|check_router['.$this->data['module'].']',
			'catalogueid' => 'is_natural_no_zero',
		];
		$errorValidate = [
			'title' => [
				'required' => 'Bạn phải nhập vào trường tiêu đề'
			],
			'canonical' => [
				'required' => 'Bạn phải nhập giá trị cho trường đường dẫn',
				'check_router' => 'Đường dẫn đã tồn tại, vui lòng chọn đường dẫn khác',
			],
			'catalogueid' => [
				'is_natural_no_zero' => 'Bạn Phải chọn danh mục cha cho bài viết',
			],
		];
		return [
			'validate' => $validate,
			'errorValidate' => $errorValidate,
		];
	}

}
