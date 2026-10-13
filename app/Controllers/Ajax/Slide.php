<?php 
namespace App\Controllers\Ajax;
use App\Controllers\BaseController;

class Slide extends BaseController{
	
	public function __construct(){
	}
	public function delete(){
		$id = $this->request->getPost('id');
		$flag = $this->AutoloadModel->_update([
			'table' => 'slide_catalogue',
			'data' => ['deleted_at' => 1],
			'where_in' => $id,
			'where_in_field' => 'id',
		]);
		if ($flag > 0){
			$flag = $this->AutoloadModel->_update([
				'table' => 'slide',
				'data' => ['deleted_at' => 1],
				'where_in' => $id,
				'where_in_field' => 'catalogueid',
			]);
			$flag = $this->AutoloadModel->_update([
				'table' => 'slide_translate',
				'data' => ['deleted_at' => 1],
				'where_in' => $id,
				'where_in_field' => 'catalogueid',
			]);
		}
		echo $flag;die();
	}
	public function echoview(){
		$file = $this->request->getPost('file');
		$count = $this->request->getPost('count');
		$fileData = [];
		if (isset($file) && is_array($file) && count($file)){
			foreach ($file as $key => $value) {
				$image_preview = process_img_link_mp4($value);
				$fileData[$key]['image_preview'] = $image_preview;
				$fileData[$key]['image'] = $value;
				$fileData[$key]['order'] = 0;
				$fileData[$key]['url'] = '';
				$fileData[$key]['title'] = '';
				$fileData[$key]['description'] = '';
				$fileData[$key]['content'] = '';
			}
		}else{
				$image_preview = process_img_link_mp4($file);
				$fileData[$key]['image_preview'] = $image_preview;
				$fileData[$count]['image'] = $file;
				$fileData[$count]['order'] = 0;
				$fileData[$count]['url'] = '';
				$fileData[$count]['title'] = '';
				$fileData[$count]['description'] = '';
				$fileData[$count]['content'] = '';
		}

		$html = view('backend/dashboard/common/slideblock', ['listSlide' => $fileData, 'count' => $count], ['saveData' => true]);

		echo json_encode([
			'fileData' => $fileData,
			'html' => $html,
		]);
		die();
	}
}
