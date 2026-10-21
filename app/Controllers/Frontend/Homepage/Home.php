<?php
namespace App\Controllers\Frontend\Homepage;
use App\Controllers\FrontendController;

class Home extends FrontendController{

	public $data = [];

	public function __construct(){
		$this->data['module'] = 'home';
	}

	public function index(){

		$this->data['general'] = $this->general;
		$this->data['meta_title'] = (isset($this->data['general']['seo_meta_title']) ? $this->data['general']['seo_meta_title'] : '');
		$this->data['meta_description'] = (isset($this->data['general']['seo_meta_description']) ? $this->data['general']['seo_meta_description'] : '');
		$this->data['meta_image'] = $this->general['homepage_logo'];
		$this->data['og_type'] = 'website';
		$this->data['canonical'] = BASE_URL;


		$panel = get_panel([
			'locate' => 'home',
			'language' => $this->locale
		]);
		$this->data['extra_list'] = $this->AutoloadModel->_get_where([
            'select' => 'tb1.id, tb2.title, tb2.canonical, tb2.description,  tb1.image,  ',

            'table' => 'product_tag as tb1',
            'join' =>  [
                    [
                        'product_translate as tb2','tb1.id = tb2.objectid AND tb2.module = "product_tag" AND tb2.language = \''.$this->locale.'\' ','inner'
                    ]
                ],
            'where' => ['tb1.deleted_at' => 0],
            /*
             * PHAI co order_by.
             *
             * Khong co thi MySQL tra ve theo thu tu nao tuy no, va thu tu do
             * DOI GIUA CAC LAN TAI TRANG. Do duoc tren trang that: goi /kr/
             * bon lan lien tiep thi hai muc "shophouse" va "penthouse" doi
             * cho nhau o lan thu hai.
             *
             * Hai the product_tag deu co `order` = 0 nen khong co gi phan
             * dinh; them `tb1.id` lam tieu chi phu de thu tu co dinh.
             *
             * Dung cung quy uoc voi cho khac trong ma nguon:
             * mypanel_helper.php dong 363 va 413.
             */
            'order_by' => 'tb1.order desc, tb1.id asc',
        ],true);
		// print_r($panel); exit;
		$this->data['panel'] = $panel;
		$this->data['ishome'] = 'ishome';
		$this->data['template'] = 'frontend/homepage/home/index';
		return view('frontend/homepage/layout/home', $this->data);
	}

	public function overview(){
		$this->data['general'] = $this->general;
		$this->data['meta_title'] = (isset($this->data['general']['seo_meta_title']) ? $this->data['general']['seo_meta_title'] : '');
		$this->data['meta_description'] = (isset($this->data['general']['seo_meta_description']) ? $this->data['general']['seo_meta_description'] : '');
		$this->data['og_type'] = 'website';
		$this->data['canonical'] = BASE_URL;
		$this->data['template'] = 'frontend/homepage/home/overview';
		return view('frontend/homepage/layout/home', $this->data);
	}

	public function test(){
		$this->data['general'] = $this->general;
		$this->data['meta_title'] = (isset($this->data['general']['seo_meta_title']) ? $this->data['general']['seo_meta_title'] : '');
		$this->data['meta_description'] = (isset($this->data['general']['seo_meta_description']) ? $this->data['general']['seo_meta_description'] : '');
		$this->data['og_type'] = 'website';
		$this->data['canonical'] = BASE_URL;

		$panel = get_panel([
			'locate' => 'home',
			'language' => $this->locale
		]);
		// print_r($panel); exit;
		$this->data['panel'] = $panel;

		$this->data['ishome'] = 'ishome';
		$this->data['template'] = 'frontend/homepage/home/test';
		return view('frontend/homepage/layout/home', $this->data);
	}

   public function sitemap(){
      $this->data['articleCatalogue'] = $this->AutoloadModel->_get_where([
         'select' => 'tb2.title, tb2.canonical',
         'table' => 'article_catalogue as tb1',
         'join' => [
            ['article_translate as tb2','tb1.id = tb2.objectid','inner']
         ],
         'where' => [
            'tb1.deleted_at' => 0,
            'tb1.publish' => 1,
            'tb2.module' => 'article_catalogue',
            'tb2.language' => $this->locale,
         ]
      ], TRUE);

      // pre($this->data['articleCatalogue']);die();

      $this->data['article'] = $this->AutoloadModel->_get_where([
         'select' => 'tb2.title, tb2.canonical',
         'table' => 'article as tb1',
         'join' => [
            ['article_translate as tb2','tb1.id = tb2.objectid','inner']
         ],
         'where' => [
            'tb1.deleted_at' => 0,
            'tb1.publish' => 1,
            'tb2.module' => 'article',
            'tb2.language' => $this->locale,
         ]
      ], TRUE);



      $this->data['projectCatalogue'] = $this->AutoloadModel->_get_where([
         'select' => 'tb2.title, tb2.canonical',
         'table' => 'project_catalogue as tb1',
         'join' => [
            ['project_translate as tb2','tb1.id = tb2.objectid','inner']
         ],
         'where' => [
            'tb1.deleted_at' => 0,
            'tb1.publish' => 1,
            'tb2.module' => 'project_catalogue',
            'tb2.language' => $this->locale,
         ]
      ], TRUE);



      $this->data['productCatalogue'] = $this->AutoloadModel->_get_where([
         'select' => 'tb2.title, tb2.canonical',
         'table' => 'product_catalogue as tb1',
         'join' => [
            ['product_translate as tb2','tb1.id = tb2.objectid','inner']
         ],
         'where' => [
            'tb1.deleted_at' => 0,
            'tb1.publish' => 1,
            'tb2.module' => 'product_catalogue',
            'tb2.language' => $this->locale,
         ]
      ], TRUE);



      $this->data['product'] = $this->AutoloadModel->_get_where([
         'select' => 'tb2.title, tb2.canonical',
         'table' => 'product as tb1',
         'join' => [
            ['product_translate as tb2','tb1.id = tb2.objectid','inner']
         ],
         'where' => [
            'tb1.deleted_at' => 0,
            'tb1.publish' => 1,
            'tb2.module' => 'product',
            'tb2.language' => $this->locale,
         ]
      ], TRUE);

      // pre($this->data['product']);die();


      $this->response->setHeader('Content-Type', 'application/xml');
		return view('frontend/homepage/home/sitemap', $this->data);
   }
}
