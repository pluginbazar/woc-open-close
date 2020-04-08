<?php
/*
* @Author 		pluginbazar
* Copyright: 	2015 pluginbazar
*/

defined( 'ABSPATH' ) || exit;

if ( wooopenclose()->get_option( 'woc_bar_where' ) === 'woc-bar-none' ) {
	return;
}

?>

<div class="shop-status-bar <?php woc_status_bar_classes( true ); ?>">

    <div class="shop-status-bar-inline status-message">
        <span><?php echo wooopenclose()->get_message(); ?></span>
    </div>

	<?php if ( wooopenclose()->is_display_bar_btn() ) : ?>

        <div class="shop-status-bar-inline close-bar">
            <span><?php echo wooopenclose()->get_bar_btn_text(); ?></span>
        </div>

	<?php endif;; ?>

</div>
