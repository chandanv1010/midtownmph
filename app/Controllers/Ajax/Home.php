<?php 
namespace App\Controllers\Ajax;
use App\Controllers\FrontendController;

class Home extends FrontendController{
	
	public function __construct(){
	}
	
	public function change_language(){
		$language = $this->request->getPost('language');
		setcookie('language', $language, time() + (86400 * 30), "/"); // 86400 = 1 day

		print_r($language); exit;
	}
}
