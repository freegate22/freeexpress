<?php
include_once('./_common.php');
include_once(G5_THEME_PLUGIN_PATH.'/oauth/functions.php');

if($member['mb_id'])
    alert_opener_url();

add_stylesheet('<link rel="stylesheet" href="'.G5_THEME_PLUGIN_URL.'/oauth/style.css">', 10);
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

require G5_THEME_PLUGIN_PATH.'/oauth/'.$service.'/login.php';

$g5['title'] = '소셜 로그인';
include_once(G5_PATH.'/head.sub.php');
?>

<div class="social-login-loading">
    <p>소셜 로그인 서비스에 연결 중입니다.<br>잠시만 기다려 주십시오<br><br><img src="<?php echo G5_THEME_PLUGIN_URL; ?>/oauth/img/loading_icon.gif" alt="로딩중"></p>
</div>

<script>
$(function() {
    document.location.href = "<?php echo $query; ?>";
});
</script>

<?php
include_once(G5_PATH.'/tail.sub.php');
?>