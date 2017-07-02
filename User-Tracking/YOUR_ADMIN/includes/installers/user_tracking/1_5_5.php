<?php
/**
 * @package functions
 * @copyright Copyright 2003-2017 Zen Cart Development Team
 * @copyright Portions Copyright 2003 osCommerce
 * @license http://www.zen-cart.com/license/2_0.txt GNU Public License V2.0
 * @version $Id: mc12345678 thanks to bislewl 6/9/2015
 */

$zc150 = (PROJECT_VERSION_MAJOR > 1 || (PROJECT_VERSION_MAJOR == 1 && substr(PROJECT_VERSION_MINOR, 0, 3) >= 5));
if ($zc150) { // continue Zen Cart 1.5.0
    // Updated installer to restore proper operation.

    $next_sort = $db->Execute('SELECT (MAX(sort_order) + 10) as sort FROM ' . TABLE_CONFIGURATION . ' WHERE configuration_id = ' . (int)$configuration_id);
    
    $db->Execute("INSERT IGNORE INTO " . TABLE_CONFIGURATION 
        . " (configuration_group_id, configuration_key, configuration_title, configuration_value, configuration_description, sort_order, date_added, use_function, set_function) 
        VALUES (" . (int) $configuration_group_id . ", 'CONFIG_USER_TRACKING_USER_FILTER_WORDS', 'User Tracking Filter Words', 'wp%20login%20php,%20action%20register', 'Identify comma separated words used in the url to filter a visitor, for example users that visit the login page, shopping cart, or those attempting to test system limits.<br />Default: wp%20login%20php,%20action%20register', " . (int)$next_sort . ", NOW(), NULL, NULL)");
    
} // END OF VERSION 1.5.x INSTALL
