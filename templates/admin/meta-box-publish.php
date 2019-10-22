<?php
/*
* @Author 		pluginbazar
* Copyright: 	2015 pluginbazar
*/

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}  // if direct access


$woc_switch_checkbox = wooopenclose()->get_active_schedule_id() == $post->ID ? 'checked' : '';

?>

<div class='woc_section woc_section_mini'>
    <div class='woc_section_inline woc_section_title'><?php echo __( 'Make Active', 'woc-open-close' ); ?></div>
    <div class="woc_section_inline woc_section_hint hint--top"
         aria-label="<?php echo __( 'Make this hour schedule as active for your Shop', 'woc-open-close' ); ?>">?
    </div>
    <div class='woc_section_inline woc_section_inputs'>

        <label class="woc_switch">
            <input type="checkbox" class="woc_switch_checkbox" <?php echo $woc_switch_checkbox; ?>
                   data-id="<?php echo $post->ID; ?>">
            <span class="woc_switch_slider woc_switch_round"></span>
        </label>

    </div>
</div>

<div class='woc_section woc_section_mini'>
    <div class='woc_section_inline woc_section_title'><?php echo __( 'Date - Now', 'woc-open-close' ); ?></div>
    <div class="woc_section_inline woc_section_hint hint--top"
         aria-label="<?php echo __( 'Current Date for your WooCommerce Shop', 'woc-open-close' ); ?>">?
    </div>
    <div class='woc_section_inline woc_section_inputs'>

        <div class="woc_current_time"><?php echo date( 'jS F Y' ); ?></div>

    </div>
</div>

<div class='woc_section woc_section_mini'>
    <div class='woc_section_inline woc_section_title'><?php echo __( 'Time - Now', 'woc-open-close' ); ?></div>
    <div class="woc_section_inline woc_section_hint hint--top"
         aria-label="<?php echo __( 'Current time for your WooCommerce Shop', 'woc-open-close' ); ?>">?
    </div>
    <div class='woc_section_inline woc_section_inputs'>

        <div class="woc_current_time"><?php echo date( 'h:i A' ); ?></div>

    </div>
</div>

<div class='woc_section woc_section_mini'>
    <div class='woc_section_inline woc_section_title'><?php echo __( 'Day - Now', 'woc-open-close' ); ?></div>
    <div class="woc_section_inline woc_section_hint hint--top"
         aria-label="<?php echo __( 'Current Day for your WooCommerce Shop', 'woc-open-close' ); ?>">?
    </div>
    <div class='woc_section_inline woc_section_inputs'>

        <div class="woc_current_time"><?php echo date( 'l' ); ?></div>

    </div>
</div>


<div class='woc_section woc_section_mini'>
    <div class='woc_section_inline woc_section_title'><?php echo __( 'Timezone', 'woc-open-close' ); ?></div>
    <div class="woc_section_inline woc_section_hint hint--top"
         aria-label="<?php echo __( 'Current timezone for your WooCommerce Shop', 'woc-open-close' ); ?>">?
    </div>
    <div class='woc_section_inline woc_section_inputs'>
        <div class="woc_current_time"><?php echo wooopenclose()->get_timezone_string(); ?></div>
        <div class='woc_note hint--top-left hint--medium hint--error'
             aria-label='<?php _e( 'You must update your time zone or time according to your city where you want to manage Shop', 'woc-open-close' ); ?>'><?php _e( 'Note', 'woc-open-close' ); ?></div>
        <p><a class='' target='_blank'
              href='edit.php?post_type=woc_hour&page=woc-open-close#timezone_string'><?php _e( 'Update Time Now', 'woc-open-close' ); ?></a>
        </p>
    </div>
</div>


<style>
    #minor-publishing-actions, .misc-pub-post-status, .misc-pub-curtime, .misc-pub-visibility {
        display: none;
    !important;
    }

    #major-publishing-actions {
        border: none !important;
        background: #fff !important;
    }

    #delete-action a,
    #delete-action a:focus,
    #delete-action a:active {
        color: #fff !important;
        text-decoration: none;
        background: #E91E63;
        border-radius: 4px;
        padding: 8px 20px;
        outline: none;
        box-shadow: none;
        user-select: none;
    }

    input#publish,
    input#publish:focus,
    input#publish:active {
        color: #fff !important;
        text-decoration: none;
        background: #4f7d79;
        border-radius: 4px;
        padding: 0px 20px !important;
        outline: none;
        box-shadow: none;
        user-select: none;
        border: none;
        text-shadow: none !important;
    }
</style>