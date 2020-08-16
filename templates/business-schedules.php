<?php
/*
* @Author 		pluginbazar
* Copyright: 	2015 pluginbazar
*/

defined( 'ABSPATH' ) || exit;

$layout = ( isset( $layout ) || ! empty( $layout ) ) ? $layout : 1;
$args   = ( isset( $args ) || ! empty( $args ) ) ? $args : array();

?>

<div class="wooopenclose-schedules-layout wooopenclose-layout-<?php echo esc_attr( $layout ); ?>">
	<?php woc_get_template( sprintf( 'schedules/layout-%s.php', $layout ), $args ); ?>
</div>