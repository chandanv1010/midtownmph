<?php 
namespace App\Validation;
use App\Models\AutoloadModel;
use CodeIgniter\HTTP\RequestInterface;

class ObjectRules {

	protected $AutoloadModel;
	protected $helper = ['mystring'];
	protected $request;

	public function __construct(){
		$this->AutoloadModel = new AutoloadModel();
		$this->request = \Config\Services::request();
		helper($this->helper);

	}

	public function check_canonical(string $canonical = '', string $module = ''): bool{
		$originalCanonical = $this->request->getPost('original_canonical');
		$modulExtract = explode('_', $module);
		$dem = 0;
		if($originalCanonical != $canonical){
			$dem = $this->AutoloadModel->_get_where([
				'select' => 'objectid',
				'table' => 'router',
				'where' => ['canonical' => $canonical],
				'count' => TRUE
			]);
		}
		if( $dem > 0){
			return false;
		}
		return true;
 	}

 	public function check_router(string $canonical = '', string $module = ''): bool{
		$originalCanonical = $this->request->getPost('original_canonical');
		$modulExtract = explode('_', $module);
		$count = 0;
		$dem = 0;

		if($originalCanonical != $canonical){
			$count = $this->AutoloadModel->_get_where([
				'select' => 'objectid',
				'table' => 'router',
				'where' => [
					'canonical' => $canonical
				],
				'count' => TRUE
			]);

			if($count == 0){
				$dem = $this->AutoloadModel->_get_where([
					'select' => 'objectid',
					'table' => 'router',
					'where' => ['canonical' => $canonical, 'module' => $module],
					'count' => TRUE
				]);
			}
		}
		if($count > 0 || $dem > 0){
			return false;
		}
		return true;
 	}

 	public function check_id(string $productid = '', string $module = ''): bool{
		$originalId = $this->request->getPost('productid_original');

		$modulExtract = explode('_', $module);
		$count = 0;
		if($originalId != $productid){
			$count = $this->AutoloadModel->_get_where([
				'select' => 'objectid',
				'table' => $modulExtract[0],
				'where' => ['productid' => $productid, 'deleted_at' => 0],
				'count' => TRUE
			]);
		}
		if($count > 0){
			return false;
		}
		return true;
 	}

 	public function check_widget(string $keyword = ''): bool{
		
		$originalId = $this->request->getPost('keyword');
		$count = 0;
		if($originalId != $keyword){
			$count = $this->AutoloadModel->_get_where([
				'select' => 'objectid',
				'table' => 'website_widget' ,
				'where' => ['keyword' => $keyword],
				'count' => TRUE
			]);
		}
		if($count > 0){
			return false;
		}
		return true;
 	}

 	public function is_no_0(string $keyword = ''): bool{
		if($keyword == '0'){
			return false;
		}
		return true;
 	}

 	public function check_keyword_translate(string $keyword = '', $module = ''): bool{
		$modulExtract = explode('_', $module);
		$originalId = $this->request->getPost('keyword_original');
		$count = 0;
		if($originalId != $keyword){
			$count = $this->AutoloadModel->_get_where([
				'select' => 'id',
				'table' => $modulExtract[0].'_translate' ,
				'where' => ['keyword' => $keyword,'module' => $module],
				'count' => TRUE
			]);
		}
		if($count > 0 ){
			return false;
		}
		return true;
 	}

 	public function check_keyword(string $keyword = '', $module = ''): bool{
		
		$originalId = $this->request->getPost('keyword_original');
		$count = 0;
		if($originalId != $keyword){
			$count = $this->AutoloadModel->_get_where([
				'select' => 'id',
				'table' => $module ,
				'where' => ['keyword' => $keyword],
				'count' => TRUE
			]);
		}
		if($count > 0){
			return false;
		}
		return true;
 	}


 	public function _checkDropdownSelect($field = ''){
	    if ($field == '0') {
	      	return false;
	    }
	    return true;
  	}

  	public function _CheckCurrency($currency = ''){
		$currency_original = (null !== $this->request->getPost('currency_original'))? $this->request->getPost('currency_original'): '';

		if ($currency_original == '' || $currency_original == $currency) {
			return true;
		}

		$module = 'currency';
	    $module_extract = explode("_", $module);
	    $language = be_currentLanguage();
	    
	    $join = [
	        [
	            $module_extract[0].'_translate as tb2','tb2.module = \''.$module.'\' AND tb2.objectid = tb1.id AND tb2.language = \''.$language.'\'', 'inner'
	        ]
	    ];

	    // print_r($currency); exit;

		$result = $this->AutoloadModel->_get_where([
			'select' => 'tb1.*',
			'table' => 'currency as tb1',
			'where' => [
				'tb2.slug' => slug($currency),
			],
			'join' => $join,
			'count' => true,
		]);

		if ($result > 0) {
			return false;
		}

		return true;
	}


}

