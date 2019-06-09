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
			'woc_widget_schedules', 
			__('WooCommerce Open Close - Schedule', WOCTEXTDOMAIN),
			array( 'description' => __( 'Display your store business hours or schedule.', WOCTEXTDOMAIN ), ) 
		);
	}

	public function widget( $args, $instance ) {
		$title = apply_filters( 'widget_title', $instance['title'] );
		$hour_set = apply_filters( 'widget_title', $instance['hour_set'] );
		
		echo $args['before_widget'];
		if ( ! empty( $title ) ) echo $args['before_title'] . $title . $args['after_title'];
		
		if( empty( $hour_set ) ) echo do_shortcode('[woc_open_close]');
		else echo do_shortcode('[woc_open_close set="'.$hour_set.'"]');
		
		echo $args['after_widget'];
	}
	
	public function form( $instance ) {

		$title = isset( $instance[ 'title' ] ) ? $instance[ 'title' ] : __( 'Our Business Hour', WOCTEXTDOMAIN );
		$hour_set = isset( $instance[ 'hour_set' ] ) ? $instance[ 'hour_set' ] : '';
		?>
		<p>
		<label for="<?php echo $this->get_field_id( 'title' ); ?>"><?php _e( 'Title:' ); ?></label> 
		<input class="widefat" id="<?php echo $this->get_field_id( 'title' ); ?>" name="<?php echo $this->get_field_name( 'title' ); ?>" type="text" value="<?php echo esc_attr( $title ); ?>" />
		</p>
		<p>
		<label for="<?php echo $this->get_field_id( 'hour_set' ); ?>"><?php _e( 'Hour Set ID:', WOCTEXTDOMAIN ); ?>
		<br>If empty default set will applied.</label> 
		<input class="widefat" id="<?php echo $this->get_field_id( 'hour_set' ); ?>" name="<?php echo $this->get_field_name( 'hour_set' ); ?>" type="text" value="<?php echo esc_attr( $hour_set ); ?>" />
		</p>
		<?php 
		
		
	}
	
	public function update( $new_instance, $old_instance ) {
		$instance = array();
		$instance['title'] = ( ! empty( $new_instance['title'] ) ) ? strip_tags( $new_instance['title'] ) : '';
		$instance['hour_set'] = ( ! empty( $new_instance['hour_set'] ) ) ? strip_tags( $new_instance['hour_set'] ) : '';
		return $instance;
	}
}