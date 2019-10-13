<?php
/*
* @Author 		pluginbazar
* Copyright: 	2015 pluginbazar
*/

if ( ! defined('ABSPATH')) exit;  // if direct access 

global $wooopenclose;

$woc_hours_meta = get_post_meta( $post->ID, 'woc_hours_meta', true );
$woc_message    = isset( $woc_hours_meta['woc_message'] ) ? $woc_hours_meta['woc_message'] : '';


?>


<div class='woc_section'>
    <div class='woc_section_inline woc_section_title'><?php echo __('Working hour Label', 'woc-open-close');?></div>
    <div class="woc_section_inline woc_section_hint hint--top" aria-label="<?php echo __('Define a title for this working hour', 'woc-open-close');?>">?</div>
    <div class='woc_section_inline woc_section_inputs'>
        <input type="text" name="post_title" value="<?php echo get_the_title(); ?>" placeholder="<?php _e('Business hour', 'woc-open-close' ); ?>" />
    </div>
</div>


<div class='woc_section'>
    <div class='woc_section_inline woc_section_title'><?php echo __('Working schedules', 'woc-open-close');?></div>
    <div class="woc_section_inline woc_section_hint hint--top" aria-label="<?php echo __('Update working schedules data here', 'woc-open-close');?>">?</div>
    <div class='woc_section_inline woc_section_inputs woc_days'>
        
        <?php foreach( $wooopenclose->get_days() as $day_id => $day ) : ?>

        <div class="woc_day <?php echo $day_id; ?>" id='<?php echo $day_id; ?>'>
            <div class="woc_day_header"><?php echo isset( $day['label'] ) ? $day['label'] : ''; ?></div>
            <div class="woc_day_content">
                <div class='woc_repeats'>
                <?php
                    $schedules = isset( $woc_hours_meta[ $day_id ] ) ? $woc_hours_meta[ $day_id ] : array();
                    foreach( $schedules as $unique_id => $schedule ) {

                        echo $wooopenclose->generate_woc_schedule( array_merge( array( 'day_id' => $day_id, 'unique_id' => $unique_id ), $schedule ) );
                    }
                ?>
                </div>
                <div class="button woc_add_schedule" day-id="<?php echo $day_id; ?>"><?php _e('Add New', 'woc-open-close'); ?> <i class="icofont icofont-plus"></i></div>
            </div>
        </div>

        <?php endforeach; ?>

    </div>
</div>

<div class='woc_section'>
    <div class='woc_section_inline woc_section_title'><?php echo __('Store close message', 'woc-open-close');?></div>
    <div class="woc_section_inline woc_section_hint hint--top" aria-label="<?php echo __('Write your custom message what your visitors will see', 'woc-open-close');?>">?</div>
    <div class='woc_section_inline woc_section_inputs'>

        <textarea name="woc_hours_meta[woc_message]" rows="5" placeholder="<?php _e('Offline ! We will start taking orders in %countdown%', 'woc-open-close' ); ?>"><?php echo $woc_message; ?></textarea>

    </div>
</div>



