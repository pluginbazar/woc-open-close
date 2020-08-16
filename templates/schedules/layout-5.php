<?php
/*
* @Author 		Pluginbazar
* Copyright: 	2015 Pluginbazar
*/

defined( 'ABSPATH' ) || exit;

$unique_a = uniqid();
$unique_b = uniqid();

$style  = ( isset( $style ) || ! empty( $style ) ) ? $style : 1;
$set_id = ( isset( $set ) || ! empty( $set ) ) ? $set : wooopenclose()->get_active_schedule_id();


?>

    <div class="wooopenclose-schedules-wrap wooopenclose-schedules-style-<?php echo esc_attr( $style ); ?> <?php echo ( woc_is_open() ) ? 'wooopenclose-shop-schedules-open' : 'wooopenclose-shop-schedules-close'; ?>">

		<?php if ( isset( $title ) || ! empty( $title ) ) : ?>
            <h2 class="wooopenclose-schedules-title">
				<?php echo wp_kses_post( $title ); ?>
                <svg xmlns="http://www.w3.org/2000/svg" width="269"
                     height="118.002" viewBox="0 0 269 118.002">
                    <defs>
                        <linearGradient id="a-<?php echo esc_attr( $unique_a ); ?>" x1="0.5" x2="0.5" y2="1" gradientUnits="objectBoundingBox">
                            <stop offset="0" stop-color="#1144da"/>
                            <stop offset="1" stop-color="#1439a7"/>
                        </linearGradient>
                        <linearGradient id="b-<?php echo esc_attr( $unique_b ); ?>" x1="0.5" x2="0.5" y2="1" gradientUnits="objectBoundingBox">
                            <stop offset="0" stop-color="#1144da"/>
                            <stop offset="1" stop-color="#3162f5"/>
                        </linearGradient>
                    </defs>
                    <g transform="translate(-814.999 -311.999)">
                        <path d="M66-9285a58.6,58.6,0,0,1-22.966-4.638A58.8,58.8,0,0,1,24.28-9302.28a58.812,58.812,0,0,1-12.644-18.756A58.622,58.622,0,0,1,7-9344a58.622,58.622,0,0,1,4.637-22.964A58.812,58.812,0,0,1,24.28-9385.72a58.8,58.8,0,0,1,18.754-12.644A58.6,58.6,0,0,1,66-9403a58.59,58.59,0,0,1,22.965,4.638,58.793,58.793,0,0,1,18.753,12.644,58.813,58.813,0,0,1,12.644,18.756A58.623,58.623,0,0,1,125-9344a58.623,58.623,0,0,1-4.636,22.964,58.813,58.813,0,0,1-12.644,18.756,58.793,58.793,0,0,1-18.753,12.644A58.59,58.59,0,0,1,66-9285Zm0-94.673A35.714,35.714,0,0,0,30.325-9344,35.717,35.717,0,0,0,66-9308.322,35.715,35.715,0,0,0,101.673-9344,35.713,35.713,0,0,0,66-9379.672Z"
                              transform="translate(895 9715)" fill="#2d5ff5" opacity="0.31"/>
                        <path d="M18-9381a10.936,10.936,0,0,1-4.282-.864,10.955,10.955,0,0,1-3.5-2.358,10.952,10.952,0,0,1-2.357-3.5A10.925,10.925,0,0,1,7-9392a10.937,10.937,0,0,1,.864-4.282,10.959,10.959,0,0,1,2.357-3.5,10.953,10.953,0,0,1,3.5-2.357A10.919,10.919,0,0,1,18-9403a10.918,10.918,0,0,1,4.281.865,10.952,10.952,0,0,1,3.5,2.357,10.959,10.959,0,0,1,2.357,3.5A10.937,10.937,0,0,1,29-9392a10.925,10.925,0,0,1-.864,4.281,10.952,10.952,0,0,1-2.357,3.5,10.955,10.955,0,0,1-3.5,2.358A10.935,10.935,0,0,1,18-9381Zm0-17.652A6.659,6.659,0,0,0,11.348-9392,6.658,6.658,0,0,0,18-9385.348,6.658,6.658,0,0,0,24.65-9392,6.659,6.659,0,0,0,18-9398.65Z"
                              transform="translate(808 9763)" fill="#2d5ff5" opacity="0.31"/>
                        <path d="M18-9381a10.936,10.936,0,0,1-4.282-.864,10.955,10.955,0,0,1-3.5-2.358,10.952,10.952,0,0,1-2.357-3.5A10.925,10.925,0,0,1,7-9392a10.937,10.937,0,0,1,.864-4.282,10.959,10.959,0,0,1,2.357-3.5,10.953,10.953,0,0,1,3.5-2.357A10.919,10.919,0,0,1,18-9403a10.918,10.918,0,0,1,4.281.865,10.952,10.952,0,0,1,3.5,2.357,10.959,10.959,0,0,1,2.357,3.5A10.937,10.937,0,0,1,29-9392a10.925,10.925,0,0,1-.864,4.281,10.952,10.952,0,0,1-2.357,3.5,10.955,10.955,0,0,1-3.5,2.358A10.935,10.935,0,0,1,18-9381Zm0-17.652A6.659,6.659,0,0,0,11.348-9392,6.658,6.658,0,0,0,18-9385.348,6.658,6.658,0,0,0,24.65-9392,6.659,6.659,0,0,0,18-9398.65Z"
                              transform="translate(1055 9757)" fill="#2d5ff5" opacity="0.31"/>
                        <circle cx="6" cy="6" r="6" transform="translate(869 400)" fill="url(#a-<?php echo esc_attr( $unique_a ); ?>)"/>
                        <circle cx="6" cy="6" r="6" transform="translate(1050 320)" opacity="0.77" fill="url(#b-<?php echo esc_attr( $unique_b ); ?>)"/>
                    </g>
                </svg>
            </h2>
		<?php endif; ?>

        <div class="wooopenclose-schedules">

			<?php foreach ( wooopenclose()->get_all_schedules( $set_id ) as $day_id => $day_schedules ) :

				if ( $day_id === 'woc_message' ) {
					continue;
				}

				?>

                <div class="wooopenclose-schedule <?php echo wooopenclose()->get_current_day_id() == $day_id ? 'current opened' : ''; ?> <?php echo woc_is_open() ? 'shop-open' : 'shop-close'; ?> ">


					<?php printf( '<div class="wooopenclose-day-name">%s <span class="wooopenclose-arrow-icon"></span></div>', wooopenclose()->get_day_name( $day_id, true ) ); ?>

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