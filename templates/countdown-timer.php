<?php
/*
* @Author 		pluginbazar
* Copyright: 	2015 pluginbazar
*/

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}  // if direct access

$unique_id     = uniqid();
$style         = ( isset( $style ) || ! empty( $style ) ) ? $style : 1;
$dynamic_class = woc_is_open() ? 'woc-shop-open' : '';
$time_diff     = date( 'U', strtotime( wooopenclose()->get_next_time() ) ) - date( 'U' );

?>

<div id="woc-countdown-timer-<?php echo esc_attr( $unique_id ); ?>"
     class="woc-countdown-timer-<?php echo esc_attr( $style ); ?> <?php echo esc_attr( $dynamic_class ); ?>">
    <span style="display: none;" class="distance" data-distance="<?php echo esc_attr( $time_diff ); ?>"></span>
    <span class="hours"><span class="count-number">0</span><span class="count-text">Hours</span></span>
    <span class="minutes"><span class="count-number">0</span><span class="count-text">Minutes</span></span>
    <span class="seconds"><span class="count-number">0</span><span class="count-text">Seconds</span></span>
</div>

<script>
    (function ($, window, document) {
        "use strict";

        (function updateTime() {

            let timerArea = $("#woc-countdown-timer-<?php echo esc_attr( $unique_id ); ?>"),
                spanDistance = timerArea.find('span.distance'),
                distance = parseInt(spanDistance.data('distance')),
                spanHours = timerArea.find('span.hours > span.count-number'),
                spanMinutes = timerArea.find('span.minutes > span.count-number'),
                spanSeconds = timerArea.find('span.seconds > span.count-number'),
                days = 0, hours = 0, minutes = 0, seconds = 0;

            if (distance > 0) {
                days = Math.floor(distance / (60 * 60 * 24));
                hours = Math.floor((distance % (60 * 60 * 24)) / (60 * 60) + days * 24);
                minutes = Math.floor((distance % (60 * 60)) / (60));
                seconds = Math.floor((distance % (60)));
            }

            spanHours.html(hours);
            spanMinutes.html(minutes);
            spanSeconds.html(seconds);
            spanDistance.data('distance', distance - 1);

            setTimeout(updateTime, 1000);
        })();

    })(jQuery, window, document);

</script>