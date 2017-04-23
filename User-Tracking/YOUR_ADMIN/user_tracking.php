<?php
//
// +----------------------------------------------------------------------+
// |zen-cart Open Source E-commerce                                       |
// +----------------------------------------------------------------------+
// | Copyright (c) 2003 The zen-cart developers                           |
// |                                                                      |
// | http://www.zen-cart.com/index.php                                    |
// |                                                                      |
// | Portions Copyright (c) 2003 osCommerce                               |
// +----------------------------------------------------------------------+
// | This source file is subject to version 2.0 of the GPL license,       |
// | that is bundled with this package in the file LICENSE, and is        |
// | available through the world-wide-web at the following url:           |
// | http://www.zen-cart.com/license/2_0.txt.                             |
// | If you did not receive a copy of the zen-cart license and are unable |
// | to obtain it through the world-wide-web, please send a note to       |
// | license@zen-cart.com so we can mail you a copy immediately.          |
// +----------------------------------------------------------------------+
//  $Id: usertracking 2004-12-1 dave@open-operations.com http://open-operations.com
//  UPDATED 2013-12-07 mc12345678 http://mc12345678.weebly.com
  require('includes/application_top.php');
  require(DIR_WS_CLASSES . 'currencies.php');
  $currencies = new currencies();
  require(DIR_WS_INCLUDES . 'geoip.inc'); // <- Updated file usually available from: https://raw.github.com/maxmind/geoip-api-php/tree/master/src/geoip.inc
  $gi = geoip_open(DIR_WS_INCLUDES . 'GeoIP.dat',GEOIP_STANDARD); // <- Updated file usually available from: http://geolite.maxmind.com/download/geoip/database/GeoLiteCountry/GeoIP.dat.gz

// Start User Tracking - Spider Mod 1 of 7 Copied from admin\whos_online.php Zen Cart V1.5.0
function zen_check_bot($checking) {
  if (empty($checking)) {
    return true;
  } else {
    return false;
  }
}

$boxes = array();

// End User Tracking - Spider Mod 1 of 7
$date_day = array();
$date_month = array();
$date_year = array();
//$date_day[] = array();
//$date_month[] = array();
//$date_year[] = array();

$today = getdate();
$today_month = (int)$today['mon'];
$today_year = (int)$today['year'];
if ($today_month == 0)
    {
    $today_month = 12;
    $today_year =  $today_year-1;
    }


if (isset($_POST['sdate_month'])) {
    $start_date_month_val = (int)$_POST['sdate_month'];
    $start_date_day_val = (int)$_POST['sdate_day'];
    $start_date_year_val = (int)$_POST['sdate_year'];
}
elseif (isset($_POST['time'])) {
    $start_date_year_val = (int)date("y", $_POST['time'])+2000;
    $start_date_month_val = (int)date("m", $_POST['time']);
    $start_date_day_val = (int)date("d", $_POST['time']);
} elseif (isset($_GET['time'])) {
    $start_date_year_val = (int)date("y", $_GET['time'])+2000;
    $start_date_month_val = (int)date("m", $_GET['time']);
    $start_date_day_val = (int)date("d", $_GET['time']);
}
else {
//    trigger_error ("initializing to today = " . $start_date_month_val, E_USER_WARNING );
    $start_date_year_val = (int)$today_year;
    $start_date_month_val = (int)$today_month;
    $start_date_day_val = (int)$today['mday'];
    }
// Start User Tracking - Spider Mod 2 of 7
if (isset($_POST['SpiderYes'])) {
  if ($_POST['SpiderYes'] == 'ShowSpiders') {
    $displaySpider = true;
  } elseif ($_POST['SpiderYes'] == 'HideSpiders') {
    $displaySpider = false;
  }
} else {
  $displaySpider = false;
}
if (isset($_POST['MinPick']) && (int)$_POST['MinPick'] > 0) {
  $MinValue = sprintf('%02d', (int)$_POST['MinPick']);
} else {
  $MinValue = sprintf('%02d', 1);
}

// End User Tracking - Spider Mod 2 of 7
for ($i=1; $i<32; $i++) {
  $date_day[] = array('id' => sprintf('%02d', $i), 'text' => sprintf('%02d', $i));
}

for ($i=1; $i<13; $i++) {
  $date_month[] = array('id' => sprintf('%02d', $i), 'text' => strftime('%B',mktime(0,0,0,$i,1,2000)));
}

for ($i=1; $i <10; $i++) {
  $min_vals[] = array('id' => sprintf('%02d', $i), 'text' => sprintf('%02d', $i));
}

$last_year = $today['year'] - 5;
$first_year = $today['year'];

for ($i=$first_year; $i > $last_year; $i--) {
  $date_year[] = array('id' => sprintf('%02d', $i), 'text' => sprintf('%02d', $i));
}

?>
<!doctype html public "-//W3C//DTD HTML 4.01 Transitional//EN">
<html <?php echo HTML_PARAMS; ?>>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=<?php echo CHARSET; ?>">
<title><?php echo TITLE; ?></title>
<link rel="stylesheet" type="text/css" href="includes/stylesheet.css">
<link rel="stylesheet" type="text/css" href="includes/cssjsmenuhover.css" media="all" id="hoverJS">
<script language="javascript" type="text/javascript" src="includes/menu.js"></script>
<script language="javascript" type="text/javascript" src="includes/general.js"></script>
<script type="text/javascript">
  <!--
  function init()
  {
    cssjsmenu('navbar');
    if (document.getElementById)
    {
      var kill = document.getElementById('hoverJS');
      kill.disabled = true;
    }
  }
  // -->
</script>
</head>
<body onload="init()">
<!-- header //-->
<?php require(DIR_WS_INCLUDES . 'header.php'); ?>
<!-- header_eof //-->

<?php 

  $heading = array();
  $heading[] = array('text' => HEADING_TITLE);

  $column = array();
  $column[] = array('params' => 'class="pageHeading"', 
                    'text' => HEADING_TITLE);
  $column[] = array('params'=>'class="main"', 
                    'text' => ENTRY_START_DATE);
  $column[] = array('params'=>'class="main"', 
                    'text' => zen_draw_pull_down_menu('sdate_month', $date_month, $start_date_month_val) . 
                              zen_draw_pull_down_menu('sdate_day', $date_day, $start_date_day_val) . 
                              zen_draw_pull_down_menu('sdate_year', $date_year, $start_date_year_val) . 
                              (zen_not_null(ENTRY_START_DATE_TEXT) ? '<span class="inputRequirement">' . ENTRY_START_DATE_TEXT . '</span>': '')
                   );
  $column[] = array('params' => 'class="Spiders"', 
                    'align' => 'left',
                    'text' => zen_draw_radio_field('SpiderYes', 'HideSpiders', $displaySpider == false, NULL, ( ($displaySpider == true) ? 'onClick="this.form.submit();"' : '' )) . TEXT_HIDE_SPIDERS . zen_draw_radio_field('SpiderYes', 'ShowSpiders', $displaySpider == true, NULL, ( ($displaySpider == false) ? 'onClick="this.form.submit();"' : '' )) . TEXT_SHOW_SPIDERS . (CONFIG_USER_TRACKING_TRACK_TYPE_RECORD == '3'? TEXT_OPTION3_SPIDER_HIDE : TEXT_SPIDER_HIDE_OTHERS)
                   );
  $column[] = array('params' => 'class="MinView"',
                    'align' => 'left',
                    'text' => TEXT_SHOW_MINIMUM_PAGE_VIEWS . zen_draw_pull_down_menu('MinPick', $min_vals, $MinValue, 'onchange="this.form.submit();"') 
                   );
  $column[] = array('params' => 'class="main"',
                    'align' => 'left',
                    'text' => zen_image_submit('button_report.gif', TEXT_BUTTON_REFRESH)
                  );  

  $row = array();
  $row[] = $column;
  
  $boxes['header'] = new box;
  $boxes['header']->table_width = '70%';
  $boxes['header']->table_cellpadding = '0';
  $boxes['table']['header'] = $boxes['header']->infoBox($heading, $row);


echo zen_draw_form('user_tracking_stats', FILENAME_USER_TRACKING, ''/*'onsubmit="return check_form(user_tracking_stats);"'*/, 'post', 'onsubmit="return check_form(user_tracking_stats);"') . zen_draw_hidden_field('action', 'process'); ?>
<table border="0" width="100%" cellspacing="4" cellpadding="4">
  <tr>
    <td width="100%" valign="top"><table border="0" width="100%" cellspacing="0" cellpadding="0">
      <tr>
        <td>
          <?php 
  echo $boxes['table']['header'];
  ?>

<!-- body //-->
<table border="0" width="100%" cellspacing="2" cellpadding="2">
      <tr>
        <td class="smallText">

<?php
  if (isset($_GET['purge']) && $_GET['purge'] == CONFIG_USER_TRACKING_PURGE_NUMBER && (CONFIG_USER_TRACKING_ADMIN_CAN_DELETE == 'true' || CONFIG_USER_TRACKING_ADMIN_CAN_DELETE_RECORDS == 'true' )) //v1.4.3 1 of 15 
  {
// JTD:10/27/05
//    $db->Execute("DELETE FROM " . TABLE_USER_TRACKING . " where time_last_click < '"  . (time() - ($purge * 3600))."'");
    $db->Execute('DELETE FROM ' . TABLE_USER_TRACKING . " WHERE time_last_click < '" . (time() - ($_GET['purge'] * 60 * CONFIG_USER_TRACKING_PURGE_UNITS))."'"); //v1.4.3 2 of 15 
    echo '<p><font color="red">' . TEXT_HAS_BEEN_PURGED . '</font></p>';
  }
  if (isset($_GET['delip']) && $_GET['delip'] == '1' && (CONFIG_USER_TRACKING_ADMIN_CAN_DELETE == 'true' || CONFIG_USER_TRACKING_ADMIN_CAN_DELETE_IP == 'true' )) //v1.4.3 3 of 15 
  {
 //   $db->Execute("DELETE FROM " . TABLE_USER_TRACKING . " WHERE ip_address = '" . CONFIG_USER_TRACKING_EXCLUDED . "'");
//    echo CONFIG_USER_TRACKING_EXCLUDED . ' has been deleted. <p>';
      echo '<p>';
      foreach(explode(",", CONFIG_USER_TRACKING_EXCLUDED) as $skip_ip)
      {
              $db->Execute('DELETE FROM ' . TABLE_USER_TRACKING . " WHERE ip_address = '" . (trim($skip_ip)) . "'");
              echo '<br />' . "\n" . CONFIG_USER_TRACKING_EXCLUDED . ' has been deleted.<br />';
      }
      echo '</p>';
    $delip='0';
  }
  if (isset($_GET['delsession']) && $_GET['delsession'] && (CONFIG_USER_TRACKING_ADMIN_CAN_DELETE == 'true' || CONFIG_USER_TRACKING_ADMIN_CAN_DELETE_SESSIONS == 'true' )) //v1.4.3 4 of 15 
  {
    $db->Execute("DELETE FROM " . TABLE_USER_TRACKING . " WHERE session_id = '" . $_GET['delsession'] . "'");
    echo $_GET['delsession'] . TEXT_HAS_BEEN_DELETED . ' has been deleted. <p>';
  }

  echo EXPLAINATION . "<p>";

  if (!isset($_POST['time']))
     $_POST['time'] = mktime (0,0,0, $start_date_month_val,$start_date_day_val,$start_date_year_val,-1);
  $time_frame = $_POST['time'];

// Start User Tracking - Ver 1.4.2 Mod 1 of 
  echo '<b>' . TEXT_SELECT_VIEW .': </b>';

  if (time() < $time_frame - 86400)
  {
    $newTime = time();
    $newTime = ($time_frame - ((int)(($time_frame - $newTime) / 86400) + 1) * 86400 );
    echo '<a href="' . zen_href_link(FILENAME_USER_TRACKING, 'time=' . $newTime, $request_type) . '">' . TEXT_BACK_TO . ' ' . TEXT_TODAY . ' </a> | ';
  }
// End User Tracking - Ver 1.4.2 Mod 1 of 

  echo '<a href="' . zen_href_link(FILENAME_USER_TRACKING, 'time=' . ($time_frame - 86400), $request_type) . '">' . TEXT_BACK_TO . ' ' . date("l M d, Y", $time_frame - 86400) . '</a> ';

  if (time() > $time_frame + 86400)
  {
    echo '| <a href="' . zen_href_link(FILENAME_USER_TRACKING,  'time=' . ($time_frame + 86400) , $request_type) . '">' . TEXT_FORWARD_TO . date("l M d, Y", $time_frame + 86400) . '</a>';
  }

// Start User Tracking - Ver 1.4.2 Mod 2 of 
  if (time() > $time_frame + 172800)
  {
//v1.4.3 8 of 15 
    $newTime = time();
    $newTime = ($time_frame + ((int)(($newTime - $time_frame) / 86400)) * 86400 );
    //echo $newTime . '">' . TEXT_FORWARD_TO . ' ' . TEXT_TODAY . ' </a>';
    echo ' | <a href="' . zen_href_link(FILENAME_USER_TRACKING,  'time=' . $newTime, $request_type) . '">' . TEXT_FORWARD_TO . ' ' . TEXT_TODAY . ' </a>';
   }
// End User Tracking - Ver 1.4.2 Mod 2 of 
if (CONFIG_USER_TRACKING_ADMIN_CAN_DELETE == 'true' || CONFIG_USER_TRACKING_ADMIN_CAN_DELETE_RECORDS == 'true'){  //v1.4.3 9 of 15 
  echo "<p>" . TEXT_DISPLAY_START . CONFIG_USER_TRACKING_SESSION_LIMIT . TEXT_DISPLAY_END;
  //Begin of v1.4.3 10 of 15 
  echo TEXT_PURGE_START . ' <a href="' . zen_href_link(FILENAME_USER_TRACKING, 'purge='. CONFIG_USER_TRACKING_PURGE_NUMBER, $request_type) . '">' . TEXT_PURGE_RECORDS . '</a> ' . TEXT_PURGE_END. '<p>'; //</font>
}
if ((CONFIG_USER_TRACKING_ADMIN_CAN_DELETE == 'true' || CONFIG_USER_TRACKING_ADMIN_CAN_DELETE_IP == 'true') && (defined('CONFIG_USER_TRACKING_IP_EXCLUSION') ? (CONFIG_USER_TRACKING_IP_EXCLUSION != 'your IP' && CONFIG_USER_TRACKING_IP_EXCLUSION != '') : true)){
  echo TEXT_DELETE_IP . CONFIG_USER_TRACKING_EXCLUDED . ' <a href="' . zen_href_link(FILENAME_USER_TRACKING, 'delip=1', $request_type) . '">'. TEXT_PURGE_RECORDS. '</a>'; //</font>
}
  echo "<p>";
  //End of v1.4.3 10 of 15 
  
  $whos_online =
      $db->Execute("select customer_id, full_name, ip_address, time_entry, time_last_click, last_page_url, page_desc," .
                   " session_id, referer_url, customers_host_address from " . TABLE_USER_TRACKING  .
                   " where time_entry > " . $time_frame .
                   " and time_entry < " . ($time_frame + 86400) .
                   " order by time_last_click desc");


  $results = 0;
  //Begin of v1.4.3 11 of 15 
  $spiderCount = 0;
  $num_sessions = 0;
  //End of v1.4.3 11 of 15 
  while (!$whos_online->EOF) {
    $user_tracking[$whos_online->fields['session_id']]['session_id']=$whos_online->fields['session_id'];
    $user_tracking[$whos_online->fields['session_id']]['ip_address']=$whos_online->fields['ip_address'];
    $user_tracking[$whos_online->fields['session_id']]['customers_host_address']=$whos_online->fields['customers_host_address'];
    $user_tracking[$whos_online->fields['session_id']]['referer_url']=$whos_online->fields['referer_url'];
    $user_tracking[$whos_online->fields['session_id']]['customer_id']=$whos_online->fields['customer_id'];

    if ($whos_online->fields['full_name'] != 'Guest')
      $user_tracking[$whos_online->fields['session_id']]['full_name'] = '<font color="0000ff"><b>' . $whos_online->fields['full_name'] . '</b></font>';

    $user_tracking[$whos_online->fields['session_id']]['last_page_url'][$whos_online->fields['time_last_click']] = $whos_online->fields['last_page_url'];

    $user_tracking[$whos_online->fields['session_id']]['page_desc'][$whos_online->fields['time_last_click']] = $whos_online->fields['page_desc'];

    if (($user_tracking[$whos_online->fields['session_id']]['time_entry'] > $whos_online->fields['time_entry']) ||
      (!$user_tracking[$whos_online->fields['session_id']]['time_entry']))
      $user_tracking[$whos_online->fields['session_id']]['time_entry'] = $whos_online->fields['time_entry'];
     
    if (($user_tracking[$whos_online->fields['session_id']]['end_time'] < $whos_online->fields['time_entry']) ||
      (!$user_tracking[$whos_online->fields['session_id']]['end_time']))
      $user_tracking[$whos_online->fields['session_id']]['end_time'] = $whos_online->fields['time_entry'];

    $results ++;

    $whos_online->MoveNext();
  }

  //Begin of v1.4.3 12 of 15 
  $listed = 0;
  if ($results && is_array($user_tracking) == true) {
    foreach ($user_tracking as $ut)
    {
      $is_a_bot=zen_check_bot($ut['session_id']);
      if ($is_a_bot) {
        $spiderCount ++;
      } else {
        $num_sessions ++;
      }
      $listed++;
    }
  }
  //End of v1.4.3 12 of 15 
?>




       <tr>
        <td class="smallText" colspan="7"><?php echo sprintf(TEXT_NUMBER_OF_CUSTOMERS, $results); /*Start User Tracking - Spider Mod 13 of 15  */ echo sprintf(TEXT_NUMBER_OF_USERS, $num_sessions); echo sprintf(TEXT_NUMBER_OF_SPIDERS, $spiderCount); /*End User Tracking - Spider Mod 13 of 15 */ ?></td>
       </tr>





<!--        <table border="0" width="100%" cellspacing="0" cellpadding="0"> -->
    <tr>
      <td valign="top" align="center"><table border="0" width="95%" cellspacing="0" cellpadding="2">

<?php

  // now let's display it

  $listed=0;
  //Begin of v1.4.3 14 of 15 
  $num_sessions = 0;
  $spiderCount = 0;
    //End of v1.4.3 14 of 15 
  
  if ($results && is_array($user_tracking) == true) {
  /* Begin v1.4.3b  (Moved statement to within test) */
     // reset($user_tracking);
  /* End v1.4.3b */
foreach ($user_tracking as $ut) {
    if ($listed++ >= CONFIG_USER_TRACKING_SESSION_LIMIT) {
      break;
    }
// Start User Tracking - Spider Mod 4 of 7
    $is_a_bot=zen_check_bot($ut['session_id']);
    if ($is_a_bot) {
      $spiderCount ++;
    } else {
      $num_sessions ++;
    }
    if ($displaySpider || (!$displaySpider && !$is_a_bot) || !$is_a_bot ) { 
// End User Tracking - Spider Mod 4 of 7
      if ($MinValue > count($ut['last_page_url'])) {
        continue;
      }
      $time_online = (time() - $ut['time_entry']);
      if ( ((!$_GET['info']) || (@$_GET['info'] == $ut['session_id'])) && (!$info) ) {
        $info = $ut['session_id'];
      }
      if ($ut['full_name'] == "")
        $ut['full_name'] = "Guest";
      
      if($ut['full_name'] != "Guest")
      {
        $stripped_name = strip_tags($ut['full_name']);
        $exploded_name = explode(" ", $stripped_name);
        $customer_link = "<a href='" . zen_href_link(FILENAME_CUSTOMERS, "search=" . end($exploded_name), $request_type) . "'>" . $ut['full_name'] . "</a>";
      } else {
        $customer_link = $ut/*['value']*/['full_name'];
      }

      /* Generate the time table */
      $dit=time() - $ut['end_time']; // Idle time
      $dtt=$ut['end_time'] - $ut['time_entry']; // Total Time
      
      $column = array();      
      $row = array();
      $column[] = array('params' => 'class="dataTableContent"',
                        'align' => 'right" valign="top',
                        'text' => '<b>' . TABLE_HEADING_ENTRY_TIME . '</b>'
                       );
      $column[] = array('params' => 'class="dataTableContent" colspan="2" valign="top"',
                        'text' => date('H:i:s', $ut['time_entry'])
                       );
      $column[] = array('params' => 'class="dataTableContent"',
                        'align' => 'right" valign="top',
                        'text' => '<b>' . TEXT_IDLE_TIME . '</b>'
                       );
      $column[] = array('params' => 'class="dataTableContent" colspan="2" valign="top"',
                        'text' => sprintf("%02d:%02d:%02d",$dit/3600, ($dit % 3600)/60, $dit % 60)
                       );
      $row[] = $column;
      $column = array();

      $column[] = array('params' => 'class="dataTableContent"',
                        'align' => 'right" valign="top',
                        'text' => '<b>' . TABLE_HEADING_END_TIME . '</b>'
                       );
      $column[] = array('params' => 'class="dataTableContent" colspan="2" valign="top"',
                        'text' => date('H:i:s', $ut['end_time'])
                       );
      $column[] = array('params' => 'class="dataTableContent"',
                        'align' => 'right" valign="top',
                        'text' => '<b>' . TEXT_TOTAL_TIME . '</b>'
                       );
      $column[] = array('params' => 'class="dataTableContent" colspan="2" valign="top"',
                        'text' => sprintf("%02d:%02d:%02d",$dtt/3600, ($dtt % 3600)/60, $dtt % 60)
                       );
      $row[] = $column;
      $header = array('text' => '');

      $boxes['time_table'] = new box;
      $boxes['table']['time_table'] = $boxes['time_table']->infoBox($header, $row);

      echo '
       <tr class="dataTableHeadingRow">
        <td class="dataTableHeadingContent" colspan="5">' . TABLE_HEADING_SESSION_ID . '</td>
        <td class="dataTableHeadingContent" colspan="1" width="150">' . TEXT_USER_SHOPPING_CART . '</td>
       </tr>';


      echo '              <tr class="dataTableRowSelected">' . "\n";

     //Begin of v1.4.3 15 of 15 ?>
   <td colspan = "5" class="dataTableContent" valign="top"><a name="<?php echo $ut['session_id'];?>"></a><?php echo $customer_link . ",&nbsp;" . $ut['session_id'] . ", <a href=\"" . zen_href_link(FILENAME_USER_TRACKING, ($_GET['time'] ? "time=" . $_GET['time'] . "&" : ""). "delsession=" . $ut['session_id'], $request_type)  . "\">" . (((CONFIG_USER_TRACKING_ADMIN_CAN_DELETE == 'true') || (CONFIG_USER_TRACKING_ADMIN_CAN_DELETE_SESSIONS == 'true' )) ? "<font color=\"red\">[delete session]</font>," : "" ) . "</a>" . " <a href=\"". zen_href_link(FILENAME_USER_TRACKING, ($_GET['time'] ? "time=" . $_GET['time'] . "&" : "") . "viewsession=" . $ut['session_id'] . "#" . $ut['session_id'], $request_type) . "\"><font color=\"green\">[view session]</font></a>";?></td>
<?php   //End of v1.4.3 15 of 15

    // shopping cart decoding
      $session_expired = FALSE;
      $session_data = $db->Execute("select value from " . TABLE_SESSIONS . " WHERE sesskey = '" . $ut['session_id'] . "' and expiry > '" . time() . "'", false, false, 0, true);
      if ($session_data->RecordCount() >0) {
        $session_data = trim($session_data->fields['value']);
      } else {
        if (file_exists(zen_session_save_path() . '/sess_' . $ut['session_id'])) {
          $session_data = @file(zen_session_save_path() . '/sess_' . $ut['session_id']);
          $session_data = trim($session_data[0]);
        } else {
          $session_data = '';
          $session_expired = TRUE;
        }
      }

      if ($session_data != '') {
        $session_data = base64_decode($session_data);
      }

      $datatest = array();
    
      $cart = "";
      $referer_url = "";
    //$num_sessions ++; // User Tracking - Spiders Mod 5 of 7 
//    $_SESSION['cart'] = array();

      $orig_session = $_SESSION;
//echo '<br />session data before: ' . print_r($_SESSION, true) . '<br />';
      if ($length = strlen($session_data)) {
      //unset($_SESSION['admin_id']);

        $start_cart = (int)strpos($session_data, 'cart|O');
        $end_cart = (int)strpos($session_data, '|', $start_cart+6);
        $end_cart = (int)strrpos(substr($session_data, 0, $end_cart), ';}');

        $session_data_cart = substr($session_data, $start_cart, ($end_cart - $start_cart+2));

        zen_session_start();
        session_decode($session_data_cart);
      }
//echo '<br />session data after: ' . print_r($_SESSION, true) . '<br />';
//echo '<br />session_id: ' . $ut['value']['session_id'] . '<br />';
      $contents = array();

      if (is_object($_SESSION['cart']) /*&& sizeof($_SESSION['cart']) > 0*/) {
        $products = $_SESSION['cart']->get_products();
        for ($i = 0, $n = sizeof($products); $i < $n; $i++) {
          $contents[] = array('text' => $products[$i]['quantity'] . ' x ' . '<a href="' . zen_href_link(FILENAME_CATEGORIES, 'cPath=' . zen_get_product_path($products[$i]['id']) . '&pID=' . $products[$i]['id']) . '">' . $products[$i]['name'] . '</a>');
// cPath=23&pID=74
        }

        if (sizeof($products) > 0) {
          $contents[] = array('text' => zen_draw_separator('pixel_black.gif', '100%', '1'));
          $contents[] = array('align' => 'right', 'text'  => TEXT_SHOPPING_CART_SUBTOTAL . ' ' . $currencies->format($_SESSION['cart']->show_total(), true, $_SESSION['currency']));
        } else {
          $contents[] = array('text' => '&nbsp;Empty Cart');
        }
      } elseif ($session_expired) {
        $contents[] = array('align' => 'center', 'text' => 'session expired');
      } else {
        $admin_user = zen_get_users($_SESSION['admin_id']);
        $contents[] = array('align' => 'center', 'text' => 'active admin group: ' . $admin_user[0]['profileName']);
      }
      
      zen_session_start();
      $_SESSION = $orig_session;

      $heading = array();

      if (zen_not_null($contents) )
      {

        echo '            <td rowspan="4" valign="top">' . "\n";

        $box = new box;

        echo $box->infoBox($heading, $contents);
        echo '            </td>' . "\n";
      }
      else
      {
        echo '            <td rowspan="4" valign="top" class="dataTableContent" align="center">session expired' . "\n";
        echo '            </td>' . "\n";
      }

?>

              </tr>
                      <tr>
        <td class="dataTableContent" align="right" valign="top"><b><?php echo TEXT_CLICK_COUNT; ?></b></td>
        <td class="dataTableContent" valign="top"><font color="FF0000"><b><?php echo count($ut['last_page_url']);?></b></font></td>
        <td class="dataTableContent" colspan="2" rowspan="4" align="center">
          <?php       echo $boxes['table']['time_table'];
      ?></td>
                          </tr>
              <tr>
        <td class="dataTableContent" align="right" valign="top"><b><?php echo TABLE_HEADING_COUNTRY; ?></b></td>
<?php $flag = strtolower(geoip_country_code_by_addr($gi, $ut['ip_address']));
      $cn = geoip_country_name_by_addr($gi, $ut['ip_address']);
      if ($flag == '') $flag = 'unknown';
      if ($cn == '') $cn = 'unknown'; ?>
        <td class="dataTableContent" valign="top"><?php echo zen_image(DIR_WS_FLAGS . $flag . '.gif', $cn); ?>&nbsp;<?php echo $cn; ?></td>
       </tr>
              <tr>
        <td class="dataTableContent" align="right" valign="top"><b><?php echo TABLE_HEADING_IP_ADDRESS; ?></b></td>
        <td class="dataTableContent" valign="top"><a href="<?php echo USER_TRACKING_WHOIS_URL; ?><?php echo $ut['ip_address']; ?>" target="_new"><?php echo $ut['ip_address']; ?></a></td>
       </tr>
       <tr>
        <td class="dataTableContent" align="right" valign="top"><b><?php echo TABLE_HEADING_HOST; ?></b></td>
        <td class="dataTableContent" valign="top"><?php echo $ut['customers_host_address']/*echo gethostbyaddr($ut['value']['ip_address']) too slow under WINDOWS */; ?></td>
       </tr>
       <tr>
        <td class="dataTableContent" align="right" valign="top"><b><?php echo TEXT_ORIGINATING_URL; ?></b></td>
<?php
      $ref_name = chunk_split($referer_url,40,"<br>");
?>
        <td class="dataTableContent" align="left" valign="top" colspan="3"><?php echo '<a href="'.$ut['referer_url'].'" target="_new">'. $ut['referer_url'] .'</a>'; ?>&nbsp;</td>
       </tr>
       <tr>
        <td class="dataTableContent"></td>
        <td class="dataTableContent" colspan="3">
<?php

      $heading = array();
      $row = array();
      
      if ($_GET['viewsession'] == $ut['session_id']){
        foreach ($ut['last_page_url'] as $key => $pu)    
        {
          $du = $ut['page_desc'][$key];
          $column = array();

          $column[] = array('params' => 'class="dataTableContent"',
                            'align' => 'right" valign="top',
                            'text' => date('H:i:s', $key) . ':'
                           );
          $column[] = array('params' => 'class="dataTableContent" nowrap',
                            'align' => 'left" valign="top',
                            'text' => '&nbsp;<a href="' . $pu . '" target="_new">' . (($du!='') ? $du : '') .'</a>&nbsp'
                           );
          $column[] = array('params' => 'width="100%"',
                            'align' => 'left',
                            'text' => '<a href="' . $pu . '" target="_new">' . chunk_split($pu,40,"<br />") . '</a>'
                           );
          $row[] = $column;
          $row[sizeof($row)-1]['params'] = 'bgcolor="ffffff"';
        }
      }
      $box = new box;
      $box->table_cellspacing = '1';
      $box->table_parameters = 'bgcolor="999999"';
      echo $box->infoBox($heading, $row);

      echo'
      </td>
     </tr> ';
// Start User Tracking - Spider Mod 6 of 7
    }
// End User Tracking - Spider Mod 6 of 7
  }
}
?>
       <tr>
        <td class="smallText" colspan="7"><?php echo sprintf(TEXT_NUMBER_OF_CUSTOMERS, $results); /*Start User Tracking - Spider Mod 7 of 7 */ echo sprintf(TEXT_NUMBER_OF_USERS, $num_sessions); echo sprintf(TEXT_NUMBER_OF_SPIDERS, $spiderCount); /*End User Tracking - Spider Mod 7 of 7 */ ?></td>
       </tr>
      </table></td>
     </tr>
    <tr>
      <td class="smallText" colspan="7">
      <br />
<?php 
// Start User Tracking - Ver 1.4.2 Mod 1 of 
  echo '<b>' . TEXT_SELECT_VIEW .': </b>';

  if (time() < $time_frame - 86400)
  {
    $newTime = time();
    $newTime = ($time_frame - ((int)(($time_frame - $newTime) / 86400) + 1) * 86400 );
    echo '<a href="' . zen_href_link(FILENAME_USER_TRACKING, 'time=' . $newTime, $request_type) . '">' . TEXT_BACK_TO . ' ' . TEXT_TODAY . ' </a> | ';
  }
// End User Tracking - Ver 1.4.2 Mod 1 of 

  echo '<a href="' . zen_href_link(FILENAME_USER_TRACKING, 'time=' . ($time_frame - 86400), $request_type) . '">' . TEXT_BACK_TO . ' ' . date("M d, Y", $time_frame - 86400) . '</a> ';

  if (time() > $time_frame + 86400)
  {
    echo '| <a href="' . zen_href_link(FILENAME_USER_TRACKING,  'time=' . ($time_frame + 86400) , $request_type) . '">' . TEXT_FORWARD_TO . date("M d, Y", $time_frame + 86400) . '</a>';
  }

// Start User Tracking - Ver 1.4.2 Mod 2 of 
  if (time() > $time_frame + 172800)
  {
//v1.4.3 8 of 15 
    $newTime = time();
    $newTime = ($time_frame + ((int)(($newTime - $time_frame) / 86400)) * 86400 );
    //echo $newTime . '">' . TEXT_FORWARD_TO . ' ' . TEXT_TODAY . ' </a>';
    echo ' | <a href="' . zen_href_link(FILENAME_USER_TRACKING,  'time=' . $newTime, $request_type) . '">' . TEXT_FORWARD_TO . ' ' . TEXT_TODAY . ' </a>';
   }
// End User Tracking - Ver 1.4.2 Mod 2 of         
   ?>
      </td> 
     </tr>
    </table>
   </td>
   </tr>
  </table></td>
<!-- body_text_eof //-->
 </tr>
</table>
</form>
<!-- body_eof //-->

<!-- footer //-->
<?php require(DIR_WS_INCLUDES . 'footer.php'); ?>
<!-- footer_eof //-->
<br />
</body>
</html>
<?php require(DIR_WS_INCLUDES . 'application_bottom.php'); ?>