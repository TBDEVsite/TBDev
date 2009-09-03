<?php
/*
+------------------------------------------------
|   TBDev.net BitTorrent Tracker PHP
|   =============================================
|   by CoLdFuSiOn
|   (c) 2003 - 2009 TBDev.Net
|   http://www.tbdev.net
|   =============================================
|   svn: http://sourceforge.net/projects/tbdevnet/
|   Licence Info: GPL
+------------------------------------------------
|   $Date$
|   $Revision$
|   $Author$
|   $URL$
+------------------------------------------------
*/
error_reporting(E_ALL);

define('SQL_DEBUG', 2);

/* Compare php version for date/time stuff etc! */
	if (version_compare(PHP_VERSION, "5.1.0RC1", ">="))
		date_default_timezone_set('Europe/London');


define('TIME_NOW', time());

$CONFIG_INFO = array( 'time_adjust' =>  0, 
                      'time_offset' => '0', 
                      'time_use_relative' => 1,
                      'time_use_relative_format' => '{--}, h:i A',
                      'time_joined' => 'j-F y',
                      'time_short' => 'jS F Y - h:i A',
                      'time_long' => 'M j Y, h:i A',
                      'time_tiny' => '',
                      'time_date' => '');


// DB setup
$mysql_host = "localhost";
$mysql_user = "root";
$mysql_pass = "blank";
$mysql_db   = "mytbdev";

// Cookie setup
$TBDEV['cookie_prefix']  = 'tbdev_'; // This allows you to have multiple trackers, eg for demos, testing etc.
$TBDEV['cookie_path']    = '/test'; // ATTENTION: You should never need this unless the above applies eg: /tbdev
$TBDEV['cookie_domain']  = ''; // set to eg: .somedomain.com or is subdomain set to: .sub.somedomain.com
                              
$SITE_ONLINE = true;
$GLOBALS['tracker_post_key'] = 'changethisorelse';
$max_torrent_size = 1000000;
$announce_interval = 60 * 30;
$signup_timeout = 86400 * 3;
$minvotes = 1;
$max_dead_torrent_time = 6 * 3600;

// Max users on site
$maxusers = 5000; // LoL Who we kiddin' here?


if ( strtoupper( substr(PHP_OS, 0, 3) ) == 'WIN' )
  {
    $file_path = str_replace( "\\", "/", dirname(__FILE__) );
    $file_path = str_replace( "/include", "", $file_path );
  }
  else
  {
    $file_path = dirname(__FILE__);
    $file_path = str_replace( "/include", "", $file_path );
  }
  
define('ROOT_PATH', $file_path);
$torrent_dir = ROOT_PATH . '/torrents';
//$torrent_dir = "F:/web/xampp/htdocs/tb/torrents";    # FOR WINDOWS ONLY - must be writable for httpd user

# the first one will be displayed on the pages
$announce_urls = array();
$announce_urls[] = "http://localhost/test/announce.php";
//$announce_urls[] = "http://localhost:2710/announce";
//$announce_urls[] = "http://domain.com:83/announce.php";

if ($_SERVER["HTTP_HOST"] == "")
  $_SERVER["HTTP_HOST"] = $_SERVER["SERVER_NAME"];
$BASEURL = "http://" . $_SERVER["HTTP_HOST"]."/test";

// Set this to your site URL... No ending slash!
$DEFAULTBASEURL = "http://localhost/test";

//set this to true to make this a tracker that only registered users may use
$MEMBERSONLY = true;

//maximum number of peers (seeders+leechers) allowed before torrents starts to be deleted to make room...
//set this to something high if you don't require this feature
$PEERLIMIT = 50000;

// Email for sender/return path.
$SITEEMAIL = "coldfusion@localhost";

$SITENAME = "TBDEV.NET";

$autoclean_interval = 900;
$sql_error_log = './logs/sql_err_'.date("M_D_Y").'.log';
$pic_base_url = "./pic/";
$stylesheet = "./1.css";
$READPOST_EXPIRY = 14*86400; // 14 days
//set this to size of user avatars
$av_img_height = 100;
$av_img_width = 100;
$allowed_ext = array('image/gif', 'image/png', 'image/jpeg');
// Set this to the line break character sequence of your system
$linebreak = "\r\n";

define ('UC_USER', 0);
define ('UC_POWER_USER', 1);
define ('UC_VIP', 2);
define ('UC_UPLOADER', 3);
define ('UC_MODERATOR', 4);
define ('UC_ADMINISTRATOR', 5);
define ('UC_SYSOP', 6);

//Do not modify -- versioning system
//This will help identify code for support issues at tbdev.net
define ('TBVERSION','TBDev_2009_svn');

?>