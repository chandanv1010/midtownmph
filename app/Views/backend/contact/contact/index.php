<?php
    $baseController = new App\Controllers\BaseController();
    $language = $baseController->currentLanguage();
?>

<style>
	.va-noseen {
		 background-color: #ffc  !important;
	}

	.va-seen{
		background-color: #fff !important;
	}

</style>
<div class="row wrapper border-bottom white-bg page-heading">
    <div class="col-lg-10">
        <h2><?php echo translate('cms_lang.contact.contact_title', $language) ?></h2>
        <ol class="breadcrumb">
            <li>
                <a href="<?php echo site_url('admin'); ?>"><?php echo translate('cms_lang.contact.contact_home', $language) ?></a>
            </li>
            <li class="active"><strong><?php echo translate('cms_lang.contact.contact_title', $language) ?></strong></li>
        </ol>
    </div>
</div>

<div class="wrapper wrapper-content animated fadeInRight">
    <div class="row">
        <div class="col-lg-12">
            <div class="ibox float-e-margins">
                <div class="ibox-title">
                	<div class="uk-flex uk-flex-middle uk-flex-space-between">
                    	<h5>Quản lý thành viên </h5>
                		<a type="button" href="" class="btn view-contact-by-va btn-primary">Đã xem</a>
                	</div>
                </div>
                <div class="ibox-content">
                    <table class="table table-striped table-bordered table-hover dataTables-example">
                        <thead>
                        <tr>
                        	<th style="width: 30px" class="text-center">
                                <input type="checkbox" id="checkbox-all">
                                <label for="check-all" class="labelCheckAll"></label>
                            </th>
                            <th style="width:250px"><?php echo translate('cms_lang.contact.contact_info', $language) ?></th>
					        <th class="text-center"><?php echo translate('cms_lang.contact.contact_table_title', $language) ?></th>
					        <th class=" big"> <?php echo translate('cms_lang.contact.contact_content', $language) ?></th>
					        <th class="text-center"><?php echo translate('cms_lang.contact.contact_created_at', $language) ?></th>
					        <th class="text-center"><?php echo translate('cms_lang.contact.contact_action', $language) ?></th>
                        </tr>
                        </thead>
                        <tbody>
                            <?php if(isset($contactList) && is_array($contactList) && count($contactList)){ ?>
						        <?php foreach($contactList as $key => $val){ ?>

						        
						        <?php  
						            $status = ($val['order'] == 1) ? '<i class="fa fa-star  text-red" data-id="6" data-bookmark="1"></i>'  : '<i class="fa fa-star" data-id="6" data-bookmark="1"></i>';
						        ?>

						        <tr id="post-<?php echo $val['id']; ?>" class="<?php echo ($val['seen'] == 0) ? 'va-noseen' : 'va-seen' ?>" data-id="<?php echo $val['id']; ?>">
						        	<td class="text-center">
	                                    <input type="checkbox" name="checkbox[]" value="<?php echo $val['id']; ?>" class="checkbox-item">
	                                    <div for="" class="label-checkboxitem"></div>
	                                </td>
						            <td class="information">
						                <div class="customer-name uk-flex uk-flex-middle uk-flex-space-between">
						                    <div class="user-name"><?php echo $val['fullname']; ?></div>
						                   
						                </div>
						                <div class="customer-detail">
						                    Phone: <?php echo $val['phone']; ?>
						                    <br>
						                    Email: <?php echo $val['email']; ?>
						                    <br>
						                    Địa chỉ: <?php echo $val['address']; ?>
						                </div>
						            </td>
						            <td class="text-center"><?php echo $val['title']; ?></td>
						            <td class="tv text">
						                <div class="tv content line-3"><a href="<?php echo base_url('backend/contact/contact/reply/'.$val['id']) ?>" class="open-window"><?php echo $val['content']; ?></a></div></td>
						            <td class="text-center"><?php echo $val['created_at']; ?></td>
						            <td class="text-center">
	                                    <a type="button" href="<?php echo base_url('backend/contact/contact/reply/'.$val['id']) ?>" class="btn open-window btn-success"><i class="fa fa-edit"></i></a>
						                <button class="btn btn-danger btn-sm delete1" id="<?php echo $val['id'] ?>" name="delete" value="delete" type="submit"><i class="fa fa-trash"></i></button>
	                                </td>
						            
						        </tr>
						        <?php }}else{ ?>
						            <tr>
						                <td colspan="100%"><span class="text-danger"><?php echo translate('cms_lang.contact.empty', $language) ?></span></td>
						            </tr>
						        <?php } ?>
                        </tbody>
                    </table>
                    <div id="pagination">
                        <?php echo (isset($pagination)) ? $pagination : ''; ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
	$(document).on('click','.view-contact-by-va', function(){
		let id = [];
		let _this = $(this);
		$('.checkbox-item:checked').each(function(){
			let _this = $(this);
		 	id.push(_this.val());
		});

		if(id.length > 0){
			swal({
				title: "Hãy chắc chắn rằng bạn muốn thực hiện thao tác này?",
				text: 'Đánh dấu người dùng là đã xem?',
				type: "warning",
				showCancelButton: true,
				confirmButtonColor: "#DD6B55",
				confirmButtonText: "Thực hiện!",
				cancelButtonText: "Hủy bỏ!",
				closeOnConfirm: false,
				closeOnCancel: false },
			function (isConfirm) {
				if (isConfirm) {
					var formURL = 'ajax/contact/seen_contact';
					$.post(formURL, {
						id: id},
						function(data){
							if(data == 0){
									sweet_error_alert('Có vấn đề xảy ra','Vui lòng thử lại')
								}else{
									for(let i = 0; i < id.length; i++){
										$('#post-'+id[i]).hide().remove()				
									}
									swal("Thay đổi trạng thái thành công!", "Các bản ghi đã được thay đổi trạng thái.", "success");
									window.location.reload()
								}
						});
				} else {
					swal("Hủy bỏ", "Thao tác bị hủy bỏ", "error");
				}
			});
		}
		else{
			sweet_error_alert('Thông báo từ hệ thống!', 'Bạn phải chọn 1 bản ghi để thực hiện chức năng này');
			return false;
		}
		return false;
	});
</script>