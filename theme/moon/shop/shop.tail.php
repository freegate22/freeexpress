<?php
if (!defined("_GNUBOARD_")) exit; // 개별 페이지 접근 불가

if (G5_IS_MOBILE) {
    include_once(G5_THEME_MSHOP_PATH.'/shop.tail.php');
    return;
}

$admin = get_admin("super");

// 사용자 화면 우측과 하단을 담당하는 페이지입니다.
// 우측, 하단 화면을 꾸미려면 이 파일을 수정합니다.
?>

    </div>
    <!-- } 콘텐츠 끝 -->

<!-- 하단 시작 { -->
</div>




<div id="quick"  class="tab-wr">
    <ul class="qk_btn">

        <li class="tabsTab">
            <a href="<?php echo G5_SHOP_URL; ?>/mypage.php""><i class="fa fa-user" aria-hidden="true"></i><span class="sound_only">마이페이지 열기</span></a>
        </li>
        <li class="tabsTab">
            <button type="button" class="cart_op_btn"><i class="fa fa-shopping-cart" aria-hidden="true"></i><span class="sound_only">장바구니 열기</span></button>
        </li>
        <li class="tabsTab">
            <button type="button" class="view_op_btn"><i class="fa fa-archive" aria-hidden="true"></i><span class="sound_only">오늘본상품 열기</span></button>
        </li>
         <li class="tabsTab">
             <button type="button" class="wish_op_btn"><i class="fa fa-heart" aria-hidden="true"></i><span class="sound_only">위시리스트 열기</span></button>
        </li>
    </ul>
    <div  class="tabsCon">
        <div class="qk_con" id="qk_cart">
            <div class="qk_con_wr">

            <h3><a href="<?php echo G5_SHOP_URL; ?>/cart.php">장바구니</a></h3>
            <div class="hdqk_wr">
                <div class="hdqk_wr" id="q_cart_wr"></div>
             <script>
            $(function(){
                $(".cart_op_btn").on("click", function() {
                    var $this = $(this);

                    $("#q_cart_wr").load(
                        g5_theme_shop_url+"/ajax.cart.php",
                        function() {
                            $this.next(".hdqk_wr").show();
                        }
                    );
                });
            });
            </script>
            </div>
            <button type="button" class="con_close"><i class="fa fa-times-circle" aria-hidden="true"></i><span class="sound_only">장바구니 닫기</span></button>
            </div>
        </div>
        <div class="qk_con" id="qk_view">
            <div class="qk_con_wr">
            <h3><span>오늘본상품</span></h3>
            <?php include(G5_SHOP_SKIN_PATH.'/boxtodayview.skin.php'); // 오늘 본 상품 ?>
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
     $(".cart_op_btn").on("click", function(){
        $("#qk_cart").show();
    });
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
    <div class="ft_wr">
        <ul class="ft_link">
            <li><a href="<?php echo G5_BBS_URL; ?>/content.php?co_id=company">회사소개</a></li>
            <li><a href="<?php echo G5_BBS_URL; ?>/content.php?co_id=provision">서비스이용약관</a></li>
            <li><a href="<?php echo G5_BBS_URL; ?>/content.php?co_id=privacy">개인정보처리방침</a></li>
        </ul>
        <div class="ft_sns">
            <h2>sns 링크</h2>
            <?php
            $save_file = G5_DATA_PATH.'/cache/theme/moon/snslink.php';
            if(is_file($save_file))
                include($save_file);
            ?>
            <?php if(isset($snslink['facebook']) && $snslink['facebook']) { ?>
            <a href="<?php echo set_http($snslink['facebook']); ?>" class="sns_f" target="_blank" ><i class="fa fa-facebook" aria-hidden="true"></i><span class="sound_only">페이스북</span></a>
            <?php } ?>
            <?php if(isset($snslink['twitter']) && $snslink['twitter']) { ?>
            <a href="<?php echo set_http($snslink['twitter']); ?>" class="sns_t" target="_blank" ><i class="fa fa-twitter" aria-hidden="true"></i><span class="sound_only">트위터</span></a>
            <?php } ?>
            <?php if(isset($snslink['instagram']) && $snslink['instagram']) { ?>
            <a href="<?php echo set_http($snslink['instagram']); ?>" target="_blank" class="sns_i"><i class="fa fa-instagram" aria-hidden="true"></i><span class="sound_only">인스타그램</span></a>
            <?php } ?>
        </div>
        <div class="ft_info">
            <h2>Info</h2>
            <p>
            <span><b>회사명.</b> <?php echo $default['de_admin_company_name']; ?></span><br>
            <span><b>주소.</b> <?php echo $default['de_admin_company_addr']; ?></span><br>
            <span><b>사업자 등록번호.</b> <?php echo $default['de_admin_company_saupja_no']; ?></span>
            <span><b>대표.</b> <?php echo $default['de_admin_company_owner']; ?></span>
            <span><b>전화.</b> <?php echo $default['de_admin_company_tel']; ?></span>
            <span><b>팩스.</b> <?php echo $default['de_admin_company_fax']; ?></span><br>
            <!-- <span><b>운영자.</b> <?php echo $admin['mb_name']; ?></span><br> -->
            <span><b>통신판매업신고번호.</b> <?php echo $default['de_admin_tongsin_no']; ?></span>
            <span><b>개인정보 보호책임자.</b> <?php echo $default['de_admin_info_name']; ?></span>

            <?php if ($default['de_admin_buga_no']) echo '<span><b>부가통신사업신고번호</b> '.$default['de_admin_buga_no'].'</span>'; ?><br>
            <span class="copyright">Copyright &copy; 2001-2017 <?php echo $default['de_admin_company_name']; ?>. All Rights Reserved.</span>
            </p>
        </div>
        <div class="ft_board">
            <h2>Board</h2>
            <ul>
                <li><a href="<?php echo G5_BBS_URL; ?>/faq.php">FAQ</a></li>
                <li><a href="<?php echo G5_BBS_URL; ?>/qalist.php">1:1문의</a></li>
                <li><a href="<?php echo G5_SHOP_URL; ?>/personalpay.php">개인결제</a></li>
                <li><a href="<?php echo G5_BBS_URL; ?>/board.php?bo_table=notice">공지</a></li>
                <li><a href="<?php echo G5_BBS_URL; ?>/board.php?bo_table=gallery">갤러리</a></li>
            </ul>
        </div>
        <button type="button" id="top_btn"><img src="<?php echo G5_THEME_IMG_URL; ?>/top_btn.gif" alt="상단으로"></button>
    </div>
    <script>
    $(function() {
        $("#top_btn").on("click", function() {
            $("html, body").animate({scrollTop:0}, '500');
            return false;
        });
    });
    </script>
</div>

<?php
$sec = get_microtime() - $begin_time;
$file = $_SERVER['SCRIPT_NAME'];

if ($config['cf_analytics']) {
    echo $config['cf_analytics'];
}
?>

<script src="<?php echo G5_JS_URL; ?>/sns.js"></script>
<!-- } 하단 끝 -->

<?php
include_once(G5_THEME_PATH.'/tail.sub.php');
?>
