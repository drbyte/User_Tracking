<?php

/**
 * Description of class.user_tracking
 *
 * @author mc12345678
 */
class user_tracking extends base {
  function __construct() {
    $this->attach($this, array('NOTIFY_FOOTER_END')); 
	}	

	function update(&$callingClass, $notifier, $paramsArray) {
		if ($notifier == 'NOTIFY_FOOTER_END') {
			/*Begin added/modified in version 1.4.3 1 of 1 */
			if (ZEN_CONFIG_USER_TRACKING == 'true' && CONFIG_USER_TRACKING_TRACK_TYPE_RECORD == 1 && function_exists('zen_update_user_tracking')) { zen_update_user_tracking(); }
			if (ZEN_CONFIG_USER_TRACKING == 'true' && CONFIG_USER_TRACKING_TRACK_TYPE_RECORD == 2 && function_exists('zen_update_user_tracking') && $session_started) { zen_update_user_tracking(); }
			if (ZEN_CONFIG_USER_TRACKING == 'true' && CONFIG_USER_TRACKING_TRACK_TYPE_RECORD == 3 && function_exists('zen_update_user_tracking') && !$spider_flag) { zen_update_user_tracking(); }			
			if (ZEN_CONFIG_USER_TRACKING == 'true' && CONFIG_USER_TRACKING_TRACK_TYPE_RECORD == 4 && function_exists('zen_update_user_tracking')) { zen_update_user_tracking(); }			
			/*End added/modified in version 1.4.3 1 of 1 */
		}
	}
	
}
