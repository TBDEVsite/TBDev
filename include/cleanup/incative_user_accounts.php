<?php



function docleanup( $data ) {
	global $TBDEV;

	set_time_limit(1200);
	ignore_user_abort(1);

	$secs = 42*86400;
	$dt = (TIME_NOW - $secs);
	$maxclass = UC_POWER_USER;
	@mysql_query("DELETE FROM users WHERE status='confirmed' AND class <= $maxclass AND last_access < $dt");

	if( false !== mysql_affected_rows() )
  {
    $data['clean_desc'] = mysql_affected_rows() . " items deleted";
  }
	
	if( $data['clean_log'] )
	{
    cleanup_log( $data );
	}

}

function cleanup_log( $data )
{
  $text = sqlesc($data['clean_title']);
  $added = TIME_NOW;
  $ip = sqlesc($_SERVER['REMOTE_ADDR']);
  $desc = sqlesc($data['clean_desc']);
  
  mysql_query( "INSERT INTO cleanup_log (clog_event, clog_time, clog_ip, clog_desc) VALUES ($text, $added, $ip, {$desc})" ) or sqlerr(__FILE__, __LINE__);
}
?>