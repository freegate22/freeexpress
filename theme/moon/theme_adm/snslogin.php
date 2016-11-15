<?php
include_once('_common.php');

$g5['title'] = 'SNS 소셜로그인';
include_once('./head.php');
?>

<form name="fsnslogin" id="fsnslogin" method="post" action="./snsloginupdate.php">
<div class="btn_confirm"><input type="submit" class="btn_save" value="저장"></div>

<div class="con_wr" id="snslogin">
    <ul>
    <?php
    $save_file = G5_DATA_PATH.'/cache/theme/moon/snslogin.php';
    if(is_file($save_file))
        include($save_file);
    ?>
        <li>
            <h3>네이버로그인 API 정보</h3>
            <label for="naver_id">ID</label>
            <input type="text" name="naver_id" id="naver_id" class="frm_input" value="<?php echo get_text($snslogin['naver_id']); ?>"><br>
            <label for="naver_key">KEY</label>
            <input type="text" name="naver_key" id="naver_key" class="frm_input" value="<?php echo get_text($snslogin['naver_key']); ?>">
        </li>
        <li>
            <h3>카카오로그인 API 정보</h3>
            <label for="kakao_key">KEY</label>
            <input type="text" name="kakao_key" id="kakao_key" class="frm_input" value="<?php echo get_text($snslogin['kakao_key']); ?>">
        </li>
        <li>
            <h3>페이스북로그인 API 정보</h3>
            <label for="facebook_id">ID</label>
            <input type="text" name="facebook_id" id="facebook_id" class="frm_input" value="<?php echo get_text($snslogin['facebook_id']); ?>"><br>
            <label for="facebook_key">KEY</label>
            <input type="text" name="facebook_key" id="facebook_key" class="frm_input" value="<?php echo get_text($snslogin['facebook_key']); ?>">
        </li>
        <li>
            <h3>구글+로그인 API 정보</h3>
            <label for="google_id">ID</label>
            <input type="text" name="google_id" id="google_id" class="frm_input" value="<?php echo get_text($snslogin['google_id']); ?>"><br>
            <label for="google_key">KEY</label>
            <input type="text" name="google_key" id="google_key" class="frm_input" value="<?php echo get_text($snslogin['google_key']); ?>">
        </li>
    </ul>
</div>
</form>

<?php
include_once('./tail.php');
?>