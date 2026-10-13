<!DOCTYPE html>
<html lang="vi-VN">
	<head>
		<!-- Google Tag Manager -->
		<script>(function(w,d,s,l,i){w[l]=w[l]||[];w[l].push({'gtm.start':
		new Date().getTime(),event:'gtm.js'});var f=d.getElementsByTagName(s)[0],
		j=d.createElement(s),dl=l!='dataLayer'?'&l='+l:'';j.async=true;j.src=
		'https://www.googletagmanager.com/gtm.js?id='+i+dl;f.parentNode.insertBefore(j,f);
		})(window,document,'script','dataLayer','GTM-NVVML2ZV');</script>
		<!-- End Google Tag Manager -->

		<!-- CONFIG -->
		<base href="<?php echo BASE_URL ?>" />
		<meta http-equiv="content-type" content="text/html; charset=utf-8" />
		<meta name="viewport" content="width=device-width, initial-scale=1.0">
		<meta name="robots" content="index,follow" />
		<meta name="author" content="<?php echo (isset($general['homepage_company'])) ? $general['homepage_company'] : ''; ?>" />
		<meta name="copyright" content="<?php echo (isset($general['homepage_company'])) ? $general['homepage_company'] : ''; ?>" />
		<meta http-equiv="refresh" content="1800" />
		<link rel="icon" href="<?php echo isset($general['homepage_favicon'])? $general['homepage_favicon']: '' ?>" type="image/png" sizes="30x30">
		<!-- GOOGLE -->
		<title><?php echo isset($meta_title)?htmlspecialchars($meta_title):'';?></title>
		<meta name="description"  content="<?php echo isset($meta_description)?htmlspecialchars($meta_description):'';?>" />
		<?php echo isset($canonical)?'<link rel="canonical" href="'.$canonical.'" />':'';?>
		<meta property="og:locale" content="vi_VN" />

		<!-- for Facebook -->
		<meta property="og:title" content="<?php echo (isset($meta_title) && !empty($meta_title))?htmlspecialchars($meta_title):'';?>" />
		<meta property="og:type" content="<?php echo (isset($og_type) && $og_type != '') ? $og_type : 'article'; ?>" />
		<meta property="og:image" content="<?php echo (isset($meta_image) && !empty($meta_image)) ? getthumb($meta_image) : base_url(isset($general['homepage_logo']) ? $general['homepage_logo'] : ''); ?>" />
		<meta property="og:image:alt" content="<?php echo (isset($meta_image) && !empty($meta_image)) ? getthumb($meta_image) : base_url(isset($general['homepage_logo']) ? $general['homepage_logo'] : ''); ?>" />
		<?php echo isset($canonical)?'<meta property="og:url" content="'.$canonical.'" />':'';?>
		<meta property="og:description" content="<?php echo (isset($meta_description) && !empty($meta_description))?htmlspecialchars($meta_description):'';?>" />
		<meta property="og:site_name" content="<?php echo (isset($general['homepage_company'])) ? $general['homepage_company'] : ''; ?>" />
		<meta property="fb:admins" content=""/>
		<meta property="fb:app_id" content="" />
		<meta name="twitter:card" content="summary" />
		<meta name="twitter:title" content="<?php echo isset($meta_title)?htmlspecialchars($meta_title):'';?>" />
		<meta name="twitter:description" content="<?php echo (isset($meta_description) && !empty($meta_description))?htmlspecialchars($meta_description):'';?>" />
		<meta name="twitter:image" content="<?php echo (isset($meta_image) && !empty($meta_image))?$meta_image:base_url((isset($general['homepage_logo'])) ? $general['homepage_logo']  : '');?>" />
		<link href="public/frontend/resources/uikit/css/components/accordion.min.css" rel="stylesheet" />
		<link href="public/plugin/rating.css" rel="stylesheet" />
		<link href="public/plugin/lightGallery/dist/css/lightgallery.css" rel="stylesheet" />
    	<link href="public/plugin/jquery.datetimepicker.css" rel="stylesheet" />
    	<link href="public/plugin/swiper.min.css" rel="stylesheet" />
    	<link href="public/plugin/grt-youtube-popup.css" rel="stylesheet" />
    	<!-- <link href="public/plugin/slick.css" rel="stylesheet" /> -->

    	<script src="public/frontend/resources/library/js/jquery.js"></script>
		<!-- <script src="public/plugin/jquery-2.1.4.js" type="text/javascript"></script> -->
		<script src="public/plugin/rating.js" type="text/javascript"></script>
		<script src="public/plugin/swiper-bundle.min.js" type="text/javascript"></script>
		<script src="public/plugin/grt-youtube-popup.js" type="text/javascript"></script>
		<!-- <script src="public/plugin/slick.min.js" type="text/javascript"></script> -->


	
		<?php
			helper('mydatafrontend');
			$widget['data'] = widget_frontend();
			$system = get_system();

			$check_css = false;
			foreach ($system as $key => $value) {
				if(isset($module) && $value['module'] == $module && $value['keyword'] == $module.'_css'){
					$check_css = true;
					echo $system[$value['module'].'_css']['content'];
				}
			}
			if($check_css == false){
				echo $system['normal_css']['content'];
			}

		?>

		<?php echo view('frontend/homepage/common/style', $widget) ?>
		<?php echo view('frontend/homepage/common/head') ?>
		<?php echo $system['general_css']['content'] ?>
    	<link href="public/frontend/resources/style-ngoc.css" rel="stylesheet" />
    	<link href="public/frontend/resources/fix.css" rel="stylesheet" />
		<?php echo $system['general_script_top']['content'] ?>

		<script type="text/javascript">
	        var BASE_URL = '<?php echo BASE_URL; ?>';
	        var SUFFIX = '<?php echo HTSUFFIX; ?>';
	        var _THUMB_VIDEO = '<?php echo _THUMB_VIDEO; ?>';
	        var AUTH_CURRENCY = '<?php echo AUTH_CURRENCY ?>';
			var CURRENCY_TITLE = '<?php echo CURRENCY ?>';
	    </script>
		<?php echo $general['analytic_google_analytic'] ?>
		<?php echo $general['facebook_facebook_pixel'] ?>
	</head>
	<!-- disable ctrl+c , ctrl+v -->
	<!-- <body oncontextmenu = "return false" oncopy="return false" oncut="return false" onpaste="return false"> -->
	<body>
			<!-- Google Tag Manager (noscript) -->
<noscript><iframe src="https://www.googletagmanager.com/ns.html?id=GTM-NVVML2ZV"
height="0" width="0" style="display:none;visibility:hidden"></iframe></noscript>
<!-- End Google Tag Manager (noscript) -->

		<?php  echo view('frontend/homepage/common/schema') ?>
		<?php echo view('frontend/homepage/common/header') ?>
		<?php echo view((isset($template)) ? $template : '') ?>
		<?php echo view('frontend/homepage/common/footer') ?>
		<?php echo view('frontend/homepage/common/offcanvas') ?>


		<!-- Tao Widget -->

		<?php
			foreach ($widget['data'] as $key => $value) {
				echo  str_replace("[phone]", isset($general['contact_phone']) ? $general['contact_phone'] : '', $value['html']);
				echo '<script>'.$value['script'].'</script>';
			}
		?>

		<?php
			$check_script = false;
			foreach ($system as $key => $value) {
				if(isset($module) && $value['module'] == $module && $value['keyword'] == $module.'_script'){
					$check_script = true;
					echo $system[$value['module'].'_script']['content'];
				}
			}
			if($check_script == false){
				echo $system['normal_script']['content'];
			}
			if(isset($module) && $module == 'member'){
	            echo ' <script src="public/frontend/resources/login.js"></script>';
	        }

		?>

		<?php echo $system['general_script_bottom']['content'] ?>
		<script src="public/plugin/lightGallery/dist/js/lightgallery-all.min.js" type="text/javascript"></script>
		<script src="public/plugin/jquery.datetimepicker.js"></script>


		<div id="fb-root"></div>
		<script async defer crossorigin="anonymous" src="https://connect.facebook.net/vi_VN/sdk.js#xfbml=1&version=v12.0" nonce="iDUV3sm7"></script>
		<script src="public/frontend/resources/uikit/js/components/accordion.min.js"></script>

		<script src="public/plugin/grt-youtube-popup.js" type="text/javascript"></script>
		<script>
		     $(".youtube-link").grtyoutube({
		        autoPlay:true,
		        theme: "dark"
		     });
		</script>
		<div class="bg-dark uk-hidden"></div>
		<style>
		    .bg-dark {
		        position: fixed;
		        background-color: rgba(0, 0, 0, .7);
		        width: 100%;
		        height: 100%;
		        top: 0;
		        left: 0;
		        display: none;
		        z-index: 12
		    }

		    .btn-bot {
		        display: block;
		        position: fixed;
		        bottom: 28px;
		        z-index: 11;
		        background: #d10d0d;
		        padding: 7px 20px;
		        border-radius: 0 20px 0 0;
		        color: #fff;
		        left: 0
		    }

		    @media screen and (min-width:1025px) {
		        .btn-bot {
		            left: 20%;
		            border-radius: 5px 5px 0 0;
		            bottom: 0
		        }
		    }

		    .btn-bot:hover {
		        background: #2196f3;
		        color: #fff
		    }
		</style>
		<?php echo $general['script_chat_live'] ?>
		<?php echo $general['script_facebook_pixel'] ?>
	</body>
</html>
