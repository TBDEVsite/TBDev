<?php

require "include/bittorrent.php";
require_once "include/user_functions.php";
require_once "include/html_functions.php";
require_once "include/emoticons.php";

dbconn(false);
loggedinorreturn();

    $lang = load_language('global');

    $HTMLOUT = '';

    $HTMLOUT .= "
                     <div class='cblock'>
                         <div class='cblock-header'>Smiles</div>
                         <div class='cblock-content'>";

    $HTMLOUT .= insert_smilies_frame();

    $HTMLOUT .= "        </div>
                     </div>";

    /////////////////////// HTML OUTPUT ///////////////////////
    print stdhead('FAQ') . $HTMLOUT . stdfoot();
?>