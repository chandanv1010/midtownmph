<?php
namespace App\Controllers\Frontend\Auth;
use App\Controllers\FrontendController;

class Auth extends FrontendController{

	public $data = [];
	public function __construct(){

	}
	public function login(){
		$this->data['general'] = $this->general;
        return view('frontend/auth/login/index', $this->data);
	}
	public function signup(){
		helper('mydata');
		$this->data['general'] = $this->general;
        return view('frontend/auth/signup/index', $this->data);
	}
	public function forgot(){
		$this->data['general'] = $this->general;
        return view('frontend/auth/forgot/index', $this->data);
	}
	public function logout(){
		unset($_COOKIE[AUTH.'member']);
        setcookie(AUTH.'member', null, -1, '/');
        return redirect()->to(BASE_URL);
	}
}
