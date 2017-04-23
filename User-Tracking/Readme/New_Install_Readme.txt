Contribution:  User Tracking
Version:  V.1.4.3a

Designed for: Zen Cart v1.5 Release
Converted into Zen by: Dave Kennelly dave@open-operations.com
Updated by: mc12345678
Thanks to BlessIsaacola for helping correct the newinstallsql
License: under the GPL - See attached License for info.
Forum Support:  Only given via the forums, please. http://www.zen-cart.com/forum/showthread.php?t=35081

========================================================
NEW Installation Procedure:


1. The files in the contribution are arranged for Zen-Cart Version 1.5.x, just put them in the appropriate folder(s).

1-B: The file structure is ready to drop in. Rename the admin folder to match your custom admin folder directory and upload the files within it.

2. Login into the admin area, upload via Admin/Tools --> Install SQL Patches the file in Sql --> new_install_user_tracking.sql

3. File Modifications: 
	3-1- to allow tracking pages loaded by visitors to the site, add the following line to the end of /includes/templates/YOUR TEMPLATE/common/tpl_footer.php.  If the file does not exist in your template, then copy the common/tpl_footer.php file from the default_template into the folder named for your chosen template(s).  (This is the recommended location to store changes to Zen Cart, reportedly to be easier to upgrade when changes come out or if it is desired to return to basic settings.)

<?php $zco_notifier->notify('NOTIFY_FOOTER_END'); ?>

	3-2- To allow tracking pages loaded in the admin area, add the following to the end of /YOUR_ADMIN/includes/footer.php.  

<?php $zco_notifier->notify('NOTIFY_ADMIN_FOOTER_END'); ?>

4. Go to admin of store (ZenCart), in admin/tools you should see user tracking config, by clicking on this you should see:

User Tracking Configuration  
 
Title Default Value
User Tracking Visitors true   
User Tracking (ADMIN) true   
User Tracking (exclude this IP-Address) your IP   
User Tracking (Session Limit) 50   
User Tracking (Admin User Can Delete) true
User Tracking (Purge this Number) 3
User Tracking (Purge Units) Days
User Tracking (Type of User Interaction to Record) 1
User Tracking (your favorite WHOIS URL) http://www.dnsstuff.com/tools/whois.ch?ip=   
User Tracking (Show Product Category when tracking product clicks) true   

5- in admin/tools click on user tracking, you should see:

User Tracking Start: 

This tool allows for you to see the click patterns of the users through your site, organized by sessions. This data can be very valuable to those looking for ways/areas to improve your site by watching how customers actually use it. You can surf back and forth through the days by using the link below.

7- troubleshooting:
7-1- first check again if all files are correctly located in the zen directories.
7-2- go to phpmyadmin, in table, configuration, delete all values related to user tracker, in table configuration-group, delete the table user tracking
7-3- try install sql package with alternative method (if the first time was phpmyadmin, so this time do with zen admin add sql patch tools)
7-4- check again if you have added one modification to both files, tpl.footer.php and footer.php
7-5- still problem, take a look at forum (forum address at the top of this readme) and submit your question.
 