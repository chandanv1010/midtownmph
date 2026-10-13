<?php
namespace App\Controllers\Ajax;
use App\Controllers\BaseController;
use App\Models\AutoloadModel;
use App\Libraries\Mailbie;

class Contact extends BaseController{
	private $Autoload_Model;
	private $mailbie;

    protected $secret = '6LcumPIrAAAAAPZOJfC5a21qOJY8GTw2bv7769Xy';

	public function __construct(){
		$this->Autoload_Model = new AutoloadModel();
		$this->mailbie = new Mailbie();
	}

	public function contact_footer(){
        // $secret = '6LcumPIrAAAAAPZOJfC5a21qOJY8GTw2bv7769Xy'; // key bí mật
        $response = $this->request->getPost('g-recaptcha-response') ?? '';
        $secret = $this->secret;

        $verify = file_get_contents("https://www.google.com/recaptcha/api/siteverify?secret={$secret}&response={$response}");
        $result = json_decode($verify, true);

        if (empty($result['success']) || !$result['success']) {
            echo json_encode(['error' => ['flag' => 1, 'message' => 'Xác minh reCAPTCHA thất bại. Vui lòng thử lại.']]);
            exit;
        }


		$_subject = '"'.FT_CONTACT_HEADER.'"';
		$upload = upload_file([
			'flag' => FALSE, // bỏ qua file rỗng
		]); // upload 1 file
		helper('mydatafrontend');
		$fullname = $this->request->getPost('fullname');
		$email = $this->request->getPost('email');
		$phone = $this->request->getPost('phone');
		$language = $this->request->getPost('language');
		$message = $this->request->getPost('message');
		$general = get_general($language);
		$validate = json_decode($this->request->getPost('validate'), true);

		$__validate = $this->dt_validation_contact($validate);
		if ($this->validate($__validate['validate'], $__validate['errorValidate'])){
			// $_subject = '"'.$subject.'"';

			$mailContent = '
				<p>'.$validate['fullname'].': <b>'.$fullname.'</b></p>
				<p>'.$validate['email'].': <b>'.$email.'</b></p>
				<p>'.$validate['phone'].': <b>'.$phone.'</b></p>
				<p>'.$validate['message'].': <b>'.$message.'</b></p>
			';

			$_insert = array(
				'catalogueid' => 1,
				'phone' => $phone,
				'email' => $email,
				'publish' => 1,
				'created_at' => gmdate('Y-m-d H:i:s', time() + 7*3600),
			);

			$insertId = $this->Autoload_Model->_insert(array(
				'table' => 'contact',
				'data' => $_insert,
			));

			$_insert_Lang = array(
				'title' => $_subject,
				'objectid' => $insertId,
				'language' => $this->currentLanguage(),
				'module' => 'contact',
				'fullname' => $fullname,
				'content' => $mailContent,
				// 'address' => $address,
				'created_at' => gmdate('Y-m-d H:i:s', time() + 7*3600),
			);

			$insertId_Lang = $this->Autoload_Model->_insert(array(
				'table' => 'contact_translate',
				'data' => $_insert_Lang,
			));

			if ($insertId > 0 && $insertId_Lang > 0) {

				$upload = upload_file([
					'flag' => FALSE, // bỏ qua file rỗng
				]); // upload 1 file


				if($upload['flag'] == 1){
					$error['flag'] = 1;
					$error['message'] = $upload['message'];
				}else{
					if (isset($upload['file'])) {
						$store['file'] = $upload['file'];

						$flag = $this->Autoload_Model->_update(array(
							'table' => 'contact',
							'where' => [
								'id' => $insertId
							],
							'data' => $store,
						));

						print_r($fe_currentLanguage); exit;
					}

					$this->mailbie->send(array(
						// 'to' => array($this->general['contact_email']),
						'to' => 'anne.hcmc@gmail.com',
						'subject' => $_subject.' từ hệ thống '.BASE_URL,
						'messages' => $mailContent,
					));
					// $this->session->set_flashdata('message-success', 'Gửi thông tin thành công, Chúng tôi sẽ liên hệ lại với bạn trong thời gian sớm nhất');
					$error['flag'] = 0;
					$error['message'] = (isset($general['param_success']) && $general['param_success']!= '' ? $general['param_success'] : 'Gửi thông tin thành công, Chúng tôi sẽ liên hệ lại với bạn trong thời gian sớm nhất');
				}
			}else{
				$error['flag'] = 1;
				$error['message'] = (isset($general['param_error']) && $general['param_error']!= '' ? $general['param_error'] : 'Gửi thông tin thành công, Chúng tôi sẽ liên hệ lại với bạn trong thời gian sớm nhất');
			}
		}else{
			$vali_error_str = "";
			$vali_error = $this->validator->getErrors();
			if (check_array($vali_error)) {
				foreach ($vali_error as $val) {
					$vali_error_str .= '- '.$val.' \n ';
				}
			}
			$error['flag'] = 1;
			$error['message'] = $vali_error_str;
		}
		echo json_encode(array(
			'error' => $error,
		));die();
	}

	private function dt_validation_contact($param = []){
		$validate = [
			'fullname' => 'required',
			'phone' => 'required|is_numeric',
			'email' => 'required|valid_email',
			'message' => 'required',
			// 'position' => 'required',
		];
		$errorValidate = [
			'fullname' => [
				'required' => 'Bạn phải nhập vào trường '.$param['fullname'],
			],
			'email' => [
				'required' => 'Bạn phải nhập vào trường '.$param['email'],
				'valid_email' => $param['email'].' không hợp lệ',
			],
			'phone' => [
				'required' => 'Bạn phải nhập vào trường '.$param['phone'],
				'is_numeric' => $param['phone'].' phải là kiểu số',
			],
			'message' => [
				'required' => 'Bạn phải nhập vào trường '.$param['message'],
			],
			// 'position' => [
			// 	'required' => 'Bạn phải nhập vào trường '.$param['position'],
			// ],
		];
		return [
			'validate' => $validate,
			'errorValidate' => $errorValidate,
		];
	}

	public function prd_detail_register(){

        // $response = $this->request->getPost('g_recaptcha_response') ?? '';
        // $secret = $this->secret;

        // $verify = file_get_contents("https://www.google.com/recaptcha/api/siteverify?secret={$secret}&response={$response}");
        // $result = json_decode($verify, true);

        // if (empty($result['success']) || !$result['success']) {
        //     echo json_encode(['error' => ['flag' => 1, 'message' => 'Xác minh reCAPTCHA thất bại. Vui lòng thử lại.']]);
        //     exit;
        // }


		$_subject = '"'.PRD_CONTACT_HEADER.'"';

		$fullname = $this->request->getPost('fullname');
		$email = $this->request->getPost('email');
		$phone = $this->request->getPost('phone');
		$message = $this->request->getPost('message');

		$product = $this->request->getPost('product');
		$price = $this->request->getPost('price');
		$bed = $this->request->getPost('bed');
		$area = $this->request->getPost('area');

		$validate = $this->request->getPost('validate');
		$__validate = $this->dt_validation_contact($validate);
		if ($this->validate($__validate['validate'], $__validate['errorValidate'])){
			// $_subject = '"'.$subject.'"';

			$mailContent = '
				<p>'.$validate['fullname'].': <b>'.$fullname.'</b></p>
				<p>'.$validate['email'].': <b>'.$email.'</b></p>
				<p>'.$validate['phone'].': <b>'.$phone.'</b></p>
				<p>'.$validate['message'].': <b>'.$message.'</b></p>
				<p>Sản phẩm: <b>'.$product.'<b/></p>
				<p>Giá: <b>'.$price.'<b/></p>
				<p>Phòng ngủ: <b>'.$bed.'<b/></p>
				<p>Diện tích: <b>'.$area.'<b/></p>
			';

			$_insert = array(
				'catalogueid' => 1,
				'phone' => $phone,
				'email' => $email,
				'publish' => 1,
				'created_at' => gmdate('Y-m-d H:i:s', time() + 7*3600),
			);

			$insertId = $this->Autoload_Model->_insert(array(
				'table' => 'contact',
				'data' => $_insert,
			));

			$_insert_Lang = array(
				'title' => $_subject,
				'objectid' => $insertId,
				'language' => $this->currentLanguage(),
				'module' => 'contact',
				'fullname' => $fullname,
				'content' => $mailContent,
				// 'address' => $address,
				'created_at' => gmdate('Y-m-d H:i:s', time() + 7*3600),
			);

			$insertId_Lang = $this->Autoload_Model->_insert(array(
				'table' => 'contact_translate',
				'data' => $_insert_Lang,
			));

			$this->mailbie->send(array(
				// 'to' => array($this->general['contact_email']),
				'to' => 'anne.hcmc@gmail.com',
				'subject' => $_subject.' từ hệ thống '.BASE_URL,
				'messages' => $mailContent,
			));
			// $this->session->set_flashdata('message-success', 'Gửi thông tin thành công, Chúng tôi sẽ liên hệ lại với bạn trong thời gian sớm nhất');
			$error['flag'] = 0;
			$error['message'] = 'Gửi thông tin thành công, Chúng tôi sẽ liên hệ lại với bạn trong thời gian sớm nhất';
		}else{
			$vali_error_str = "";
			$vali_error = $this->validator->getErrors();
			if (check_array($vali_error)) {
				foreach ($vali_error as $val) {
					$vali_error_str .= '- '.$val.' \n ';
				}
			}
			$error['flag'] = 1;
			$error['message'] = $vali_error_str;
		}
		echo json_encode(array(
			'error' => $error,
		));die();
	}

	private function dt_validation_prd_detail_register($param = []){
		$validate = [
			'fullname' => 'required',
			'phone' => 'required|is_numeric|min_length[10]|max_length[10]',
			'email' => 'required|valid_email',
			'message' => 'required',
			// 'position' => 'required',
		];
		$errorValidate = [
			'fullname' => [
				'required' => 'Bạn phải nhập vào trường '.$param['fullname'],
			],
			'email' => [
				'required' => 'Bạn phải nhập vào trường '.$param['email'],
				'valid_email' => $param['email'].' không hợp lệ',
			],
			'phone' => [
				'required' => 'Bạn phải nhập vào trường '.$param['phone'],
				'is_numeric' => $param['phone'].' phải là kiểu số',
				'min_length' => $param['phone'].' không được ít hơn 10 ký tự',
				'max_length' => $param['phone'].' không được vượt quá 10 ký tự',
			],
			'message' => [
				'required' => 'Bạn phải nhập vào trường '.$param['message'],
			],
			// 'position' => [
			// 	'required' => 'Bạn phải nhập vào trường '.$param['position'],
			// ],
		];
		return [
			'validate' => $validate,
			'errorValidate' => $errorValidate,
		];
	}

	public function ticket(){
		$subject = $this->request->getPost('subject');

		$fullname = $this->request->getPost('fullname');
		$phone = $this->request->getPost('phone');
		$message = $this->request->getPost('message');

		$validate = $this->request->getPost('validate');
		$__validate = $this->validation_ticket($validate);
		if ($this->validate($__validate['validate'], $__validate['errorValidate'])){
			$_subject = '"'.$subject.'"';

			$mailContent = '
				<p>'.$validate['fullname'].': <b>'.$fullname.'</b></p>
				<p>'.$validate['phone'].': <b>'.$phone.'</b></p>
				<p>'.$validate['message'].': <b>'.$message.'</b></p>
			';

			$_insert = array(
				'catalogueid' => 1,
				'phone' => $phone,
				'publish' => 1,
				'created_at' => gmdate('Y-m-d H:i:s', time() + 7*3600),
			);

			// print_r($_insert); exit;
			// print_r($_insert_Lang); exit;

			$insertId = $this->Autoload_Model->_insert(array(
				'table' => 'contact',
				'data' => $_insert,
			));

			$_insert_Lang = array(
				'title' => $_subject,
				'objectid' => $insertId,
				'language' => $this->currentLanguage(),
				'module' => 'contact',
				'fullname' => $fullname,
				'content' => $mailContent,
				'created_at' => gmdate('Y-m-d H:i:s', time() + 7*3600),
			);

			$insertId_Lang = $this->Autoload_Model->_insert(array(
				'table' => 'contact_translate',
				'data' => $_insert_Lang,
			));

			$this->mailbie->send(array(
				// 'to' => array($this->fcSystem['contact_email']),
				'to' => 'tudo210994@gmail.com',
				'subject' => $_subject.' từ hệ thống '.BASE_URL,
				'messages' => $mailContent,
			));
			// $this->session->set_flashdata('message-success', 'Gửi thông tin thành công, Chúng tôi sẽ liên hệ lại với bạn trong thời gian sớm nhất');
			$error['flag'] = 0;
			$error['message'] = 'Gửi thông tin thành công, Chúng tôi sẽ liên hệ lại với bạn trong thời gian sớm nhất';
		}else{
			$vali_error_str = "";
			$vali_error = $this->validator->getErrors();
			if (check_array($vali_error)) {
				foreach ($vali_error as $val) {
					$vali_error_str .= '- '.$val.' \n ';
				}
			}
			$error['flag'] = 1;
			$error['message'] = $vali_error_str;
		}
		echo json_encode(array(
			'error' => $error,
		));die();
	}

	public function booking(){
		$subject = $this->request->getPost('subject');

		$hotel = $this->request->getPost('hotel');
		$hotel_address = $this->request->getPost('hotel_address');
		$fullname = $this->request->getPost('fullname');
		$phone = $this->request->getPost('phone');
		$message = $this->request->getPost('message');

		$validate = $this->request->getPost('validate');
		$__validate = $this->validation_booking($validate);
		if ($this->validate($__validate['validate'], $__validate['errorValidate'])){
			$_subject = '"'.$subject.'"';

			$mailContent = '
				<p>'.$validate['hotel'].': <b>'.$hotel.'</b></p>
				<p>'.$validate['hotel_address'].': <b>'.$hotel_address.'</b></p>
				<p>'.$validate['fullname'].': <b>'.$fullname.'</b></p>
				<p>'.$validate['phone'].': <b>'.$phone.'</b></p>
				<p>'.$validate['message'].': <b>'.$message.'</b></p>
			';

			$_insert = array(
				'catalogueid' => 1,
				'phone' => $phone,
				'publish' => 1,
				'created_at' => gmdate('Y-m-d H:i:s', time() + 7*3600),
			);

			// print_r($_insert); exit;
			// print_r($_insert_Lang); exit;

			$insertId = $this->Autoload_Model->_insert(array(
				'table' => 'contact',
				'data' => $_insert,
			));

			$_insert_Lang = array(
				'title' => $_subject,
				'objectid' => $insertId,
				'language' => $this->currentLanguage(),
				'module' => 'contact',
				'fullname' => $fullname,
				'content' => $mailContent,
				'created_at' => gmdate('Y-m-d H:i:s', time() + 7*3600),
			);

			$insertId_Lang = $this->Autoload_Model->_insert(array(
				'table' => 'contact_translate',
				'data' => $_insert_Lang,
			));

			$this->mailbie->send(array(
				// 'to' => array($this->fcSystem['contact_email']),
				'to' => 'tudo210994@gmail.com',
				'subject' => $_subject.' từ hệ thống '.BASE_URL,
				'messages' => $mailContent,
			));
			// $this->session->set_flashdata('message-success', 'Gửi thông tin thành công, Chúng tôi sẽ liên hệ lại với bạn trong thời gian sớm nhất');
			$error['flag'] = 0;
			$error['message'] = 'Gửi thông tin thành công, Chúng tôi sẽ liên hệ lại với bạn trong thời gian sớm nhất';
		}else{
			$vali_error_str = "";
			$vali_error = $this->validator->getErrors();
			if (check_array($vali_error)) {
				foreach ($vali_error as $val) {
					$vali_error_str .= '- '.$val.' \n ';
				}
			}
			$error['flag'] = 1;
			$error['message'] = $vali_error_str;
		}
		echo json_encode(array(
			'error' => $error,
		));die();
	}

	public function reg_daily(){
		$subject = $this->request->getPost('subject');

		$fullname = $this->request->getPost('fullname');
		$store_name = $this->request->getPost('store_name');
		$city = $this->request->getPost('city');
		$phone = $this->request->getPost('phone');
		$message = $this->request->getPost('message');

		$validate = $this->request->getPost('validate');
		$__validate = $this->validation_daily($validate);
		if ($this->validate($__validate['validate'], $__validate['errorValidate'])){
			$_subject = '"'.$subject.'"';

			$mailContent = '
				<p>'.$validate['fullname'].': <b>'.$fullname.'</b></p>
				<p>'.$validate['store_name'].': <b>'.$store_name.'</b></p>
				<p>'.$validate['city'].': <b>'.$city.'</b></p>
				<p>'.$validate['phone'].': <b>'.$phone.'</b></p>
				<p>'.$validate['message'].': <b>'.$message.'</b></p>
			';

			$_insert = array(
				'catalogueid' => 1,
				'phone' => $phone,
				'publish' => 1,
				'created_at' => gmdate('Y-m-d H:i:s', time() + 7*3600),
			);

			// print_r($_insert); exit;
			// print_r($_insert_Lang); exit;

			$insertId = $this->Autoload_Model->_insert(array(
				'table' => 'contact',
				'data' => $_insert,
			));

			$_insert_Lang = array(
				'title' => $_subject,
				'objectid' => $insertId,
				'language' => $this->currentLanguage(),
				'module' => 'contact',
				'fullname' => $fullname,
				'content' => $mailContent,
				'address' => $city,
				'created_at' => gmdate('Y-m-d H:i:s', time() + 7*3600),
			);

			$insertId_Lang = $this->Autoload_Model->_insert(array(
				'table' => 'contact_translate',
				'data' => $_insert_Lang,
			));

			$this->mailbie->send(array(
				// 'to' => array($this->fcSystem['contact_email']),
				'to' => 'tudo210994@gmail.com',
				'subject' => $_subject.' từ hệ thống '.BASE_URL,
				'messages' => $mailContent,
			));
			// $this->session->set_flashdata('message-success', 'Gửi thông tin thành công, Chúng tôi sẽ liên hệ lại với bạn trong thời gian sớm nhất');
			$error['flag'] = 0;
			$error['message'] = 'Gửi thông tin thành công, Chúng tôi sẽ liên hệ lại với bạn trong thời gian sớm nhất';
		}else{
			$vali_error_str = "";
			$vali_error = $this->validator->getErrors();
			if (check_array($vali_error)) {
				foreach ($vali_error as $val) {
					$vali_error_str .= '- '.$val.' \n ';
				}
			}
			$error['flag'] = 1;
			$error['message'] = $vali_error_str;
		}
		echo json_encode(array(
			'error' => $error,
		));die();
	}

	public function modal_reg(){
		$subject = $this->request->getPost('subject');

		$fullname = $this->request->getPost('fullname');
		$phone = $this->request->getPost('phone');
		$email = $this->request->getPost('email');
		$address = $this->request->getPost('address');
		$position = $this->request->getPost('position');
		$message = $this->request->getPost('message');

		$validate = $this->request->getPost('validate');
		$__validate = $this->validation($validate);
		if ($this->validate($__validate['validate'], $__validate['errorValidate'])){
			$_subject = '"'.$subject.'"';

			$mailContent = '
				<p>'.$validate['fullname'].': <b>'.$fullname.'</b></p>
				<p>'.$validate['phone'].': <b>'.$phone.'</b></p>
				<p>'.$validate['email'].': <b>'.$email.'</b></p>
				<p>'.$validate['address'].': <b>'.$address.'</b></p>
				<p>'.$validate['position'].': <b>'.$position.'</b></p>
				<p>'.$validate['message'].': <b>'.$message.'</b></p>
			';

			$_insert = array(
				'catalogueid' => 1,
				'phone' => $phone,
				'email' => $email,
				'publish' => 1,
				'created_at' => gmdate('Y-m-d H:i:s', time() + 7*3600),
			);

			// print_r($_insert); exit;
			// print_r($_insert_Lang); exit;

			$insertId = $this->Autoload_Model->_insert(array(
				'table' => 'contact',
				'data' => $_insert,
			));

			$_insert_Lang = array(
				'title' => $_subject,
				'objectid' => $insertId,
				'language' => $this->currentLanguage(),
				'module' => 'contact',
				'fullname' => $fullname,
				'content' => $mailContent,
				'address' => $address,
				'created_at' => gmdate('Y-m-d H:i:s', time() + 7*3600),
			);

			$insertId_Lang = $this->Autoload_Model->_insert(array(
				'table' => 'contact_translate',
				'data' => $_insert_Lang,
			));

			$this->mailbie->send(array(
				// 'to' => array($this->fcSystem['contact_email']),
				'to' => 'tudo210994@gmail.com',
				'subject' => $_subject.' từ hệ thống '.BASE_URL,
				'messages' => $mailContent,
			));
			// $this->session->set_flashdata('message-success', 'Gửi thông tin thành công, Chúng tôi sẽ liên hệ lại với bạn trong thời gian sớm nhất');
			$error['flag'] = 0;
			$error['message'] = 'Gửi thông tin thành công, Chúng tôi sẽ liên hệ lại với bạn trong thời gian sớm nhất';
		}else{
			$vali_error_str = "";
			$vali_error = $this->validator->getErrors();
			if (check_array($vali_error)) {
				foreach ($vali_error as $val) {
					$vali_error_str .= '- '.$val.' \n ';
				}
			}
			$error['flag'] = 1;
			$error['message'] = $vali_error_str;
		}
		echo json_encode(array(
			'error' => $error,
		));die();
	}

	public function deleteContact(){
		$id = $this->request->getPost('id');
		$language = $this->request->getPost('language');
		$flag = $this->AutoloadModel->_update([
			'table' => 'contact_catalogue',
			'data' => ['deleted_at' => 1],
			'where' => ['id' => $id]
		]);
		if ($flag > 0){
			$delete = $this->AutoloadModel->_update([
				'table' => 'contact_translate',
				'data' => ['deleted_at' => 1],
				'where' => ['objectid' => $id, 'language' => $language]
			]);
		}

		echo $flag;die();
	}

	public function seen_contact(){
		$id = $this->request->getPost('id');
		$flag = $this->AutoloadModel->_update([
			'table' => 'contact',
			'data' => ['seen' => 1],
			'where_in' => $id,
			'where_in_field' => 'id',
		]);
		echo $flag;die();
	}
	public function delete1Contact(){
		$id = $this->request->getPost('id');
		$flag = $this->AutoloadModel->_update([
			'table' => 'contact',
			'data' => ['deleted_at' => 1],
			'where' => ['id' => $id]
		]);
		if ($flag > 0){
				$deleteRep = $this->AutoloadModel->_update([
					'table' => 'reply',
					'data' => ['deleted_at' => 1],
					'where' => ['objectid' => $id,]
				]);
			}


		echo $flag;die();
	}

	private function validation($param = []){
		$validate = [
			'fullname' => 'required',
			'phone' => 'required|is_numeric',
			'email' => 'required|valid_email',
			'address' => 'required',
			'position' => 'required',
		];
		$errorValidate = [
			'fullname' => [
				'required' => 'Bạn phải nhập vào trường '.$param['fullname'],
			],
			'phone' => [
				'required' => 'Bạn phải nhập vào trường '.$param['phone'],
				'is_numeric' => $param['phone'].' phải là kiểu số',
			],
			'email' => [
				'required' => 'Bạn phải nhập vào trường '.$param['email'],
				'valid_email' => $param['fullname'].' không hợp lệ',
			],
			'address' => [
				'required' => 'Bạn phải nhập vào trường '.$param['address'],
			],
			'position' => [
				'required' => 'Bạn phải nhập vào trường '.$param['position'],
			],
		];
		return [
			'validate' => $validate,
			'errorValidate' => $errorValidate,
		];
	}

	private function validation_daily($param = []){
		$validate = [
			'fullname' => 'required',
			'phone' => 'required|is_numeric',
			'city' => 'required|_checkDropdownSelect',
		];
		$errorValidate = [
			'fullname' => [
				'required' => 'Bạn phải nhập '.$param['fullname'],
			],
			'phone' => [
				'required' => 'Bạn phải nhập '.$param['phone'],
				'is_numeric' => $param['phone'].' phải là kiểu số',
			],
			'city' => [
				'required' => 'Bạn phải nhập '.$param['city'],
				'_checkDropdownSelect' => 'Bạn phải chọn '.$param['city'],
			],
		];
		return [
			'validate' => $validate,
			'errorValidate' => $errorValidate,
		];
	}

	private function validation_booking($param = []){
		$validate = [
			'fullname' => 'required',
			'phone' => 'required|is_numeric',
		];
		$errorValidate = [
			'fullname' => [
				'required' => 'Bạn phải nhập '.$param['fullname'],
			],
			'phone' => [
				'required' => 'Bạn phải nhập '.$param['phone'],
				'is_numeric' => $param['phone'].' phải là kiểu số',
			],
		];
		return [
			'validate' => $validate,
			'errorValidate' => $errorValidate,
		];
	}

	private function validation_ticket($param = []){
		$validate = [
			'fullname' => 'required',
			'phone' => 'required|is_numeric',
			'message' => 'required',
		];
		$errorValidate = [
			'fullname' => [
				'required' => 'Bạn phải nhập '.$param['fullname'],
			],
			'phone' => [
				'required' => 'Bạn phải nhập '.$param['phone'],
				'is_numeric' => $param['phone'].' phải là kiểu số',
			],
			'message' => [
				'required' => 'Bạn phải nhập '.$param['message'],
			],
		];
		return [
			'validate' => $validate,
			'errorValidate' => $errorValidate,
		];
	}





}
