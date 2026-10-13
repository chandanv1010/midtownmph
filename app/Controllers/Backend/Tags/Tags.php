<?php 
namespace App\Controllers\Backend\Tags;

use App\Controllers\BaseController;
use App\Libraries\Nestedsetbie;

class Tags extends BaseController{

	public function __construct(){
		$this->data = [];
		$this->data['module'] = 'tags';
		$this->nestedsetbie = new Nestedsetbie(['table' => $this->data['module'], 'language' => $this->currentLanguage()]);
	}

	public function index($page = 1){
		$session = session();
		$flag = $this->authentication->check_permission([
			'routes' => 'backend/tags/tags/index',
		]);

		if ($flag == false) {
			$session->setFlashdata('message-danger', 'Bạn không có quyền truy cập vào chức năng này !');
			return redirect()->to(BASE_URL.'backend/dashboard/dashboard/index');
		}

		helper(['mypagination']);
		$page = (int)$page;
		$perpage = ($this->request->getGet('perpage'))?? 20;
		$where = $this->condition_where();
		// $keyword = $this->condition_keyword();

		$module_explode = explode('_', $this->data['module']);

		$join = [
			[
				$module_explode[0].'_translate as tb2', 'tb2.objectid = tb1.id AND tb2.module = \''.$this->data['module'].'\' AND tb2.language = \''.$this->currentLanguage().'\'',
			],
			[
				'tags_relationship as tb3', 'tb3.tagsid = tb1.id AND tb2.module = \''.$this->data['module'].'\' AND tb2.language = \''.$this->currentLanguage().'\'',
			],
		];

		$config['total_rows'] = $this->AutoloadModel->_get_where([
			'select' => 'tb1.id, tb2.title',
			'table' => $this->data['module'].' as tb1',
			'join' => $join,
			'where' => $where,
			'group_by' => 'tb1.id',
			'count' => TRUE,
		]);

		if ($config['total_rows'] > 0) {
			$config = pagination_config_bt([
				'url' => 'backend/tags/tags/index',
				'perpage' => $perpage,
			], $config);

			$this->pagination->initialize($config);
			$this->data['pagination'] = $this->pagination->create_links();

			$totalPage = ceil($config['total_rows']/$config['per_page']);
			$page = ($page <= 0)?1:$page;
			$page = ($page > $totalPage)?$totalPage:$page;
			$page = $page - 1;
			$languageDetect = $this->detect_language();

			$this->data['tagsList'] = $this->AutoloadModel->_get_where([
				'select' => 'tb1.id, tb1.order, tb1.publish, tb1.created_at, tb1.updated_at, tb2.title, tb2.canonical, tb2.keyword'.((isset($languageDetect['select'])) ? $languageDetect['select'] : ''),
				'table' => $this->data['module'].' as tb1',
				'where' => $where,
				'join' => $join,
				'limit' => $config['per_page'],
				'start' => $page * $config['per_page'],
				'order_by'=> 'tb1.order desc, tb1.id desc',
			], TRUE);
		}

		$this->data['template'] = 'backend/tags/tags/index';
		return view('backend/dashboard/layout/home', $this->data);
	}

	public function create(){
		$session = session();
		$flag = $this->authentication->check_permission([
			'routes' => 'backend/tags/tags/create'
		]);
		if($flag == false){
 			$session->setFlashdata('message-danger', 'Bạn không có quyền truy cập vào chức năng này!');
 			return redirect()->to(BASE_URL.'backend/dashboard/dashboard/index');
		}
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
			 			'table' => 'tags_translate',
			 			'data' => $storeLanguage,
			 		]);
		 			$this->insert_router(['method' => 'create','id' => $resultid]);

	 				if($flag > 0){
	 					$session->setFlashdata('message-success', 'Tạo Tags Thành Công! Hãy tạo danh mục tiếp theo.');
 						return redirect()->to(BASE_URL.'backend/tags/tags/index');
	 				}else{
	 					$session->setFlashdata('message-danger', 'Có vấn đề xảy ra, vui lòng thử lại!');
	 					return redirect()->to(BASE_URL.'backend/tags/tags/index');
	 				}
		 		}
	        }else{
	        	$this->data['validate'] = $this->validator->listErrors();
	        }
		}
		$this->data['dropdown'] = $this->nestedsetbie->dropdown();
		$this->data['fixWrapper'] = 'fix-wrapper';
		$this->data['method'] = 'create';
		$this->data['template'] = 'backend/tags/tags/create';
		return view('backend/dashboard/layout/home', $this->data);
	}


	// check publish and deleted_at
	private function condition_where(){
		$where = [];
		
		$publish = ($this->request->getPost('publish'))?? 1;
		$deleted_at = ($this->request->getPost('deleted_at'))?? 0;

		$where = [
			'tb1.publish' => $publish,
			'tb1.deleted_at' => $deleted_at,
		];

		return $where;
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
				$select = $select.'(SELECT COUNT(objectid) FROM tags_translate WHERE tags_translate.objectid = tb1.id AND tags_translate.language = "'.$val['canonical'].'") as '.$val['canonical'].'_detect';
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
			'canonical' => 'required|check_canonical['.$this->data['module'].']',
		];
		$errorValidate = [
			'title' => [
				'required' => 'Bạn phải nhập vào trường tiêu đề!'
			],
			'canonical' => [
				'required' => 'Bạn phải nhập giá trị cho trường đường dẫn',
				'check_canonical' => 'Đường dẫn đã tồn tại, vui lòng chọn đường dẫn khác',
			],
		];
		return [
			'validate' => $validate,
			'errorValidate' => $errorValidate,
		];
	}
}

?>