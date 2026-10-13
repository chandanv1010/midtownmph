<section class="breadcrumb-panel">
    <div class="uk-container-center uk-container">
        <ul class="uk-breadcrumb uk-clearfix ">
            <li class="breadcrumb-home"><a href=""> <i class="fa fa-home"></i> <?php echo esc(lang('app.HOME_LINK')) ?></a></li>
            <?php if(isset($breadcrumb) && is_array($breadcrumb) && count($breadcrumb)){
				foreach ($breadcrumb as $key => $value) {
			 ?>
				<li class=""><a href="<?php echo BASE_URL.$value['canonical'].HTSUFFIX ?>" ><span><?php echo $value['title'] ?></span></a></li>
			<?php }} ?>
        </ul>
    </div>
</section>