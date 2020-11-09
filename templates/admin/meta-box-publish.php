<?php
/*
* @Author 		pluginbazar
* Copyright: 	2015 pluginbazar
*/

defined( 'ABSPATH' ) || exit;

global $post;

if ( $post->post_status === 'publish' ) :
	?>
    <div class='woc_section woc_section_mini'>
        <div class='woc_section_inline woc_section_title'>
			<?php esc_html_e( 'Default', 'woc-open-close' ); ?>
        </div>
        <div class="woc_section_inline woc_section_hint hint--top"
             aria-label="<?php echo __( 'Make this hour schedule as default for your Shop', 'woc-open-close' ); ?>">?
        </div>
        <div class='woc_section_inline woc_section_inputs'>
            <label class="woc_switch">
                <input <?php echo wooopenclose()->get_active_schedule_id() == $post->ID ? 'checked' : ''; ?>
                        type="checkbox"
                        class="woc_switch_checkbox"
                        data-id="<?php echo $post->ID; ?>">
                <span class="woc_switch_slider woc_switch_round"></span>
            </label>
        </div>
    </div>
<?php endif; ?>

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

<div class='woc_section woc_section_mini'>
    <div class='woc_section_inline woc_section_title'><?php echo __( 'Shortcode', 'woc-open-close' ); ?></div>
    <div class="woc_section_inline woc_section_hint hint--top"
         aria-label="<?php echo __( 'Copy this shortcode to display the schedule anywhere you want!', 'woc-open-close' ); ?>">
        ?
    </div>
    <div class='woc_section_inline woc_section_inputs'>
        <span class='wooopenclose-shortcode hint--top' aria-label='<?php _e( 'Click to Copy', 'woc-open-close' ); ?>'><?php printf( '[schedule id="%s"]', $post->ID ); ?></span>
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
        display: flex;
        align-items: center;
        justify-content: space-between;
    }

    #major-publishing-actions .clear {
        display: none;
    }

    input#publish {
        background: #1dbf73;
        border: none;
    }
</style>