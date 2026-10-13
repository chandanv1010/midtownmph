<?php
    $baseController = new App\Controllers\FrontendController();
    $language = request()->getLocale();
    $ft_menu = get_menu(['keyword' => 'footer-nav','output' => 'array','language' => $language]);
    // print_r($ft_menu); exit;
?>
<footer id="footer">
    <section class="footer_top bg-mask" style="background: url('public/frontend/resources/img/bg/bg_ft.png') no-repeat center;">
        <div class="uk-container uk-container-center por">
            <div class="uk-grid uk-grid-medium" data-uk-grid-match="{target: '.ht2109_grid_match'}">
                <div class="uk-width-large-1-3">
                    <div class="ft-panel">
                        <div class="ft_heading mb20"><?php echo $general['footer_link'] ?></div>
                        <?php if (isset($ft_menu['data']) && check_array($ft_menu['data'])): ?>
                        <?php
                            foreach ($ft_menu['data'] as $key => $val) {

                         ?>
                            <?php if (isset($val['children']) && check_array($val['children'])): ?>
                                <ul class="uk-list uk-clearfix link-panel_list">
                                    <?php
                                        foreach ($val['children'] as $keyPost => $valPost) {
                                            $title = $valPost['title'];
                                            $href = $valPost['canonical'];
                                    ?>
                                        <li><a href="<?php echo $href ?>" title="<?php echo $title ?>" class="link">- <?php echo $title ?></a></li>
                                    <?php } ?>
                                </ul>
                            <?php endif ?>
                        <?php } ?>
                        <?php endif ?>
                    </div>
                </div>
                <div class="uk-width-large-1-3">
                    <section class="reg_panel_2 dt_heading dt_shadow_1 br6 mb20">
                        <!-- <div class="uk-container uk-container-center"> -->
                            <h2 class="reg_panel_2_heading uk-text-center"><?php echo $general['footer_contact'] ?></h2>
                            <div class="reg_panel_body">
                                <form action="" method="post" class="uk-form form loading application-form" enctype="multipart/form-data" id="contact_footer" data-lang="<?php echo $language ?>">
                                    <div class="uk-grid uk-grid-small" data-uk-grid-match="{target: '.ht2109_grid_match'}">
                                        <div class="uk-width-1-1 ">
                                            <div class="form-row mb10 dt_validate" data-validate="<?php echo $general['footer_name'] ?>">
                                                <input type="text" name="fullname" value="" class="form-control uk-width-1-1 input-text input-reset fullname" placeholder="<?php echo $general['footer_name'] ?>" autocomplete="off" >
                                            </div>
                                        </div>
                                        <div class="uk-width-1-1 ">
                                            <div class="form-row mb10 dt_validate" data-validate="Email">
                                                <input type="text" name="email" value="" class="form-control uk-width-1-1 input-text input-reset email" placeholder="Email *" autocomplete="off" >
                                            </div>
                                        </div>
                                        <div class="uk-width-1-1 ">
                                            <div class="form-row mb10 dt_validate" data-validate="<?php echo $general['footer_phone'] ?>">
                                                <input type="text" name="phone" value="" class="form-control uk-width-1-1 input-text input-reset phone" placeholder="<?php echo $general['footer_phone'] ?>" autocomplete="off" >
                                            </div>
                                        </div>
                                        <div class="uk-width-1-1 ">
                                            <div class="form-row mb10 dt_validate" data-validate="<?php echo $general['footer_content'] ?>">
                                                <textarea name="message" class="uk-width-1-1 form-textarea input-reset message" placeholder="<?php echo $general['footer_content'] ?>" style="padding: 5px 10px; resize:vertical"></textarea>
                                            </div>
                                        </div>
                                        <div class="g-recaptcha" style="width:100%;margin-bottom:10px;" data-sitekey="6LcumPIrAAAAAC3vyEc8GgngZZvOnPx569x-ATZK"></div>
                                        <div class="uk-width-1-2">
                                            <div class="form-row uk-text-center">
                                                <div class="submit">
                                                    <button class="btn-submit js_upload_file" type="button" value="" name=""><?php echo $general['footer_file'] ?></button>
                                                    <input type="file" name="file" size="40" class="input-file uk-hidden" accept=".pdf" aria-invalid="false">
                                                </div>
                                            </div>

                                        </div>
                                        <div class="uk-width-1-2">
                                            <div class="form-row uk-text-center">
                                                <div class="submit">
                                                    <div class="loading" style="display: block">
                                                        <div class="bg-loader"></div>
                                                        <button class="btn-submit" type="submit" value="" name=""><?php echo $general['footer_btn'] ?></button>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="form-row uk-width-1-1 file-result uk-hidden mt10">

                                        </div>
                                    </div>
                                </form>
                            </div>
                        <!-- </div> -->
                    </section>
                </div>
                <div class="uk-width-large-1-3">
                    <div class="uk-flex uk-flex-right">
                        <div class="uk-width-large-4-5">
                            <section class="ft_contact_panel dt_shadow_1 br6">
                                <!-- <div class="uk-container uk-container-center"> -->
                                    <h2 class="reg_panel_2_heading uk-text-center"><?php echo $general['footer_contact_2'] ?></h2>
                                    <div class="ft_contact_body bg_white">
                                        <div class="hotline mb5">Hotline: <a href="tel: <?php echo $general['contact_hotline'] ?>" title=""><?php echo $general['contact_hotline'] ?></a></div>
                                        <div class="email">Email: <a target="_blank" href="https://mail.google.com/mail/?view=cm&fs=1&to=<?php echo $general['contact_email'] ?>" title=""><?php echo $general['contact_email'] ?></a></div>
                                        <div class="wrap_social">
                                            <ul class="uk-flex uk-flex-middle uk-flex-center list-social-image order-1">
                                                <li>
                                                    <div class="social line">
                                                        <a target="_blank" href="<?php echo $general['social_zalo']?>" title="facebook" class="image img-cover">
                                                            <img src="public/frontend/resources/img/icon_zalo.png" alt="zalo">
                                                        </a>
                                                    </div>
                                                </li>
                                                <li>
                                                    <div class="social line">
                                                        <a target="_blank" href="<?php echo $general['social_whatsapp']?>" title="facebook" class="image img-cover">
                                                            <img src="public/frontend/resources/img/whatsapp.png" alt="Whatsapp">
                                                        </a>
                                                    </div>
                                                </li>
                                                <li>
                                                    <div class="social line">
                                                        <a target="_blank" href="<?php echo $general['social_viber']?>" title="Viber" class="image img-cover">
                                                            <img src="public/frontend/resources/img/viber.png" alt="Viber">
                                                        </a>
                                                    </div>
                                                </li>
                                            </ul> <!-- list-social-->
                                        </div>
                                    </div>
                                <!-- </div> -->
                            </section>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
</footer>
<?php echo view('frontend/homepage/common/ft_project') ?>
<section class="ft_rate_panel">
    <div class="uk-container uk-container-center">
        <div class="uk-flex uk-flex-middle uk-flex-wrap uk-flex-space-between">
            <div class="dt_ft_rate dt-margin-wrap">
                <div class="uk-flex uk-flex-middle">
                    <div class="title mr10"><?php echo $general['footer_rate']?></div>
                    <span class="rating fs20" data-stars="5" data-default-rating="5" ></span>
                </div>
            </div>
            <ul class="uk-list uk-clearfix uk-flex uk-flex-middle list-social order-3 dt-mg-wrap">
                <li>
                    <div class="social fb"><a target="_blank" href="<?php echo $general['social_facebook']?>" title=""><i class="fa fa-facebook"></i></a></div>
                </li>
                <li>
                    <div class="social yt"><a target="_blank" href="<?php echo $general['social_youtube']?>" title=""><i class="fa fa-play"></i></a></div>
                </li>
                <li>
                    <div class="social twitter"><a target="_blank" href="<?php echo $general['social_twitter']?>" title=""><i class="fa fa-twitter"></i></a></div>
                </li>

                <?php /*
                <?php if(isset($general['social_instagram'])){?>
                    <li>
                        <div class="social insta"><a target="_blank" href="<?php echo $general['social_instagram']?>" title="instagram"><i class="fa fa-instagram"></i></a></div>
                    </li>
                <?php }?>
                */ ?>
            </ul> <!-- list-social-->
        </div>
    </div>
</section>
<div id="copyright" class="txt-center">
    <div class="uk-container uk-container-center">
        Copyright @ <a href="<?php echo $general['contact_website'] ?>"><?php echo $general['contact_website'] ?></a>. All right reserved
    </div>
</div>

<?php /*
<div id="toolbox-fixed" class="">
    <div class="uk-containter uk-container-center">
      <ul class="uk-list uk-clearfix">
        <li>
            <a target="_blank" href="<?php echo $general['tmdt_tiki'] ?>" title="" class="icon img-cover"><img src="public/frontend/resources/img/icon/tiki.png" alt=""></a>
        </li>
        <li>
            <a target="_blank" href="<?php echo $general['tmdt_shopee'] ?>" title="" class="icon img-cover"><img src="public/frontend/resources/img/icon/shopee.png" alt=""></a>
        </li>
        <li>
            <a target="_blank" href="<?php echo $general['tmdt_lazada'] ?>" title="" class="icon img-cover"><img src="public/frontend/resources/img/icon/laz.png" alt=""></a>
        </li>
        <li>
            <a target="_blank" href="<?php echo $general['tmdt_sendo'] ?>" title="" class="icon img-cover"><img src="public/frontend/resources/img/icon/sendo.png" alt=""></a>
        </li>
        <li>
            <a target="_blank" href="<?php echo $general['social_facebook'] ?>" title="" class="icon img-cover"><img src="public/frontend/resources/img/icon/fb.png" alt=""></a>
        </li>
        <li>
            <a target="_blank" href="<?php echo $general['social_instagram'] ?>" title="" class="icon img-cover"><img src="public/frontend/resources/img/icon/insta.png" alt=""></a>
        </li>
    </ul>
    </div>
</div>
*/ ?>




    <style>
      /*ngang*/
      /*#toolbox-fixed{
        position: fixed;
        bottom: 0;
        left: 0;
        right: 0;
        z-index: 999;
        background: #f5f5f5;
        padding: 1% 0;
        box-shadow: 0 -5px 5px -5px #3333338c;
      }*/

      /*dọc*/

      #toolbox-fixed {
          position: fixed;
          bottom: 130px;
          left: initial;
          right: 0;
          z-index: 999;
          /* background: #f5f5f5; */
          padding: 0 10px;
          /* box-shadow: 0 -5px 5px -5px #3333338c; */
      }
      #toolbox-fixed ul > li,
      #toolbox-fixed ul{
        margin: 0;
      }
      #toolbox-fixed a{
      display: block;
      margin-bottom: 10px;
      border-radius: 50%;
      overflow: hidden;
      /*box-shadow: 0 0 3px #000;*/
      }
      #toolbox-fixed .icon{
        width: 40px;
        height: 40px;
      }

      #toolbox-fixed .title{
        display: block;
        text-align: center;
        font-size: 12px;
        color: #333;
        text-decoration: none;
      }

      #toolbox-fixed ul > li> a:hover{
        color: #333;
        text-decoration: none;
      }

      @media (max-width: 959px) {
        #UTTWh47-1566642215919{
          bottom: 65px !important;
        }
      }
    </style>

<div class="zalo-phone">
    <div class="img-cover image  uk-visible-large">
        <a target="_blank" href="<?php echo $general['social_zalo']?>" title="facebook" class="image img-cover">
            <img src="public/frontend/resources/img/contact-2/circle-zalo.png">
        </a>
    </div>
    <div class="zalo-number">
            <a href="tel:<?php echo $general['contact_hotline'] ?>"><?php echo $general['contact_hotline'] ?></a>
        </div>
    </div>
</div>
<!-- <div class="live-chat uk-visible-large">
    <div class="img-cover image">
        <img src="public/frontend/resources/img/contact-2/box-chat.png">
    </div>
</div> -->

<a id="backtop" href="" title="Về đầu trang" style="bottom: 10px;"><i class="fa fa-angle-up"></i></a>
<script>
    $(window).scroll(function() {
        if($(this).scrollTop() > 50) $('#backtop').stop().animate({ bottom: '150px' }, 200);
        else $('#backtop').stop().animate({ bottom: '-60px' }, 200);

        if($(this).scrollTop() > 80){
            $('.pc-header').addClass('scroll-fixed');
        }else{
            $('.pc-header').removeClass('scroll-fixed');
        }

    });
    $(document).ready(function() {
        $('#backtop').click(function(event) {
            event.preventDefault();
            $('html, body').animate({scrollTop: 0},500);
        });
    });
</script>


<script>
    /* Chu cho nut "xem them / thu gon" trong public/frontend/resources/function.js.
     *
     * Truoc day cho nay la mot switch liet ke tung ngon ngu, nen them ngon ngu
     * moi la phai sua them mot case - va quen thi nguoi dung thay tieng Anh.
     * Nay lay thang tu app/Language/<ma>/app.php: them ngon ngu chi can them
     * thu muc ngon ngu, khong dung toi file nay nua.
     *
     * json_encode lo viec escape dau nhay va ky tu dac biet, an toan hon la
     * noi chuoi bang tay vao JavaScript.
     */
    const expand   = <?= json_encode(lang('app.BTN_EXPAND'), JSON_UNESCAPED_UNICODE) ?>;
    const minimize = <?= json_encode(lang('app.BTN_MINIMIZE'), JSON_UNESCAPED_UNICODE) ?>;

</script>