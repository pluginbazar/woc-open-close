<?php
/*
* @Author 		pluginbazar
* Copyright: 	2015 pluginbazar
*/

if (!defined('ABSPATH')) exit;  // if direct access

global $wooopenclose, $wp_query;


?>

<div id="woc-box-container" class="woc-box-container mfp-with-anim mfp-hide">
    <div class="woc-box">
        <p class="box-message">
            <?php echo $wooopenclose->get_message(); ?>
        </p>
    </div>
</div>