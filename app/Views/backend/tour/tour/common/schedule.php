<div class="ibox mb20 block-schedule">
	<div class="ibox-title uk-flex uk-flex-middle uk-flex-space-between">
		<h5>Lịch trình</h5>
		<div class="ibox-tools">
			<button class="btn btn_schedule btn-success block full-width m-b">Tạo lịch trình</button>
		</div>
	</div>
	<div class="ibox-content schedule_more ">
		<?php 
			if(isset($schedule_list) && is_array($schedule_list) && count($schedule_list)){ 
			$count = 1;
		?>
			<?php foreach ($schedule_list as $key => $value) { ?>
				<div class="schedule_desc mb15">
					<div class="uk-flex uk-flex-middle uk-flex-space-between">
						<div class="va-flex-row">
							<div class="form-row">
								<label class="control-label text-left">
									<span>Từ</span>
								</label>
								<input type="text" name="schedule[schedule_start][]" value="<?php echo $value['schedule_start'] ?>" class="form-control schedule_start" placeholder=""  autocomplete="off" >
							</div>
						</div>
						<div class="va-flex-row">
							<label class="control-label ">
								<span>Đến</span>
							</label>
							<input type="text" name="schedule[schedule_to][]" value="<?php echo $value['schedule_to'] ?>" class="form-control schedule_to" placeholder=""  autocomplete="off" >
						</div>
						<div class="va-flex-row">
							<label class="control-label ">
								<span>Giá mới</span>
							</label>
							<input type="text" name="schedule[schedule_price][]" value="<?php echo $value['price_schedule'] ?>" class="form-control schedule_price int" placeholder=""  autocomplete="off" id="schedule_<?php echo $count ?>">
						</div>
						<div class="va-flex-row">
							<a type="button" class="btn btn-default schedule_del" ><i class="fa fa-trash"></i></a>
						</div>
					</div>
				</div>
		<?php $count++; }} ?>
	</div>
</div>