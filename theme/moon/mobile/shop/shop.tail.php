<?php
if (!defined("_GNUBOARD_")) exit; // 개별 페이지 접근 불가

$admin = get_admin("super");

// 사용자 화면 우측과 하단을 담당하는 페이지입니다.
// 우측, 하단 화면을 꾸미려면 이 파일을 수정합니다.
?>

</div><!-- container End -->

<button type="button" id="quick_open"><i class="fa fa-plus" aria-hidden="true"></i><span class="sound_only">퀵메뉴 열기</span></button>

<div id="quick"  class="tab-wr">
    <ul class="qk_btn">

        <li class="tabsTab">
            <a href="<?php echo G5_SHOP_URL; ?>/mypage.php"><i class="fa fa-user" aria-hidden="true"></i><span class="sound_only">마이페이지 </span></a>
        </li>
        <li class="tabsTab">
            <a href="<?php echo G5_SHOP_URL; ?>/cart.php""><i class="fa fa-shopping-cart" aria-hidden="true"></i><span class="sound_only">장바구니</span></a>
        </li>
        <li class="tabsTab">
            <button type="button" class="view_op_btn"><i class="fa fa-archive" aria-hidden="true"></i><span class="sound_only">오늘본상품 열기</span></button>
        </li>
         <li class="tabsTab">
             <button type="button" class="wish_op_btn"><i class="fa fa-heart" aria-hidden="true"></i><span class="sound_only">위시리스트 열기</span></button>
        </li>
    </ul>
    <div  class="tabsCon">
        <div class="qk_con" id="qk_view">
            <div class="qk_con_wr">
            <h3><span>오늘본상품</span></h3>
            <?php include(G5_MSHOP_SKIN_PATH.'/boxtodayview.skin.php'); // 오늘 본 상품 ?>
            <button type="button" class="con_close"><i class="fa fa-times-circle" aria-hidden="true"></i><span class="sound_only">오늘본상품 닫기</span></button>
            </div>
        </div>
        <div class="qk_con tabsList" id="qk_wish">
            <div class="qk_con_wr">
            <h3><a href="<?php echo G5_SHOP_URL; ?>/wishlist.php">위시리스트</a></h3>

            <ul class="qk_prdli">
            <?php
            $sql = " select *
                       from {$g5['g5_shop_wish_table']} a,
                            {$g5['g5_shop_item_table']} b
                      where a.mb_id = '{$member['mb_id']}'
                        and a.it_id  = b.it_id
                      order by a.wi_id desc
                      limit 0, 10 ";
            $result = sql_query($sql);
            for ($i=0; $row = sql_fetch_array($result); $i++)
            {
                $image_w = 80;
                $image_h = 80;
                $image = get_it_image($row['it_id'], $image_w, $image_h, true);
                $list_left_pad = $image_w + 10;
            ?>

            <li>
                <div class="qk_img"><?php echo $image; ?></div>
                <div class="qk_txt">
                    <div  class="qk_name"><a href="./item.php?it_id=<?php echo $row['it_id']; ?>"><?php echo stripslashes($row['it_name']); ?></a></div>
                    <span class="info_date"><?php echo substr($row['wi_time'], 2, 8); ?></span>
                </div>
            </li>

            <?php
            }

            if ($i == 0)
                echo '<li class="empty_list">보관 내역이 없습니다.</li>';
            ?>
            </ul>
            <button type="button" class="con_close"><i class="fa fa-times-circle" aria-hidden="true"></i><span class="sound_only">위시리스트닫기 </span></button>
            </div>
        </div>
    </div>
</div>
<script>
$(function (){

     $(".view_op_btn").on("click", function(){
        $("#qk_view").show();
    });
     $(".wish_op_btn").on("click", function(){
        $("#qk_wish").toggle();
    });
    $(".con_close").on("click", function(){
        $(".qk_con").hide();
    });
    $("#quick_open").on("click", function(){
        $("#quick").toggle();
    });
   

});
$(document).mouseup(function (e){
    var container = $(".qk_con");
    if( container.has(e.target).length === 0)
    container.hide();
});
</script>

<div id="ft">
    <h2><?php echo $config['cf_title']; ?> 정보</h2>
    <ul class="ft-link">
        <li class="ft_li1">
            <?php if ($is_member) { ?>
            <a href="<?php echo G5_BBS_URL; ?>/logout.php?url=shop">로그아웃</a>
            <?php } else { ?>
            <a href="<?php echo G5_BBS_URL; ?>/login.php?url=<?php echo $urlencode; ?>">로그인</a>
            <?php } ?>
        </li>
        <li><a href="<?php echo G5_BBS_URL; ?>/content.php?co_id=company">회사소개</a></li>
        <li><a href="<?php echo G5_BBS_URL; ?>/content.php?co_id=provision">이용약관</a></li>
        <li><a href="#">맨위로</a></li>
    </ul>
    <div class="ft-cs">
        <h3>고객센터</h3><span class="st_bg_2"></span>
         <?php
            $save_file = G5_DATA_PATH.'/cache/theme/someday/footerinfo.php';
            if(is_file($save_file))
                include($save_file);
            ?>
            <strong class="cs_tel"><?php echo get_text($footerinfo['tel']); ?></strong>
            <p class="cs_info"><?php echo get_text($footerinfo['etc'], 1); ?></p>
    </div>
    <div class="ft-board">
        <h3>Board</h3>
        <ul>
            <li><a href="<?php echo G5_BBS_URL; ?>/faq.php">FAQ</a></li>
            <!--<li><a href="<?php echo G5_BBS_URL; ?>/qalist.php">1:1문의</a></li>-->
            <li><a href="<?php echo G5_SHOP_URL; ?>/personalpay.php">개인결제</a></li>
            <li><a href="<?php echo G5_BBS_URL; ?>/board.php?bo_table=notice">공지</a></li>
            <li><a href="<?php echo G5_BBS_URL; ?>/board.php?bo_table=gallery">갤러리</a></li>
        </ul>
    </div>
    <p>
        <span><b>회사명</b> <?php echo $default['de_admin_company_name']; ?></span>
        <span><b>주소</b> <?php echo $default['de_admin_company_addr']; ?></span><br>
        <span><b>사업자 등록번호</b> <?php echo $default['de_admin_company_saupja_no']; ?></span><br>
        <span><b>대표</b> <?php echo $default['de_admin_company_owner']; ?></span>
        <span><b>전화</b> <?php echo $default['de_admin_company_tel']; ?></span>
        <!--<span><b>팩스</b> <?php echo $default['de_admin_company_fax']; ?></span>--><br>
        <!-- <span><b>운영자</b> <?php echo $admin['mb_name']; ?></span><br> -->
        <span><b>통신판매업신고번호</b> <?php echo $default['de_admin_tongsin_no']; ?></span><br>
        <span><b>개인정보관리책임자</b> <?php echo $default['de_admin_info_name']; ?></span>

        <?php if ($default['de_admin_buga_no']) echo '<span><b>부가통신사업신고번호</b> '.$default['de_admin_buga_no'].'</span>'; ?><br>
        Copyright &copy; 2001-2013 <?php echo $default['de_admin_company_name']; ?>. All Rights Reserved.
    </p>
</div>

<?php
$sec = get_microtime() - $begin_time;
$file = $_SERVER['SCRIPT_NAME'];

if ($config['cf_analytics']) {
    echo $config['cf_analytics'];
}
?>

<script src="<?php echo G5_JS_URL; ?>/sns.js"></script>

<?php
include_once(G5_THEME_PATH.'/tail.sub.php');
?>
