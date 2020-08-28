<?php
/**
 *
 */

$set_id        = ( isset( $set ) || ! empty( $set ) ) ? $set : wooopenclose()->get_active_schedule_id();
$schedules     = wooopenclose()->get_all_schedules( $set_id );
$schedules_new = array();
$schedule_prev = array();

foreach ( $schedules as $day_id => $schedule ) {



	echo '<pre>';
	print_r( $schedule );
	echo '</pre>';
}
