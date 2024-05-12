<?php

require "include/bittorrent.php";
require_once "include/html_functions.php";
require_once "include/user_functions.php";

dbconn();
    
    $lang = array_merge( load_language('global'), load_language('useragreement') );
    
    $HTMLOUT = '';

    $HTMLOUT .= "
                    <div class='cblock'>
                        <div class='cblock-header'>{$TBDEV['site_name']},&nbsp;{$lang['frame_usragrmnt']}</div>
                        <div class='cblock-content'>";

    $HTMLOUT .= begin_frame();

    $HTMLOUT .= "{$lang['text_usragrmnt']}";

    $HTMLOUT .= end_frame();

    $HTMLOUT .= "       </div>
                    </div>";

    print stdhead("{$lang['stdhead_usragrmnt']}") . $HTMLOUT . stdfoot();
?>