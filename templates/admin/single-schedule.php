<?php
/**
 * Display single schedule in Meta box
 *
 * @var array $args | comes from template function
 */

$unique_id = wooopenclose()->get_args_option( 'unique_id', time() . rand( 1, 1000 ) );
$day_id    = wooopenclose()->get_args_option( 'day_id' );
$open      = wooopenclose()->get_args_option( 'open' );
$close     = wooopenclose()->get_args_option( 'close' );


?>

<div class="woc_repeat"
     data-day-id="<?php echo esc_attr( $day_id ); ?>"
     data-open="<?php echo esc_attr( $open ); ?>"
     data-close="<?php echo esc_attr( $close ); ?>"
     data-unique-id="<?php echo esc_attr( $unique_id ); ?>">

    <label for="woc_tp_start_<?php echo esc_attr( $unique_id ); ?>"><?php esc_html_e( 'Start time', 'woc-open-close' ); ?></label>
    <input type="text"
           name="woc_hours_meta[<?php echo esc_attr( $day_id ); ?>][<?php echo esc_attr( $unique_id ); ?>][open]"
           value="<?php echo esc_attr( $open ); ?>"
           autocomplete="off"
           id="woc_tp_start_<?php echo esc_attr( $unique_id ); ?>"
           placeholder="<?php echo esc_attr( '08:00 AM' ); ?>"/>

    <label for="woc_tp_end_<?php echo esc_attr( $unique_id ); ?>"><?php esc_html_e( 'End time', 'woc-open-close' ); ?></label>
    <input type="text"
           name="woc_hours_meta[<?php echo esc_attr( $day_id ); ?>][<?php echo esc_attr( $unique_id ); ?>][close]"
           value="<?php echo esc_attr( $close ); ?>"
           autocomplete="off"
           id="woc_tp_end_<?php echo esc_attr( $unique_id ); ?>"
           placeholder="<?php echo esc_attr( '06:00 PM' ); ?>"/>

    <span class="woc_repeat_actions woc_repeat_copy hint--top"
          aria-label="<?php esc_html_e( 'Copy to all other days', 'woc-open-close' ); ?>">
        <span class="dashicons dashicons-sort"></span>
    </span>

    <span class="woc_repeat_actions woc_repeat_sort hint--top"
          aria-label="<?php esc_html_e( 'Sort schedule', 'woc-open-close' ); ?>">
        <span class="dashicons dashicons-admin-page"></span>
    </span>

    <span class="woc_repeat_actions woc_repeat_remove hint--top"
          aria-label="<?php esc_html_e( 'Remove schedule', 'woc-open-close' ); ?>">
        <span class="dashicons dashicons-trash"></span>
    </span>
</div>

<script>
    jQuery('#woc_tp_start_<?php echo esc_attr( $unique_id ); ?>').timepicker({'timeFormat': 'h:i A', step: 1});
    jQuery('#woc_tp_end_<?php echo esc_attr( $unique_id ); ?>').timepicker({'timeFormat': 'h:i A', step: 1});
</script>