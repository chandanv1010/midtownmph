<?php 
namespace App\Controllers\Backend\Slide;
use App\Controllers\BaseController;

class Translate extends BaseController
{
		protected $data;
		public function __construct(){
			$this->data = [];
		}

		public function translate($catalogueid = 0, $module = '', $language = ''){
		$session = session();
		$flag = $this->authentication->check_permission([
			'routes' => 'backend/slide/translate/translate'
		]);
		if($flag == false){
 			$session->setFlashdata('message-danger', 'Bạn không có quyền truy cập vào chức năng này!');
 			return redirect()->to(BASE_URL.'backend/dashboard/dashboard/index');
		}
		$catalogueid = (int)$catalogueid;
		$moduleExtract = explode('_', $module);
		$dataTrans=[];
		$this->data['object'] = $this->AutoloadModel->_get_where([
		 	'select' => 'tb2.id, tb3.title, tb2.image, tb2.order, tb3.url, tb3.language, tb3.content, tb3.description ',
			'table' => $module.'_catalogue as tb1',
			'join' => [
				[
		 			$moduleExtract[0].' as tb2','tb1.id = tb2.catalogueid','inner'
				],
				[
		 			$moduleExtract[0].'_translate as tb3','tb2.id = tb3.objectid AND tb3.language = \''.$this->currentLanguage().'\' ','inner'
				],
			],
			'where' => ['tb1.id' => $catalogueid, 'tb3.language' => $this->currentLanguage() ],
		],true);
		if(!isset($this->data['object']) || is_array($this->data['object']) == false || count($this->data['object']) == 0){
		 	$session->setFlashdata('message-danger', 'Bản ghi không tồn tại!');
		 	return redirect()->to(BASE_URL.'backend/'.$moduleExtract[0].'/'.((count($moduleExtract) == 1) ? $moduleExtract[0] : $moduleExtract[1]).'/index');
		 }
	 		$check = $this->AutoloadModel->_get_where([
	 			'table'  => 'slide_translate',
	 			'select' => 'catalogueid, language, content, description, title, url',
				'where'  => ['catalogueid' => $catalogueid,'language' => $language],
	 		],true);//lay gia tri da dich do ra view
	 		$idTrans = $this->AutoloadModel->_get_where([
	 			'table'  => 'slide',
	 			'select' => 'id',
				'where'  => ['catalogueid' => $catalogueid],
	 		],true);
	 		$idTrans = array_column($idTrans, 'id');
			$this->data['value'] = $check;
			$this->data['language'] = $language;
			if(isset($check) && is_array($check) && $check != null){
				if($this->request->getMethod() == 'post'){
					$deleteAll = $this->AutoloadModel->_delete([
		 				'table' => $moduleExtract[0].'_translate',
		 				'where' => ['catalogueid' => $catalogueid, 'language' => $language],
			 		]);
					$store = $this->storeSlide([
			  			'language' => $language,
			  			'method'   => 'update',
				 	]);	
					$dataTrans = $this->dataTranslate($store, $idTrans, 'update', $catalogueid);
					$slideTranslate = 0;
				 	$slideTranslate =  $this->AutoloadModel->_create_batch([
	 					'table' => 'slide_translate',
		 		 		'data'  => $dataTrans,
	 				]);
			 		if($slideTranslate > 0){
		 			$session->setFlashdata('message-success', 'Tạo Bản Dịch Thành Công! Hãy tạo danh mục tiếp theo.');
 	 				return redirect()->to(BASE_URL.'backend/'.$moduleExtract[0].'/'.((count($moduleExtract) == 1) ? $moduleExtract[0] : $moduleExtract[1]).'/index');
		  			}
				}
			}else{
				if($this->request->getMethod() == 'post'){
					$store = $this->storeSlide([
			  			'language' => $language,
			  			'method'   => 'create',
				 	]);
				 	$dataTrans = $this->dataTranslate($store, $idTrans, 'create', $catalogueid);
			 		$slideTranslate = 0;
			 		$slideTranslate =  $this->AutoloadModel->_create_batch([
	 					'table' => 'slide_translate',
		 		 		'data'  => $dataTrans,
	 				]);
		 		if($slideTranslate > 0){
		 			$session->setFlashdata('message-success', 'Tạo Bản Dịch Thành Công! Hãy tạo danh mục tiếp theo.');
 	 				return redirect()->to(BASE_URL.'backend/'.$moduleExtract[0].'/'.((count($moduleExtract) == 1) ? $moduleExtract[0] : $moduleExtract[1]).'/index');
		  			}
		  		}
	         }
		$this->data['template'] = 'backend/translate/translate/translateSlide';
		return view('backend/dashboard/layout/home', $this->data);
	}

	private function storeSlide($param = []){
		helper(['text']);
		$store = [
			'dataTrans' => $this->request->getPost('dataTrans'),
			'language'  => $param['language'],
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
	private function dataTranslate($store = [], $objectid = [], $method = '', $catalogueid = ''){
		$dataTrans = [];
		foreach ($store['dataTrans'] as $key => $val) {
 			$dataTrans[$key]['title'] = $val['title'];
 			$dataTrans[$key]['url'] = $val['url'];
 			$dataTrans[$key]['description'] = $val['description'];
 			$dataTrans[$key]['content'] = $val['content'];
 			$dataTrans[$key]['language'] = $store['language'];
 			$dataTrans[$key]['catalogueid'] = $catalogueid;
 			$dataTrans[$key]['objectid'] = $objectid[$key];
 			if ($method == 'create'){
 				$dataTrans[$key]['created_at'] = $store['created_at'];
 				$dataTrans[$key]['userid_created'] = $store['userid_created'];
 			}else{
 				$dataTrans[$key]['updated_at'] = $store['updated_at'];
 				$dataTrans[$key]['userid_updated'] = $store['userid_updated'];
 			}
 		}
 		return $dataTrans;
	}

	private function validation($module = ''){
		$validate = [
			'title' => 'required',
			'canonical' => 'required|check_canonical['.$module.']',
		];
		$errorValidate = [
			'title' => [
				'required' => 'Bạn phải nhập vào trường tiêu đề'
			],
			'canonical' => [
				'required' => 'Bạn phải nhập giá trị cho trường đường dẫn',
				'check_canonical' => 'Đường dẫn đã tồn tại, vui lòng chọn đường dẫn khác',
			],
		];
		return [
			'validate'      => $validate,
			'errorValidate' => $errorValidate,
		];
	}

}