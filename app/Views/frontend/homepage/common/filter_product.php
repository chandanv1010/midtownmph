<?php
	$actual_link = "$_SERVER[REQUEST_SCHEME]://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";
	$baseController = new App\Controllers\FrontendController();
	$language = request()->getLocale();
	$model = new App\Models\AutoloadModel();

	$category = $model->_get_where([
		'select' => 'tb1.id, tb2.title, tb1.level, tb1.lft, tb1.rgt',
		'table' => 'product_catalogue as tb1',
		'join' => [
			['product_translate as tb2','tb1.id = tb2.objectid','inner'],
		],
		'where' => [
			'tb1.publish' => 1,
			'tb1.deleted_at' => 0,
			'tb2.language' => $language,
			'tb2.module' => 'product_catalogue'
		],
		'order_by' => 'tb1.lft asc'
	], TRUE);
	$temp_ban = [];
	$temp_thue = [];
	if(isset($category) && is_array($category) && count($category)){
		$temp_ban[0] = '['.$general['search_select_1'].']';
		$temp_thue[0] = '['.$general['search_select_1'].']';
		$category_ban = [];
		$category_thue = [];
		foreach($category as $key => $val){
			if($val['id'] == 10){
				$category_ban = $val;
			}
			if($val['id'] == 18){
				$category_thue = $val;
			}
		}
		foreach($category as $key => $val){
			if($val['lft'] > $category_ban['lft'] && $val['rgt'] < $category_ban['rgt']){
				$temp_ban[$val['id']] = $val['title'];
			}
			if($val['lft'] > $category_thue['lft'] && $val['rgt'] < $category_thue['rgt']){
				$temp_thue[$val['id']] = $val['title'];
			}
		}
	}

	$currency = (isset($_COOKIE['HTVIETNAM_currency'])) ? json_decode($_COOKIE['HTVIETNAM_currency'], TRUE) : '';

	$bds_list = get_attr_by_id(4, $language);
	$funiture_list = get_attr_by_id(5, $language);

 ?>

<section class="filter_panel filter_prd_panel mb-common" data-module="product">
	<div class="uk-container uk-container-center">
		<form action="<?php echo write_url('tim-kiem-bat-dong-san') ?>" method="get">
			<div class="filter-row mb20 uk-flex uk-flex-middle">
				<div class="filter-title mr10"><?php echo $general['search_text_1'] ?>:</div>
				<div class="uk-flex uk-flex-middle va-width-mobile">
					<div class="filter-row va-filter-row uk-flex uk-flex-middle mr20 ">
						<input type="checkbox" <?php echo (isset($_GET['type']) && $_GET['type'] == 2 ? 'checked' : '') ?> id="sell" value="2" name="type"  class="input-radio mr5">
						<label for="sell"><?php echo $general['search_select_type_1'] ?></label>
					</div>
					<div class="filter-row va-filter-row uk-flex uk-flex-middle">
						<input type="checkbox" <?php echo (isset($_GET['type']) && $_GET['type'] == 1 ? 'checked' : '') ?> id="rent" value="1" name="type" class="input-radio mr5">
						<label for="rent"><?php echo $general['search_select_type_2'] ?></label>
					</div>
				</div>
			</div>
			<div class="filter-row mb20 uk-flex uk-flex-middle">
				<div class="filter-title mr10"><?php echo $general['search_text_2'] ?>:</div>
				<select name="catalogueid" id="" class="select2 select-type-va va-width-mobile" style="width:300px">
					<?php if(isset($temp_ban) && is_array($temp_ban) && count($temp_ban)){
						foreach($temp_ban as $key => $val){ ?>
						<option <?php echo (isset($_GET['catalogueid']) && $_GET['catalogueid'] == $key) ? 'selected'  : '' ?> value="<?php echo $key ?>"><?php echo $val; ?></option>
					<?php }} ?>
				</select>
			</div>
			<script>
				var va_catalogueid = '<?php echo (isset($_GET['catalogueid'])) ? $_GET['catalogueid']  : '' ?>';
			</script>

			<?php if(isset($bds_list['data']) && is_array($bds_list['data']) && count($bds_list['data'])){ ?>
			<div class="filter-row mb20 uk-flex uk-flex-middle">
				<div class="filter-title mr10"><?php echo $bds_list['title'] ?>:</div>
				<select name="<?php echo 'attr['.$bds_list['id'].']' ?>" id="" class="select2 va-width-mobile" style="width:300px">
					<option value="0">[<?php echo $bds_list['title'] ?>]</option>
					<?php foreach($bds_list['data'] as $key => $value){ ?>
					<option <?php echo (isset($_GET['attr'][$bds_list['id']]) && $_GET['attr'][$bds_list['id']] == $value['id']) ? 'selected'  : '' ?> value="<?php echo $value['id'] ?>"><?php echo $value['title'] ?></option>
					<?php } ?>
				</select>
			</div>
			<?php } ?>
			<div class="filter-row mb20 uk-flex uk-flex-middle">
				<div class="filter-title mr10"><?php echo $general['search_text_4'] ?>:</div>
				<div class="form-row va-width-mobile" style="width:298px;">
					<input style="width:100%;" type="text" value="<?php echo (isset($_GET['bed'])) ? $_GET['bed'] : '' ?>" name="bed" class="input-text"  placeholder="<?php echo $general['search_text_4'] ?>">
				</div>
			</div>
			<div class="filter-row mb20 uk-flex uk-flex-middle">
				<div class="filter-title mr10"><?php echo $general['search_text_5'] ?>:</div>
				<div class="uk-flex uk-flex-middle va-width-mobile">
					<div class="form-row va-kc">
						<input type="text" value="<?php echo (isset($_GET['area_from'])) ? $_GET['area_from'] : 0 ?>" name="area_from" class="input-text"  placeholder="<?php echo esc(lang('app.SEARCH_AREA_FROM')) ?>"> <?php echo $general['search_kc'] ?>
					</div>
					<div class="form-row ml5 va-kc">
						 <input type="text" name="area_to" class="input-text" value="<?php echo (isset($_GET['area_to'])) ? $_GET['area_to'] : 0 ?>"  placeholder="-">
					</div>
				</div>
			</div>

			<div class="filter-row mb20 uk-flex uk-flex-middle">
				<div class="filter-title mr10"><?php echo $general['search_text_6'] ?> (<?php echo isset($currency['title']) ? $currency['title'] : 'USD' ?>):</div>
				<div class="uk-flex uk-flex-middle va-width-mobile">
					<div class="form-row va-kc">
						<input type="text" name="price_from" class="input-text"  value="<?php echo (isset($_GET['price_from'])) ? $_GET['price_from'] : 0 ?>" placeholder="<?php echo esc(lang('app.SEARCH_PRICE_FROM')) ?>"> <?php echo $general['search_kc'] ?>
					</div>
					<div class="form-row ml5 va-kc">
						 <input type="text" name="price_to" value="<?php echo (isset($_GET['price_to'])) ? $_GET['price_to'] : 0 ?>" class="input-text"  placeholder="-">
					</div>
				</div>
			</div>
			<?php if(isset($funiture_list['data']) && is_array($funiture_list['data']) && count($funiture_list['data'])){ ?>
			<div class="filter-row mb20 uk-flex uk-flex-middle">
				<div class="filter-title mr10"><?php echo $funiture_list['title'] ?>:</div>
				<select name="<?php echo 'attr['.$funiture_list['id'].']' ?>" id="" class="select2 va-width-mobile" style="width:300px">
					<option value="0">[<?php echo $funiture_list['title'] ?>]</option>
					<?php foreach($funiture_list['data'] as $key => $value){ ?>
					<option <?php echo (isset($_GET['attr'][$funiture_list['id']]) && $_GET['attr'][$funiture_list['id']] == $value['id']) ? 'selected'  : '' ?> value="<?php echo $value['id'] ?>"><?php echo $value['title'] ?></option>
					<?php } ?>
				</select>
			</div>
			<?php } ?>

			<div class="filter-row mb20 uk-flex uk-flex-middle">
				<div class="filter-title mr10"><?php echo $general['search_text_8'] ?>:</div>
				<div class="form-row va-width-mobile" style="width:298px;">
					<input style="width:100%;" type="text" value="<?php echo (isset($_GET['productid'])) ? $_GET['productid'] : '' ?>" name="productid" class="input-text"  placeholder="<?php echo $general['search_text_8'] ?>">
				</div>
			</div>
			<div class="filter-row mb20 uk-flex uk-flex-middle">
				<div class="filter-title mr10"><?php echo $general['search_text_9'] ?>:</div>
				<div class="form-row va-width-mobile" style="width:298px;">
					<input style="width:100%;" type="text" value="<?php echo (isset($_GET['keyword'])) ? $_GET['keyword'] : '' ?>" name="keyword" class="input-text"  placeholder="<?php echo $general['search_text_9'] ?>">
				</div>
			</div>
			<div class="filter-row uk-flex uk-flex-middle">
				<div class="filter-title mr10"></div>
				<input type="submit" name="search" value="<?php echo $general['search_button'] ?>" class="input-submit">
			</div>
		</form>
	</div>
</section><!-- .main-filter -->
<script>
	var actual_link = '<?php echo $actual_link ?>';
</script>
<script>
	$(document).on('change', 'input[name="type"]', function(){
		let val = $("input[name='type']:checked").val()
		let html = '';
		if(val == 2){
			<?php
				if(isset($temp_ban) && is_array($temp_ban) && count($temp_ban)){
				foreach($temp_ban as $key => $val){
			?>
				html= html +'<option <?php echo (isset($_GET['catalogueid']) && $_GET['catalogueid'] == $key) ? 'selected'  : '' ?> value="<?php echo $key ?>"><?php echo $val; ?></option>';
			<?php }} ?>
		}
		if(val == 1){
			<?php if(isset($temp_thue) && is_array($temp_thue) && count($temp_thue)){
				foreach($temp_thue as $key => $val){ ?>
				html= html +'<option <?php echo (isset($_GET['catalogueid']) && $_GET['catalogueid'] == $key) ? 'selected'  : '' ?> value="<?php echo $key ?>"><?php echo $val; ?></option>';
			<?php }} ?>
		}
		$('.select-type-va').html(html)
	})

	$(document).ready(function(){
		if(va_catalogueid != '' && va_catalogueid != 0 && va_catalogueid != undefined){
			$('input[name="type"]').trigger('change')
			$('.select-type-va').val(va_catalogueid)
		}
	})
</script>


<script>
	$(document).on('change', '.va-filter-row input', function(){
		$('.va-filter-row input').prop('checked', false);
		$(this).prop('checked', true);
	})

</script>
