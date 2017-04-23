/* Tables need to be ensured to include the prefix if it exists. */

INSERT INTO admin_pages (page_key, language_key, main_page, page_params, menu_key, display_on_menu, sort_order) VALUES ('UserTracking', 'BOX_TOOLS_USER_TRACKING', 'FILENAME_USER_TRACKING', '', 'tools', 'Y', 10000); 

INSERT INTO admin_pages (page_key, language_key, main_page, page_params, menu_key, display_on_menu, sort_order) VALUES ('UserTrackingConfig', 'BOX_TOOLS_USER_TRACKING_CONFIG', 'FILENAME_USER_TRACKING_CONFIG', '', 'tools', 'Y', 10001);

DROP TABLE IF EXISTS user_tracking;
CREATE TABLE user_tracking (
  `customer_id` int(11) default NULL,
  `click_id` int(11) default NULL,
  `full_name` varchar(64) NOT NULL default '',
  `session_id` varchar(32) NOT NULL default '',
  `ip_address` varchar(15) NOT NULL default '',
  `time_entry` varchar(14) NOT NULL default '',
  `time_last_click` varchar(14) NOT NULL default '',
  `last_page_url` varchar(128) NOT NULL default '',
  `referer_url` varchar(254) NOT NULL default '',
  `page_desc` varchar(64) NOT NULL default '',
  `customers_host_address` varchar(64) NOT NULL default ''
) ENGINE=MyISAM;


SELECT @UserTrackgID := configuration_group_id 
FROM configuration_group where configuration_group_title LIKE '%User Tracking%';

DELETE FROM configuration where configuration_group_id = @UserTrackgID; 
/* DELETE FROM configuration where configuration_group_id = '999'; Desire is to replace 999 with the next configuration_group_id; however, this may require revision to the base code to support*/
INSERT INTO configuration_group (`configuration_group_id`, `configuration_group_title`, `configuration_group_description`, `sort_order`, `visible`) VALUES (0, 'User Tracking Config', 'User Tracking', '', 1);

SELECT @UserTrackgID := configuration_group_id 
FROM configuration_group where configuration_group_title LIKE '%User Tracking%';

UPDATE configuration_group SET sort_order = @UserTrackgID WHERE configuration_group_id = @UserTrackgID;

INSERT INTO configuration VALUES (0, 'User Tracking Visitors', 'ZEN_CONFIG_USER_TRACKING', 'true', 'Check the Customers/Guests behaviour ? (each click will be recorded)', @UserTrackgID, 1, '2003-03-03 11:19:26', '2003-02-09 21:20:07', NULL, 'zen_cfg_select_option(array(''true'', ''false''),');
INSERT INTO configuration VALUES (0, 'User Tracking (ADMIN)', 'ADMIN_CONFIG_USER_TRACKING', 'true', 'Check the ADMINs behaviour ? (each click will be recorded)', @UserTrackgID, 2, '2003-03-03 11:19:26', '2003-02-09 21:20:07', NULL, 'zen_cfg_select_option(array(''true'', ''false''),');
INSERT INTO configuration VALUES (0, 'User Tracking (exclude this IP-Address)', 'CONFIG_USER_TRACKING_EXCLUDED', 'your IP', 'Do NOT record this IP Address<br>(like webmaster/owners/Beta-testers)', @UserTrackgID, 10, '2003-03-04 23:08:38', '2003-02-09 21:20:07', NULL, NULL);
INSERT INTO configuration VALUES (0, 'User Tracking (Session Limit)', 'CONFIG_USER_TRACKING_SESSION_LIMIT', '50', 'Displaying the latest # sessions of this 24 hour period.<br>(SET to 999999 for unlimited per 24 hour period)<br>NOTE:<BR>Watch you space !', @UserTrackgID, 15, '2003-03-03 11:19:13', '2003-02-09 21:20:07', NULL, NULL);
INSERT INTO configuration (configuration_id, configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, last_modified, date_added, use_function, set_function) VALUES (0, 'User Tracking (Admin User Can Delete)', 'CONFIG_USER_TRACKING_ADMIN_CAN_DELETE', 'true', 'Allow Record Deletion to be Active?<br/>Setting this to true will override ENTRY and SESSION purges.<br/>Default <b>true</b><br/>', @UserTrackgID, 25, '2013-11-09 11:19:26', '2013-11-09 11:19:26', NULL, 'zen_cfg_select_option(array(''true'', ''false''),');
INSERT INTO configuration (configuration_id, configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, last_modified, date_added, use_function, set_function) VALUES (0, 'User Tracking (Admin User Can Delete Historical Data)', 'CONFIG_USER_TRACKING_ADMIN_CAN_DELETE_RECORDS', 'false', 'Allow Record Deletion of records older than now - the purge duration set below.<br/>This value is ignored if Admin User Can Delete is set to true.  Otherwise set this to true to allow deletion of visits.<br/>Default <b>false</b>.<br/>', @UserTrackgID, 26, '2013-11-09 11:19:26', '2013-11-09 11:19:26', NULL, 'zen_cfg_select_option(array(''true'', ''false''),');
INSERT INTO configuration (configuration_id, configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, last_modified, date_added, use_function, set_function) VALUES (0, 'User Tracking (Admin User Can Delete SESSIONS)', 'CONFIG_USER_TRACKING_ADMIN_CAN_DELETE_SESSIONS', 'false', 'Allow SESSION Deletion to be Active?<br/>This setting is ignored if Admin User Can Delete is set to true.<br/>Default <b>false</b><br/>', @UserTrackgID, 27, '2013-11-09 11:19:26', '2013-11-09 11:19:26', NULL, 'zen_cfg_select_option(array(''true'', ''false''),');
INSERT INTO configuration (configuration_id, configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, last_modified, date_added, use_function, set_function) VALUES (0, 'User Tracking (Admin User Can Delete IP)', 'CONFIG_USER_TRACKING_ADMIN_CAN_DELETE_IP', 'false', 'Allow Deletion of records that match the identified IP address?<br/>This setting is ignored if Admin User Can Delete is set to true.<br/>Default <b>false</b><br/>', @UserTrackgID, 28, '2013-11-09 11:19:26', '2013-11-09 11:19:26', NULL, 'zen_cfg_select_option(array(''true'', ''false''),');
INSERT INTO configuration (configuration_id, configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, last_modified, date_added, use_function, set_function) VALUES (0, 'User Tracking (Purge this Number)', 'CONFIG_USER_TRACKING_PURGE_NUMBER', '3', 'What is the number associated with purging before the current date/time?<br/><br/>An example would be to choose 3 here and units associated with days to delete data greater than 3 days before today.<br/>', @UserTrackgID, 30, '2013-11-09 23:08:38', '2013-11-09 23:08:38', NULL, NULL);
INSERT INTO configuration (configuration_id, configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, last_modified, date_added, use_function, set_function) VALUES (0, 'User Tracking (Purge Units)', 'CONFIG_USER_TRACKING_PURGE_UNITS', '1440', 'Pick the units associate with the periodicity to allow purging data.<br/><br/>60) Hours<br/>1440) Days<br/>10080) Weeks<br/>43200) Months (Based on 30 days)<br/>', @UserTrackgID, 31, '2013-11-09 23:08:38', '2013-11-09 23:08:38', NULL, 'zen_cfg_select_option(array(''60'', ''1440'',''10080'',''43200''),');
INSERT INTO configuration (configuration_id, configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, last_modified, date_added, use_function, set_function) VALUES (0, 'User Tracking (Type of User Interaction to Record)', 'CONFIG_USER_TRACKING_TRACK_TYPE_RECORD', '1', 'Type of user tracking to record?<br/><br/>1 - All visitors.<br/>2 - Visitors views where sessions have been started.<br/>3 - All users except bots/spiders ( requires Configuration->Sessions->Prevent Spider Sessions->true)  (Don\'t know if this works with Zen-Cart versions older than 1.2.6d)<br/><br/>Related to above: If you set Force Cookie Use->true, then at the first user entry, sessions do not start!!! And in variants 2 and 3, user-tracking will not have started. In this case you lose one click and do not log the refferal. But if this user is a returning user and has an old/previous zen cookie the session started and your tracking system will collect this info. So, the result beforehand is not logged or will not be known.<br/>', @UserTrackgID, 40, '2013-11-09 11:19:26', '2013-11-09 11:19:26', NULL, 'zen_cfg_select_option(array(''1'', ''2'',''3''),');
INSERT INTO configuration VALUES (0, 'User Tracking (your favorite WHOIS URL)', 'USER_TRACKING_WHOIS_URL', 'http://www.dnsstuff.com/tools/whois.ch?ip=', 'Put here you favorite WHOIS tracking site<br>(the IP will follow automaticly after this url)', @UserTrackgID, 50, '2003-03-03 11:19:13', '2003-03-11 11:40:01', NULL, NULL);
INSERT INTO configuration VALUES (0, 'User Tracking (Show Product Category when tracking product clicks)', 'ZEN_CONFIG_SHOW_USER_TRACKING_CATEGORY', 'true', 'Show Product Category when tracking product clicks', @UserTrackgID, 60, '2006-12-05 11:19:26', '2006-12-05 21:20:07', NULL, 'zen_cfg_select_option(array(''true'', ''false''),');
INSERT INTO configuration VALUES (0, 'User Tracking (Version Installed)', 'CONFIG_USER_TRACKING_VERSION', '1.4.3', 'Shows the version number associated with user tracking and should be updated with each upgrade', @UserTrackgID, 1000, '2013-11-10 04:19:26', '2013-11-18 04:19:26', NULL, NULL);