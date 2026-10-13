var count = 0;
var count_album = 0;
$(document).ready(function(){
	$(document).on('click','.add-album',function(){
		console.log(1)
		let _this = $(this);
		render_album();
	})
	$(document).on('click','.delete', function(){

		let _this = $(this);
		

		if(id.length > 0){
			swal({
				title: "Hãy chắc chắn rằng bạn muốn thực hiện thao tác này? Dữ liệu bị xóa sẽ không thể khôi phục!",
				text: 'Xóa nhóm Dự án này',
				type: "warning",
				showCancelButton: true,
				confirmButtonColor: "#DD6B55",
				confirmButtonText: "Thực hiện!",
				cancelButtonText: "Hủy bỏ!",
				closeOnConfirm: false,
				closeOnCancel: false },
			function (isConfirm) {
				if (isConfirm) {
					var formURL = 'ajax/project/deleteCat';
					$.post(formURL, {
						id: id, module: _this.attr('data-module')},
						function(data){
							if(data == 0){
									sweet_error_alert('Có vấn đề xảy ra','Vui lòng thử lại')
								}else{
									swal("Xóa thành công!", "Bản ghi đã được xóa khỏi danh sách.", "success");
									window.location.href = BASE_URL+'backend/project/catalogue/index';
								}
						});
				} else {
					swal("Hủy bỏ", "Thao tác bị hủy bỏ", "error");
				}
			});
		}
		
		return false;
	});

	function render_album(){
		let html ='';
		html = html + '<div class="ibox desc-more album" style="opacity: 1;">';
	        html = html + '<div class="ibox-title ui-sortable-handle">';
	        	html = html + '<div class="uk-flex uk-flex-middle">';
	        	html = html + '<div class="col-lg-2">';
						html = html + 'Album ảnh';
					html = html + '</div>';
	                html = html + '<div class="col-lg-6">';
						html = html + '<input type="text" name="sub_album_title['+count_album+'][]" class="form-control" value="" placeholder="Tiêu đề">';
					html = html + '</div>';
					html = html + '<div class="col-lg-4">';
						html = html + '<div class="uk-flex uk-flex-middle uk-flex-space-between">';
							html = html + '<a onclick="BrowseServerAlbum($(this),&quot;sub_album&quot;,'+count_album+');return false;" href="" title="" class="upload-picture">Chọn hình</a>';
			                html = html + '<div class="ibox-tools">';
			                    html = html + '<a class="collapse-link ui-sortable">';
			                        html = html + '<i class="fa fa-chevron-up"></i>';
			                    html = html + '</a>';
			                    html = html + '<a class="close-link">';
			                        html = html + '<i class="fa fa-times"></i>';
			                    html = html + '</a>';
			                html = html + '</div>';
						html = html + '</div>';
					html = html + '</div>';
	        	html = html + '</div>';
	        html = html + '</div>';
	        html = html + '<div class="ibox-content" style="">';
	        	html= html + '<div class="row">';
					html= html + '<div class="col-lg-12">';
						html= html + '<div class="click-to-upload">';
							html= html + '<div class="icon">';
								html= html + '<a type="button" class="upload-picture" onclick="BrowseServerAlbum($(this),&quot;sub_album&quot;,'+count_album+');return false;">';
									html= html + '<svg style="width:80px;height:80px;fill: #d3dbe2;margin-bottom: 10px;" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 80 80"><path d="M80 57.6l-4-18.7v-23.9c0-1.1-.9-2-2-2h-3.5l-1.1-5.4c-.3-1.1-1.4-1.8-2.4-1.6l-32.6 7h-27.4c-1.1 0-2 .9-2 2v4.3l-3.4.7c-1.1.2-1.8 1.3-1.5 2.4l5 23.4v20.2c0 1.1.9 2 2 2h2.7l.9 4.4c.2.9 1 1.6 2 1.6h.4l27.9-6h33c1.1 0 2-.9 2-2v-5.5l2.4-.5c1.1-.2 1.8-1.3 1.6-2.4zm-75-21.5l-3-14.1 3-.6v14.7zm62.4-28.1l1.1 5h-24.5l23.4-5zm-54.8 64l-.8-4h19.6l-18.8 4zm37.7-6h-43.3v-51h67v51h-23.7zm25.7-7.5v-9.9l2 9.4-2 .5zm-52-21.5c-2.8 0-5-2.2-5-5s2.2-5 5-5 5 2.2 5 5-2.2 5-5 5zm0-8c-1.7 0-3 1.3-3 3s1.3 3 3 3 3-1.3 3-3-1.3-3-3-3zm-13-10v43h59v-43h-59zm57 2v24.1l-12.8-12.8c-3-3-7.9-3-11 0l-13.3 13.2-.1-.1c-1.1-1.1-2.5-1.7-4.1-1.7-1.5 0-3 .6-4.1 1.7l-9.6 9.8v-34.2h55zm-55 39v-2l11.1-11.2c1.4-1.4 3.9-1.4 5.3 0l9.7 9.7c-5.2 1.3-9 2.4-9.4 2.5l-3.7 1h-13zm55 0h-34.2c7.1-2 23.2-5.9 33-5.9l1.2-.1v6zm-1.3-7.9c-7.2 0-17.4 2-25.3 3.9l-9.1-9.1 13.3-13.3c2.2-2.2 5.9-2.2 8.1 0l14.3 14.3v4.1l-1.3.1z"></path></svg>';
								html= html + '</a>';
							html= html + '</div>';
							html= html + '<div class="small-text">Sử dụng nút <b>Chọn hình</b> để thêm hình.</div>';
						html= html + '</div>';
						html= html + '<div class="upload-list"  style="padding:5px;">';
							html= html + '<div class="row">';
								html= html + '<ul id="" class="clearfix sortui data-album">';
								html= html +'</ul>';
							html= html +'</div>';
						html= html +'</div>';
					html= html +'</div>';
				html= html +'</div>';
	        html = html + '</div>';
	    html = html + '</div>';
	    count_album++;
		$('.album-more').prepend(html);
	}
});