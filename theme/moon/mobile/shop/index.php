<?php
include_once('./_common.php');

define("_INDEX_", TRUE);

include_once(G5_THEME_MSHOP_PATH.'/shop.head.php');
?>


<?php echo display_banner('메인', 'mainbanner.10.skin.php'); ?>
<?php include_once(G5_MSHOP_SKIN_PATH.'/main.event.skin.php'); // 이벤트 ?>

<div id="sidx">
    <?php if($default['de_mobile_type1_list_use']) { ?>
    <div class="sct_wrap">
        <header>
            <h2><a href="<?php echo G5_SHOP_URL; ?>/listtype.php?type=1">히트상품</a></h2>
            <p class="sct_wrap_hdesc"><?php echo $config['cf_title']; ?> 히트상품 모음</p>
        </header>
        <?php
        $list = new item_list();
        $list->set_mobile(true);
        $list->set_type(1);
        $list->set_view('it_id', false);
        $list->set_view('it_name', true);
        $list->set_view('it_cust_price', true);
        $list->set_view('it_price', true);
        $list->set_view('it_icon', true);
        $list->set_view('sns', false);
        echo $list->run();
        ?>
    </div>
    <?php } ?>

    <?php if($default['de_mobile_type2_list_use']) { ?>
    <div class="sct_wrap">
        <header>
            <h2><a href="<?php echo G5_SHOP_URL; ?>/listtype.php?type=2">추천상품</a></h2>
            <p class="sct_wrap_hdesc"><?php echo $config['cf_title']; ?> 추천상품 모음</p>
        </header>
        <?php
        $list = new item_list();
        $list->set_mobile(true);
        $list->set_type(2);
        $list->set_view('it_id', false);
        $list->set_view('it_name', true);
        $list->set_view('it_cust_price', true);
        $list->set_view('it_price', true);
        $list->set_view('it_icon', true);
        $list->set_view('sns', false);
        echo $list->run();
        ?>
    </div>
    <?php } ?>

    <?php if($default['de_mobile_type3_list_use']) { ?>
    <div class="sct_wrap">
        <header>
            <h2><a href="<?php echo G5_SHOP_URL; ?>/listtype.php?type=3">최신상품</a></h2>
            <p class="sct_wrap_hdesc"><?php echo $config['cf_title']; ?> 최신상품 모음</p>
        </header>
        <?php
        $list = new item_list();
        $list->set_mobile(true);
        $list->set_type(3);
        $list->set_view('it_id', false);
        $list->set_view('it_name', true);
        $list->set_view('it_cust_price', true);
        $list->set_view('it_price', true);
        $list->set_view('it_icon', true);
        $list->set_view('sns', false);
        echo $list->run();
        ?>
    </div>
    <?php } ?>

    <?php if($default['de_mobile_type4_list_use']) { ?>
    <div class="sct_wrap">
        <header>
            <h2><a href="<?php echo G5_SHOP_URL; ?>/listtype.php?type=4">인기상품</a></h2>
            <p class="sct_wrap_hdesc"><?php echo $config['cf_title']; ?> 인기상품 모음</p>
       </header>
        <?php
        $list = new item_list();
        $list->set_mobile(true);
        $list->set_type(4);
        $list->set_view('it_id', false);
        $list->set_view('it_name', true);
        $list->set_view('it_cust_price', true);
        $list->set_view('it_price', true);
        $list->set_view('it_icon', true);
        $list->set_view('sns', false);
        echo $list->run();
        ?>
    </div>
    <?php } ?>

    <?php if($default['de_mobile_type5_list_use']) { ?>
    <div class="sct_wrap">
        <header>
            <h2><a href="<?php echo G5_SHOP_URL; ?>/listtype.php?type=5">할인상품</a></h2>
            <p class="sct_wrap_hdesc"><?php echo $config['cf_title']; ?> 할인상품 모음</p>
        </header>
        <?php
        $list = new item_list();
        $list->set_mobile(true);
        $list->set_type(5);
        $list->set_view('it_id', false);
        $list->set_view('it_name', true);
        $list->set_view('it_cust_price', true);
        $list->set_view('it_price', true);
        $list->set_view('it_icon', true);
        $list->set_view('sns', false);
        echo $list->run();
        ?>
    </div>
    <?php } ?>

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
    echo latest('theme/magazine', 'gallery', 4, 40, 1, $options);
    ?>
    </div>
</div>


<?php
include_once(G5_THEME_MSHOP_PATH.'/shop.tail.php');
?>