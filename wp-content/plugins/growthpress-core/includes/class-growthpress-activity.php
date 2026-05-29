<?php
/**
 * GrowthPress Activity Logger
 */

if ( ! defined( 'ABSPATH' ) ) {
	return;
}

class GrowthPress_Activity {

    public static function log( $message ) {
        $logs = get_option( 'gp_activity_logs', array() );
        array_unshift( $logs, array(
            'time' => current_time('mysql'),
            'msg'  => $message
        ));
        update_option( 'gp_activity_logs', array_slice( $logs, 0, 20 ) ); // Keep last 20
    }

    public static function get_logs( $limit = 20 ) {
        $logs = get_option( 'gp_activity_logs', array() );
        return array_slice( $logs, 0, $limit );
    }
}
