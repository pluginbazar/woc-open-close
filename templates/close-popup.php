<?php
/*
* @Author 		pluginbazar
* Copyright: 	2015 pluginbazar
*/

if (!defined('ABSPATH')) exit;  // if direct access

global $wooopenclose, $wp_query;


?>

<div id="wooopenclose-box-container" class="wooopenclose-box-container mfp-with-anim mfp-hide">
    <div class="wooopenclose-box">
        <p class="box-message">
            <?php echo $wooopenclose->get_message(); ?>
        </p>
    </div>
</div>