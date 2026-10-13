<?php
namespace App\Controllers\Frontend\Contact;
use App\Controllers\FrontendController;

class Contact extends FrontendController{

	public $data = [];

	public function __construct(){
		$this->data = [];
		$this->data['module'] = 'article';
	}

	public function index($id = ''){


		$module_extract = explode("_", $this->data['module']);

        $this->data['meta_title'] = 'Contact us';
        $this->data['meta_description'] = 'Liên hệ với chúng tôi, chúng tôi sẽ liên lạc với bạn trong thời gian sớm nhất';
        $this->data['canonical'] = BASE_URL.'contact-us'.HTSUFFIX;
        $this->data['meta_image'] = $this->general['homepage_logo'];
			$this->data['slide_company'] = get_slide([
				'keyword' => 'slide-company',
				'language' => $this->currentLanguage(),
				'output' => 'array',
			]);
			$this->data['general'] = $this->general;

		 // if(in_array($this->detect_type, array('tablet', 'mobile'))){
   //          $this->data['template'] = 'mobile/contact/contact/index';
   //          return view('mobile/homepage/layout/home', $this->data);
   //      }else{
            $this->data['template'] = 'frontend/contact/contact/index';
            return view('frontend/homepage/layout/home', $this->data);
        // }
	}
}
