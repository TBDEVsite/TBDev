<?php



if ( ! defined( 'IN_TBDEV_ADMIN' ) )
{
	print "<h1>{$lang['text_incorrect']}</h1>{$lang['text_cannot']}";
	exit();
}

require_once "include/user_functions.php";

    $lang = array_merge( $lang, load_language('ad_docleanup') );
    
    if( get_user_class() != UC_SYSOP )
      stderr("{$lang['stderr_error']}", "{$lang['text_denied']}");
      
    //docleanup();
    register_shutdown_function("docleanup");

    stderr("{$lang['text_done']}", "{$lang['text_done']}");

?>
