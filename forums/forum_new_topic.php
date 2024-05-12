<?php


if ( ! defined( 'IN_TBDEV_FORUM' ) )
{
	print "{$lang['forum_new_topic_access']}";
	exit();
}


    $forumid = (int)$_GET["forumid"];

    if (!is_valid_id($forumid))
      header("Location: {$TBDEV['baseurl']}/forums.php");
    
    $js = "<script type='text/javascript' src='scripts/bbcode2text.js'></script>";

    $HTMLOUT = stdhead($lang['forum_new_topic_newtopic'], $js);

    $HTMLOUT .= begin_main_frame();

    $HTMLOUT .= insert_compose_frame($forumid);

    $HTMLOUT .= end_main_frame();

    $HTMLOUT .= stdfoot();
    
    print $HTMLOUT;

    die;

?>