<?php
if (!defined('_GNUBOARD_')) exit; // 개별 페이지 접근 불가

$snslogin = array();

$save_file = G5_DATA_PATH.'/cache/theme/moon/snslogin.php';
if(is_file($save_file))
    include($save_file);

// 네이버로그인 API 정보
define('G5_NAVER_OAUTH_CLIENT_ID',  $snslogin['naver_id']);
define('G5_NAVER_OAUTH_SECRET_KEY', $snslogin['naver_key']);

// 카카오로그인 API 정보
define('G5_KAKAO_OAUTH_REST_API_KEY', $snslogin['kakao_key']);

// 페이스북로그인 API 정보
define('G5_FACEBOOK_CLIENT_ID',  $snslogin['facebook_id']);
define('G5_FACEBOOK_SECRET_KEY', $snslogin['facebook_key']);

// 구글+ 로그인 API 정보
define('G5_GOOGLE_CLIENT_ID',  $snslogin['google_id']);
define('G5_GOOGLE_SECRET_KEY', $snslogin['google_key']);

// OAUTH Callback URL
define('G5_OAUTH_CALLBACK_URL', G5_THEME_PLUGIN_URL.'/oauth/callback.php');

if($oauth_mb_no = get_session('ss_oauth_member_no')) {
    $member = get_session('ss_oauth_member_'.$oauth_mb_no.'_info');
    $is_member = true;
    $is_guest  = false;
}
?>