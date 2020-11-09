<?php
/**
 *
 */

$set_id        = ( isset( $set ) || ! empty( $set ) ) ? $set : wooopenclose()->get_active_schedule_id();
$schedules     = wooopenclose()->get_all_schedules( $set_id );
$new_schedules = array();
$temp_arr      = array();

foreach ( $schedules as $day_id => $schedule ) {

	$this_schedules = array();
	foreach ( $schedule as $this_schedule ) {
		$this_schedules[] = $this_schedule;
	}
	$temp_arr[ $day_id ] = serialize( $this_schedules );
}

echo '<pre>';
print_r( $temp_arr );
echo '</pre>';

//$index         = 0;
//$prev_schedule = '';

//foreach ( $temp_arr as $day_id => $schedule ) {
//	if ( $prev_schedule == $schedule ) {
//		$new_schedules[ $index ][] = $schedule;
//	}
//
//	$index ++;
//	$prev_schedule = $schedule;
//}


//$counts   = array_count_values( $temp_arr );
//$filtered = array_filter( $temp_arr, function ( $value ) use ( $counts ) {
//	return $counts[ $value ] > 1;
//} );


//$cnt      = array_count_values( $temp_arr );
//$newArray = array();
//foreach ( $temp_arr as $k => $v ) {
//	if ( $cnt[ $v ] > 1 ) {
//		$newArray[ $k ] = $v;
//	}
//}


echo '<pre>'; print_r( array_diff_assoc( $temp_arr, array_unique( $temp_arr ) ) ); echo '</pre>';

$arr = array_unique( array_diff_assoc( $temp_arr, array_unique( $temp_arr ) ) );



echo '<pre>'; print_r( $arr ); echo '</pre>';



$new_schedules[10000] = array();

$searched_array_10000 = array_search( $schedules[10000], $schedules );
$searched_array_10001 = array_search( $schedules[10001], $schedules );
$searched_array_10002 = array_search( $schedules[10002], $schedules );
$searched_array_10003 = array_search( $schedules[10003], $schedules );
$searched_array_10004 = array_search( $schedules[10004], $schedules );
$searched_array_10005 = array_search( $schedules[10005], $schedules );
$searched_array_10006 = array_search( $schedules[10006], $schedules );


//$schedule_10000[10000] = wooopenclose()->get_args_option( '10000', array(), $schedules );
//$schedule_10001[10001] = wooopenclose()->get_args_option( '10001', array(), $schedules );
//$schedule_10002[10002] = wooopenclose()->get_args_option( '10002', array(), $schedules );
//$schedule_10003[10003] = wooopenclose()->get_args_option( '10003', array(), $schedules );
//$schedule_10004[10004] = wooopenclose()->get_args_option( '10004', array(), $schedules );
//$schedule_10005[10005] = wooopenclose()->get_args_option( '10005', array(), $schedules );
//$schedule_10006[10006] = wooopenclose()->get_args_option( '10006', array(), $schedules );


//$schedules_new = array_intersect( $schedule_10000, $schedule_10001, $schedule_10002, $schedule_10003, $schedule_10004, $schedule_10005, $schedule_10006 );
//$schedules_new = array_diff( $schedule_10000, $schedule_10001, $schedule_10002, $schedule_10003, $schedule_10004, $schedule_10005, $schedule_10006 );

