<?php
if (!defined('_GNUBOARD_')) exit; // 개별 페이지 접근 불가

include_once('./head.sub.php');
add_stylesheet('<link rel="stylesheet" href="./css/style.css">', 0);

$adm_menu = array(
    array('url' => 'maincategory.php',     'text' => '메인카테고리설정'),
    array('url' => 'mainbestcategory.php', 'text' => '메인베스트 카테고리설정'),
    array('url' => 'keyword.php',          'text' => '인기검색어설정'),
    array('url' => 'footerinfo.php',       'text' => '고객센터'),
    array('url' => 'snslink.php',          'text' => 'SNS링크'),
    array('url' => 'snslogin.php',          'text' => 'SNS소셜로그인')

);
?>

<div id="head">
    <h1><a href="./index.php">Moon 테마관리</a></h1>
    <div class="go_home"><a href="<?php echo G5_URL ?>">홈으로가기</a></div>
    <div id="left_menu">
        <h2>테마설정메뉴</h2>
        <ul>
        <?php
        foreach($adm_menu as $menu) {
            if(basename($_SERVER['SCRIPT_NAME']) == $menu['url'])
                $menu_on = ' class="menu_on"';
            else
                $menu_on = '';
        ?>
            <li><a href="./<?php echo $menu['url']; ?>"<?php echo $menu_on; ?>><?php echo $menu['text']; ?></a></li>
        <?php
        }
        ?>
        </ul>
    </div>
</div>
<div id="container">
    <h2 id="container_title"><?php echo $g5['title'] ?></h2>
    <div class="container_wr">