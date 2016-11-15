<?php
include_once('./_common.php');
include_once(G5_THEME_PLUGIN_PATH.'/oauth/functions.php');

if($member['mb_id'])
    alert_opener_url();

//var_dump($_REQUEST); exit;

$service = preg_replace('#[^a-z]#', '', $_GET['service']);

switch($service) {
    case 'naver' :
    case 'kakao' :
    case 'facebook' :
    case 'google' :
        break;
    default :
        alert_opener_url('소셜 로그인 서비스가 올바르지 않습니다.');
        break;
}

require G5_THEME_PLUGIN_PATH.'/oauth/'.$service.'/callback.php';
?>

<script>
var popup = window.opener;
var url   = "";

if(popup.document.getElementsByName("url").length)
    url = decodeURIComponent(popup.document.getElementsByName("url")[0].value);

popup.location.href = url;
window.close();
</script>