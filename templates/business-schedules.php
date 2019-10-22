<?php
/*
* @Author 		pluginbazar
* Copyright: 	2015 pluginbazar
*/

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}  // if direct access


$template = ( isset( $template ) || ! empty( $template ) ) ? $template : 1;
$set_id   = ( isset( $set ) || ! empty( $set ) ) ? $set : wooopenclose()->get_active_schedule_id();

?>
<div class="woc-schedules-wrap woc-schedules-style-<?php echo esc_attr( $template ); ?> <?php echo ( woc_is_open() ) ? 'woc-shop-schedules-open' : 'woc-shop-schedules-close'; ?>">

    <div class="woc-schedules">

		<?php if ( ! empty( $status_image = wooopenclose()->get_status_image() ) ) : ?>
            <div class="woc-status-img">
                <img src="<?php echo esc_url( $status_image ); ?>"
                     alt="<?php woc_is_open() ? esc_attr_e( 'Shop open', 'woc-open-close' ) : esc_attr_e( 'Shop close', 'woc-open-close' ); ?>">
            </div>
		<?php endif; ?>

		<?php foreach ( wooopenclose()->get_all_schedules( $set_id ) as $day_id => $day_schedules ) :

			if ( $day_id === 'woc_message' ) {
				continue;
			}
			?>

            <div class="woc-schedule <?php echo wooopenclose()->get_current_day_id() == $day_id ? 'current opened' : ''; ?> <?php echo woc_is_open() ? 'shop-open' : 'shop-close'; ?> ">

                <div class="woc-day-name">
					<?php
					printf( '%s %s <span class="woc-arrow-icon"></span>',
						in_array( 'yes', woc_get_option( 'woc_bh_check_icon', array( 'yes' ) ) ) ? '<i class="icofont-tick-mark"></i>' : '',
						wooopenclose()->get_day_name( $day_id )
					);
					?>
                </div>

                <div class="woc-day-schedules">
					<?php
					foreach ( $day_schedules as $schedule_id => $schedule ) {
						if ( isset( $schedule['open'] ) && $schedule['close'] ) {
							printf( '<div class="woc-day-schedule"><iclass="icofont-wall-clock"></i>%s - %s</div>', $schedule['open'], $schedule['close'] );
						}
					}
					?>
                </div>

            </div>

		<?php endforeach; ?>

    </div>

</div>