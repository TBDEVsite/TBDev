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
if ( ! defined( 'IN_TBDEV_FORUM' ) )
{
	print "<h1>Incorrect access</h1>You cannot access this file directly.";
	exit();
}




    $topicid = (int)$_GET["topicid"];

    $page = isset($_GET["page"]) ? (int)$_GET["page"] : false;

    if (!is_valid_id($topicid))
      die;

    $userid = $CURUSER["id"];

    //------ Get topic info

    $res = mysql_query("SELECT * FROM topics WHERE id=$topicid") or sqlerr(__FILE__, __LINE__);

    $arr = mysql_fetch_assoc($res) or stderr("Forum error", "Topic not found");

    $locked = ($arr["locked"] == 'yes');
    $subject = htmlentities($arr["subject"], ENT_QUOTES);
    $sticky = $arr["sticky"] == "yes";
    $forumid = $arr["forumid"];

	//------ Update hits column

    mysql_query("UPDATE topics SET views = views + 1 WHERE id=$topicid") or sqlerr(__FILE__, __LINE__);

    //------ Get forum

    $res = mysql_query("SELECT * FROM forums WHERE id=$forumid") or sqlerr(__FILE__, __LINE__);

    $arr = mysql_fetch_assoc($res) or die("Forum = NULL");

    $forum = $arr["name"];

    if ($CURUSER["class"] < $arr["minclassread"])
		stderr("Error", "You are not permitted to view this topic.");

    //------ Get post count

    $res = mysql_query("SELECT COUNT(*) FROM posts WHERE topicid=$topicid") or sqlerr(__FILE__, __LINE__);

    $arr = mysql_fetch_row($res);

    $postcount = $arr[0];

    //------ Make page menu

    $pagemenu = "<p>\n";

    $perpage = $postsperpage;

    $pages = ceil($postcount / $perpage);

    if ($page[0] == "p")
  	{
	    $findpost = substr($page, 1);
	    $res = mysql_query("SELECT id FROM posts WHERE topicid=$topicid ORDER BY added") or sqlerr(__FILE__, __LINE__);
	    $i = 1;
	    while ($arr = mysql_fetch_row($res))
	    {
	      if ($arr[0] == $findpost)
	        break;
	      ++$i;
	    }
	    $page = ceil($i / $perpage);
	  }

    if ($page == "last")
      $page = $pages;
    else
    {
      if($page < 1)
        $page = 1;
      elseif ($page > $pages)
        $page = $pages;
    }

    $offset = $page * $perpage - $perpage;

    for ($i = 1; $i <= $pages; ++$i)
    {
      if ($i == $page)
        $pagemenu .= "<font class='gray'><b>$i</b></font>\n";

      else
        $pagemenu .= "<a href='forums.php?action=viewtopic&amp;topicid=$topicid&amp;page=$i'><b>$i</b></a>\n";
    }

    if ($page == 1)
      $pagemenu .= "<br /><font class='gray'><b>&lt;&lt; Prev</b></font>";

    else
      $pagemenu .= "<br /><a href='forums.php?action=viewtopic&amp;topicid=$topicid&amp;page=" . ($page - 1) .
        "'><b>&lt;&lt; Prev</b></a>";

    $pagemenu .= "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;";

    if ($page == $pages)
      $pagemenu .= "<font class='gray'><b>Next &gt;&gt;</b></font></p>\n";

    else
      $pagemenu .= "<a href='forums.php?action=viewtopic&amp;topicid=$topicid&amp;page=" . ($page + 1) .
        "'><b>Next &gt;&gt;</b></a></p>\n";

    //------ Get posts

    $res = mysql_query("SELECT p. * , u.username, u.class, u.avatar, u.av_w, u.av_h, 
						u.donor, u.title, u.enabled, u.warned, u.reputation 
						FROM posts p
						LEFT JOIN users u ON u.id = p.userid
						WHERE topicid = $topicid ORDER BY p.id LIMIT $offset,$perpage") or sqlerr(__FILE__, __LINE__);

    
    
    
    stdhead("View topic");
	
	echo "<script type='text/javascript' src='./scripts/popup.js'></script>";
	
    print("<a name='top'></a><a href='forums.php?action=viewforum&amp;forumid=$forumid'>$forum</a> &gt; $subject\n");

    print($pagemenu);

    //------ Print table

    begin_main_frame();

    //begin_frame();

    $pc = mysql_num_rows($res);

    $pn = 0;

    $r = mysql_query("SELECT lastpostread FROM readposts WHERE userid=" . $CURUSER["id"] . " AND topicid=$topicid") or sqlerr(__FILE__, __LINE__);

    $a = mysql_fetch_row($r);

    $lpr = $a[0];

    //..rp..
/* if (!$lpr)
mysql_query("INSERT INTO readposts (userid, topicid) VALUES($userid, $topicid)") or sqlerr(__FILE__, __LINE__);
*/
//..rp..

    while ($arr = mysql_fetch_assoc($res))
    {
      ++$pn;

      $postid = $arr["id"];

      $posterid = $arr["userid"];

      $added = get_date( $arr['added'],'');

      //---- Get poster details

      //$res2 = mysql_query("SELECT username, class, avatar, av_w, av_h, donor, title, enabled, warned FROM users WHERE id=$posterid") or sqlerr(__FILE__, __LINE__);

      //$arr2 = mysql_fetch_assoc($res2);

      $postername = $arr["username"];

      if ($postername == "")
      {
        $by = "unknown[$posterid]";

        //$avatar = "";
      }
      else
      {
//		if ($arr2["enabled"] == "yes")
	        //$avatar = ($CURUSER["avatars"] == "yes" ? htmlspecialchars($arr2["avatar"]) : "");
//	    else
//			$avatar = "{$TBDEV['pic_base_url']}disabled_avatar.gif";

        $title = $arr["title"];

        if (!$title)
          $title = get_user_class_name($arr["class"]);

        $by = "<a href='userdetails.php?id=$posterid'><b>$postername</b></a>" . ($arr["donor"] == "yes" ? "<img src='".
        "{$TBDEV['pic_base_url']}star.gif' alt='Donor' />" : "") . ($arr["enabled"] == "no" ? "<img src='".
        "{$TBDEV['pic_base_url']}disabled.gif' alt='This account is disabled' style='margin-left: 2px' />" : ($arr["warned"] == "yes" ? "<a href='rules.php#warning' class='altlink'><img src='{$pic_base_url}warned.gif' alt='Warned' border='0' /></a>" : "")) . " ($title)";
      }

      if ($CURUSER["avatars"] == "yes")
          {
            $avatar = $arr['avatar'] ? "<div style='text-align:center;padding:5px;'><img width='{$arr['av_w']}' height='{$arr['av_h']}' src='".htmlentities($arr['avatar'], ENT_QUOTES)."' alt='' /></div>" : "<img width='100' src='{$forum_pic_url}default_avatar.gif' alt='' />";
          }
      else
            $avatar = "<img width='100' src='{$forum_pic_url}default_avatar.gif' alt='' />";

      print("<a name='$postid'></a>\n");

      if ($pn == $pc)
      {
        print("<a name='last'></a>\n");
        //..rp..
/* if ($postid > $lpr)
mysql_query("UPDATE readposts SET lastpostread=$postid WHERE userid=$userid AND topicid=$topicid") or sqlerr(__FILE__, __LINE__);
*/
//..rp..
      }

      print("<table border='0' cellspacing='0' cellpadding='0'><tr><td class='embedded' width='99%'>#$postid by $by at $added");

      if (!$locked || get_user_class() >= UC_MODERATOR)
				print(" - [<a href='forums.php?action=quotepost&amp;topicid=$topicid&amp;postid=$postid'><b>Quote</b></a>]");

      if (($CURUSER["id"] == $posterid && !$locked) || get_user_class() >= UC_MODERATOR)
        print(" - [<a href='forums.php?action=editpost&amp;postid=$postid'><b>Edit</b></a>]");

      if (get_user_class() >= UC_MODERATOR)
        print(" - [<a href='forums.php?action=deletepost&amp;postid=$postid'><b>Delete</b></a>]");

      print("</td><td class='embedded' width='1%'><a href='#top'><img src='{$forum_pic_url}top.gif' border='0' alt='Top' /></a></td></tr>");

      print("</table>\n");

      begin_table(true);

      $body = format_comment($arr["body"]);

      if (is_valid_id($arr['editedby']))
      {
        $res2 = mysql_query("SELECT username FROM users WHERE id={$arr['editedby']}");
        if (mysql_num_rows($res2) == 1)
        {
          $arr2 = mysql_fetch_assoc($res2);
          $body .= "<p><font size='1' class='small'>Last edited by <a href='userdetails.php?id={$arr['editedby']}'><b>{$arr2['username']}</b></a> on ".get_date( $arr['editedat'],'')."</font></p>\n";
        }
      }

		$member_reputation = $arr['username'] != '' ? get_reputation($arr) : '';
      print("<tr valign='top'><td width='150' align='center' style='padding: 0px'>" .
        ($avatar ? $avatar : ""). "<br /><div>$member_reputation</div></td><td class='comment'>$body</td></tr>\n");

      end_table();
    
    $postadd = $arr['added'];
	//..rp..
	if (($postid > $lpr) AND ($postadd > (time() - $TBDEV['readpost_expiry']))) {
	
	if ($lpr)
	mysql_query("UPDATE readposts SET lastpostread=$postid ".
	"WHERE userid=$userid AND topicid=$topicid") or sqlerr(__FILE__, __LINE__);
	else
	mysql_query("INSERT INTO readposts (userid, topicid, lastpostread) ".
	"VALUES($userid, $topicid, $postid)") or sqlerr(__FILE__, __LINE__);
	
	}}
	//..rp..
	
	

  	//end_frame();

  	end_main_frame();

  	print($pagemenu);

  	if ($locked && get_user_class() < UC_MODERATOR)
  		print("<p>This topic is locked; no new posts are allowed.</p>\n");

  	else
  	{
	    $arr = get_forum_access_levels($forumid) or die;

	    if (get_user_class() < $arr["write"])
	      print("<p><i>You are not permitted to post in this forum.</i></p>\n");

	    else
	      $maypost = true;
	  }

	  //------ "View unread" / "Add reply" buttons

	  print("<table class='main' border='0' cellspacing='0' cellpadding='0'><tr>\n");
	  print("<td class='embedded'><form method='post' action='forums.php?action=viewunread'>\n");
	  print("<input type='hidden' name='action' value='viewunread' />\n");
	  print("<input type='submit' value='View Unread' class='btn' />\n");
	  print("</form></td>\n");

    if ($maypost)
    {
      print("<td class='embedded' style='padding-left: 10px'><form method='post' action='forums.php?action=reply'>\n");
      print("<input type='hidden' name='action' value='reply' />\n");
      print("<input type='hidden' name='topicid' value='$topicid' />\n");
      print("<input type='submit' value='Add Reply' class='btn' />\n");
      print("</form></td>\n");
    }
    print("</tr></table>\n");
    
    //------ Mod options

	  if (get_user_class() >= UC_MODERATOR)
	  {
	    //attach_frame();
      $req_uri = htmlentities($_SERVER['PHP_SELF']);
      
	    $res = mysql_query("SELECT id,name,minclasswrite FROM forums ORDER BY name") or sqlerr(__FILE__, __LINE__);
	    
	    print("<table border='1' cellspacing='0' cellpadding='0'>\n");

	    print("<tr><td align='right'>Sticky:</td>\n");
	    print("<td>");
	    print("<form method='post' action='forums.php?action=setsticky'>\n");
	    print("<input type='hidden' name='topicid' value='$topicid' />\n");
	    print("<input type='hidden' name='returnto' value='{$req_uri}' />\n");
	    print("<input type='radio' name='sticky' value='yes' " . ($sticky ? " checked='checked'" : "") . " /> Yes <input type='radio' name='sticky' value='no' " . (!$sticky ? " checked='checked'" : "") . " /> No\n");
	    print("<input type='submit' value='Set' /></form>\n</td></tr>");

	    
	    print("<tr><td align='right'>Locked:</td>\n");
	    print("<td >");
	    print("<form method='post' action='forums.php?action=setlocked'>\n");
	    print("<input type='hidden' name='topicid' value='$topicid' />\n");
	    print("<input type='hidden' name='returnto' value='{$req_uri}' />\n");
	    print("<input type='radio' name='locked' value='yes' " . ($locked ? " checked='checked'" : "") . " /> Yes <input type='radio' name='locked' value='no' " . (!$locked ? " checked='checked'" : "") . " /> No\n");
	    print("<input type='submit' value='Set' /></form>\n</td></tr>");

	    
	    print("<tr><td align='right'>Rename topic:</td>");
	    print("<td >");
	    print("<form method='post' action='forums.php?action=renametopic'>\n");
	    print("<input type='hidden' name='topicid' value='$topicid' />\n");
	    print("<input type='hidden' name='returnto' value='{$req_uri}' />\n");
	    print("<input type='text' name='subject' size='60' maxlength='$maxsubjectlength' value='" . htmlspecialchars($subject) . "' />\n");
	    print("<input type='submit' value='Okay' /></form></td></tr>");

	    
	    print("<tr><td align='right'>Move this thread to:&nbsp;</td>");
	    print("<td>");
	    print("<form method='post' action='forums.php?action=movetopic&amp;topicid=$topicid'>\n");
	    print("<select name='forumid'>");

	    while ($arr = mysql_fetch_assoc($res))
	      if ($arr["id"] != $forumid && get_user_class() >= $arr["minclasswrite"])
	        print("<option value='" . $arr["id"] . "'>" . $arr["name"] . "</option>\n");

	    print("</select> <input type='submit' value='Okay' /></form></td></tr>\n");
	    
	    print("<tr><td align='right'>Delete topic:</td><td>\n");
	    print("<form method='post' action='forums.php?action=deletetopic'>\n");
	    print("<input type='hidden' name='action' value='deletetopic' />\n");
	    print("<input type='hidden' name='topicid' value='$topicid' />\n");
	    print("<input type='hidden' name='forumid' value='$forumid' />\n");
	    print("<input type='checkbox' name='sure' value='1' />I'm sure\n");
	    print("<input type='submit' value='Okay' />\n");
	    print("</form></td></tr>\n");
	    print("</table>\n");
	  }

    //------ Forum quick jump drop-down

    insert_quick_jump_menu($forumid);

    stdfoot();

    die;
?>