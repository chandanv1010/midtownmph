<section class="detail-user-panel">
	<div class="uk-container uk-container-center">
		<div class="uk-grid uk-grid-medium uk-clearfix">
			<div class="uk-width-large-1-5 uk-visible-large">
				<div class="wrap-detail-user">
					<div class="detial-user-body">
						<div class="detail-name">
							<div class="uk-flex uk-flex-middle">
								<div class="image-user mr20">
									<img src="<?php echo (isset($user['image']) && $user['image'] != '') ? $user['image'] : '//theme.hstatic.net/1000376739/1000672536/14/user.svg?v=1615' ?>" <?php echo (isset($user['image']) && $user['image'] != '') ? '' : 'style="filter: invert(0.8);"' ?> alt="<?php echo (isset($user['fullname']) && $user['fullname'] != '') ? $user['fullname'] : 'Tài khoản khách' ?>">
								</div>
								<div class="name-user-file">
									<?php echo check_isset($user['fullname']) ?>
								</div>
							</div>
						</div>
						<ul class="detail-select-action uk-list" data-uk-switcher="{connect:'#user-detail'}">
						    <li><a href=""><i class="fa fa-user" aria-hidden="true"></i> Hồ sơ</a></li>
						    <li><a href=""><i class="fa fa-university" aria-hidden="true"></i> Ngân hàng</a></li>
						    <li><a href=""><i class="fa fa-lock" aria-hidden="true"></i> Đổi mật khẩu</a></li>
						</ul>
					</div>
				</div>
			</div>
			<script>
				var cityid = '<?php echo (isset($_POST['cityid'])) ? $_POST['cityid'] : ((isset($user['cityid'])) ? $user['cityid'] : ''); ?>';
				var districtid = '<?php echo (isset($_POST['districtid'])) ? $_POST['districtid'] : ((isset($user['districtid'])) ? $user['districtid'] : ''); ?>'
				var wardid = '<?php echo (isset($_POST['wardid'])) ? $_POST['wardid'] : ((isset($user['wardid'])) ? $user['wardid'] : ''); ?>'
			</script>
			<div class="uk-width-large-4-5 uk-width-small-1-1">
				<div class="wrap-detail-user">
					<div class="detial-user-body">
						<div class="wrap-tab-detail">
							<ul id="user-detail" class="uk-switcher">
							    <li>
							    	<div class="my-file-panel">
							    		<div class="file-title">
							    			<h2>Hồ Sơ Của Tôi</h2>
							    			<p>Quản lý thông tin hồ sơ để bảo mật tài khoản</p>
							    		</div>
							    		<div class="detail-file">
							    			<form id="form-user" action="" method="post">
							    				<div class="wrap-form-user">
							    					<div class="uk-grid uk-grid-large uk-clearfix">
							    						<div class="uk-width-small-1-1 uk-width-large-3-4">
							    							<div class="info-user-file">
									    						<div class="form-input">
									    							<div class="input-user-general">
									    								<div class="uk-flex uk-flex-middle">
											    							<label>Họ và tên</label>
											    							<div class="input-user">
											    								<?php echo form_input('fullname', validate_input(set_value('fullname', (isset($user['fullname'])) ? $user['fullname'] : '')), 'class="form-control" placeholder="" id="fullname" autocomplete="off"'); ?>
											    							</div>
											    						</div>
									    							</div>
									    							<div class="input-user-general">
									    								<div class="uk-flex uk-flex-middle">
											    							<label>Địa chỉ</label>
											    							<div class="input-user">
											    								<?php echo form_input('address', validate_input(set_value('address', (isset($user['address'])) ? $user['address'] : '')), 'class="form-control" placeholder="" id="address" autocomplete="off"'); ?>
											    							</div>
											    						</div>
									    							</div>
									    							<div class="input-user-general">
									    								<div class="uk-flex uk-flex-middle">
											    							<label>Tỉnh/Thành Phố</label>
											    							<div class="input-user">
											    								<?php 
																					$city = get_data(['select' => 'provinceid, name','table' => 'vn_province','order_by' => 'order desc, name asc']);
																					$city = convert_array([
																						'data' => $city,
																						'field' => 'provinceid',
																						'value' => 'name',
																						'text' => 'Thành Phố',
																					]);
																				?>
																				<?php echo form_dropdown('cityid', $city, set_value('cityid', (isset($user['cityid'])) ? $user['cityid'] : 0), 'class="form-control select2 m-b city"  id="city"');?>
											    							</div>
											    						</div>
									    							</div>
									    							<div class="input-user-general">
									    								<div class="uk-flex uk-flex-middle">
											    							<label>Quận/Huyện</label>
											    							<div class="input-user">
											    								<select name="districtid" id="district" class="form-control m-b select2 location">
																					<option value="0">[Chọn Quận/Huyện]</option>
																				</select>
											    							</div>
											    						</div>
									    							</div>
									    							<div class="input-user-general">
									    								<div class="uk-flex uk-flex-middle">
											    							<label>Phường/Xã</label>
											    							<div class="input-user">
											    								<select name="wardid" id="ward" class="form-control select2 m-b location">
																					<option value="0">[Chọn Phường/Xã]</option>
																				</select>
											    							</div>
											    						</div>
									    							</div>
									    							<div class="input-user-general">
									    								<div class="uk-flex uk-flex-middle">
											    							<label>Số điện thoại</label>
											    							<div class="input-user">
											    								<?php echo form_input('phone', validate_input(set_value('phone', (isset($user['phone'])) ? $user['phone'] : '')), 'class="form-control" placeholder="" id="phone" autocomplete="off"'); ?>
											    							</div>
											    						</div>
									    							</div>
									    							<div class="input-user-general">
											    						<div class="uk-flex uk-flex-middle">
											    							<label>Email</label>
											    							<div class="chage-email-ajax">
											    								<?php echo (isset($user['email']) ? $user['email'] : '') ?>
											    								<?php echo form_hidden('email', validate_input(set_value('email', (isset($user['email'])) ? $user['email'] : '')), 'class="form-control" placeholder="" autocomplete="off"'); ?>
											    								<!-- <a href="" title="change email" class="ml10 btn-change-email">Thay đổi</a> -->
											    							</div>
											    						</div>
											    					</div>
											    					<div class="input-user-general">
											    						<div class="uk-flex uk-flex-middle">
											    							<label>Giới tính</label>
											    							<div class="radio-gender-panel w80">
											    								<div class="uk-flex uk-flex-middle">
											    									<div class="radio-gender mr30">
													    								<?php 
													    									$male = [
												    											'name' => 'gender',
												    											'value' => '1',
												    											'id' => 'male',
													    									];
													    									(isset($user['gender']) && $user['gender'] == 1) ? $male['checked'] = TRUE : $male['checked'] = FALSE;
													    									echo form_radio($male);
													    									echo '<span class="design mr10"></span>';
													    									echo form_label('Nam', 'male',['class'=>'radio-text']);
													    								?>
											    									</div>
											    									<div class="radio-gender">
													    								<?php 
													    									$female = [
												    											'name' => 'gender',
												    											'value' => '0',
												    											'id' => 'female',
													    									];
													    									(isset($user['gender']) && $user['gender'] == 0) ? $female['checked'] = TRUE : $female['checked'] = FALSE;
													    									echo form_radio($female);
													    									echo '<span class="design mr10"></span>';
													    									echo form_label('Nữ', 'female',['class'=>'radio-text']);
													    								?>
											    									</div>
											    								</div>
											    							</div>
											    						</div>
											    					</div>
											    					<div class="input-user-general">
											    						<div class="uk-flex uk-flex-middle">
											    							<label>Ngày sinh</label>
											    							<div class="input-user">
											    								<?php echo form_input('birthday', set_value('birthday', (isset($user['birthday'])) ? $user['birthday'] : ''), 'class="form-control datetimepicker" placeholder="" autocomplete="off"');?>
											    							</div>
											    						</div>
											    					</div>
											    					<button type="submit" name="save" value="save" class="btn btn-primary block m-b pull-right btn-submit-form-user">Lưu thay đổi</button>
									    						</div>
									    					</div>
							    						</div>
							    						<div class="uk-width-small-1-1 uk-width-large-1-4">
							    							<div class="wrap-avatar-user">
							    								<div class="select-img-avatar-user">
								    								<a href="" title="Chọn avatar" class="img-cover">
								    									<img src="<?php echo (isset($user['image']) && $user['image'] != '') ? $user['image'] : 'public/avatar_default.png' ?>"  alt="Chọn avatar">
								    								</a>
							    								</div>
							    								<input type="hidden" name="image" value="<?php echo (isset($user['image']) && $user['image'] != '') ? $user['image'] : '' ?>" class="input-avatar-user">
							    								<div class="select-btn-avatar-user">
								    								<a href="" title="Chọn avatar" >
								    									Chọn hình
								    								</a>
							    								</div>
							    							</div>
							    						</div>
							    					</div>
							    				</div>
							    			</form>
							    		</div>
							    	</div>
							    </li>
							    <li> 
							    	<div class="my-bank-panel">
							    		<div class="bank-title">
							    			<div class="uk-flex uk-flex-middle uk-flex-space-between">
							    				<h2>Thẻ Tín Dụng/Ghi Nợ Của Tôi</h2>
							    				<button class="btn-add-credit">
							    					+ Thêm thẻ Tín dụng mới
							    				</button>
							    			</div>
							    		</div>
							    		<div class="detail-bank">
						    				<div class="wrap-form-bank">
						    					<div class="bank-empty">Bạn chưa có thẻ Tín dụng/Ghi nợ.</div>
						    				</div>
							    		</div>
							    	</div>
							    	<div class="my-bank-panel">
							    		<div class="bank-title">
							    			<div class="uk-flex uk-flex-middle uk-flex-space-between">
							    				<h2>Tài Khoản Ngân Hàng Của Tôi</h2>
							    				<button class="btn-add-bank">
							    					+ Thêm tài khoản ngân hàng
							    				</button>
							    			</div>
							    		</div>
							    		<div class="detail-bank">
						    				<div class="wrap-form-bank">
						    					<div class="bank-empty">Bạn chưa có Tài khoản Ngân hàng.</div>
						    				</div>
							    		</div>
							    	</div>
							    </li>
							    <li>
							    	<div class="my-file-panel">
							    		<div class="file-title">
							    			<h2>Đổi Mật Khẩu</h2>
							    			<p>Để bảo mật tài khoản, vui lòng không chia sẻ mật khẩu cho người khác</p>
							    		</div>
							    		<div class="detail-file">
							    			<form id="form-password" action="" method="post">
							    				<div class="wrap-form-password">
							    					<div class="form-input">
						    							<div class="input-user-general">
						    								<div class="uk-flex uk-flex-middle">
								    							<label>Mật khẩu hiện tại</label>
								    							<div class="input-user mr20">
								    								<?php echo form_password('password', '', 'class="form-control " placeholder="" autocomplete="off"'); ?>
								    								<?php echo form_hidden('email',set_value('email', (isset($user['email'])) ? $user['email'] : ''), 'class="form-control " placeholder="" autocomplete="off"'); ?>
								    							</div>
								    							<div class="forgot">
					                                                <a href="forgot.html" title="Quên mật khẩu">Quên mật khẩu?</a>
					                                            </div>
								    						</div>
						    							</div>
						    							<div class="input-user-general">
						    								<div class="uk-flex uk-flex-middle">
								    							<label>Mật khẩu mới</label>
								    							<div class="input-user mr20">
								    								<?php echo form_password('new_password', '', 'class="form-control " placeholder="" id="password-field" autocomplete="off"'); ?>
								    							</div>
								    						</div>
						    							</div>
						    							<div class="input-user-general">
						    								<div class="uk-flex uk-flex-middle">
								    							<label>Xác nhận mật khẩu</label>
								    							<div class="input-user mr20">
								    								<?php echo form_password('confirm_password', '', 'class="form-control " placeholder="" id="confirm-password" autocomplete="off"'); ?>
								    							</div>
								    						</div>
						    							</div>
						    							<div class="input-user-general">
						    								<div class="uk-flex uk-flex-middle">
								    							<label></label>
								    							<div class="input-user mr20">
								    								<button type="submit" name="save" value="save" class="btn btn-submit-change-password btn-primary block m-b pull-right">Xác nhận</button>
								    							</div>
								    						</div>
						    							</div>
						    						</div>
							    				</div>
							    			</form>
							    		</div>
							    	</div>
							    </li>
							</ul>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
</section>

