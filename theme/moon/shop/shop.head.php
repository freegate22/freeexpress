<?php
if (!defined("_GNUBOARD_")) exit; // 개별 페이지 접근 불가

if(G5_IS_MOBILE) {
    include_once(G5_THEME_MSHOP_PATH.'/shop.head.php');
    return;
}

include_once(G5_THEME_PATH.'/head.sub.php');
include_once(G5_LIB_PATH.'/outlogin.lib.php');
include_once(G5_LIB_PATH.'/poll.lib.php');
include_once(G5_LIB_PATH.'/visit.lib.php');
include_once(G5_LIB_PATH.'/connect.lib.php');
include_once(G5_LIB_PATH.'/popular.lib.php');
include_once(G5_LIB_PATH.'/latest.lib.php');
?>

<!-- 상단 시작 { -->
<div id="hd">
    <h1 id="hd_h1"><?php echo $g5['title'] ?></h1>

    <div id="skip_to_container"><a href="#container">본문 바로가기</a></div>

    <?php if(defined('_INDEX_')) { // index에서만 실행
        include G5_BBS_PATH.'/newwin.inc.php'; // 팝업레이어
     } ?>

    <div id="tnb">
        <h3>회원메뉴</h3>
        <ul>
            <?php if ($is_member) { ?>
            <?php if ($is_admin) {  ?>
            <li class="thene_adm_btn"><a href="<?php echo G5_THEME_ADM_URL; ?>/" target="_blank"><i class="fa fa-cog" aria-hidden="true"></i><b>테마관리</b></a></li>
            <li><a href="<?php echo G5_ADMIN_URL; ?>/shop_admin/"><b>admin</b></a></li>
            <?php }  ?>
            <li><a href="<?php echo G5_BBS_URL; ?>/logout.php?url=shop">logout</a></li>
            <?php } else { ?>
            <li><a href="<?php echo G5_BBS_URL; ?>/register.php">join us</a></li>
            <li><a href="<?php echo G5_BBS_URL; ?>/login.php?url=<?php echo $urlencode; ?>"><b>login</b></a></li>
            <?php } ?>
            <li><a href="<?php echo G5_SHOP_URL; ?>/mypage.php">mypage</a></li>
            <li><a href="<?php echo G5_SHOP_URL; ?>/couponzone.php">coponzone</a></li>
            <li class="tnb_cart"><a href="<?php echo G5_SHOP_URL; ?>/cart.php"><i class="fa fa-shopping-cart" aria-hidden="true"></i> cart</a></li>
            <li class="tnb_bookmark"><a href="#" onclick="try{window.external.AddFavorite('<?php echo G5_SHOP_URL; ?>','<?php echo $default['de_admin_company_name']; ?>')}catch(e){alert('이 브라우저에서는 즐겨찾기 기능을 사용할 수 없습니다.\n크롬에서는 Ctrl 키와 D 키를 동시에 눌러서 즐겨찾기에 추가할 수 있습니다.')}; return false;"><i class="fa fa-bookmark" aria-hidden="true"></i> bookmark</a> <span class="st_bg"></span> </li>
            <?php if(G5_COMMUNITY_USE) { ?>
            <li><a href="<?php echo G5_URL; ?>/">커뮤니티</a></li>
            <?php } ?>
        </ul>
    </div>
    <div id="hd_wrapper">
        
        <div id="logo"><a href="<?php echo G5_SHOP_URL; ?>/"><img src="<?php echo G5_DATA_URL; ?>/common/logo_img" alt="<?php echo $config['cf_title']; ?>"></a></div>

        <div id="hd_sch">
            <h3>쇼핑몰 검색</h3>
            <form name="frmsearch1" action="<?php echo G5_SHOP_URL; ?>/search.php" onsubmit="return search_submit(this);">

            <label for="sch_str" class="sound_only">검색어<strong class="sound_only"> 필수</strong></label>
            <input type="text" name="q" value="<?php echo stripslashes(get_text(get_search_string($q))); ?>" id="sch_str" required>
            <button type="submit"  id="sch_submit"><i class="fa fa-search" aria-hidden="true"></i><span class="sound_only">검색</span></button>

            </form>
            <script>
            function search_submit(f) {
                if (f.q.value.length < 2) {
                    alert("검색어는 두글자 이상 입력하십시오.");
                    f.q.select();
                    f.q.focus();
                    return false;
                }

                return true;
            }
            </script>
             <?php
            $save_file = G5_DATA_PATH.'/cache/theme/moon/keyword.php';
            if(is_file($save_file))
                include($save_file);

            if(!empty($keyword)) {
            ?>
            <div id="ppl_word">
                <h3>인기검색어</h3>
                <ol class="slides">
                <?php
                foreach($keyword as $word) {
                ?>
                    <li><span class="word-rank"><?php echo $seq; ?></span><a href="<?php echo G5_SHOP_URL; ?>/search.php?q=<?php echo urlencode($word); ?>"><?php echo get_text($word); ?></a></li>
                <?php
                }
                ?>
                </ol>

            </div>
            <?php } ?>
        </div>

        
    </div>
    <nav class="nav">
        <div id="gnb">
            <h2>쇼핑몰 카테고리</h2>
            <ul id="gnb_1dul">
                <li class="cate_btn">
                    <button type="button" id="menu_open"><i class="fa fa-bars" aria-hidden="true"></i><span class="sound_only">전체카테고리</span></button>
                    <?php include_once(G5_THEME_SHOP_PATH.'/category.php'); // 분류 ?>

                </li>
                <?php include_once(G5_SHOP_SKIN_PATH.'/boxcategory.skin.php'); // 상품분류 ?>
            </ul>
        </div>

    </nav>
    <script>
    $(window).scroll(function(){
      var sticky = $('.nav'),
          scroll = $(window).scrollTop();

      if (scroll >= 200) sticky.addClass('fixed');
      else sticky.removeClass('fixed');
    });

    $(function (){
        var $category = $("#category");

        $("#menu_open").on("click", function() {
            $category.css("display","block");
        });

        $("#category .close_btn").on("click", function(){
            $category.css("display","none");
        });
    });
    </script>
</div>

<div id="wrapper">

    <!-- 콘텐츠 시작 { -->
    <div id="container">
        <?php if ((!$bo_table || $w == 's' ) && !defined('_INDEX_')) { ?><div id="wrapper_title"><?php echo $g5['title'] ?></div><?php } ?>

        <?php echo display_banner('왼쪽'); ?>

