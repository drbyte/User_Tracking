NEW Installation Procedure:

Forum Support URL: http://www.zen-cart.com/forum/showthread.php?t=35081

1. The files in the contribution are arranges to zen 1.50 directory, just put them in the appropriate places

1-B: File structure is ready to drop in. Rename the (admin) to match your custom admin folder directory and upload the files within it.

2. Install sql patch file, preferabely by phpMyAdmin than add sql patch of zen (as recommended in the forum)

3. File Modifications: 3-1- add the following to the end of /includes/templates/ YOUR TEMPLATE /common/tpl_footer.php

<?php if (ZEN_CONFIG_USER_TRACKING == 'true') { zen_update_user_tracking(); } ?>

3.B add if you want to track admin pages viewed add the following to the end of /admin/includes/footer.php

<?php if (ADMIN_CONFIG_USER_TRACKING == 'true') { zen_update_user_tracking(); } ?>

4. Login into the admin upload via Install SQL Patches the file in Sql --> new_install_user_tracking.sql

5. Go to admin of store (ZenCart), in admin/configuration and or in admin/tools you should see user tracking config, by clicking on this you should see:

User Tracking Configuration  
 
Title Value Action  
User Tracking Visitors true   
User Tracking (ADMIN) true   
User Tracking (exclude this IP-Address) your IP   
User Tracking (Session Limit) 50   
User Tracking (your favorite WHOIS URL) http://www.dnsstuff.com/tools/whois.ch?ip=   
User Tracking (Show Product Category when tracking product clicks) true   
User Tracking Visitors 

Check the Customers/Guests behaviour ? (each click will be recorded) 

Date Added: 09/02/2003 
Last Modified: 03/03/2003 
5- in admin/tools click on user tracking, you should see:

User Tracking Start: 

This tool allows for you to see the click patterns of the users through your site, organized by sessions. This data can be very valuable to those looking for how to improve your site by watching how customers actually use it. You can surf back and forth through the days by using the link below.
SELECT VIEW: Back to Feb 15, 2007 

Now displaying the latest 50 sessions of this 24 hour period. You can also purge all records past the last 72 hours of data.

Delete all info from IP-Address your IP purge all records 