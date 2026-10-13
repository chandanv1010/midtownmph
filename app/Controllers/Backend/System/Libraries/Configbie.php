<?php

namespace App\Controllers\Backend\System\Libraries;
use App\Controllers\BaseController;

class ConfigBie{

	function __construct($params = NULL){
		$this->params = $params;
	}

	// meta_title là 1 row -> seo_meta_title
	// contact_address
	// chưa có thì insert
	// có thì update
	public function system(){

		$data['homepage'] =  array(
			'label' => 'Thông tin chung',
			'description' => 'Cài đặt đầy đủ thông tin chung của website. Tên thương hiệu website. Logo của website và icon website trên tab trình duyệt',
			'value' => array(
				'company' => array('type' => 'text', 'label' => 'Tên công ty'),
				'brand' => array('type' => 'text', 'label' => 'Tên thương hiệu'),
				'slogan_0' => array('type' => 'text', 'label' => 'Slogan'),
				'logo' => array('type' => 'images', 'label' => 'Logo'),
				'logo_footer' => array('type' => 'images', 'label' => 'Logo Chân trang'),
				'banner_sp' => array('type' => 'images', 'label' => 'Background Chi tiết Khách sạn'),
				'favicon' => array('type' => 'images', 'label' => 'Favicon','title' => 'Favicon là gì?','link' => 'https://webchuanseoht.com/favicon-la-gi-tac-dung-cua-favicon-nhu-the-nao.html'),
				'banner_slide' => array('type' => 'images', 'label' => 'Background Video trang chủ','attention' => 'Nếu có video thì sẽ không có slide'),
				'banner_overview' => array('type' => 'images', 'label' => 'Background Video tổng quan','attention' => 'Nếu có video thì sẽ không có slide'),
				'slogan' => array('type' => 'editor', 'label' => 'Giới thiệu đầu trang chủ'),
				// 'slogan_2' => array('type' => 'editor', 'label' => 'Text cuối trang chủ'),
				'intro' => array('type' => 'editor', 'label' => 'Intro bên trên Footer'),
				'footer' => array('type' => 'editor', 'label' => 'Thông tin chung Footer'),
			),
		);
		$data['contact'] =  array(
			'label' => 'Thông tin liên lạc',
			'description' => 'Cấu hình đầy đủ thông tin liên hệ giúp khách hàng dễ dàng tiếp cận với dịch vụ của bạn',
			'value' => array(
				'address' => array('type' => 'editor', 'label' => 'Địa chỉ'),
				// 'factory' => array('type' => 'text', 'label' => 'Kho'),
				'phone' => array('type' => 'text', 'label' => 'Điện thoại'),
				'hotline' => array('type' => 'text', 'label' => 'Hotline'),

				'email' => array('type' => 'text', 'label' => 'Email'),
				'website' => array('type' => 'text', 'label' => 'Website'),
				'map' => array('type' => 'textarea', 'label' => 'Bản đồ','title' => 'Hướng dẫn thiết lập bản đồ','link' => 'https://webchuanseoht.com/huong-dan-thiet-lap-ban-do-google-map.html'),
				'bct' => array('type' => 'text', 'label' => 'Link Bộ công thương'),
				// 'us' => array('type' => 'editor', 'label' => 'Thông tin liên hệ'),
				// 'bank' => array('type' => 'editor', 'label' => 'Thông tin ngân hàng'),
			),
		);

		$data['contact_page'] =  array(
			'label' => 'Thông tin Trang Liên hệ',
			'description' => 'Cấu hình đầy đủ thông tin trang liên hệ giúp khách hàng dễ dàng tiếp cận với dịch vụ của bạn',
			'value' => array(
				'image' => array('type' => 'images', 'label' => 'Banner đầu trang'),
				'map' => array('type' => 'textarea', 'label' => 'Bản đồ'),
				'info' => array('type' => 'editor', 'label' => 'Thông tin liên hệ'),
			),
		);

		$data['search_page'] =  array(
			'label' => 'Thông tin Trang Tìm kiếm',
			'description' => 'Cấu hình đầy đủ thông tin trang tìm kiếm giúp khách hàng dễ dàng tiếp cận với dịch vụ của bạn',
			'value' => array(
				'image' => array('type' => 'images', 'label' => 'Banner đầu trang'),
			),
		);

		$data['param'] =  array(
			'label' => 'Cấu hình thông tin Dự án',
			'description' => '',
			'value' => array(
				'error' => array('type' => 'textarea', 'label' => 'Lỗi liên hệ'),
				'success' => array('type' => 'textarea', 'label' => 'Liên hệ thành công'),
			),
		);

		$data['seo'] =  array(
			'label' => 'Cấu hình thẻ tiêu đề',
			'description' => 'Cài đặt đầy đủ Thẻ tiêu đề và thẻ mô tả giúp xác định cửa hàng của bạn xuất hiện trên công cụ tìm kiếm.',
			'value' => array(
				'meta_title' => array('type' => 'text', 'label' => 'Tiêu đề trang','extend' => ' trên 70 kí tự', 'class' => 'meta-title', 'id' => 'titleCount'),
				'meta_description' => array('type' => 'textarea', 'label' => 'Mô tả trang','extend' => ' trên 320 kí tự', 'class' => 'meta-description', 'id' => 'descriptionCount'),
			),
		);
		$data['analytic'] =  array(
			'label' => 'Google Analytics',
			'description' => 'Dán đoạn mã hoặc mã tài khoản GA được cung cấp bởi Google.',
			'value' => array(
				'google_analytic' => array('type' => 'textarea', 'label' => 'Mã Google Analytics','title' => 'Hướng dẫn thiết lập Google Analytic','link' => 'https://webchuanseoht.com/huong-dan-thiet-lap-google-analytics.html'),
			),
		);
		$data['facebook'] =  array(
			'label' => 'Facebook Pixel',
			'description' => 'Facebook Pixel giúp bạn tạo chiến dịch quảng cáo trên facebook để tìm kiếm khách hàng mới mua hàng trên website của bạn.',
			'value' => array(
				'facebook_pixel' => array('type' => 'text', 'label' => 'Facebook Pixel','title' => 'Hướng dẫn thiết lập Facebook Pixel','link' => 'https://webchuanseoht.com/huong-dan-su-dung-pixel-quang-cao-facebook-moi-cap-nhat.html'),
			),
		);
		$data['script'] =  array(
			'label' => 'Mã Nhúng Mở rộng',
			'description' => 'Mã nhúng mở rộng giúp bạn dễ dàng tích hợp các tính năng của nhà cung cấp thứ 3 phát triển vào website.',
			'value' => array(
				'facebook_pixel' => array('type' => 'textarea', 'label' => 'Script'),
				'chat_live' => array('type' => 'textarea', 'label' => 'Script'),
			),
		);
		$data['social'] =  array(
			'label' => 'Mạng xã hội',
			'description' => 'Cập nhật đầy đủ thông tin mạng xã hội giúp khách hàng dễ dàng tiếp cận với dịch vụ của bạn',
			'value' => array(
				'facebook' => array('type' => 'text', 'label' => 'Fanpage Facebook'),
				'youtube' => array('type' => 'text', 'label' => 'Youtube'),
				'twitter' => array('type' => 'text', 'label' => 'Twitter'),
				'instagram' => array('type' => 'text', 'label' => 'Instagram'),
				'google' => array('type' => 'text', 'label' => 'Google Plus'),
				'linkedin' => array('type' => 'text', 'label' => 'Linkedin'),
				'pinterest' => array('type' => 'text', 'label' => 'Pinterest'),
				'email' => array('type' => 'text', 'label' => 'Email'),
				'zalo' => array('type' => 'text', 'label' => 'Zalo'),
				'whatsapp' => array('type' => 'text', 'label' => 'whatsapp'),
				'viber' => array('type' => 'text', 'label' => 'viiber'),
				'kakaotalk' => array('type' => 'text', 'label' => 'Kakao Talk'),
				'line' => array('type' => 'text', 'label' => 'Line'),
				'wechat' => array('type' => 'text', 'label' => 'Wechat'),
			),
		);

		$data['another'] =  array(
			'label' => 'Các mục khác',
			'description' => 'Cập nhật đầy đủ thông tin giúp khách hàng dễ dàng tiếp cận với dịch vụ của bạn',
			'value' => array(
				'price' => array('type' => 'textarea', 'label' => 'Cấu hình khoảng giá'  ),
				// 'product' => array('type' => 'textarea', 'label' => 'Cấu hình chung cho sản phẩm'  ),
			),
		);
		$data['cart'] =  array(
			'label' => 'Cấu hình giỏ hàng (Nếu có)',
			'description' => 'Cập nhật đầy đủ thông tin giúp khách hàng dễ dàng tiếp cận với dịch vụ của bạn',
			'value' => array(
				'description' => array('type' => 'editor', 'label' => 'Cấu hình thông báo đặt hàng thành công'),
			),
		);
		$data['website'] =  array(
			'label' => 'Cấu hình website',
			'description' => 'Cài đặt đầy đủ Cấu hình của website. Trạng thái website, index google, ...',
			'value' => array(
				'status' => array('type' => 'select2', 'label' => 'Trạng thái website','select' => array(0 => 'Mở cửa Website', 1 => 'Đóng cửa website')),
				'index' => array('type' => 'select2', 'label' => 'Index Google','select' => array(1 => 'Có', 0 => 'Không')),
				'canonical' => array('type' => 'select2', 'label' => 'Đường dẫn','select' => array('normal' => 'Normal', 'silo' => 'Silo')),
				'language' => array('type' => 'select', 'label' => 'Ngôn ngữ mặc định','select' => array('vi' => 'Tiếng Việt', 'en' => 'Tiếng Anh')),
			),
		);

		$data['search'] =  array(
			'label' => 'Nội dung cố định khung tìm kiếm',
			'description' => 'Nội dung hiển thị cố định bên ngoài website',
			'value' => array(
				'title' => array('type' => 'text', 'label' => 'Tiêu đề'),
				'text_1' => array('type' => 'text', 'label' => 'Text khung search 1'),
				'text_2' => array('type' => 'text', 'label' => 'Text khung search 2'),
				'text_4' => array('type' => 'text', 'label' => 'Text khung search 4'),
				'text_5' => array('type' => 'text', 'label' => 'Text khung search 5'),
				'text_6' => array('type' => 'text', 'label' => 'Text khung search 6'),
				'text_8' => array('type' => 'text', 'label' => 'Text khung search 8'),
				'text_9' => array('type' => 'text', 'label' => 'Text khung search 9'),
				'select_type_1' => array('type' => 'text', 'label' => 'Giá trị chọn hình thức 1'),
				'select_type_2' => array('type' => 'text', 'label' => 'Giá trị chọn hình thức 2'),
				'select_1' => array('type' => 'text', 'label' => 'Giá trị mặc định select 1'),
				'kc' => array('type' => 'text', 'label' => 'Text khoảng cách'),
				'button' => array('type' => 'text', 'label' => 'Nút tìm kiếm'),
				'result' => array('type' => 'text', 'label' => 'Kết quả'),
				'no_result' => array('type' => 'text', 'label' => 'Không có kết quả'),
			),
		);
		$data['home'] =  array(
			'label' => 'Nội dung cố định trang chủ',
			'description' => 'Nội dung hiển thị cố định bên ngoài website',
			'value' => array(
				'view' => array('type' => 'text', 'label' => 'Xem tất cả'),

			),
		);

		$data['footer'] =  array(
			'label' => 'Nội dung cố định chân trang',
			'description' => 'Nội dung hiển thị cố định bên ngoài website',
			'value' => array(
				'contact' => array('type' => 'text', 'label' => 'Tiêu đề liên hệ 1'),
				'contact_2' => array('type' => 'text', 'label' => 'Tiêu đề liên hệ 2'),
				'link' => array('type' => 'text', 'label' => 'Tiêu đề link'),
				'name' => array('type' => 'text', 'label' => 'Tiêu đề họ tên'),
				'phone' => array('type' => 'text', 'label' => 'Tiêu đề điện thoại'),
				'content' => array('type' => 'text', 'label' => 'Tiêu đề nội dung'),
				'file' => array('type' => 'text', 'label' => 'Tiêu đề file'),
				'btn' => array('type' => 'text', 'label' => 'Tiêu đề nút'),
				'rate' => array('type' => 'text', 'label' => 'Đánh giá'),
				'title' => array('type' => 'text', 'label' => 'Tiêu đề dự án chân trang'),
			),
		);

		$data['product'] =  array(
			'label' => 'Nội dung cố định căn hộ',
			'description' => 'Nội dung hiển thị cố định bên ngoài website',
			'value' => array(
				'contact' => array('type' => 'text', 'label' => 'Tiêu đề liên hệ 1'),
				'home' => array('type' => 'text', 'label' => 'Tiêu đề tình trạng nội thất 1'),
				'now' => array('type' => 'text', 'label' => 'Tiêu đề tình trạng nội thất 2'),

			),
		);
		$data['overview'] =  array(
			'label' => 'Nội dung cố định tổng quan',
			'description' => 'Nội dung hiển thị cố định bên ngoài website',
			'value' => array(
				'location' => array('type' => 'text', 'label' => 'Tiêu đề vị trí'),
				'home' => array('type' => 'text', 'label' => 'Mặt bằng lọc 1'),
				'now' => array('type' => 'text', 'label' => 'Mặt bằng lọc 2'),

			),
		);

		return $data;
	}

	public function canonical_system(){
		$data['general'] =  array(
			'label' => 'Đường dẫn hệ thống',
			'description' => 'Đường dẫn mặc định của tất cả trang trên website',
			'value' => array(
				'css' => array('type' => 'textarea', 'label' => 'CSS mặc định'),
				'script_top' => array('type' => 'textarea', 'label' => 'JS mặc định - phần Header'),
				'script_bottom' => array('type' => 'textarea', 'label' => 'JS mặc định - phần cuối Body'),
			),
		);
		$data['normal'] =  array(
			'label' => 'File mặc định nếu không tồn tại module',
			'description' => 'Cấu hình đầy đủ thông tin đường dẫn giúp lập trình viên dễ dàng kiểm soát file css và file script một cách hiệu quả. Để cho trang web có thể chạy mượt mà nhất có thể :D',
			'value' => array(
				'css' => array('type' => 'textarea', 'label' => 'CSS'),
				'script' => array('type' => 'textarea', 'label' => 'Javascript'),
			),
		);
		$data['home'] =  array(
			'label' => 'Đường dẫn cho Trang chủ',
			'description' => 'Cấu hình đầy đủ thông tin đường dẫn giúp lập trình viên dễ dàng kiểm soát file css và file script một cách hiệu quả. Để cho trang web có thể chạy mượt mà nhất có thể :D',
			'value' => array(
				'css' => array('type' => 'textarea', 'label' => 'CSS'),
				'script' => array('type' => 'textarea', 'label' => 'Javascript'),
			),
		);
		$data['product'] =  array(
			'label' => 'Đường dẫn cho Sản phẩm',
			'description' => 'Cấu hình đầy đủ thông tin đường dẫn giúp lập trình viên dễ dàng kiểm soát file css và file script một cách hiệu quả. Để cho trang web có thể chạy mượt mà nhất có thể :D',
			'value' => array(
				'css' => array('type' => 'textarea', 'label' => 'CSS'),
				'script' => array('type' => 'textarea', 'label' => 'Javascript'),
			),
		);
		$data['product_catalogue'] =  array(
			'label' => 'Đường dẫn cho danh mục Sản phẩm',
			'description' => 'Cấu hình đầy đủ thông tin đường dẫn giúp lập trình viên dễ dàng kiểm soát file css và file script một cách hiệu quả. Để cho trang web có thể chạy mượt mà nhất có thể :D',
			'value' => array(
				'css' => array('type' => 'textarea', 'label' => 'CSS'),
				'script' => array('type' => 'textarea', 'label' => 'Javascript'),
			),
		);
		$data['article'] =  array(
			'label' => 'Đường dẫn cho Bài viết',
			'description' => 'Cấu hình đầy đủ thông tin đường dẫn giúp lập trình viên dễ dàng kiểm soát file css và file script một cách hiệu quả. Để cho trang web có thể chạy mượt mà nhất có thể :D',
			'value' => array(
				'css' => array('type' => 'textarea', 'label' => 'CSS'),
				'script' => array('type' => 'textarea', 'label' => 'Javascript'),
			),
		);
		$data['article_catalogue'] =  array(
			'label' => 'Đường dẫn cho danh mục Bài viết',
			'description' => 'Cấu hình đầy đủ thông tin đường dẫn giúp lập trình viên dễ dàng kiểm soát file css và file script một cách hiệu quả. Để cho trang web có thể chạy mượt mà nhất có thể :D',
			'value' => array(
				'css' => array('type' => 'textarea', 'label' => 'CSS'),
				'script' => array('type' => 'textarea', 'label' => 'Javascript'),
			),
		);

		$data['member'] =  array(
			'label' => 'Đường dẫn cho Thông tin chi tiết Thành viên',
			'description' => 'Cấu hình đầy đủ thông tin đường dẫn giúp lập trình viên dễ dàng kiểm soát file css và file script một cách hiệu quả. Để cho trang web có thể chạy mượt mà nhất có thể :D',
			'value' => array(
				'css' => array('type' => 'textarea', 'label' => 'CSS'),
				'script' => array('type' => 'textarea', 'label' => 'Javascript'),
			),
		);


		return $data;
	}
}
