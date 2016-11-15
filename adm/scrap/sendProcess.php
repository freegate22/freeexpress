<?php

$_ver = $_POST['ver'] ;

// 버전별로 불러오는 방식 처리.
if( $_ver=='01'){ // cur
  include_once("sendProcess.v01.inc.php");

}else if( $_ver=='03'){ // cur - 미진열기능 추가 : 2013-12-30
  include_once("sendProcess.v03.inc.php");

}else{ // cur
  include_once("sendProcess.v01.inc.php");

}




// end file
