<?php

/**
 * Description of class.user_tracking
 *
 * @author mc12345678
 */

if (!defined('IS_ADMIN_FLAG')) {
	die('Illegal Access');
} 

class user_tracking_admin extends base {
	function user_tracking_admin() {
		global $zco_notifier;
		$zco_notifier->attach($this, array('NOTIFY_ADMIN_FOOTER_END')); 
	}	

	function update(&$callingClass, $notifier, $paramsArray) {
		if ($notifier == 'NOTIFY_ADMIN_FOOTER_END') {
			if (ADMIN_CONFIG_USER_TRACKING == 'true') { zen_update_user_tracking(); }
		}
	}
	
}

?>
