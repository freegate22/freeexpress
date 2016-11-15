<?php
include_once('_common.php');

$g5['title'] = 'SNS링크';
include_once('./head.php');
?>

<form name="fsnslink" id="fsnslink" method="post" action="./snslinkupdate.php">
<div class="btn_confirm"><input type="submit" class="btn_save" value="저장"></div>

<div id="sns" class="con_wr">
    <ul>
    <?php
    $save_file = G5_DATA_PATH.'/cache/theme/moon/snslink.php';
    if(is_file($save_file))
        include($save_file);
    ?>
        <li class="li_clear">
            <div class="li_wr">
                <label for="facebook">페이스북</label>
                <input type="text" name="facebook" id="facebook" class="frm_input" value="<?php echo get_text($snslink['facebook']); ?>">
            </div>
        </li>
        <li class="li_clear">
            <div class="li_wr">
                <label for="twitter">트위터</label>
                <input type="text" name="twitter" id="twitter" class="frm_input" value="<?php echo get_text($snslink['twitter']); ?>">
            </div>
        </li>
        <li class="li_clear">
            <div class="li_wr">
                <label for="instagram">인스타그램</label>
                <input type="text" name="instagram" id="instagram" class="frm_input" value="<?php echo get_text($snslink['instagram']); ?>">
            </div>
        </li>
    </ul>
</div>
</form>

<?php
include_once('./tail.php');
?>