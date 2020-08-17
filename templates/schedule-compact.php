<?php
/**
 *
 */

//$set_id    = ( isset( $set ) || ! empty( $set ) ) ? $set : wooopenclose()->get_active_schedule_id();
//$schedules = wooopenclose()->get_all_schedules( $set_id );

echo '<pre>'; print_r( wooopenclose()->get_days() ); echo '</pre>';
