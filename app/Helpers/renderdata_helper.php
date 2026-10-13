<?php
use App\Models\AutoloadModel;

if (! function_exists('render_menu_frontend')){
    function render_menu_frontend(array $param = [], $child = false){
        $html = '';
        if($child == false){
            $html = '<ul class="uk-navbar-nav uk-clearfix main-menu">';
        }
        if(isset($param) && is_array($param) && count($param)){

            foreach ($param as $key => $val) {
                $class = '';


                $html = $html.'<li data-url="'.$val['canonical'].'">';
                if($val['canonical'] == 'trang-chu.html' || $val['canonical'] == 'home.html'){
                    $canonical = '';
                }else{
                    $canonical = $val['canonical'];
                }
                $html = $html.'<a href="'.$canonical.'" title="'.$val['title'].'">'.$val['title'].'</a>';
                if($val['level'] >= 2){
                    $class = 'css_submenu';
                }
                if($val['children'] != []){
                    $html = $html.'<div class="dropdown-menu '.$class.'">';
                        $html = $html.'<ul class="uk-list submenu">';
                            $html = $html.render_menu_frontend($val['children'], true);
                        $html = $html.'</ul>';
                    $html = $html.'</div>';
                }

            }
        }
        if($child == false){
            $html = $html.'</ul>';
        }
        return $html;
    }
}

if (! function_exists('render_menu_mobile')){
    function render_menu_mobile(array $param = [], $child = false, $count = 1){
        $html = '';
        if($child == false){
            $html = '<ul class="l'.$count.' uk-nav uk-nav-offcanvas uk-nav-parent-icon" data-uk-nav>';
        }
        if(isset($param) && is_array($param) && count($param)){
            foreach ($param as $key => $val) {
                $class = 'uk-parent uk-position-relative';
                $html = $html.'<li class="l'.$count.' '.($val['children'] != [] ? $class : '').'">';
                if($val['children'] != []){
                    $html = $html.'<a href="#" title="'.$val['title'].'" class="dropicon"></a>';
                }
                    $html = $html.'<a href="'.$val['canonical'].'" title="'.$val['title'].'" class="l'.$count.'">'.$val['title'].'</a>';
                    if($val['children'] != []){
                        $dem = $count+1;
                        $html = $html.'<ul class="l'.$dem.' uk-nav-sub">';
                            $html = $html.render_menu_frontend($val['children'], true, $count + 1);
                         $html = $html.'</ul>';
                }
                    $html = $html.'</li>';
            }
        }
        if($child == false){
            $html = $html.'</ul>';
        }
        return $html;
    }
}

if (! function_exists('render_slideshow_uikit')){
    function render_slideshow_uikit(array $param = []){
        $html = '';
        if(isset($param) && is_array($param) && count($param)){
            $html = $html.'<div class="uk-slidenav-position" data-uk-slideshow="{autoplay:true}">';
                $html = $html.'<ul class="uk-slideshow"  >';
                    foreach ($param as $key => $value) {
                        $html = $html.'<li><a href="'.$value['url'].'" title="'.$value['title'].'" class="img-cover"><img src="'.$value['image'].'" alt="'.(($value['title'] != '') ? $value['title'] : $value['image']).'"></a></li>';
                    }
                $html = $html.'</ul>';
                $html = $html.'<a href="" class="uk-slidenav uk-slidenav-contrast uk-slidenav-previous" data-uk-slideshow-item="previous"></a>';
                $html = $html.'<a href="" class="uk-slidenav uk-slidenav-contrast uk-slidenav-next" data-uk-slideshow-item="next"></a>';
            $html = $html.'</div>';
        }
        return $html;
    }
}


?>
