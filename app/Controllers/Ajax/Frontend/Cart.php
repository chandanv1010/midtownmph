<?php
namespace App\Controllers\Ajax\Frontend;
use App\Controllers\FrontendController;

class Cart extends FrontendController{

	public $cart;
	public function __construct(){
		$this->cart = \Config\Services::cart();
	}

	public function insert(){
		$response = [];
		try {
			$sku = $this->request->getPost('sku');
			$qty = $this->request->getPost('qty');
			$objectId = str_replace('SKU_','', $sku);
			$object = $this->AutoloadModel->_get_where([
				'select' => 'tb1.id, tb2.title, tb1.price, tb1.price_promotion',
				'table' => 'product as tb1',
				'join' => [
					['product_translate as tb2', 'tb1.id = tb2.objectid', 'inner']
				],
				'where' => ['tb1.publish' => 1,'tb1.deleted_at' => 0,'tb1.id' => $objectId, 'tb2.module' => 'product']
			]);

			$price = price($object['price'], $object['price_promotion']);
			$option = makeCartOption();
			if(isset($option) && is_array($option) && count($option)){
				$cart['options'] = $option;
			}
			$cartData = [
				'id'      => $sku,
				'qty'     => (int)$qty,
				'price'   => $price['finalPrice'],
				'name'    => $object['title'],
			];

			$this->cart->insert($cartData);
			$response['message'] = 'Thêm sản phẩm vào giỏ hàng thành công';
			$response['code'] = '10';
			$response['totalItems'] = $this->cart->totalItems();

		}catch(Exception $e) {
			$response['message'] = $e->getMessage();
			$response['code'] = '99';
		}

		echo json_encode([
			'response' => $response
		]);die();
	}

	public function change_quantity(){
		$qty = $this->request->getPost('quantity');
		$rowid = $this->request->getPost('code');
		$cart = $this->cart->contents();

		$cartUpdate = array(
		   'rowid'   => $rowid,
		   'qty'     => $qty,
	   );
		$this->cart->update($cartUpdate);

	}


	public function remove(){
		$rowid= $this->request->getPost('code');
		$content = $this->cart->remove($rowid);
		die();
	}

}
