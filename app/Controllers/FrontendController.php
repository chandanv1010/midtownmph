<?php
namespace App\Controllers;
use CodeIgniter\Controller;
use App\Models\AutoloadModel;
use App\Libraries\Pagination;
use App\Libraries\Mobile_Detect;

class FrontendController extends Controller
{

	protected $helpers = ['mystring','mydatafrontend','renderdata','myurl','form','mypanel','mydata','url','myauthentication','mypagination','lang'];
	public $currentTime;
	public $AutoloadModel;
	public $client;
	protected $pagination;
	public $general;
	public $detect_type;
	public $currency;
	public $locale;

	public function initController(\CodeIgniter\HTTP\RequestInterface $request, \CodeIgniter\HTTP\ResponseInterface $response, \Psr\Log\LoggerInterface $logger)
	{
		parent::initController($request, $response, $logger);
		$this->AutoloadModel = new AutoloadModel();
		$this->pagination = new Pagination();
		$this->currentTime =  gmdate('Y-m-d H:i:s', time() + 7*3600);

		helper($this->helpers);
		$this->request = \Config\Services::request();
		$this->locale = $this->request->getLocale();
		$this->general = get_general($this->locale);


		/* Da go setcookie('language', ...) o day.
		 *
		 * Ngon ngu nay xac dinh bang URL qua request()->getLocale() - xem
		 * currentLanguage() ben duoi, phan doc cookie da duoc comment lai tu
		 * truoc. Cookie chi con duoc GHI chu khong ai doc: da ra soat ca PHP
		 * lan JavaScript, khong cho nao doc no.
		 *
		 * Giu lai con nguy hiem: setcookie() KHONG lam $_COOKIE co gia tri
		 * trong chinh request do, nen bat ky doan code nao doc lai se nhan ve
		 * ngon ngu cua lan tai trang TRUOC - sai mot nhip va rat kho tim.
		 */

		$currency = (isset($_COOKIE[AUTH_CURRENCY])) ? $_COOKIE[AUTH_CURRENCY] : '';

      // if(!isset($currency) || empty($currency)){
			// $cookie_name = AUTH_CURRENCY;
			// $cookie_value = json_encode([
			// 	'currency_id' => 1,
			// 	'title' => 'VND',
			// 	'rate' => 1,
			// ]);
			// setcookie($cookie_name, $cookie_value, time() + (86400 * 30 * 30), "/");
			// header("Refresh:0");
		// }
		

		

		if(!isset($currency) || empty($currency)){
			$cookie_name = AUTH_CURRENCY;
			$cookie_value = json_encode([
				'currency_id' => 1,
				'title' => 'VND',
				'rate' => 1,
			]);
			setcookie($cookie_name, $cookie_value, time() + (86400 * 30 * 30), "/");
			// header("Refresh:0");
		}


	}

	public function currentLanguage(){
		$locale = $this->locale;
		return $locale;
		// echo $locale;die();

		// $language = (isset($this->general['website_language']) ? $this->general['website_language'] : 'vi');
		// if(!isset($_COOKIE['language']) || $_COOKIE['language'] == ''){
		// 	setcookie('language', $language , time() + 1*24*3600, "/");
		// }else{
		// 	$language = $_COOKIE['language'];
		// }
		// return $language;

		// return $this->locale;
	}
}
