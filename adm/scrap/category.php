<?php
header("Content-Type: text/html; charset=utf-8");
include("_common.php");

sql_query(" set names UTF8 ");

$sql = " SELECT ca_id , ca_name FROM g5_shop_category WHERE ca_use = 1 ORDER BY ca_id ASC ";
$res = sql_query($sql);
if ( sql_num_rows($res) > 0 ) {
  $arr = array();
  while ( $row=sql_fetch_array($res) ) {
    $arr[$row['ca_id']] = $row['ca_name'];
  } // end while

  echo(serialize($arr));
} else {

} // end if.


// end file
