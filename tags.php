<?php

require_once "include/bittorrent.php";
require_once "include/user_functions.php";
require_once "include/html_functions.php";
require_once "include/bbcode_functions.php";

dbconn();
loggedinorreturn();

$lang = array_merge( load_language('global'), load_language('tags') );

function insert_tag($name, $description, $syntax, $example, $remarks)
{
    global $lang;
    
    $result = format_comment($example);
    $htmlout = '';


    $htmlout .= "<table class='main' width='100%' border='1' cellspacing='0' cellpadding='5'>\n";
    $htmlout .= "      <tr valign='top' class='inner_header'><td class='sub'><b>$name</b></td><td class='inner_header'></td></tr>\n";
    $htmlout .= "      <tr valign='top'><td style='width:25%;'>{$lang['tags_description']}</td><td>$description</td></tr>\n";
    $htmlout .= "      <tr valign='top'><td>{$lang['tags_systax']}</td><td><tt>$syntax</tt></td></tr>\n";
    $htmlout .= "      <tr valign='top'><td>{$lang['tags_example']}</td><td><tt>$example</tt></td></tr>\n";
    $htmlout .= "      <tr valign='top'><td>{$lang['tags_result']}</td><td>$result</td></tr>\n";
    if ($remarks != "")
      $htmlout .= "    <tr><td>{$lang['tags_remarks']}</td><td>$remarks</td></tr>\n";
    $htmlout .= "</table><br />\n";

    return $htmlout;
}

    $HTMLOUT = '';

    $HTMLOUT .= "
                     <div class='cblock'>
                         <div class='cblock-header'>Tags</div>
                         <div class='cblock-content'>";

    $HTMLOUT .= begin_frame();
    $test = isset($_POST["test"]) ? $_POST["test"] : '';

    $HTMLOUT .= "{$lang['tags_title']}
             <form method='post' action='?'>
    <textarea name='test' cols='60' rows='3'>".($test ? htmlsafechars($test) : "")."</textarea>
    <input type='submit' value='{$lang['tags_test']}' style='height: 23px; margin-left: 5px' />
    </form>";


    if ($test != "")
      $HTMLOUT .= "<table style='width:100%;' cellspacing='0' cellpadding='0'>\n";
      $HTMLOUT .= "      <tr class='inner_header'><td style='padding:0px;'><b>Results&nbsp;from&nbsp;BBcode&nbsp;:&nbsp;</b></td><td class='inner_header'></td></tr>\n";
      $HTMLOUT .= "      <tr style='width:100%;'>
                            <td style='width:100%;' align='center'>" . format_comment($test) . "</td>
                         </tr>\n";
      $HTMLOUT .= "</table><br />\n";


    $HTMLOUT .= insert_tag(
      $lang['tags_bold1'],
      $lang['tags_bold2'],
      $lang['tags_bold3'],
      $lang['tags_bold4'],
      ""
    );

    $HTMLOUT .= insert_tag(
    $lang['tags_italic1'],
    $lang['tags_italic2'],
    $lang['tags_italic3'],
    $lang['tags_italic4'],
      ""
    );

    $HTMLOUT .= insert_tag(
    $lang['tags_underline1'],
    $lang['tags_underline2'],
    $lang['tags_underline3'],
    $lang['tags_underline4'],
      ""
    );

    $HTMLOUT .= insert_tag(
    $lang['tags_color1'],
    $lang['tags_color2'],
    $lang['tags_color3'],
    $lang['tags_color4'],
    $lang['tags_color5']
    );

    $HTMLOUT .= insert_tag(
    $lang['tags_color6'],
    $lang['tags_color7'],
    $lang['tags_color8'],
    $lang['tags_color9'],
    $lang['tags_color10']
    );

    $HTMLOUT .= insert_tag(
    $lang['tags_size1'],
    $lang['tags_size2'],
    $lang['tags_size3'],
    $lang['tags_size4'],
    $lang['tags_size5']
    );

    $HTMLOUT .= insert_tag(
    $lang['tags_fonts1'],
    $lang['tags_fonts2'],
    $lang['tags_fonts3'],
    $lang['tags_fonts4'],
    $lang['tags_fonts5']
    );

    $HTMLOUT .= insert_tag(
    $lang['tags_hyper1'],
    $lang['tags_hyper2'],
    $lang['tags_hyper3'],
    $lang['tags_hyper4'],
    $lang['tags_hyper5']
    );

    $HTMLOUT .= insert_tag(
    $lang['tags_hyper6'],
    $lang['tags_hyper7'],
    $lang['tags_hyper8'],
    $lang['tags_hyper9'],
    $lang['tags_hyper10']
    );

    $HTMLOUT .= insert_tag(
    $lang['tags_image1'],
    $lang['tags_image2'],
    $lang['tags_image3'],
    $lang['tags_image4'],
    $lang['tags_image5']
    );

    $HTMLOUT .= insert_tag(
    $lang['tags_image6'],
    $lang['tags_image7'],
    $lang['tags_image8'],
    $lang['tags_image9'],
    $lang['tags_image10']
    );

    $HTMLOUT .= insert_tag(
    $lang['tags_quote1'],
    $lang['tags_quote2'],
    $lang['tags_quote3'],
    $lang['tags_quote4'],
      ""
    );

    $HTMLOUT .= insert_tag(
    $lang['tags_quote5'],
    $lang['tags_quote6'],
    $lang['tags_quote7'],
    $lang['tags_quote8'],
      ""
    );

    $HTMLOUT .= insert_tag(
    $lang['tags_list1'],
    $lang['tags_list2'],
    $lang['tags_list3'],
    $lang['tags_list4'],
      ""
    );

    $HTMLOUT .= insert_tag(
    $lang['tags_preformat1'],
    $lang['tags_preformat2'],
    $lang['tags_preformat3'],
    $lang['tags_preformat4'],
      ""
    );

    $HTMLOUT .= end_frame();

    $HTMLOUT .= "        </div>
                     </div>";

    print stdhead("{$lang['tags_tags']}") . $HTMLOUT . stdfoot();
?>