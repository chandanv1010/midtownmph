<?php
namespace App\Controllers\Ajax;
use App\Controllers\BaseController;
use App\Models\AutoloadModel;

class Currency extends BaseController{

	public function __construct(){
	}
	public function default_currency(){
		$AutoloadModel = new AutoloadModel();
		$id = $this->request->getPost('id');

		$flag = $AutoloadModel->_update([
			'table' => 'currency',
			'data' => ['default' => 0],
		]);
		$flag = $AutoloadModel->_update([
			'table' => 'currency',
			'data' => ['default' => 1],
			'where' => ['id' => $id]
		]);
		echo $flag;die();
	}

	public function getCurrency(){
		$currency_id = $this->request->getPost('currency_id');
		$title = $this->request->getPost('title');

		$rate = get_currency_rate([
	        'currency_id' => $currency_id,
		]);

		$cookie_value = json_encode(array(
	        'currency_id' => $currency_id,
	        'title' => $title,
	        'rate' => $rate,
	    ));

	    setcookie(AUTH_CURRENCY, $cookie_value, time() + (86400 * 30), "/"); // 86400 = 1 day

		echo 1;die;
	}
}
