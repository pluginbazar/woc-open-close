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

    <div class="wooopenclose-schedules-header">
        <?php printf( '<h3>%s</h3>', date( 'h:i:s A' ) ); ?>
        <?php printf( '<span>%s</span>', date('jS M Y' ) ); ?>
    </div>

    <div class="wooopenclose-schedules">

		<?php foreach ( wooopenclose()->get_all_schedules( $set_id ) as $day_id => $day_schedules ) :

			if ( $day_id === 'woc_message' ) {
				continue;
			}

			?>

            <div class="wooopenclose-schedule <?php echo wooopenclose()->get_current_day_id() == $day_id ? 'current opened' : ''; ?> <?php echo woc_is_open() ? 'shop-open' : 'shop-close'; ?> ">

	            <?php printf( '<div class="wooopenclose-day-name">%s</div>', wooopenclose()->get_day_name( $day_id, true ) ); ?>

                <div class="wooopenclose-day-schedules">
		            <?php
		            foreach ( $day_schedules as $schedule_id => $schedule ) {
			            if ( isset( $schedule['open'] ) && $schedule['close'] ) {
				            printf( '<div class="wooopenclose-day-schedule">%s - %s</div>', $schedule['open'], $schedule['close'] );
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

<?php if ( isset( $note ) || ! empty( $note ) ) : ?>
    <p class="wooopenclose-schedules-note"><?php echo wp_kses_post( $note ); ?></p>
<?php endif; ?>