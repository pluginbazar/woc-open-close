<?php	


/*
* @Author 		pluginbazar
* Copyright: 	2015 pluginbazar
*/

if ( ! defined('ABSPATH')) exit;  // if direct access 

?>

<div class="woc_wrap_faq">
	<h2>WooCommerce Open Close - <?php echo __('Frequently Asked Question',WOCTEXTDOMAIN); ?></h2>
	
	<ul class="woc_faq_panel">
		<li class="woc_video_tutorial">
			<div class="woc_block_header"><?php echo __('Video Tutorial',WOCTEXTDOMAIN); ?></div>
			<iframe src="https://www.youtube.com/embed/<?php echo WOC_VIDEO_CODE; ?>?feature=oembed&amp;autoplay=0&amp;controls=0&amp;showinfo=0&amp;rel=0frameborder=" style="width:100%;height:320px;" allowfullscreen="allowfullscreen"></iframe>
		</li>
		<li class="woc_faq_link">
			<div class="woc_block_header"><?php echo __('Asked a Question',WOCTEXTDOMAIN); ?></div>
			<div class="woc_asked_on_forum">
				<a href="<?php echo WOC_SUPPORT_URL; ?>" class="button button-orange" target="_blank"><?php echo __('Ask Here',WOCTEXTDOMAIN); ?></a>
			</div>
		</li>
	</ul>
</div>
