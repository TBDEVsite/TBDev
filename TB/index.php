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
ob_start("ob_gzhandler");

require_once "include/bittorrent.php";
require_once "include/user_functions.php";

dbconn(true);

loggedinorreturn();

$HTMLOUT = '';
/*
$a = @mysql_fetch_assoc(@mysql_query("SELECT id,username FROM users WHERE status='confirmed' ORDER BY id DESC LIMIT 1")) or die(mysql_error());
if ($CURUSER)
  $latestuser = "<a href='userdetails.php?id=" . $a["id"] . "'>" . $a["username"] . "</a>";
else
  $latestuser = $a['username'];
*/

    $registered = number_format(get_row_count("users"));
    //$unverified = number_format(get_row_count("users", "WHERE status='pending'"));
    $torrents = number_format(get_row_count("torrents"));
    //$dead = number_format(get_row_count("torrents", "WHERE visible='no'"));

    $r = mysql_query("SELECT value_u FROM avps WHERE arg='seeders'") or sqlerr(__FILE__, __LINE__);
    $a = mysql_fetch_row($r);
    $seeders = 0 + $a[0];
    $r = mysql_query("SELECT value_u FROM avps WHERE arg='leechers'") or sqlerr(__FILE__, __LINE__);
    $a = mysql_fetch_row($r);
    $leechers = 0 + $a[0];
    if ($leechers == 0)
      $ratio = 0;
    else
      $ratio = round($seeders / $leechers * 100);
    $peers = number_format($seeders + $leechers);
    $seeders = number_format($seeders);
    $leechers = number_format($leechers);


    //stdhead();
    //echo "<font class='small''>Welcome to our newest member, <b>$latestuser</b>!</font>\n";

    $HTMLOUT .= "<table width='737' class='main' border='0' cellspacing='0' cellpadding='0'><tr><td class='embedded'>
    <h2>Recent news";
    
    if (get_user_class() >= UC_ADMINISTRATOR)
      $HTMLOUT .= " - <font class='small'>[<a class='altlink' href='admin.php?action=news'><b>News page</b></a>]</font>";
      
    $HTMLOUT .= "</h2>\n";
    
    $res = mysql_query("SELECT * FROM news WHERE added + ( 3600 *24 *45 ) >
					".time()." ORDER BY added DESC LIMIT 10") or sqlerr(__FILE__, __LINE__);
					
    if (mysql_num_rows($res) > 0)
    {
      require_once "include/bbcode_functions.php";

      $HTMLOUT .= "<table width='100%' border='1' cellspacing='0' cellpadding='10'>
      <tr><td class='text'>\n<ul>";
      
      while($array = mysql_fetch_assoc($res))
      {
        $HTMLOUT .= "<li>" . get_date( $array['added'],'DATE') . "<br />" . format_comment($array['body']);
        if (get_user_class() >= UC_ADMINISTRATOR)
        {
          $HTMLOUT .= " <br /><font size=\"-2\">[<a class='altlink' href='admin.php?action=news&amp;mode=edit&amp;newsid={$array['id']}&amp;returnto=index.php'><b>E</b></a>]</font>";
          $HTMLOUT .= " <font size=\"-2\">[<a class='altlink' href='admin.php?action=news&amp;mode=delete&amp;newsid={$array['id']}&amp;returnto=index.php'><b>D</b></a>]</font>";
        }
        $HTMLOUT .= "</li>";
      }
      $HTMLOUT .= "</ul></td></tr></table>\n";
    }




    $HTMLOUT .= "<h2>Stats</h2>
    <table width='100%' border='1' cellspacing='0' cellpadding='10'>
    <tr>
    <td align='center'>
      <table class='main' border='1' cellspacing='0' cellpadding='5'>
      <tr>
      <td class='rowhead'>Registered users</td><td align='right'>{$registered}</td>
      </tr>
      <!-- <tr><td class='rowhead'>Unconfirmed users</td><td align=right>{unverified}</td></tr> -->
      <tr>
      <td class='rowhead'>Torrents</td><td align='right'>{$torrents}</td>
      </tr>";
      
    if (isset($peers)) 
    { 
      $HTMLOUT .= "<tr><td class='rowhead'>Peers</td><td align='right'>{$peers}</td></tr>
      <tr><td class='rowhead'>Seeders</td><td align='right'>{$seeders}</td></tr>
      <tr><td class='rowhead'>Leechers</td><td align='right'>{$leechers}</td></tr>
      <tr><td class='rowhead'>Seeder/leecher ratio (%)</td><td align='right'>{$ratio}</td></tr>";
    } 
    
      $HTMLOUT .= "</table>
      </td></tr>
      </table>";

/*
<h2>Server load</h2>
<table width='100%' border='1' cellspacing='0' cellpadding='1'0><tr><td align=center>
<table class=main border='0' width=402><tr><td style='padding: 0px; background-image: url("<?php echo $TBDEV['pic_base_url']?>loadbarbg.gif"); background-repeat: repeat-x'>
<?php $percent = min(100, round(exec('ps ax | grep -c apache') / 256 * 100));
if ($percent <= 70) $pic = "loadbargreen.gif";
elseif ($percent <= 90) $pic = "loadbaryellow.gif";
else $pic = "loadbarred.gif";
$width = $percent * 4;
print("<img height='1'5 width=$width src=\"{$TBDEV['pic_base_url']}{$pic}\" alt='$percent%'>"); ?>
</td></tr></table>
</td></tr></table>
*/

    $HTMLOUT .= "<p><font class='small'>Disclaimer: None of the files shown here are actually hosted on this server. The links are provided solely by this site's users.
The administrator of this site (www.tbdev.net) cannot be held responsible for what its users post, or any other actions of its users.
You may not use this site to distribute or download any material when you do not have the legal rights to do so.
It is your own responsibility to adhere to these terms.</font></p>
    
    </td></tr>
    </table>";

///////////////////////////// FINAL OUTPUT //////////////////////

    print stdhead('Home') . $HTMLOUT .stdfoot();
?>