<?php


if ( ! defined( 'IN_TBDEV_FORUM' ) )
{
	print "{$lang['forum_functions_access']}";
	exit();
}


  function catch_up()
  {
	//die("This feature is currently unavailable.");
    global $CURUSER, $TBDEV;

    $userid = $CURUSER["id"];

    //..rp..
    $dt = (TIME_NOW - $TBDEV['readpost_expiry']);

    $res = @mysql_query(
                      "SELECT t.id, t.lastpost FROM topics AS t ".
                      "LEFT JOIN posts AS p ON p.id = t.lastpost ".
                      "WHERE p.added > $dt"
                      ) or sqlerr(__FILE__, __LINE__);
                      //..rp..

    while ($arr = mysql_fetch_assoc($res))
    {
      $topicid = $arr["id"];

      $postid = $arr["lastpost"];

      $r = @mysql_query("SELECT id,lastpostread FROM readposts WHERE userid=$userid and topicid=$topicid") or sqlerr(__FILE__, __LINE__);

      if (mysql_num_rows($r) == 0)
        @mysql_query("INSERT INTO readposts (userid, topicid, lastpostread) VALUES($userid, $topicid, $postid)") or sqlerr(__FILE__, __LINE__);

      else
      {
        $a = mysql_fetch_assoc($r);

        if ($a["lastpostread"] < $postid)
          @mysql_query("UPDATE readposts SET lastpostread=$postid WHERE id=" . $a["id"]) or sqlerr(__FILE__, __LINE__);
      }
    }
  }

  //-------- Returns the minimum read/write class levels of a forum

  function get_forum_access_levels($forumid)
  {
    $res = @mysql_query("SELECT minclassread, minclasswrite, minclasscreate FROM forums WHERE id=$forumid") or sqlerr(__FILE__, __LINE__);

    if (mysql_num_rows($res) != 1)
      return false;

    $arr = mysql_fetch_assoc($res);

    return array("read" => $arr["minclassread"], "write" => $arr["minclasswrite"], "create" => $arr["minclasscreate"]);
  }

  //-------- Returns the forum ID of a topic, or false on error

  function get_topic_forum($topicid)
  {
    $res = @mysql_query("SELECT forumid FROM topics WHERE id=$topicid") or sqlerr(__FILE__, __LINE__);

    if (mysql_num_rows($res) != 1)
      return false;

    $arr = mysql_fetch_row($res);

    return $arr[0];
  }

  //-------- Returns the ID of the last post of a forum

  function update_topic_last_post($topicid)
  {
    $res = @mysql_query("SELECT id FROM posts WHERE topicid=$topicid ORDER BY id DESC LIMIT 1") or sqlerr(__FILE__, __LINE__);

    $arr = mysql_fetch_row($res) or die("No post found");

    $postid = $arr[0];

    @mysql_query("UPDATE topics SET lastpost=$postid WHERE id=$topicid") or sqlerr(__FILE__, __LINE__);
  }

  function get_forum_last_post($forumid)
  {
    $res = @mysql_query("SELECT lastpost FROM topics WHERE forumid=$forumid ORDER BY lastpost DESC LIMIT 1") or sqlerr(__FILE__, __LINE__);

    $arr = mysql_fetch_row($res);

    $postid = $arr[0];

    if ($postid)
      return $postid;

    else
      return 0;
  }

  //-------- Inserts a quick jump menu

  function insert_quick_jump_menu($currentforum = 0)
  {
  	global $lang;
  	
  	$htmlout = "<div><form method='get' action='forums.php?' name='jump'>\n";

    $htmlout .= "<input type='hidden' name='action' value='viewforum' />\n";

    $htmlout .= "{$lang['forum_functions_jump']}";

    $htmlout .= "<select name='forumid' onchange=\"if(this.options[this.selectedIndex].value != -1){ forms['jump'].submit() }\">\n";

    $res = @mysql_query("SELECT * FROM forums ORDER BY name") or sqlerr(__FILE__, __LINE__);

    while ($arr = mysql_fetch_assoc($res))
    {
      if (get_user_class() >= $arr["minclassread"])
        $htmlout .= "<option value='{$arr["id"]}' ". ($currentforum == $arr["id"] ? " selected='selected'>" : ">") . $arr["name"] . "</option>\n";
    }

    $htmlout .= "</select>\n";

    $htmlout .= "<input class='jbtn' type='submit' value='Go!' />\n";

    $htmlout .= "</form>\n</div>";
    
    return $htmlout;
  }

  //-------- Inserts a compose frame

  function insert_compose_frame($id, $newtopic = true, $quote = false)
  {
    global $TBDEV, $maxsubjectlength, $CURUSER, $lang, $forum_pic_url;

    $htmlout = '';
    $title = '';
    
    if ($newtopic)
    {
      $res = mysql_query("SELECT name, minclassread, minclasscreate FROM forums WHERE id=$id") or sqlerr(__FILE__, __LINE__);

      $arr = mysql_fetch_assoc($res) or die($lang['forum_functions_badid']);
      
      if( ($CURUSER['class'] < $arr['minclassread']) OR ($CURUSER['class'] < $arr['minclasscreate']) )
      {
        stderr( $lang['forum_functions_error'], $lang['forum_functions_badid'] );
      }
      
      $forumname = htmlsafechars($arr["name"]);

      $htmlout .= "<p style='text-align:center;'>{$lang['forum_functions_newtopic']}<a href='forums.php?action=viewforum&amp;forumid=$id'>$forumname</a>{$lang['forum_functions_forum']}</p>\n";
    }
    else
    {
      $res = mysql_query("SELECT t . * , f.minclassread, f.minclasswrite FROM topics t LEFT JOIN forums f ON t.forumid = f.id WHERE t.id = $id") or sqlerr(__FILE__, __LINE__);

      $arr = mysql_fetch_assoc($res) or stderr($lang['forum_functions_error'], $lang['forum_functions_topic']);
      
      if( ($CURUSER['class'] < $arr['minclassread']) OR ($CURUSER['class'] < $arr['minclasswrite']) )
      {
        stderr( $lang['forum_functions_error'], $lang['forum_functions_badid'] );
      }
      
      $subject = htmlsafechars($arr["subject"]);

      $htmlout .= "<p style='text-align:center;'>{$lang['forum_functions_reply']}<a href='forums.php?action=viewtopic&amp;topicid=$id'>$subject</a></p>";
    }

    $htmlout .= begin_frame("Compose", true);

    $htmlout .= "<form name='bbcode2text' method='post' action='forums.php?action=post'>\n";

    if ($newtopic)
      $htmlout .= "<input type='hidden' name='forumid' value='$id' />\n";

    else
      $htmlout .= "<input type='hidden' name='topicid' value='$id' />\n";

    //$htmlout .= begin_table();

    if ($newtopic)
    {
      $htmlout .= "<div align='center'>
       <input style='width:615px;' type='text' name='subject' size='50' value='{$title}' />
       </div>";
    }
    
    if ($quote)
    {
       $postid = (int)$_GET["postid"];
       if (!is_valid_id($postid))
         header("Location: {$TBDEV['baseurl']}/forums.php");

	   $res = mysql_query("SELECT posts.*, users.username FROM posts LEFT JOIN users ON posts.userid = users.id WHERE posts.id=$postid") or sqlerr(__FILE__, __LINE__);

	   if (mysql_num_rows($res) != 1)
	     stderr("{$lang['forum_functions_error']}", "{$lang['forum_functions_nopost']}");

	   $arr = mysql_fetch_assoc($res);
    }

/*    $htmlout .= "<tr>
    <td class='rowhead'>{$lang['forum_functions_body']}</td>
    <td align='left' style='padding: 0px'>";
*/    
    $body = ($quote?(("[quote=".htmlsafechars($arr["username"])."]".htmlsafechars($arr["body"])."[/quote]\n")):"");
    
    $htmlout .= bbcode2textarea( 'body', $body );

    //$htmlout .= "</td></tr>\n";

    //$htmlout .= end_table();

    $htmlout .= "<div align='center'>
                <input type='submit' name='postquickreply' value='{$lang['forum_functions_submit']}' class='' />
             </div></form>\n";

		//$htmlout .= "<p style='text-align:center;'><a href='tags.php' target='_blank'>{$lang['forum_functions_tags']}</a> | <a href='smilies.php' target='_blank'>{$lang['forum_functions_smilies']}</a></p>\n";

    $htmlout .= end_frame();

    //------ Get 10 last posts if this is a reply

    if (!$newtopic && $TBDEV['last_10_posts'])
    {
      $postres = mysql_query("SELECT * FROM posts WHERE topicid=$id ORDER BY id DESC LIMIT 10") or sqlerr(__FILE__, __LINE__);

      $htmlout .= begin_frame("{$lang['forum_functions_last10']}");

      while ($post = mysql_fetch_assoc($postres))
      {
        //-- Get poster details

        $userres = mysql_query("SELECT * FROM users WHERE id=" . $post["userid"] . " LIMIT 1") or sqlerr(__FILE__, __LINE__);

        $user = mysql_fetch_assoc($userres);

      	if ($CURUSER["avatars"] == "yes")
          {
            $avatar = $user['avatar'] ? "<img width='{$user['av_w']}' height='{$user['av_h']}' src='".htmlsafechars($user['avatar'])."' alt='' />" : "<img width='100' src='{$TBDEV['forum_pic_url']}default_avatar.gif' alt='default' />";
          }
          else
            $avatar = "<img width='100' src='{$TBDEV['forum_pic_url']}default_avatar.gif' alt='' />";

        $htmlout .= "<p class='sub'>#{$post["id"]} by {$user["username"]} on " . get_date( $post['added'],''). "</p>";

        $htmlout .= begin_table(true);

        $htmlout .= "<tr valign='top'><td width='150' align='center' style='padding: 0px'>" . ($avatar ? $avatar : "").
          "</td><td class='comment'>" . format_comment($post["body"]) . "</td></tr>\n";

        $htmlout .= end_table();

      }

      $htmlout .= end_frame();

    }

    $htmlout .= insert_quick_jump_menu();
  
    return $htmlout;

}

//-------- Insert A Fast Reply Frame
  
function insert_fastreply($ids, $pkey = '') {
	
    global $TBDEV;
    
    $htmlout = "<div style='display: none;' id='fastreply'>
    <div class='tb_table_inner_wrap'>
    <span style='color:#ffffff;'>Fast Reply</span>
    </div>

    <form name='bbcode2text' method='post' action='{$TBDEV['baseurl']}/forums.php?action=post'>\n";
    
    if ( !empty($pkey) )
    {
        $htmlout .= "<input type='hidden' name='postkey' value='$pkey' />\n";
    }
    
    $htmlout .= "<input type='hidden' name='topicid' value='{$ids['topicid']}' />
    
    <input type='hidden' name='forumid' value='{$ids['forumid']}' />
    
    <textarea name='body' cols='50' rows='10'></textarea>

    <br /><input type='submit' class='btn' value='Submit' />
    
    <input onclick=\"showhide('fastreply'); return(false);\" value='Close Fast Reply' type='button' class='btn' />

    </form>
    </div><br />\n";
    
    return $htmlout;
}
?>