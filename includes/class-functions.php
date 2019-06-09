<?php

/*
* @Author 		pluginbazar
* Copyright: 	2015 pluginbazar
*/

if ( ! defined('ABSPATH')) exit;  // if direct access 

class WOC_Functions{
	
	public function __construct(){
		
	}
	
	
	public function woc_days_array() {
        
		$days_array = array(
		
			'mon' => array(
					'name' => __('Monday',WOCTEXTDOMAIN),
					'icon' => apply_filters( 'woc_filters_icon_mon', '<i class="fa fa-sun-o"></i>' ),
				),
			'tue' => array(
					'name' => __('Tuesday',WOCTEXTDOMAIN),
					'icon' => apply_filters( 'woc_filters_icon_tue', '<i class="fa fa-sun-o"></i>' ),
				),
			'wed' => array(
					'name' => __('Wednesday',WOCTEXTDOMAIN),
					'icon' => apply_filters( 'woc_filters_icon_wed', '<i class="fa fa-sun-o"></i>' ),
				),
			'thu' => array(
					'name' => __('Thursday',WOCTEXTDOMAIN),
					'icon' => apply_filters( 'woc_filters_icon_thu', '<i class="fa fa-sun-o"></i>' ),
				),
			'fri' => array(
					'name' => __('Friday',WOCTEXTDOMAIN),
					'icon' => apply_filters( 'woc_filters_icon_fri', '<i class="fa fa-sun-o"></i>' ),
				),
			'sat' => array(
					'name' => __('Saturday',WOCTEXTDOMAIN),
					'icon' => apply_filters( 'woc_filters_icon_sat', '<i class="fa fa-sun-o"></i>' ),
				),
			'sun' => array(
					'name' => __('Sunday',WOCTEXTDOMAIN),
					'icon' => apply_filters( 'woc_filters_icon_sun', '<i class="fa fa-sun-o"></i>' ),
				),
		);
		
		return apply_filters( 'woc_filters_days_array', $days_array );	
	}
	
} new WOC_Functions();