<?php
namespace App\Controllers\Frontend\Homepage;
use App\Controllers\FrontendController;

class Router extends FrontendController{

	public $data = [];
    public function silo($segment_1 = '', $segment_2 = ''){
        $canonical = $segment_1.'/'.$segment_2;
        $this->index($canonical);
    }

    public function search($page = 0){
        $page = (int)$page;
        $router = '\App\Controllers\Frontend\Search\Search::index';
        return view_cell($router, 'page='.$page.'');
    }

    public function login(){
        $router = '\App\Controllers\Frontend\Homepage\Auth::login';
        return view_cell($router);
    }
    public function signup(){
        $router = '\App\Controllers\Frontend\Homepage\Auth::signup';
        return view_cell($router);
    }

    public function forgot(){
        $router = '\App\Controllers\Frontend\Homepage\Auth::forgot';
        return view_cell($router);
    }

    public function logout(){
        $router = '\App\Controllers\Frontend\Homepage\Auth::logout';
        return view_cell($router);
    }


	public function index($canonical = '', $page = 1){

        /*
         * Tim dong router UNG VOI NGON NGU DANG XEM truoc da.
         *
         * Truoc day chi tra theo `canonical`, khong he loc ngon ngu. Co ba
         * chuoi canonical bi dung chung boi hai ngon ngu, va chung tro vao
         * HAI BAN GHI KHAC HAN NHAU:
         *
         *   midtown-m5-the-grande   vi -> article_catalogue 14 (id 813)
         *                           tq -> product 112          (id 870)
         *   midtown-m6-the-symphony vi -> article_catalogue 15 (id 814)
         *                           tq -> article 35           (id 2200)
         *   midtown-m8-the-peak     vi -> article_catalogue 17 (id 816)
         *                           tq -> product 129          (id 885)
         *
         * Dong `vi` luon co id nho hon nen luon duoc lay truoc, khien ba URL
         * /tq/... hien ra trang danh muc cua tieng Viet thay vi san pham cua
         * minh. Kiem chung tren ca ban that.
         */
        $locale = request()->getLocale();

        $router = $this->AutoloadModel->_get_where([
            'select' => '*',
            'table' => 'router',
            'where' => ['canonical' => $canonical, 'language' => $locale],
        ]);

        /*
         * Khong co dong dung ngon ngu thi VAN lui ve khop long nhu truoc.
         *
         * Co nhung URL cu da duoc Google danh chi muc chi ton tai o mot ngon
         * ngu; loc cung la chung thanh 404 ngay. Uu tien dung ngon ngu, con
         * thieu thi giu nguyen hanh vi cu - sua duoc cai sai ma khong lam
         * chet cai dang chay.
         */
        if(!(isset($router) && is_array($router) && count($router))){
            $router = $this->AutoloadModel->_get_where([
                'select' => '*',
                'table' => 'router',
                'where' => ['canonical' => $canonical],
            ]);
        }

        if(isset($router) && is_array($router) && count($router)){
            /*
             * view_cell() BAT BUOC controller tra ve chuoi.
             *
             * Nhung vai controller quyet dinh "ban ghi khong ton tai" va tra
             * ve redirect()->to(...) - vi du Media\Catalogue::index khi danh
             * muc chua xuat ban. Luc do CodeIgniter nem TypeError:
             *
             *     Cell::render() must be of the type string, object returned
             *
             * va khach nhan mot trang 500 rong thay vi duoc dua ve trang chu.
             * Day la nguyen nhan 5 trang media_catalogue hong tren ban that
             * (media_catalogue id 2 dang publish = 0 nhung van con du 5 dong
             * trong bang router).
             *
             * Bat dung loai loi do roi lam thay viec chuyen huong ma controller
             * da dinh lam. Cac TypeError khac van nem len nhu cu.
             */
            try{
                return view_cell($router['view'], 'id='.$router['objectid'].', page='.$page.'');
            }catch(\TypeError $e){
                if(strpos($e->getMessage(), 'Cell::render()') === false){
                    throw $e;
                }

                log_message('warning', 'Router: {view} tra ve redirect thay vi chuoi (canonical: {canonical})', [
                    'view' => $router['view'],
                    'canonical' => $canonical,
                ]);

                return redirect()->to(BASE_URL);
            }
        }

        return redirect()->to('notfound');
	}
}
