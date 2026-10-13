<?php
	helper(['mydatafrontend','mydata']);
	$baseController = new App\Controllers\FrontendController();
    $language = $baseController->currentLanguage();
    $brand = brand($language);
    $get_all_catalogue = get_all_catalogue([
    	'module' => 'product_catalogue',
    	'language' => $language,
    	'where' => [
    		'level >' => 1,
    	],
    ]);
    $price = explode_price($general['another_price']);
?>

<div class="aside-product">
	<?php /*
	<div class="aside-list mb30">
		<div class="aside-list-title">
			<h3>Danh mục sản phẩm</h3>
		</div>
		<ul class="uk-list">
			<?php if(isset($get_all_catalogue) && is_array($get_all_catalogue) && count($get_all_catalogue)){
                foreach ($get_all_catalogue as $key => $value) {
             ?>
                <li>
                    <label class="check-aside-product check-catalogue uk-flex uk-flex-middle mb10" >
		                <input type="checkbox" name="catalogue[]"  value="<?php echo check_isset($value['id']) ?>">
		                <?php echo check_isset($value['title']) ?>
		            </label>
                </li>
            <?php }} ?>
		</ul>
	</div>
	<div class="aside-list mb30">
		<div class="aside-list-title">
			<h3>Nhu cầu sử dụng</h3>
		</div>
		<ul class="uk-list">
			<?php if(isset($brand) && is_array($brand) && count($brand)){
                foreach ($brand as $key => $value) {
             ?>
                <li>
                    <label class="check-aside-product check-brand uk-flex uk-flex-middle mb10" data-select="<?php echo check_isset($value['keyword']) ?>" >
		                <input type="checkbox" name="brand[]"  value="<?php echo check_isset($value['id']) ?>">
		                <?php echo check_isset($value['title']) ?>
		            </label>
                </li>
            <?php }} ?>
		</ul>
	</div>
		*/ ?>
	
	<div class="aside-list mb30">
		<div class="aside-list-title">
			<h3>Hạng sao</h3>
		</div>
		<ul class="uk-list">
            <li>
                <label class="check-aside-product check-star uk-flex uk-flex-middle mb10">
	                <input type="checkbox" name="star" class="star" value="5">
                    <span class="rating fs20" data-stars="5" data-default-rating="5" disabled></span>

	            </label>
            </li>
            <li>
                <label class="check-aside-product check-star uk-flex uk-flex-middle mb10">
	                <input type="checkbox" name="star" class="star" value="4">
                    <span class="rating fs20" data-stars="5" data-default-rating="4" disabled></span>

	            </label>
            </li>
            <li>
                <label class="check-aside-product check-star uk-flex uk-flex-middle mb10">
	                <input type="checkbox" name="star" class="star" value="3">
                    <span class="rating fs20" data-stars="5" data-default-rating="3" disabled></span>

	            </label>
            </li>
            <li>
                <label class="check-aside-product check-star uk-flex uk-flex-middle mb10">
	                <input type="checkbox" name="star" class="star" value="2">
                    <span class="rating fs20" data-stars="5" data-default-rating="2" disabled></span>

	            </label>
            </li>
            <li>
                <label class="check-aside-product check-star uk-flex uk-flex-middle mb10">
	                <input type="checkbox" name="star" class="star" value="1">
                    <span class="rating fs20" data-stars="5" data-default-rating="1" disabled></span>

	            </label>
            </li>
		</ul>
	</div>
	<div class="aside-list">
		<div class="aside-list-title">
			<h3>Giá (<?php echo CURRENCY ?>)</h3>
		</div>
		<ul class="uk-list">
			<?php if(isset($price) && is_array($price) && count($price)){
				foreach ($price as $key => $value) {
			 ?>
	            <li>
	                <label class="check-aside-product check-price uk-flex uk-flex-middle mb10">
		                <input type="checkbox" data-start="<?php echo check_isset($value['start']) ?>" data-end="<?php echo check_isset($value['end']) ?>" name="price[]" class="about-price" value="<?php echo check_isset($value['value']) ?>">
		                <?php 
		                	if(check_isset($value['start']) == 'min'){
		                		echo 'Dưới '.convertPriceNumber($value['end']);
		                	}else if(check_isset($value['end']) == 'max'){
		                		echo 'Trên '.convertPriceNumber($value['start']);
		                	}else{
		                		echo convertPriceNumber($value['start']).' - '.convertPriceNumber($value['end']);
		                	}
		                ?>
		            </label>
	            </li>
        	<?php }} ?>
		</ul>
	</div>
</div>

