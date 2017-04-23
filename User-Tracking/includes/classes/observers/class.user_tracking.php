<?php

/**
 * Description of class.user_tracking
 *
 * @author mc12345678
 */
class user_tracking extends base {
	function user_tracking() {
		global $zco_notifier;
		$zco_notifier->attach($this, array('NOTIFY_FOOTER_END')); 
	}	

	function update(&$callingClass, $notifier, $paramsArray) {
		if ($notifier == 'NOTIFY_FOOTER_END') {
			if (ZEN_CONFIG_USER_TRACKING == 'true') { zen_update_user_tracking(); }
		}
	}
	
}

?>
