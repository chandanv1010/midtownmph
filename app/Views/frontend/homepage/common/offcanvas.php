<?php 

$baseController = new App\Controllers\FrontendController();
    $language = request()->getLocale();
$main_nav = get_menu(array('keyword' => 'main-menu','language' => $language , 'output' => 'array')); 
/* Truoc day dong nay la ternary long nhau liet ke tung ngon ngu, nen them
 * ngon ngu moi la roi ve tieng Viet. Khoa HOME da co san trong moi file
 * app/Language/<ma>/app.php voi dung cac chu cu. */
$homepage = lang('app.HOME');
?>
<div id="offcanvas" class="uk-offcanvas offcanvas">
	<div class="uk-offcanvas-bar">
		<form class="uk-search" action="<?php echo site_url('tim-kiem'); ?>" data-uk-search="{}">
		    <input class="uk-search-field" type="search" name="keyword" placeholder="<?php echo esc(lang('app.SEARCH_PLACEHOLDER'), 'attr') ?>">
        </form>
		<ul class="l1 uk-nav uk-nav-offcanvas uk-nav uk-nav-parent-icon mb20" data-uk-nav>
			<li class="l1 ">
				<a href="" title="<?php echo $homepage ?>" class="l1 uk-text-uppercase"><?php echo $homepage ?></a>
			</li>
	        <?php if(isset($main_nav['data']) && is_array($main_nav['data']) && count($main_nav['data'])) {?>
				<?php foreach ($main_nav['data'] as $key => $val) { ?>
				<li class="l1 <?php echo (isset($val['children']) && is_array($val['children']) && count($val['children']))?'uk-parent uk-position-relative':''; ?>">
					<?php echo (isset($val['children']) && is_array($val['children']) && count($val['children']))?'<a href="#" title="" class="dropicon"></a>':''; ?>
					<a href="<?php echo $val['canonical']; ?>" title="<?php echo $val['title']; ?>" class="l1"><?php echo $val['title']; ?></a>
					<?php if(isset($val['children']) && is_array($val['children']) && count($val['children'])) { ?>
					<ul class="l2 uk-nav-sub">
						<?php foreach ($val['children'] as $keyItem => $valItem) { ?>
						<li class="l2"><a href="<?php echo $valItem['canonical']; ?>" title="<?php echo $valItem['title']; ?>" class="l2"><?php echo $valItem['title']; ?></a></li>
						<?php } ?>
					</ul>
					<?php } ?>
				</li>
				<?php } ?>
			<?php } ?>
		</ul>
		<?php
			$currency_init = get_currency([
				'publish' => 1,
			]);

			$currency = $currency_init;
			if (check_array($currency_init)) {
				$currency = array_column($currency_init, 'title', 'id');
			}

			$currency_id = 0; // k tồn tại cookie
			if (isset($__currency) && check_array($__currency)) {
				$currency_id = $__currency['currency_id'];
			}
	 	?>
	 	<?php if (check_array($currency)): ?>
			<div class="uk-flex uk-flex-middle mb20 uk-flex-center dt_language js_dt_currency">
				<?php
					foreach ($currency as $key => $val) {
						$_class = ($currency_id == $key)? 'active': '';
						if ($currency_id == 0) {
							$_class = ($val == array_values($currency)[0])? 'active':'';
						}
			 	?>
			        <a class="<?php echo $_class ?>" title="<?php echo $val ?>" data-currency_id="<?php echo $key ?>"><?php echo $val ?></a>
			    <?php } ?>
			</div>
		<?php endif ?>
		<div class="uk-flex uk-flex-middle uk-flex-center mb20 dt_language">
			<?php
				/* Sinh tu du lieu thay cho ba the <a> viet cung truoc day.
				 * Xem language_switcher() trong app/Helpers/mydata_helper.php. */
				echo language_switcher(fe_currentLanguage());
			?>
		</div>
 		<div class="text-white mb15 uk-text-center">Hotline: <a href="tel: <?php echo $general['contact_hotline'] ?>" title="" class="text-white  "><?php echo $general['contact_hotline'] ?></a></div>
 		<div class="wrap_social mb15 ">
            <ul class="uk-flex uk-flex-middle uk-flex-center list-social-image order-1">
                <li>
                    <div class="social line">
                        <a target="_blank" href="https://zalo.me/<?php echo $general['social_zalo']?>" title="facebook" class="image img-cover">
                            <img src="public/zalo.png" alt="zalo">
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
        <div class="btn uk-text-center" >
        	<div class="uk-display-inline-block" onclick="UIkit.offcanvas.hide([force = false])" style="color: #fff;padding: 5px 10px;border: 1px solid #fff;margin: auto;border-radius: 5px;">Close</div>
        </div>
	</div>
</div><!-- #offcanvas -->
