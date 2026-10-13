<?php
	$brand_1 = get_object_catalogue([
        'module' => 'brand_catalogue',
        'language' => $language,
        'where' => [
            'tb1.id' => 1,
        ],
        'children' => [
            'flag' => false,
            'limit' => 2,
            // 'post' => true,
            // 'post_limit' => 9,
        ],
        'post' => [
            'flag' => true,
            // 'limit' => 0,
        ],
    ], true);
	$brand_2 = get_object_catalogue([
        'module' => 'brand_catalogue',
        'language' => $language,
        'where' => [
            'tb1.id' => 24,
        ],
        'children' => [
            'flag' => false,
            'limit' => 2,
            // 'post' => true,
            // 'post_limit' => 9,
        ],
        'post' => [
            'flag' => true,
            // 'limit' => 0,
        ],
    ], true);

    // print_r($brand_2); exit;

	$brandid = [];
	if(!empty($product['brandid']) && $product['brandid'] != null ){
		$brandid = json_decode($product['brandid'], TRUE);
	}

	// pre($brandid);
 ?>
 <?php if (check_array($brand_1)): ?>
	<div class="ibox mb20 dt_brand_product">
		<div class="ibox-title">
			<div class="uk-flex uk-flex-middle uk-flex-space-between">
				<h5><?php echo $brand_1['title'] ?></h5>
				<input type="checkbox" class="brand_1_checkall" value="0">
			</div>
		</div>
		<?php if (isset($brand_1['post']) && check_array($brand_1['post'])): ?>
			<div class="ibox-content">
				<div class="form-group clearfix mb0">
					<div class="col-md-12">
						<div class="userGroupContainer clearfix brand_1_checkall_container">
							<?php
								foreach ($brand_1['post'] as $key => $val) {
									$brand_id = $val['id'];
									$title = $val['title'];
									$image = $val['image'];
							 ?>
							<div class="col-md-4">
								<div class="i-checks mb10">
									<label class="uk-flex">
											<input style="margin-top:0;margin-right:10px" name="brandid[indoor][]" <?php echo (isset($brandid['indoor']) && is_array($brandid['indoor']) && count($brandid['indoor']) && in_array($val['id'], $brandid['indoor']) ) ? 'checked' : '' ?> type="checkbox" value="<?php echo $brand_id ?>">
										<span><?php echo $title ?></span>
									</label>
								</div>
							</div>
						<?php } ?>

						</div>
					</div>
				</div>
			</div>
		<?php endif ?>
	</div>
 <?php endif ?>
 <?php if (check_array($brand_2)): ?>
	<div class="ibox mb20 dt_brand_product">
		<div class="ibox-title">
			<div class="uk-flex uk-flex-middle uk-flex-space-between">
				<h5><?php echo $brand_2['title'] ?></h5>
				<input type="checkbox" class="brand_2_checkall" value="0">
			</div>
		</div>
		<?php if (isset($brand_2['post']) && check_array($brand_2['post'])): ?>
			<div class="ibox-content">
				<div class="form-group clearfix mb0">
					<div class="col-md-12">
						<div class="userGroupContainer clearfix brand_2_checkall_container">
							<?php
								foreach ($brand_2['post'] as $key => $val) {
									$brand_id = $val['id'];
									$title = $val['title'];
									$image = $val['image'];
							 ?>
							<div class="col-md-4">
								<div class="i-checks mb10">
									<label class="uk-flex">
										<input style="margin-top:0;margin-right:10px" name="brandid[outdoor][]" <?php echo (isset($brandid['outdoor']) && is_array($brandid['outdoor']) && count($brandid['outdoor']) && in_array($val['id'], $brandid['outdoor']) ) ? 'checked' : '' ?> type="checkbox" value="<?php echo $brand_id ?>">
										<span><?php echo $title ?></span>
									</label>
								</div>
							</div>
						<?php } ?>

						</div>
					</div>
				</div>
			</div>
		<?php endif ?>
	</div>
 <?php endif ?>

 <script type="text/javascript">
 	$(document).ready(function(){
		$('.brand_1_checkall').click(function(){
			let _this = $(this);
			let table = $('.brand_1_checkall_container');

			if(_this.prop('checked')){
				table.find('input').prop('checked', true);
			}
			else{
				table.find('input').prop('checked', false);
			}
		});
		$('.brand_2_checkall').click(function(){
			let _this = $(this);
			let table = $('.brand_2_checkall_container');

			if(_this.prop('checked')){
				table.find('input').prop('checked', true);
			}
			else{
				table.find('input').prop('checked', false);
			}
		});
	});
 </script>
