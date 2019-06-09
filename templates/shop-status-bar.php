<?php
/*
* @Author 		pluginbazar
* Copyright: 	2015 pluginbazar
*/

if (!defined('ABSPATH')) exit;  // if direct access

global $wooopenclose;

?>


<div class="shop-status-bar <?php woc_status_bar_classes( true ); ?>">

    <div class="shop-status-bar-inline status-message">
        <span><?php echo $wooopenclose->get_message(); ?></span>
    </div>

    <?php if( $wooopenclose->is_display_bar_btn() ) : ?>

    <div class="shop-status-bar-inline close-bar">
        <span><?php echo $wooopenclose->get_bar_btn_text(); ?></span>
    </div>

    <?php endif;; ?>

</div>
