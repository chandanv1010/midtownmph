<form action="" method="post">	
	<div class="wrapper wrapper-content animated fadeInRight">
		<div class="row">
			<div class="col-lg-2"></div>
	        <div class="col-lg-8">
				<div class=" animated fadeInRight">
					<div class="mail-box-header">
						<div class="pull-right tooltip-demo">
						</div>
						<h2>Thông tin chi tiết yêu cầu của khách</h2>
						<hr>
						<div class="mail-tools tooltip-demo">
							<h3 class="contact-viewdetail-subject">
							<span class="font-normal">Tiêu đề: </span><?php echo $contact['title'] ?>															</h3>
							<h5 class="contact-box-info tv">
								<span class="pull-right font-normal"><?php echo $contact['created_at'] ?></span>
								<p><span class="font-normal">Họ tên: </span><?php echo $contact['fullname'] ?></p>
								<p><span class="font-normal">Email: </span><?php echo $contact['email'] ?></p>
								<p><span class="font-normal">Điện thoại: </span><?php echo $contact['phone'] ?></p>
								
								<p><span class="font-normal">Địa chỉ: </span><?php echo $contact['address'] ?></p>
							</h5>
						</div>
						<div class="mail-box-content">
							<h2>Nội dung thư:</h2>
							<p><?php echo $contact['content'] ?></p>
						</div>
						<div class="mail-body tooltip-demo">
							<a type="button" class="btn btn-primary " href="<?php echo base_url('backend/contact/contact/index/') ?>">Quay lại</a>
						</div>
					</div>

				</div>
			</div>
			<div class="col-lg-2"></div>
		</div>
	</div>
</form>
