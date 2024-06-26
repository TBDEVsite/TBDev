<?php

require_once "include/bittorrent.php";
require_once "include/user_functions.php";

dbconn(false);

    $lang = array_merge( load_language('global'), load_language('links') );

function add_link($url, $title, $description = "")
{
  $text = "<a class='altlink' href=$url>$title</a>";
  if ($description)
    $text = "$text - $description";
  return "<li>$text</li>\n";
}

    $HTMLOUT = '';
    
    if ($CURUSER)
    {
    $HTMLOUT .= "
                     <div class='cblock'>
                         <div class='cblock-header'>Information</div>
                         <div class='cblock-content'>
                             {$lang['links_dead']}
                         </div>
                     </div>";
    }

    $HTMLOUT .= "
                     <div class='cblock'>
                         <div class='cblock-header'>{$lang['links_other_pages_header']}</div>
                         <div class='cblock-content'>
                             <table width='100%' border='1' cellspacing='0' cellpadding='10'>
                                   <tr>
                                      <td class='text'>
                                         <ul>
                                            {$lang['links_other_pages_body']}
                                         </ul>
                                      </td>
                                   </tr>
                             </table>
                         </div>
                     </div>";

    $HTMLOUT .= "
                     <div class='cblock'>
                         <div class='cblock-header'>{$lang['links_bt_header']}</div>
                         <div class='cblock-content'>
                             <table width='100%' border='1' cellspacing='0' cellpadding='10'>
                                   <tr>
                                      <td class='text'>
                                         <ul>
                                            {$lang['links_bt_body']}
                                         </ul>
                                      </td>
                                   </tr>
                             </table>
                         </div>
                     </div>";

    $HTMLOUT .= "
                     <div class='cblock'>
                         <div class='cblock-header'>{$lang['links_software_header']}</div>
                         <div class='cblock-content'>
                             <table width='100%' border='1' cellspacing='0' cellpadding='10'>
                                   <tr>
                                      <td class='text'>
                                         <ul>
                                            {$lang['links_software_body']}
                                         </ul>
                                      </td>
                                   </tr>
                             </table>
                         </div>
                     </div>";

    $HTMLOUT .= "
                     <div class='cblock'>
                         <div class='cblock-header'>{$lang['links_download_header']}</div>
                         <div class='cblock-content'>
                             <table width='100%' border='1' cellspacing='0' cellpadding='10'>
                                   <tr>
                                      <td class='text'>
                                         <ul>
                                            {$lang['links_download_body']}
                                         </ul>
                                      </td>
                                   </tr>
                             </table>
                         </div>
                     </div>";

    $HTMLOUT .= "
                     <div class='cblock'>
                         <div class='cblock-header'>{$lang['links_forums_header']}</div>
                         <div class='cblock-content'>
                             <table width='100%' border='1' cellspacing='0' cellpadding='10'>
                                   <tr>
                                      <td class='text'>
                                         <ul>
                                            {$lang['links_forums_body']}
                                         </ul>
                                      </td>
                                   </tr>
                             </table>
                         </div>
                     </div>";

    $HTMLOUT .= "
                     <div class='cblock'>
                         <div class='cblock-header'>{$lang['links_other_header']}</div>
                         <div class='cblock-content'>
                             <table width='100%' border='1' cellspacing='0' cellpadding='10'>
                                   <tr>
                                      <td class='text'>
                                         <ul>
                                            {$lang['links_other_body']}
                                         </ul>
                                      </td>
                                   </tr>
                             </table>
                         </div>
                     </div>";


    $HTMLOUT .= "
                     <div class='cblock'>
                         <div class='cblock-header'>{$lang['links_tbdev_header']}></div>
                         <div class='cblock-content'>
                             <table width='100%' border='1' cellspacing='0' cellpadding='10'>
                                   <tr>
                                      <td class='text'>
                                         {$lang['links_tbdev_body']}
                                      </td>
                                   </tr>
                             </table>
                         </div>
                     </div>";

    print stdhead("Links") . $HTMLOUT . stdfoot();

?>