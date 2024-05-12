<?php

if ( ! defined( 'IN_TBDEV_FORUM' ) )
{
	print "{$lang['forum_quote_post_access']}";
	exit();
}

    
  //-------- Action: Quote

		$topicid = (int)$_GET["topicid"];

		if (!is_valid_id($topicid))
			stderr("{$lang['forum_quote_post_error']}", "{$lang['forum_quote_post_invalid']}");
    
    $js = "<script type='text/javascript' src='scripts/bbcode2text.js'></script>";
    
    $HTMLOUT = stdhead($lang['forum_quote_post_reply'], $js);

    $HTMLOUT .= begin_main_frame();

    $HTMLOUT .= insert_compose_frame($topicid, false, true);

    $HTMLOUT .= end_main_frame();

    $HTMLOUT .= stdfoot();
    
    print $HTMLOUT;

    die;

  
?>