<?php

/*
* @Author 		pickplugins
* Copyright: 	2015 pickplugins
*/

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}  // if direct access

class class_qa_woc_hour_column {

	public function __construct() {

		add_action( 'manage_woc_hour_posts_columns', array( $this, 'add_columns' ), 16, 1 );
		add_action( 'manage_woc_hour_posts_custom_column', array( $this, 'custom_columns_content' ), 10, 2 );
		add_filter( 'post_row_actions', array( $this, 'remove_row_actions' ), 10, 2 );
	}

	public function add_columns( $columns ) {

		$new = array();
		foreach ( $columns as $col_id => $col_label ) {

			if ( 'cb' != $col_id ) {
				continue;
			}

			$new[ $col_id ] = $col_label;

			$new['title']         = '<i class="icofont-wall-clock"></i> ' . esc_html__( 'Schedule Title', 'woc-open-close' );
			$new['woc_shortcode'] = '';
			$new['woc_author']    = esc_html__( 'Author', 'woc-open-close' );
			$new['woc_status']    = '';
			$new['woc_date']      = '';
		}

		unset( $new['date'] );

		return $new;
	}

	public function custom_columns_content( $column, $post_id ) {
		switch ( $column ) {

			case 'woc_shortcode':
				?>
                <div class="woc_shortcode">
                    <input type="text" size="30" readonly="readonly" onclick="this.select()"
                           value='[woc_open_close set="<?php echo $post_id; ?>"]'/>
                </div>
				<?php
				break;

			case 'woc_author':

				global $authordata;

				if ( $authordata instanceof WP_User ) {
					printf( '<div class="woc_author"><a href="%s">%s</a></div>', admin_url( "user-edit.php?user_id={$authordata->ID}" ), $authordata->display_name );
				}
				break;

			case 'woc_status':

				if ( $post_id == wooopenclose()->get_active_schedule_id() ) {
					echo '<div class="woc_column_status"><i class="fa fa-check"></i> ' . esc_html__( 'Active Schedule', 'woc-open-close' ) . '</div>';
				}

				break;

			case 'woc_date':

				$dateago = human_time_diff( get_the_time( 'U', $post_id ), current_time( 'timestamp' ) );

				?>Created <em><?php printf( __( '%s ago', 'woc-open-close' ), $dateago ); ?></em><?php

				break;


		}
	}

	public function remove_row_actions( $actions ) {
		global $post;

		if ( $post->post_type === 'woc_hour' ) {
			unset( $actions['inline hide-if-no-js'] );
			unset( $actions['view'] );
		}

		return $actions;
	}


}

new class_qa_woc_hour_column();