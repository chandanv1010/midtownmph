<?php 
	use App\Controllers\FrontendController;
	$FrontendController = new FrontendController();
	$language = $FrontendController->currentLanguage();
	helper('mypanel');
	$panel = get_panel([
		'locate' => 'footer',
		'language' => $language,
	]);

	// print_r($panel); exit;

	$about_us = isset($panel['about-us']) ? $panel['about-us'] : [];

	// print_r($about_us); exit;
 ?>

<?php echo view('frontend/homepage/common/breadcrumb'); ?>
<section class="artcatalogue">
	<div class="uk-container uk-container-center">
		<h1 class="cat_heading_1 txt-center mb20 uk-hidden"><?php echo $detailCatalogue['title'] ?></h1>
		<?php /*
		<?php if ($detailCatalogue['description'] != ''): ?>
			<div class="dt_item_desc_1 mb20"><?php echo base64_decode($detailCatalogue['description']) ?></div>
		<?php endif ?>
		<?php if ($detailCatalogue['content'] != ''): ?>
			<div class="dt_item_desc_1 mb20"><?php echo base64_decode($detailCatalogue['content']) ?></div>
		<?php endif ?>
		*/ ?>
		<?php 
			$partner = get_slide(array(
		    	'keyword' => 'partner',
		    	'type' => '',
		    	'output' => 'array',
		    ));
		    $partner = isset($partner['data'])? $partner['data'] : [];
		 ?>
		<?php 

			// print_r($partner); exit;
		?>
		<?php if(isset($partner) && is_array($partner) && count($partner)){ ?>
		<section class="partner-section">
			<div class="uk-container uk-container-center">
				<div class="cat_heading_1 txt-center dt_about_heading">Đối tác tin cậy của chúng tôi</div>
				<ul id="partner" class="uk-grid uk-grid-small uk-grid-width-1-3 uk-grid-width-small-1-4 uk-grid-width-medium-1-5 list">
					<?php foreach($partner as $key => $val) { ?>
						<?php 															
							$title = $val['title'];
							$image = $val['image'];
							$href = $val['url'];
						?>
						<li >	
							<div class="thumb">
								<a class="image img-scaledown" href="<?php echo $href?>" title="<?php echo $title; ?>"><img src="<?php echo $image; ?>" alt="<?php echo $title; ?>" /></a>
							</div>
						</li>
					<?php } ?>
				</ul>
			</div>
			
		</section><!-- .partner-section -->
		<?php } ?>

		<script type="text/javascript">
			$(document).ready(function() {
				$("#partner").simplyScroll();
			});
		</script>

		<style>
			.partner-section {
			    padding: 30px 0;
			    margin-bottom: 20px;
			}
			.partner-section .heading-1{
				margin-bottom: 20px;
			}

			.simply-scroll, .simply-scroll .simply-scroll-clip {
			    width: 100%;
			    height: auto;
			    margin-bottom: 0;
			}

			.simply-scroll .simply-scroll-list li {
			    width: 150px;
			    height: auto;
			    margin-right: 60px;
			}

			.list >li>.thumb{
			    /*margin: 3px 0;*/
			}
			.list >li>.thumb .image {
			    box-shadow: 0px 0px 0 0 #999;
			    height: 100px;
			    position: relative;
			}
			.list >li>.thumb .image >img{
				max-width: 100%;
			    position: absolute;
			    top: 0px;
			    bottom: 0px;
			    left: 0px;
			    right: 0px;
			    margin: auto;
			    max-height: 100%;
		        opacity: .8;
			    -webkit-transition-duration: .3s;
			    transition-duration: .3s;
			}

			.list >li>.thumb .image:hover >img{
				opacity: 1;
			}

		</style>

		<?php if (check_array($about_us)): ?>
			<section class="about_panel">
				<div class="cat_heading_1 txt-center dt_about_heading"><?php echo $about_us['title'] ?></div>
				<?php if (isset($about_us['data']) && check_array($about_us['data'])): ?>
					<div class="uk-width-large-2-3 uk-container-center">
						<div class="uk-accordion" data-uk-accordion>
							<?php 
								foreach ($about_us['data'] as $key => $val) {
									$title = $val['title'];
									$description = $val['description'];
									$view = ($key == 0)? 'frontend/homepage/common/form_daily' : 'frontend/homepage/common/form_tuyendung';
							 ?>
							    <h3 class="uk-accordion-title"><?php echo $title ?></h3>
					    		<div class="uk-accordion-content">
					    			<?php if ($description != ''): ?>
										<div class="dt_about_description">
											<?php echo $description ?>
										</div>
					    			<?php endif ?>
									<?php echo view($view); ?>
					    		</div>
					    	<?php } ?>
			    		</div>
		    		</div>
				<?php endif ?>
			</section>
		<?php endif ?>
	</div>
</section>
