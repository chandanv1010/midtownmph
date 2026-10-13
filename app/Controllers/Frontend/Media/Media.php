<?php
namespace App\Controllers\Frontend\Media;
use App\Controllers\FrontendController;

class Media extends FrontendController{

    protected $data;

    public function __construct(){
        $this->data = [];
        $this->data['module'] = 'media';
    }

    public function index($id = 0, $page = 1){
        helper(['mypagination']);
        $id = (int)$id;

        $session = session();
        $module_extract = explode("_", $this->data['module']);
        $keyword = $this->condition_keyword();
        $this->data['object'] = $this->AutoloadModel->_get_where([
            'select' => 'tb1.id,tb1.catalogueid, tb1.viewed, tb1.album, tb1.image, tb2.title, tb2.canonical, tb2.meta_title, tb2.meta_description,  tb2.description, tb2.content, ',
            'table' => $module_extract[0].' as tb1',
            'where' => [
                'tb1.deleted_at' => 0,
                'tb1.publish' => 1,
                'tb1.id' => $id
            ],
            'join' => [
                [
                    'media_translate as tb2','tb1.id = tb2.objectid AND tb2.module = "media" AND tb2.language = \''.$this->currentLanguage().'\' ','inner'
                ]
            ],
        ]);
        $panel = get_panel([
            'locate' => 'sidebar',
            'language' => $this->currentLanguage()
        ]);

        foreach ($panel as $key => $value) {
            $this->data['panel'][$value['keyword']] = $value;
        }

        // if(isset($this->data['object']) && is_array($this->data['object']) && count($this->data['object'])){
        //     $session->setFlashdata('message-danger', 'Bài viết không tồn tại!');
        //     return redirect()->to(BASE_URL);
        // }
        $this->data['object']['album'] = json_decode($this->data['object']['album']);
        $this->data['object']['description'] = validate_input(base64_decode($this->data['object']['description']));
        $this->data['object']['content'] = validate_input(base64_decode($this->data['object']['content']));

        $this->data['detailCatalogue'] = $this->AutoloadModel->_get_where([
            'select' => ' tb1.id,tb1.lft, tb1.rgt, tb1.level, tb1.parentid, tb1.image,  tb2.title, tb2.canonical,  tb2.content, tb2.description, tb2.meta_title, tb2.meta_description',
            'table' => $this->data['module'].'_catalogue as tb1',
            'join' => [
                [
                    $module_extract[0].'_translate as tb2','tb2.module = \''.$this->data['module'].'_catalogue\' AND tb2.objectid = tb1.id AND tb2.language = \''.$this->currentLanguage().'\'', 'inner'
                ]
            ],
            'where' => [
                'tb1.deleted_at' => 0,
                'tb1.publish' => 1,
                'tb1.id' => $this->data['object']['catalogueid']
            ]
        ]);

        // if(isset($this->data['detailCatalogue']) && is_array($this->data['detailCatalogue']) && count($this->data['detailCatalogue'])){
        //     $session->setFlashdata('message-danger', 'Bài viết không tồn tại!');
        //     return redirect()->to(BASE_URL);
        // }
        $this->data['breadcrumb'] = $this->AutoloadModel->_get_where([
            'select' => 'tb1.lft, tb1.rgt, tb1.id, tb1.parentid,  tb2.title, tb2.canonical',
            'table' => $this->data['module'].'_catalogue as tb1',
            'join' => [
                [
                    $module_extract[0].'_translate as tb2','tb2.module = \''.$this->data['module'].'_catalogue\' AND tb2.objectid = tb1.id AND tb2.language = \''.$this->currentLanguage().'\'', 'inner'
                ]
            ],
            'where' => [
                'tb1.deleted_at' => 0,
                'tb1.publish' => 1,
                'tb1.lft <=' => $this->data['detailCatalogue']['lft'],
                'tb1.rgt >=' => $this->data['detailCatalogue']['rgt'],
            ],
            'order_by' => 'tb1.lft asc'
        ], TRUE);

        $cookie = $this->set_cookie($id, $this->data['object']);

        $this->data['meta_title'] = (!empty( $this->data['object']['meta_title'])? $this->data['object']['meta_title']: $this->data['object']['title']);
        $this->data['meta_description'] = (!empty( $this->data['object']['meta_description'])? $this->data['object']['meta_description']:cutnchar(strip_tags( $this->data['object']['description']), 300));
        $this->data['meta_image'] = !empty( $this->data['object']['image'])?base_url( $this->data['object']['image']):((isset($this->data['object']['album'][0])) ? $this->data['object']['album'][0] : '');

        $config['base_url'] = write_url($this->data['object']['canonical'], FALSE, TRUE);
        if(!isset($this->data['canonical']) || empty($this->data['canonical'])){
            $this->data['canonical'] = $config['base_url'].HTSUFFIX;
        }

        $this->data['general'] = $this->general;

        //  if(in_array($this->detect_type, array('tablet', 'mobile'))){
        //     $this->data['template'] = 'mobile/media/media/index';
        //     return view('mobile/homepage/layout/home', $this->data);
        // }else{
            $this->data['template'] = 'frontend/media/media/index';
            return view('frontend/homepage/layout/home', $this->data);
        // }
    }

    private function condition_keyword($keyword = ''): string{
        if(!empty($this->request->getGet('keyword'))){
            $keyword = $this->request->getGet('keyword');
            $keyword = '(title LIKE \'%'.$keyword.'%\')';
        }
        return $keyword;
    }

    private function set_cookie($id = 0, $param = []){
        $idList = [];
        if(!isset($_COOKIE['COUNT_'.$this->data['module']]) || empty($_COOKIE['COUNT_'.$this->data['module']])){
            array_push($idList, $id);
            setcookie('COUNT_'.$this->data['module'], json_encode($idList), time() + 1*24*3600, "/");
            $cookie = $this->AutoloadModel->_update([
                'table' => $this->data['module'],
                'where' => [
                    'id' => $id,
                    'deleted_at' => 0,
                    'publish' => 1
                ],
                'data' => [
                    'viewed' => $param['viewed'] + 1
                ]
            ]);
        }else{
            $getCookie = $this->request->getCookie('COUNT_'.$this->data['module']);
            $getCookie = json_decode($getCookie);
            $count = 0;
            foreach ($getCookie as $key => $value) {
                if($id == $value){
                    $count++;
                }
            }
            if($count == 0){
                array_push($getCookie, $id);
                setcookie('COUNT_'.$this->data['module'], json_encode($getCookie), time() + 1*24*3600, "/");
                $cookie = $this->AutoloadModel->_update([
                    'table' => $this->data['module'],
                    'where' => [
                        'id' => $id,
                        'deleted_at' => 0,
                        'publish' => 1
                    ],
                    'data' => [
                        'viewed' => $param['viewed'] + 1
                    ]
                ]);
            }
        }
        return true;
    }

    public function condition_catalogue($catalogueid = 0){
        $id = [];
        $module_extract = explode("_", $this->data['module']);
        if($catalogueid > 0){
            $catalogue = $this->AutoloadModel->_get_where([
                'select' => 'tb1.id, tb1.lft, tb1.rgt, tb3.title',
                'table' => $module_extract[0].'_catalogue as tb1',
                'join' =>  [
                    [
                        'media_translate as tb3','tb1.id = tb3.objectid AND tb3.language = \''.$this->currentLanguage().'\' AND tb3.module = "media"','inner'
                    ],
                ],
                'where' => ['tb1.id' => $catalogueid],
            ]);

            $catalogueChildren = $this->AutoloadModel->_get_where([
                'select' => 'id',
                'table' => $module_extract[0].'_catalogue',
                'where' => ['lft >=' => $catalogue['lft'],'rgt <=' => $catalogue['rgt']],
            ], TRUE);

            $id = array_column($catalogueChildren, 'id');
        }
        return [
            'where_in' => $id,
            'where_in_field' => 'tb2.catalogueid'
        ];

    }
}
