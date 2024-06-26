<?php

function linkcolor($num) {
    if (!$num)
        return "red";
//    if ($num == 1)
//        return "yellow";
    return "green";
}

function mytorrenttable( $res ) {
    global $TBDEV, $CURUSER, $lang;

    $wait = 0;
    $htmlout = '';
    
    if ($CURUSER["class"] < UC_VIP)
    {
      $gigs = $CURUSER["uploaded"] / (1024*1024*1024);
      $ratio = (($CURUSER["downloaded"] > 0) ? ($CURUSER["uploaded"] / $CURUSER["downloaded"]) : 0);
      if ($ratio < 0.5 || $gigs < 5) $wait = 48;
      elseif ($ratio < 0.65 || $gigs < 6.5) $wait = 24;
      elseif ($ratio < 0.8 || $gigs < 8) $wait = 12;
      elseif ($ratio < 0.95 || $gigs < 9.5) $wait = 6;
      else $wait = 0;
    }

  $htmlout .= "
  <table border='1' cellspacing='0' cellpadding='5'>
  <tr>
  <td class='colhead' align='center'>{$lang["torrenttable_type"]}</td>
  <td class='colhead' align='left'>{$lang["torrenttable_name"]}</td>
  <!--<td class='heading' align='left'>{$lang["torrenttable_dl"]}</td>-->";

	if ($wait)
	{
		$htmlout .= "<td class='colhead' align='center'>{$lang["torrenttable_wait"]}</td>\n";
	}

	$htmlout .= "
	<td class='colhead' align='center'>{$lang["torrenttable_edit"]}</td>
  <td class='colhead' align='center'>{$lang["torrenttable_visible"]}</td>
  <td class='colhead' align='right'>{$lang["torrenttable_files"]}</td>
  <td class='colhead' align='right'>{$lang["torrenttable_comments"]}</td>
  <!--<td class='colhead' align='center'>{$lang["torrenttable_rating"]}</td>-->
  <td class='colhead' align='center'>{$lang["torrenttable_added"]}</td>
  <td class='colhead' align='center'>{$lang["torrenttable_ttl"]}</td>
  <td class='colhead' align='center'>{$lang["torrenttable_size"]}</td>
  <!--
  <td class='colhead' align='right'>{$lang["torrenttable_views"]}</td>
  <td class='colhead' align='right'>{$lang["torrenttable_hits"]}</td>
  -->
  <td class='colhead' align='center'>{$lang["torrenttable_snatched"]}</td>
  <td class='colhead' align='right'>{$lang["torrenttable_seeders"]}</td>
  <td class='colhead' align='right'>{$lang["torrenttable_leechers"]}</td>
  </tr>\n";

    while ($row = mysql_fetch_assoc($res)) 
    {
        $id = $row["id"];
        $htmlout .= "<tr>\n";

        $htmlout .= "<td align='center' style='padding: 0px'>";
        if (isset($row["cat_name"])) 
        {
            $htmlout .= "<a href='browse.php?cat={$row['category']}'>";
            if (isset($row["cat_pic"]) && $row["cat_pic"] != "")
                $htmlout .= "<img src='{$TBDEV['pic_base_url']}caticons/{$row['cat_pic']}' alt='{$row['cat_name']}' />";
            else
            {
                $htmlout .= $row["cat_name"];
            }
            $htmlout .= "</a>";
        }
        else
        {
            $htmlout .= "-";
        }
        $htmlout .= "</td>\n";

        $dispname = htmlsafechars( itsawrap($row['name']) );
      
        $disptitle = htmlsafechars( $row['name'] );
        
        $htmlout .= "<td align='left'><a href='details.php?returnto=" . urlencode($_SERVER["REQUEST_URI"]) . "&amp;id=$id' title'$disptitle'><strong>$dispname</strong></a>\n";

				if ($wait)
				{
				  $elapsed = floor((TIME_NOW - $row["added"]) / 3600);
	        if ($elapsed < $wait)
	        {
	          $color = dechex(floor(127*($wait - $elapsed)/48 + 128)*65536);
	          $htmlout .= "<td align='center'><span style='white-space: nowrap;'><a href='faq.php#dl8'><div style='color:$color;'>" . number_format($wait - $elapsed) . " ".$lang["torrenttable_wait_h"]."</div></a></span></td>\n";
	        }
	        else
	          $htmlout .= "<td align='center'><span style='white-space: nowrap;'>{$lang["torrenttable_wait_none"]}</span></td>\n";
        }

/*
        if ($row["nfoav"] && get_user_class() >= UC_POWER_USER)
          print("<a href='viewnfo.php?id=$row[id]''><img src='{$TBDEV['pic_base_url']}viewnfo.gif" border='0' alt='".$lang["torrenttable_view_nfo_alt"]."' /></a>\n");
        if ($variant == "index")
            print("<a href='download.php/$id/" . rawurlencode($row["filename"]) . "'><img src='{$TBDEV['pic_base_url']}download.gif' border='0' alt='".$lang["torrenttable_download_alt"]."' /></a>\n");

        else */ 
  $htmlout .= "</td><td align='center'><a href='edit.php?returnto=" . urlencode($_SERVER["REQUEST_URI"]) . "&amp;id={$row['id']}'>{$lang["torrenttable_edit"]}</a>
  </td>\n";

  $htmlout .= "<td align='right'>";
      if ($row["visible"] == "no")
          $htmlout .= "<b>".$lang["torrenttable_not_visible"]."</b>";
      else
          $htmlout .= "".$lang["torrenttable_visible"]."";
      $htmlout .= "</td>\n";


        if ($row["type"] == "single")
        {
            $htmlout .= "<td align='right'>{$row["numfiles"]}</td>\n";
        }
        else 
        {
            $htmlout .= "<td align='right'><b><a href='filelist.php?id=$id'>" . $row["numfiles"] . "</a></b></td>\n";
        
        }

        if (!$row["comments"])
        {
            $htmlout .= "<td align='right'>{$row["comments"]}</td>\n";
        }
        else 
        {
            $htmlout .= "<td align='right'><b><a href='details.php?id=$id&amp;page=0#startcomments'>" . $row["comments"] . "</a></b></td>\n";
        
        }

/*
        print("<td align='center'>");
        if (!isset($row["rating"]))
            print("---");
        else {
            $rating = round($row["rating"] * 2) / 2;
            $rating = ratingpic($row["rating"]);
            if (!isset($rating))
                print("---");
            else
                print($rating);
        }
        print("</td>\n");
*/
        $htmlout .= "<td align='center'><span style='white-space: nowrap;'>" . str_replace(",", "<br />", get_date( $row['added'],'')) . "</span></td>\n";
        
		$ttl = (28*24) - floor((TIME_NOW - $row["added"]) / 3600);
		
		if ($ttl == 1) 
       $ttl .= "<br />".$lang["torrenttable_hour_singular"].""; 
    else 
       $ttl .= "<br />".$lang["torrenttable_hour_plural"]."";
    
    $htmlout .= "<td align='center'>$ttl</td>\n
    <td align='center'>" . str_replace(" ", "<br />", mksize($row["size"])) . "</td>\n";
//        print("<td align='right'>" . $row["views"] . "</td>\n");
//        print("<td align='right'>" . $row["hits"] . "</td>\n");

        
        if ($row["times_completed"] != 1)
          $_s = "".$lang["torrenttable_time_plural"]."";
        else
          $_s = "".$lang["torrenttable_time_singular"]."";
        $htmlout .= "<td align='center'>" . number_format($row["times_completed"]) . "<br />$_s</td>\n";

        if ($row["seeders"]) 
        {
            $htmlout .= "<td align='right'><b><a class='" . linkcolor($row["seeders"]) . "' href='peerlist.php?id=$id#seeders'>{$row["seeders"]}</a></b></td>\n";
        
        }
        else
        {
            $htmlout .= "<td align='right'><span class='" . linkcolor($row["seeders"]) . "'>" . $row["seeders"] . "</span></td>\n";
        }

        if ($row["leechers"]) 
        {
            $htmlout .= "<td align='right'><b><a class='" . linkcolor($row["leechers"]) . "' href='peerlist.php?id=$id#leechers'>{$row["leechers"]}</a></b></td>\n";
        }
        else
            $htmlout .= "<td align='right'>0</td>\n";


       $htmlout .= "</tr>\n";
    }

    $htmlout .= "</table>\n";

    return $htmlout;
}


?>