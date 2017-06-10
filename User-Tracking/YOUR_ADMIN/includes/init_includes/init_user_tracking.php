<?php
/**
 * @package functions
 * @copyright Copyright 2003-2014 Zen Cart Development Team
 * @copyright Portions Copyright 2003 osCommerce
 * @license http://www.zen-cart.com/license/2_0.txt GNU Public License V2.0
 * @version $Id: $
 */
if (!defined('IS_ADMIN_FLAG')) {
    die('Illegal Access');
}

$module_constant = 'USER_TRACKING'; // This should be a UNIQUE name followed by _VERSION for convention
$module_installer_directory = DIR_FS_ADMIN . 'includes/installers/user_tracking'; // This is the directory your installer is in, usually this is lower case
$module_name = "User Tracking"; // This should be a plain English or Other in a user friendly way
$admin_page = 'UserTracking';
$zencart_com_plugin_id = 159; // from zencart.com plugins - Leave Zero not to check
//Just change the stuff above... Nothing down here should need to change


$configuration_group_id = '';
if (defined('CONFIG_' . $module_constant . '_VERSION')) {
    $current_version = constant('CONFIG_' . $module_constant . '_VERSION');
} else {
    $current_version = "0.0.0";

    $installed = $db->Execute("SELECT configuration_group_id FROM " . TABLE_CONFIGURATION_GROUP . " WHERE configuration_group_title = '" . $module_name . " Config'");
    if ($installed->EOF || $installed->RecordCount() == 0)
    {
      $db->Execute("INSERT INTO " . TABLE_CONFIGURATION_GROUP . " (configuration_group_title, configuration_group_description, sort_order, visible) VALUES ('" . $module_name . " Config', 'Set " . $module_name . " Configuration Options', '1', '1');");
      $configuration_group_id = $db->Insert_ID();
    } else {
      $configuration_group_id = $installed->fields['configuration_group_id'];
      $installed = $db->Execute("SELECT configuration_value FROM " . TABLE_CONFIGURATION . " WHERE configuration_key = 'CONFIG_" . $module_constant . "_VERSION' LIMIT 1;");
    }

    $db->Execute("UPDATE " . TABLE_CONFIGURATION_GROUP . " SET sort_order = " . $configuration_group_id . " WHERE configuration_group_id = " . $configuration_group_id . ";");

    if ($installed->EOF || $installed->RecordCount() == 0)
    {
      $db->Execute("INSERT INTO " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added, use_function, set_function) VALUES
                      ('" . $module_name . " (Version Installed)', 'CONFIG_" . $module_constant . "_VERSION', '0.0.0', 'Version installed:', " . $configuration_group_id . ", 0, NOW(), NULL, 'zen_cfg_select_option(array(\'0.0.0\'),');");
    }
}
if ($configuration_group_id == '') {
    $config = $db->Execute("SELECT configuration_group_id FROM " . TABLE_CONFIGURATION . " WHERE configuration_key= '" . $module_constant . "'");
    $configuration_group_id = $config->fields['configuration_group_id'];
}

$installers = scandir($module_installer_directory, 1);

$file_extension = substr($PHP_SELF, strrpos($PHP_SELF, '.'));
$file_extension_len = strlen($file_extension);

while (substr($installers[0], strrpos($installers[0], '.')) != $file_extension || preg_match('~^[^\._].*\.php$~i', $installers[0]) <= 0 || $installers[0] == 'empty.txt') {
  unset($installers[0]);
  if (sizeof($installers) == 0) {
    break;
  }
  $installers = array_values($installers);
}


if (sizeof($installers) > 0) {
    $newest_version = $installers[0];
    $newest_version = substr($newest_version, 0, -1 * $file_extension_len);

    sort($installers);
    if (version_compare($newest_version, $current_version) > 0) {
        foreach ($installers as $installer) {
            if (substr($installer, strrpos($installer, '.')) == $file_extension && (preg_match('~^[^\._].*\.php$~i', $installer) > 0 || $installer != 'empty.txt')) {
                if (version_compare($newest_version, substr($installer, 0, -1 * $file_extension_len)) >= 0 && version_compare($current_version, substr($installer, 0, -1 * $file_extension_len)) < 0) {
                    include($module_installer_directory . '/' . $installer);
                    $current_version = str_replace("_", ".", substr($installer, 0, -1 * $file_extension_len));
                    $db->Execute("UPDATE " . TABLE_CONFIGURATION . " SET configuration_value = '" . $current_version . "', set_function = 'zen_cfg_select_option(array(\'" . $current_version . "\'),' WHERE configuration_key = 'CONFIG_" . $module_constant . "_VERSION' LIMIT 1;");
                    $messageStack->add("Installed " . $module_name . " v" . $current_version, 'success');
                }
            }
        }
    }
}


if (SHOW_VERSION_UPDATE_IN_HEADER && !function_exists('plugin_version_check_for_updates')) {
    function plugin_version_check_for_updates($fileid = 0, $version_string_to_check = '') {
        if ($fileid == 0){
            return FALSE;
        }
        $new_version_available = FALSE;
        $lookup_index = 0;
        $url = 'https://www.zen-cart.com/downloads.php?do=versioncheck' . '&id=' . (int) $fileid;
        $data = json_decode(file_get_contents($url), true);
        if (!$data || !is_array($data)) return false;
        // compare versions
        if (version_compare($data[$lookup_index]['latest_plugin_version'], $version_string_to_check) > 0) {
            $new_version_available = TRUE;
        }
        // check whether present ZC version is compatible with the latest available plugin version
        if (!in_array('v' . PROJECT_VERSION_MAJOR . '.' . PROJECT_VERSION_MINOR, $data[$lookup_index]['zcversions'])) {
            $new_version_available = FALSE;
        }
        if ($version_string_to_check == true) {
            return $data[$lookup_index];
        } else {
            return FALSE;
        }
    }
}

// Version Checking 
if ($zencart_com_plugin_id != 0 && SHOW_VERSION_UPDATE_IN_HEADER && (!defined($module_constant . '_PLUGIN_CHECK') || constant($module_constant . '_PLUGIN_CHECK'))) {
    $new_version_details = plugin_version_check_for_updates($zencart_com_plugin_id, $current_version);
    if ($_GET['gID'] == $configuration_group_id && $new_version_details != FALSE) {
        $messageStack->add("Version ".$new_version_details['latest_plugin_version']." of " . $new_version_details['title'] . ' is available at <a href="' . $new_version_details['link'] . '" target="_blank">[Details]</a>', 'caution');
    }
}
 