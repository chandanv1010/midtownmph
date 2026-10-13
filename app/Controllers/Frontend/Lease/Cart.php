<?php
namespace App\Controllers\Frontend\Product;
use App\Controllers\FrontendController;
use App\Libraries\Mailbie;
use App\Libraries\AppotaPay;

class Cart extends FrontendController{
	public $cartbie;
	public $cart;

    public function __construct(){
		$this->cart = \Config\Services::cart();
    }
    public function index(){
		$session = session();
		$this->data['cart'] = $this->get_cart_detail($this->cart->contents());
		$this->data['cartTotal'] = $this->cart->total();



    	if($this->request->getMethod() == 'post'){

			$validate = $this->validation();
			if ($this->validate($validate['validate'], $validate['errorValidate'])){
				$store = $this->store();
				$cart = $this->cart->contents();
				if(isset($store) && is_array($store) && count($store)){
					$bill_id = $this->bill_id_generator();
					$store['bill_id'] = $bill_id;
					$resultid = $this->AutoloadModel->_insert([
						'table' => 'bill',
						'data' => $store
					]);
					if($resultid > 0){
						$flag = $this->create_bill_detail($resultid, $cart);
						if($flag > 0){
							if($this->request->getPost('payment_method_id') == 'apota'){
								$this->payment_online($store, $cart);
							}
							$token = base64_encode($resultid);
							$this->send_mail($this->data['cart'], $store, $bill_id);
							$this->cart->destroy();
 							return redirect()->to(BASE_URL.'thong-tin-don-hang.html?token='.$token);
						}
					}
				}
			}
        }

        $this->data['general'] = $this->general;
        return view('frontend/product/cart/index', $this->data);
    }

	public function method(){
		$id = (int)base64_decode($this->request->getGet('token'));
		$this->data['orderDetail'] = $this->AutoloadModel->_get_where([
			'select' => 'tb1.id, tb1.fullname, tb1.email, tb1.bill_id',
			'table' => 'bill as tb1',
			'where' => [
				'tb1.id' => $id,
			]
		]);

		$this->data['general'] = $this->general;
		$this->data['meta_title'] = $this->general['homepage_company'].' Đặt hàng Thành Công';
		$this->data['meta_description'] = $this->general['homepage_company'].' Đặt hàng Thành Công';
		return view('frontend/product/cart/method', $this->data);
    }

	private function payment_online($param = [], $cart = []){
		$case = 'apota';
		switch ($case) {
		  	case "apota":
		    	$this->apota_pay($param, $cart);
		    	break;
		  	default:
		    	$this->apota_pay($param, $cart);
		}
	}

	private function apota_pay($param = [], $cart = []){
		$path = substr(APPPATH, 0, -4);
		$config = [
		    'partner_code' => $this->general['payment_partner_code'],
		    'api_key' => $this->general['payment_api_key'],
		    'secret_key' => $this->general['payment_secret_key'],
		];
		$appotaPay = new AppotaPay($config);
		$orderDetails = [
		    'order_id' => $param['bill_id'],
		    'order_info' => 'Thanh toán đơn hàng mã: '.$param['bill_id'].' Ghi Chú: '.$param['messages'],
		    'amount' => $this->cart->total(),
		];
		$paymentDetails = [
		    'bank_code' => '',
		    'method' => 'CC',
		    'client_ip' => $_SERVER['REMOTE_ADDR'],
		];
		$paymentUrl = $appotaPay->makeBankPayment($orderDetails, $paymentDetails);
		redirect($paymentUrl);
	}


	private function create_bill_detail($bill_id = 0, $cart = []){
		$insert = [];
		$row = 0;
		if(isset($cart) && is_array($cart) && count($cart)){
			foreach($cart as $key => $val){
				$insert[] = [
					'bill_id' => $bill_id,
					'product_id' => str_replace('SKU_', '', $val['id']),
					'name' => $val['name'],
					'subtotal' => $val['subtotal'],
					'quantity' => $val['qty'],
					'price' => $val['price'],
					'option' => json_encode((isset($val['options'])) ? $val['options'] : '')
				];
			}
		}

		if(isset($insert) && is_array($insert) && count($insert)){
			$row = $this->AutoloadModel->_create_batch([
				'table' => 'bill_detail',
				'data' => $insert,
			]);
		}
		return $row;
	}
	private function send_mail($cart = [], $info = [], $bill_id = ''){
		helper(['mymail']);
		$html = '';
		if(isset($cart) && is_array($cart) && count($cart)){
			foreach($cart as $key => $val){
				$image = BASE_URL.$val['detail']['image'];
				$title = $val['detail']['title'];
				$price = $val['price'];
				$price_promotion = $val['detail']['price_promotion'];
				$main_price = $val['detail']['price'];
				$quantity = $val['qty'];
				$subtotal = $val['subtotal'];
				$html = $html.'<tr>
					<td style="padding:5px 9px">
					<img style="width:40px ; height: 40px" src="'.$image.'"></td>
					<td style=" padding:5px 9px">'.$title.'</td>
					<td style="text-align:right ; padding:5px 9px">'.number_format($main_price).' đ</td>
					<td style="text-align:center ; padding:5px 9px">'.$quantity.'</td>
					<td style="text-align:right ; padding:5px 9px">'.number_format($main_price - $price_promotion).' đ</td>
					<td style="text-align:right ; padding:5px 9px">'.number_format($subtotal).' đ</td>
				</tr>';
			}
		}
		$mailbie = new Mailbie();
		$mailFlag = $mailbie->send([
		   'to' => $info['email'],
		   'subject' => 'Xác nhận đặt hàng thành công tại hệ thống website: '.$this->general['contact_website'],
		   'messages' => mail_html(array(
			   'header' => 'Thông tin đặt hàng',
			   'fullname' => $info['fullname'],
			   'email' => $info['email'],
			   'p_phone' => $info['phone'],
			   'address' => $info['address'],
			   'total_price' => $this->cart->total(),
			   'payment_code' => $bill_id,
			   'payment_created' => $info['created_at'],
			   'fee' =>'-',
			   'product' => $html,
			   'web' => $this->general['contact_website'],
			   'hotline' => $this->general['contact_hotline'],
			   'phone' => $this->general['contact_hotline'],
			   'logo' => base_url($this->general['homepage_logo']),
			   'brandname' => $this->general['contact_website'],
			   'system_email' => $this->general['contact_email'],
			   'system_address' => $this->general['contact_address'],
		   ))
	   	]);
	 	return $mailFlag;
	}
	private function store(){
		$store = [
			'fullname' => $this->request->getPost('fullname'),
			'phone' => $this->request->getPost('phone'),
			'email' => $this->request->getPost('email'),
			'cityid' => $this->request->getPost('cityid'),
			'districtid' => $this->request->getPost('districtid'),
			'address' => $this->request->getPost('address'),
			'messages' => $this->request->getPost('message'),
			'method' => $this->request->getPost('payment_method_id'),
			'quantity' => $this->cart->totalItems(),
			'total' => $this->cart->total(),
			'status' => 'pending',
			'created_at' => $this->currentTime,
		];
		return $store;
	}

	private function get_cart_detail($cart = []){
		$id = [];
		$productInCart = [];
		if(isset($cart) && is_array($cart) && count($cart)){
			foreach($cart as $key => $val){
				$objectid = str_replace('SKU_', '', $val['id']);
				$id[] = $objectid;
			}
		}
		if(isset($id) && is_array($id) && count($id)){
			$productInCart = $this->AutoloadModel->_get_where([
				'select' => 'tb1.id, tb2.title, tb1.image, tb2.canonical, tb1.price, tb1.price_promotion',
				'table' => 'product as tb1',
				'join' => [
					['product_translate as tb2', 'tb1.id = tb2.objectid', 'inner']
				],
				'where' => [
					'tb1.publish' => 1, 'tb1.deleted_at' => 0,'tb2.module' => 'product'
				],
				'where_in' => $id,
				'where_in_field' => 'tb1.id'
			], TRUE);
		}
		$cartRemake = $this->cart_remake($cart, $productInCart);
		return $cartRemake;
	}

	private function cart_remake($cart = [], $productInCart = []){
		if(isset($cart) && is_array($cart) && count($cart)){
			foreach($cart as $key => $val){
				$objectid = str_replace('SKU_', '', $val['id']);
				if(isset($productInCart) && is_array($productInCart) && count($productInCart)){
					foreach($productInCart as $keyItem => $valItem){
						if($objectid == $valItem['id']){
							$cart[$key]['detail'] = $valItem;
							$cart[$key]['canonical'] = $valItem['canonical'];
							$cart[$key]['image'] = $valItem['image'];
						}
					}
				}
			}
		}
		return $cart;
	}

	private function validation(){
		$validate = [
			'fullname' => 'required',
			'phone' => 'required',
			'address' => 'required',
		];
		$errorValidate = [
			'fullname' => [
				'required' => 'Bạn phải nhập vào trường Họ Tên'
			],
			'phone' => [
				'required' => 'Bạn phải nhập vào trường Số Điện Thoại'
			],
			'address' => [
				'required' => 'Bạn phải nhập vào trường địa chỉ'
			],
		];
		return [
			'validate' => $validate,
			'errorValidate' => $errorValidate,
		];
	}

	private function bill_id_generator(){
		$order = $this->AutoloadModel->_get_where([
			'select' => 'id',
			'table' => 'bill',
			'order_by' => 'id desc'
		]);
		$lastId = 0;
		if(!isset($order) || is_array($order) == false || count($order) == 0){
			$lastId = 1;
		}else{
			$lastId = $order['id']+1;
		}
	 	$orderId = 'ORDER_'.str_pad($lastId, 6, '0', STR_PAD_LEFT);
		return $orderId;
	}

}
