SELECT @UserTrackgID := configuration_group_id 
FROM configuration_group where configuration_group_title LIKE '%User Tracking%';

UPDATE configuration SET configuration_value = '1.4.5', last_modified = NOW()
WHERE configuration_group_id = @UserTrackgID AND configuration_key = 'CONFIG_USER_TRACKING_VERSION';