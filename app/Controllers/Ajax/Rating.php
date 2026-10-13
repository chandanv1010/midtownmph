<?php 
namespace App\Controllers\Ajax;
use App\Controllers\FrontendController;

class Rating extends FrontendController{
	
	public function __construct(){
	}

	public function Rating(){
		$module = $this->request->getPost('module');
		$objectid = $this->request->getPost('objectid');
		$rating = $this->request->getPost('rating');
		$language = ($this->request->getPost('language')) ?? fe_currentLanguage();

		$info_rating = get_detail_rating([
			'module' => $module,
			'objectid' => $objectid,
			'language' => $language,
		]);

		if (isset($info_rating) && check_array($info_rating)) {
			$rating_id = $info_rating['id'];
			$delete = $this->AutoloadModel->_delete([
				'table' => 'rating_relationship',
				'where' => [
					'user_agent' => $_SERVER['HTTP_USER_AGENT'],
					'remote_addr' => $_SERVER['REMOTE_ADDR'],
					'rating_id' => $rating_id,
				],
			]);
			
		}else{
			$rating_id = $this->AutoloadModel->_insert([
				'table' => 'rating',
				'data' => [
					'module' => $module,
					'objectid' => $objectid,
					'language' => $language,
					'publish' => 1,
				],
			]);
		}

		$flag = $this->AutoloadModel->_insert([
			'table' => 'rating_relationship',
			'data' => [
				'user_agent' => $_SERVER['HTTP_USER_AGENT'],
				'remote_addr' => $_SERVER['REMOTE_ADDR'],
				'rating' => $rating,
				'rating_id' => $rating_id,
			],
		]);

		echo $flag;die();
	}
}
