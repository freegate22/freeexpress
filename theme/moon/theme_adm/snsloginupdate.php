<?php
include_once('./_common.php');

@mkdir(G5_DATA_PATH."/cache/theme", G5_DIR_PERMISSION);
@chmod(G5_DATA_PATH."/cache/theme", G5_DIR_PERMISSION);
@mkdir(G5_DATA_PATH."/cache/theme/moon", G5_DIR_PERMISSION);
@chmod(G5_DATA_PATH."/cache/theme/moon", G5_DIR_PERMISSION);

$save_file = G5_DATA_PATH.'/cache/theme/moon/snslogin.php';

$login = array();

$naver_id     = str_replace(array("\'", '\"', "'", '"'), '', strip_tags(trim($_POST['naver_id'])));
$naver_key    = str_replace(array("\'", '\"', "'", '"'), '', strip_tags(trim($_POST['naver_key'])));
$kakao_key    = str_replace(array("\'", '\"', "'", '"'), '', strip_tags(trim($_POST['kakao_key'])));
$facebook_id  = str_replace(array("\'", '\"', "'", '"'), '', strip_tags(trim($_POST['facebook_id'])));
$facebook_key = str_replace(array("\'", '\"', "'", '"'), '', strip_tags(trim($_POST['facebook_key'])));
$google_id    = str_replace(array("\'", '\"', "'", '"'), '', strip_tags(trim($_POST['google_id'])));
$google_key   = str_replace(array("\'", '\"', "'", '"'), '', strip_tags(trim($_POST['google_key'])));


$login = array('naver_id' => $naver_id, 'naver_key' => $naver_key, 'kakao_key' => $kakao_key, 'facebook_id' => $facebook_id, 'facebook_key' => $facebook_key, 'google_id' => $google_id, 'google_key' => $google_key);

// 캐시파일로 저장
$cache_fwrite = true;
if($cache_fwrite) {
    $handle = fopen($save_file, 'w');
    $cache_content = "<?php\nif (!defined('_GNUBOARD_')) exit;";
    $cache_content .= "\n\n\$snslogin=".var_export($login, true).";";
    fwrite($handle, $cache_content);
    fclose($handle);
}

goto_url('./snslogin.php');
?>