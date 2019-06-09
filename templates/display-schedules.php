<?php
/*
* @Author 		pluginbazar
* Copyright: 	2015 pluginbazar
*/

if ( ! defined('ABSPATH')) exit;  // if direct access


global $wooopenclose;

?>
<div class="woc-schedules-wrap">
		
	<div class="woc-schedules">
	
	<?php foreach( $wooopenclose->get_all_schedules( $set ) as $day_id => $day_schedules ) : ?>
		
		<?php if( $day_id === 'woc_message' ) continue; ?>

		<div class="woc-schedule <?php echo $wooopenclose->get_current_day_id() == $day_id ? 'current opened' : ''; ?> <?php echo woc_is_open() ? 'shop-open' : 'shop-close'; ?> ">
		
		    <div class="woc-day-name"><i class="icofont-tick-mark"></i> <?php echo $wooopenclose->get_day_name( $day_id ); ?></div>
		
            <div class="woc-day-schedules">

                <?php foreach( $day_schedules as $schedule_id => $schedule ) : ?>

                    <?php if( isset( $schedule['open'] ) && $schedule['close'] ) : ?>

                        <div class="woc-day-schedule"><i class="icofont-wall-clock"></i> <?php echo $schedule['open'].' - '.$schedule['close']; ?></div>

                    <?php endif; ?>

                <?php endforeach; ?>

            </div>

        </div>

    <?php endforeach; ?>

	</div>

</div>
	
	
	