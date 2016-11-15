<?php
include_once('./_common.php');

if (G5_IS_MOBILE) {
    include_once(G5_THEME_MSHOP_PATH.'/index.php');
    return;
}

define("_INDEX_", TRUE);

include_once(G5_THEME_SHOP_PATH.'/shop.head.php');
?>

<!-- 메인이미지 시작 { -->
<?php echo display_banner('메인', 'mainbanner.10.skin.php'); ?>
<!-- } 메인이미지 끝 -->
<div class="idx_ev_nt">
    <?php include_once(G5_SHOP_SKIN_PATH.'/boxevent.skin.php'); // 이벤트 ?>
    <div id="idx_notice">
        <?php
        // 이 함수가 바로 최신글을 추출하는 역할을 합니다.
        // 사용방법 : latest(스킨, 게시판아이디, 출력라인, 글자수);
        // 테마의 스킨을 사용하려면 theme/basic 과 같이 지정
        echo latest('theme/basic', 'notice', 4, 18);
        ?>
        <div id="idx_cs">
            <h2>고객센터</h2>
             <?php
            $save_file = G5_DATA_PATH.'/cache/theme/moon/footerinfo.php';
            if(is_file($save_file))
                include($save_file);
            ?>
            <strong class="cs_tel"><?php echo get_text($footerinfo['tel']); ?></strong>
            <p class="cs_info"><?php echo get_text($footerinfo['etc'], 1); ?></p>
        </div>
    </div>
</div>


<div id="idx_magazine">
    <?php
    // 이 함수가 바로 최신글을 추출하는 역할을 합니다.
    // 사용방법 : latest(스킨, 게시판아이디, 출력라인, 글자수);
    // 테마의 스킨을 사용하려면 theme/basic 과 같이 지정
    $options = array(
        'thumb_width'    => 380, // 썸네일 width
        'thumb_height'   => 240,  // 썸네일 height
        'content_length' => 20   // 간단내용 길이
    );
    echo latest('theme/magazine', 'gallery', 3, 40, 1, $options);
    ?>
</div>

<?php
if($default['de_type4_list_use']) {
    $save_file = G5_DATA_PATH.'/cache/theme/moon/mainbestcategory.php';
    if(is_file($save_file))
        include($save_file);

    $cnt = 0;
    $first_ca_id = '';

    if(!empty($mainbestcategory)) {
        foreach($mainbestcategory as $val) {
            $sql = " select ca_id, ca_name from {$g5['g5_shop_category_table']} where ca_id = '$val' and ca_use = '1' ";
            $row = sql_fetch($sql);

            if(!$row['ca_id'])
                continue;

            $tab_class = '';
            $tab_selected = '';

           if($cnt == 0) {
                echo '<section id="cate_best">'.PHP_EOL;
                echo '<header>'.PHP_EOL;
                echo '<h2>인기상품</h2>'.PHP_EOL;
                echo '</header>'.PHP_EOL;
                echo '<div class="tab">'.PHP_EOL;
                echo '<ul>'.PHP_EOL;
                $tab_class = ' class="tab-1"';
                $tab_selected = ' tab_selected';
                $first_ca_id = $val;
            }
        ?>
            <li<?php echo $tab_class; ?>><button type="button" data-ca_id="<?php echo $val; ?>" class="category_best<?php echo $tab_selected; ?>"><?php echo get_text($row['ca_name']); ?></button></li>
        <?php
            $cnt++;
        }

        if($cnt > 0) {
            echo '</ul>'.PHP_EOL;
            echo '</div>'.PHP_EOL;
            $_GET['ca_id'] = $first_ca_id;
            echo '<div id="cate_best_item">'.PHP_EOL;
            include_once(G5_THEME_SHOP_PATH.'/ajax.mainbestitem.php');
            echo '</div>'.PHP_EOL;
            echo '</section>'.PHP_EOL;
        }
    }
?>

<script>
$(function() {
    $(".category_best").on("click", function() {
        var $this = $(this);
        if($this.hasClass("tab_selected"))
            return false;

        var ca_id = $this.data("ca_id");

        $.ajax({
            type: "GET",
            url: g5_theme_shop_url+"/ajax.mainbestitem.php",
            data: { ca_id: ca_id },
            async: true,
            cache: false,
            success: function(data) {
                $("#cate_best_item").html(data);
                $(".category_best").removeClass("tab_selected");
                $this.addClass("tab_selected");
            }
        });
    });
});
</script>

<?php
}
?>

<?php if($default['de_type1_list_use']) { ?>
<!-- 히트상품 시작 { -->
<section class="sct_wrap">
    <h2><a href="<?php echo G5_SHOP_URL; ?>/listtype.php?type=1">히트상품</a></h2>
    <?php
    $list = new item_list();
    $list->set_type(1);
    $list->set_view('it_img', true);
    $list->set_view('it_id', true);
    $list->set_view('it_name', true);
    $list->set_view('it_basic', true);
    $list->set_view('it_cust_price', true);
    $list->set_view('it_price', true);
    $list->set_view('it_icon', true);
    $list->set_view('sns', true);
    echo $list->run();
    ?>
</section>
<!-- } 히트상품 끝 -->
<?php } ?>

<?php if($default['de_type2_list_use']) { ?>
<!-- 추천상품 시작 { -->
<section class="sct_wrap">
    <h2><a href="<?php echo G5_SHOP_URL; ?>/listtype.php?type=2">추천상품</a></h2>
    <?php
    $list = new item_list();
    $list->set_type(2);
    $list->set_view('it_id', false);
    $list->set_view('it_name', true);
    $list->set_view('it_basic', true);
    $list->set_view('it_cust_price', true);
    $list->set_view('it_price', true);
    $list->set_view('it_icon', true);
    $list->set_view('sns', true);
    echo $list->run();
    ?>
</section>
<!-- } 추천상품 끝 -->
<?php } ?>

<?php if($default['de_type3_list_use']) { ?>
<!-- 최신상품 시작 { -->
<section class="sct_wrap">
    <h2><a href="<?php echo G5_SHOP_URL; ?>/listtype.php?type=3">최신상품</a></h2>
    <?php
    $list = new item_list();
    $list->set_type(3);
    $list->set_view('it_id', false);
    $list->set_view('it_name', true);
    $list->set_view('it_basic', true);
    $list->set_view('it_cust_price', true);
    $list->set_view('it_price', true);
    $list->set_view('it_icon', true);
    $list->set_view('sns', true);
    echo $list->run();
    ?>
</section>
<!-- } 최신상품 끝 -->
<?php } ?>

<?php if($default['de_type5_list_use']) { ?>
<!-- 할인상품 시작 { -->
<section class="sct_wrap">
    <h2><a href="<?php echo G5_SHOP_URL; ?>/listtype.php?type=5">할인상품</a></h2>
    <?php
    $list = new item_list();
    $list->set_type(5);
    $list->set_view('it_id', false);
    $list->set_view('it_name', true);
    $list->set_view('it_basic', true);
    $list->set_view('it_cust_price', true);
    $list->set_view('it_price', true);
    $list->set_view('it_icon', true);
    $list->set_view('sns', true);
    echo $list->run();
    ?>
</section>
<!-- } 할인상품 끝 -->
<?php } ?>

<?php
include_once(G5_THEME_SHOP_PATH.'/shop.tail.php');
?>