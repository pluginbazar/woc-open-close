<?php
/*
* @Author 		pluginbazar
* Copyright: 	2015 pluginbazar
*/

if (!defined('ABSPATH')) exit;  // if direct access

?>

<span id="hellobar-countdown-timer" class="d-none d-lg-inline-block">

    <span class="countdown-days">
        <span>4</span>days
    </span>

    <span class="countdown-hours">
        <span>21</span>hours
    </span>

    <span class="countdown-minutes">
        <span>50</span>minutes
    </span>

    <span class="countdown-seconds">
        <span>41</span>seconds
    </span>

</span>

<script>
    var cookie_expires = new Date('Jan 02 2019 23:59:59');
    var expired_on = cookie_expires.toString()
    var countDownDate = new Date(expired_on).getTime();

    var x = setInterval(function () {
        var now = new Date().getTime();
        var distance = countDownDate - now;

        var days = Math.floor(distance / (1000 * 60 * 60 * 24));
        var hours = Math.floor((distance % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60));
        var minutes = Math.floor((distance % (1000 * 60 * 60)) / (1000 * 60));
        var seconds = Math.floor((distance % (1000 * 60)) / 1000);

        document.getElementById("hellobar-countdown-timer").innerHTML = '<span class="countdown-days"><span>' + days + '</span>days</span><span class="countdown-hours"><span>' + hours + '</span>hours</span><span class="countdown-minutes"><span>' + minutes + '</span>minutes</span><span class="countdown-seconds"><span>' + seconds + '</span>seconds</span>';

        if (distance < 0) {
            clearInterval(x);
            document.getElementById("hellobar-countdown-timer").innerHTML = "EXPIRED";
        }
    }, 1000);

</script>


<style>
    #hellobar-countdown-timer > span {
        display: inline-block;
        font-size: 11px;
        text-transform: uppercase;
        font-weight: 600;
        line-height: 1;
        margin: 0 5px 0 1px;
        background: rgba(0, 0, 0, .4);
        color: #fff;
        padding: 5px;
        border-radius: 3px;
    }

    #hellobar-countdown-timer > span > span {
        display: block;
        line-height: 1;
        font-size: 14px;
        font-weight: 700;
        margin-bottom: 5px;
    }
</style>