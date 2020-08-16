<?php
/*
* @Author 		Pluginbazar
* Copyright: 	2015 Pluginbazar
*/

defined( 'ABSPATH' ) || exit;

$style  = ( isset( $style ) || ! empty( $style ) ) ? $style : 1;
$set_id = ( isset( $set ) || ! empty( $set ) ) ? $set : wooopenclose()->get_active_schedule_id();

?>

<div class="wooopenclose-schedules-wrap wooopenclose-schedules-style-<?php echo esc_attr( $style ); ?> <?php echo ( woc_is_open() ) ? 'wooopenclose-shop-schedules-open' : 'wooopenclose-shop-schedules-close'; ?>">

    <div class="wooopenclose-schedules">

		<?php if ( ! empty( $status_image = wooopenclose()->get_status_image() ) ) : ?>
            <div class="wooopenclose-status-img">
                <img src="<?php echo esc_url( $status_image ); ?>"
                     alt="<?php woc_is_open() ? esc_attr_e( 'Shop open', 'woc-open-close' ) : esc_attr_e( 'Shop close', 'woc-open-close' ); ?>">
            </div>
		<?php endif; ?>

		<?php foreach ( wooopenclose()->get_all_schedules( $set_id ) as $day_id => $day_schedules ) :

			if ( $day_id === 'woc_message' ) {
				continue;
			}

			?>

            <div class="wooopenclose-schedule <?php echo wooopenclose()->get_current_day_id() == $day_id ? 'current opened' : ''; ?> <?php echo woc_is_open() ? 'shop-open' : 'shop-close'; ?> ">

                <div class="wooopenclose-day-name">
					<?php
					printf( '%s %s <span class="wooopenclose-arrow-icon"></span>',
						in_array( 'yes', woc_get_option( 'woc_bh_check_icon', array( 'yes' ) ) ) ? '<span class="dashicons dashicons-yes"></span>' : '',
						wooopenclose()->get_day_name( $day_id, true )
					);
					?>
                </div>

                <div class="wooopenclose-day-schedules">
					<?php
					foreach ( $day_schedules as $schedule_id => $schedule ) {
						if ( isset( $schedule['open'] ) && $schedule['close'] ) {
							printf( '<div class="wooopenclose-day-schedule"><span class="dashicons dashicons-clock"></span> %s - %s</div>', $schedule['open'], $schedule['close'] );
						}
					}

					if ( empty( $day_schedules ) ) {
						printf( '<div class="wooopenclose-day-schedule">%s</div>', esc_html__( 'Closed!', 'woc-open-close' ) );
					}
					?>
                </div>

            </div>

		<?php endforeach; ?>

    </div>

</div>