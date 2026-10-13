<script>
	var count_catalogue = '<?php echo ((isset($attribute_catalogue)) ? count($attribute_catalogue) : '') ?>'
</script>
<div class="ibox mb20 block-version" data-countattribute_catalogue="2">
	<div class="ibox-title" style="padding-bottom: 15px;">
		<div class="uk-flex uk-flex-middle uk-flex-space-between">
			<h5 class="mb0">Option (2 phòng ngủ, 3 phòng ngủ, duplex, ...)</h5>
			<div class="ibox-tools">
				<button class="btn version_setting btn-success full-width m-b m0">+Thêm</button>
			</div>
		</div>
	</div>
	<script>
		var attribute_cat = '<?php //echo $encode_catalogue ?>';
	</script>
	<script>
		var product_select = '<?php //echo json_encode($rewrite_select) ?>';
	</script>
	<div class="ibox-content" <?php echo ((isset($version) && is_array($version) && count($version)) ? '' : 'style="display: none;"') ?>>
		<input type="text" name="checked[]" value="<?php echo isset($version[0]['checked']) ? $version[0]['checked'] : '' ?>" class="hidden checked_value">
		<div class="row block-attribute">
			<div class="col-lg-12">
				<table class="show_attribute table">
					<thead>
						<tr>
							<td></td>
							<td style="width: 200px;">Tiêu đề</td>
							<td>Giá trị</td>
							<td style="width: 50px;"></td>
						</tr>
					</thead>
					<tbody class="select_attribute">
						<?php 
							$index = 0;
						 ?>
						<tr class=""></tr>
							<td data-index="">
								<input type="checkbox" name="checkbox[]" value="1" class="checkbox-item">
								<input type="text" name="checkbox_val[]" value="1" class="hidden">
								<div for="" class="label-checkboxitem <?php
									if(isset($checked) && is_array($checked) && count($checked)){
										foreach ($checked as $keyCheck => $valCheck) {
											if($value['catalogue'] == $valCheck){
												echo 'checked';
											}
										}
									}
								?>"></div>
							</td>
							<td>
								<div class="form-row">
									<input name="attribute[<?php echo $index; ?>][]" class="tagsinput form-control" type="text" value=""/>
									<select name="" multiple="multiple" data-title="Nhập 2 kí tự để tìm kiếm..." style="width: 100%;" data-join="attribute_translate" data-catalogueid="" data-module="attribute" data-select="title"></select>
								</div>
							<td>
								<a type="button" class="btn btn-default delete-attribute" ><i class="fa fa-trash"></i></a>
							</td>
						</tr>
					</tbody>
				</table>
			</div>
		</div>
		<table class="block_attribute_canonical table <?php echo (isset($version[0]) && $version[0]['type'] == 'canonical') ? 'show' : '' ?>" style="display: none">
			<thead>
				<tr>
					<th style="width:150px">Tên thuộc tính</th>
					<th style="max-width:200px">Chọn sản phẩm</th>
					<th>Đường dẫn</th>
					<th>Mã sản phẩm</th>
				</tr>
			</thead>
			<tbody>
				<?php
					if(isset($version) && is_array($version) && count($version)  && $version[0]['type'] == 'canonical'){
					foreach ($version as $key => $value) {
				?>
				<tr id="<?php echo isset($value['content']['code_version']) ? $value['content']['code_version'] : '' ?>">
					<td>
						<input type="text" name="attribute1[]" value="<?php echo isset($value['content']['attribute1']) ? $value['content']['attribute1'] : '' ?>" class="hidden">
						<input type="text" name="attribute2[]" value="<?php echo isset($value['content']['attribute2']) ? $value['content']['attribute2'] : '' ?>" class="hidden">
						<input type="text" name="attribute3[]" value="<?php echo isset($value['content']['attribute3']) ? $value['content']['attribute3'] : '' ?>" class="hidden">
						<input type="text" name="title_version[]" readonly="" value="<?php echo isset($value['content']['title_version']) ? $value['content']['title_version'] : '' ?>" data-module="<?php echo $module; ?>" class="form-control" autocomplete="off" >
					</td>
					<td>
						<?php echo form_dropdown('objectid[]', $get_canonical, set_value('objectid[]', (isset($value['content']['objectid'])) ? $value['content']['objectid'] : ''), 'data-module= "'.$module.'" class="form-control  select2 select_canonical"');?>
					</td>
					<td>
						<input type="text" name="canonical_version[]" readonly="" value="" readonly="" class="form-control canonical_version" autocomplete="off" >
					</td>
					<td>
						<input type="text" name="code_version[]" value="<?php echo isset($value['content']['code_version']) ? $value['content']['code_version'] : '' ?>" class="form-control" autocomplete="off">
					</td>
				</tr>

				<?php
				}}
				 ?>
			</tbody>
		</table>
		<table class="table block_attribute_normal <?php echo (isset($version[0]) && $version[0]['type'] == 'normal') ? 'show' : '' ?>" style="display: none">
			<thead>
				<tr>
					<th></th>
					<th style="width:150px">Tên thuộc tính</th>
					<th>Giá</th>
					<th>Mã sản phẩm</th>
					<th class="text-center" style="width:100px">Tác vụ</th>
				</tr>
			</thead>
			<tbody>
				<?php if(isset($version) && is_array($version) && count($version)  && $version[0]['type'] == 'normal'){
					foreach ($version as $key => $value) {
					if($value['type'] == 'normal'){
						$img = json_decode(validate_input($value['content']['img_version']), TRUE);
					}

				?>
				<tr id="<?php echo isset($value['content']['code_version']) ? $value['content']['code_version'] : '' ?>">
					<td>
						<input type="text" name="attribute1[]" value="<?php echo isset($value['content']['attribute1']) ? $value['content']['attribute1'] : '' ?>" class="hidden">
						<input type="text" name="attribute2[]" value="<?php echo isset($value['content']['attribute2']) ? $value['content']['attribute2'] : '' ?>" class="hidden">
						<input type="text" name="attribute3[]" value="<?php echo isset($value['content']['attribute3']) ? $value['content']['attribute3'] : '' ?>" class="hidden">
						<input type="text" name="barcode_version[]"  value="<?php echo isset($value['content']['barcode_version']) ? $value['content']['barcode_version'] : '' ?>" class="hidden barcode_version">
						<input type="text" name="model_version[]" value="<?php echo isset($value['content']['model_version']) ? $value['content']['model_version'] : '' ?>" class="hidden model_version">
						<div class="img_version img-scaledown" style="cursor: pointer;">
							<img src="<?php echo (isset($img)) ? $img[0] : 'public/select-img.png' ?>" class="img_version_select" alt="" data-target="#<?php echo isset($value['content']['code_version']) ? $value['content']['code_version'] : '' ?>">
							<?php echo form_input('img_version[]', validate_input(set_value('img_version[]', (isset($value['content']['img_version'])) ? $value['content']['img_version'] : '')), 'class="form-control input_img_version" placeholder="Đường dẫn của ảnh"    autocomplete="off" style="display:none; "');?>
						</div>

					</td>
					<td>
						<input type="text" name="title_version[]" readonly="" value="<?php echo isset($value['content']['title_version']) ? $value['content']['title_version'] : '' ?>" class="form-control" autocomplete="off" >
					</td>
					<td>
						<input type="text" name="price_version[]" value="<?php echo isset($value['content']['price_version']) ? $value['content']['price_version'] : '' ?>" class="form-control price_version int" autocomplete="off">
					</td>
					<td>
						<input type="text" name="code_version[]" value="<?php echo isset($value['content']['code_version']) ? $value['content']['code_version'] : '' ?>" class="form-control" autocomplete="off">
					</td>
					<td><button type="button" class=" product_edit" data-toggle="modal" data-id="#<?php echo isset($value['content']['code_version']) ? $value['content']['code_version'] : '' ?>" data-target="#openModalDetail" >Chỉnh sửa</button></td>
				</tr>

				<?php }} ?>
			</tbody>
		</table>
	</div>
</div>
