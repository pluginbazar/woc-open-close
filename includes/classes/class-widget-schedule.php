<?php

/*
* @Author 		pluginbazar
* @Folder	 	job-board-manager\themes\joblist

* Copyright: 	2015 pluginbazar
*/

if ( ! defined('ABSPATH')) exit;  // if direct access 
	

class WocWidgetSchedule extends WP_Widget {

	function __construct() {
		
		parent::__construct(
			'woc_widget_schedules', __('WooCommerce Open Close - Schedule', 'woc-open-close'),
			array( 'description' => __( 'Display your store business hours or schedule.', 'woc-open-close' ), ) 
		);
	}

	public function widget( $args, $instance ) {

		$title      = apply_filters( 'widget_title', isset( $instance['title'] ) ? $instance['title'] : '' );
		$hour_set   = isset( $instance['hour_set'] ) ? $instance['hour_set'] : '';
		
		echo $args['before_widget'];
		if ( ! empty( $title ) ) echo $args['before_title'] . $title . $args['after_title'];

		if( empty( $hour_set ) ) echo do_shortcode('[woc_open_close]');
		else echo do_shortcode('[woc_open_close set="'.$hour_set.'"]');
		
		echo $args['after_widget'];
	}
	
	public function form( $instance ) {

		$title      = isset( $instance[ 'title' ] ) ? esc_attr( $instance[ 'title' ] ) : __( 'Our Business Schedules', 'woc-open-close' );
		$hour_set   = isset( $instance[ 'hour_set' ] ) ? $instance[ 'hour_set' ] : '';

		?>

        <div class='woc_section woc_section_mini'>
            <div class='woc_section_inline woc_section_title'><?php echo __('Widget Title', 'woc-open-close');?></div>
            <div class="woc_section_inline woc_section_hint hint--top" aria-label="<?php echo __('Set Title for this Widget', 'woc-open-close');?>">?</div>
            <div class='woc_section_inline woc_section_inputs'>

                <input name="<?php echo $this->get_field_name('title'); ?>" type="text" placeholder="<?php _e('Our Business Schedules') ?>" value="<?php echo $title; ?>">

            </div>
        </div>

        <div class='woc_section woc_section_mini'>
            <div class='woc_section_inline woc_section_title'><?php echo __('Schedule', 'woc-open-close');?></div>
            <div class="woc_section_inline woc_section_hint hint--top" aria-label="<?php echo __('Select which schedules you want to Display. Leave empty to display default Schedule', 'woc-open-close');?>">?</div>
            <div class='woc_section_inline woc_section_inputs'>

                <select name="<?php echo $this->get_field_name('hour_set'); ?>">

                    <option value=""><?php _e('Select a Schedule') ?></option>

                    <?php foreach ( get_posts('post_type=woc_hour&posts_per_page=-1') as $post ) : ?>

                        <option <?php echo $post->ID == $hour_set ? 'selected' : ''; ?> value="<?php echo $post->ID; ?>"><?php echo $post->post_title; ?></option>

                    <?php endforeach; ?>

                </select>

            </div>
        </div>

		<?php
	}
	
	public function update( $new_instance, $old_instance ) {
		$instance = array();
		$instance['title'] = ( ! empty( $new_instance['title'] ) ) ? strip_tags( $new_instance['title'] ) : '';
		$instance['hour_set'] = ( ! empty( $new_instance['hour_set'] ) ) ? strip_tags( $new_instance['hour_set'] ) : '';
		return $instance;
	}
}