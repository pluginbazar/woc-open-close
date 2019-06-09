<?php
/*
* @Author 		pluginbazar
* Copyright: 	2015 pluginbazar
*/

if (!defined('ABSPATH')) exit;  // if direct access

global $wooopenclose;

?>

<div class="woc-box-container">
    <div class="woc-box">
        <span class="box-close"><i class="icofont-close"></i></span>
        <p class="box-message">
            <?php echo $wooopenclose->get_message(); ?>
        </p>
    </div>
</div>