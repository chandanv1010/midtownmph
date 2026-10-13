<div class="ibox mb20 block-general-product hidden">
	<div class="ibox-title uk-flex uk-flex-middle uk-flex-space-between">
		<h5>Thông tin Chung</h5>
		<div class="ibox-tools">
			<button type="submit" name="create" value="create" class="btn btn-primary block full-width m-b">Lưu</button>
		</div>
	</div>
	<div class="ibox-content">
		<div class="row">
			
			<div class="col-lg-6 m-b hidden">
				<label class="control-label ">
					<span>Giá khuyến mại</span>
				</label>
				<?php echo form_input('promotion_price', set_value('promotion_price', (isset($product['price_promotion'])) ? $product['price_promotion'] : ''), 'class="form-control price int" placeholder="" id="promotion_price" autocomplete="off"'); ?>
			</div>
		</div>
		<div class="row hidden">
			<div class="col-lg-4 mb15">
				<label class="control-label ">
					<span>BarCode</span>
				</label>
				<?php echo form_input('bar_code', set_value('bar_code', (isset($product['bar_code'])) ? $product['bar_code'] : ''), 'class="form-control" placeholder="VD:UPC,ISBN" id="bar_code" autocomplete="off"'); ?>
			</div>
			<div class="col-lg-4 mb15">
				<label class="control-label ">
					<span>Model</span>
				</label>
				<?php echo form_input('model', set_value('model', (isset($product['model'])) ? $product['model'] : ''), 'class="form-control" placeholder="UA50NU7400KXXV" id="model" autocomplete="off"'); ?>
			</div>
			<div class="col-lg-4 mb15">
				<label class="control-label ">
					<span>Xuất xứ</span>
				</label>
				<?php echo form_input('made_in', set_value('made_in', (isset($product['made_in'])) ? $product['made_in'] : ''), 'class="form-control" placeholder="Việt Nam" id="made_in" autocomplete="off"'); ?>
			</div>
			
			
		</div>
	</div>
</div>