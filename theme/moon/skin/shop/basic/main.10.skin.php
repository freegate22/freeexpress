<?php
if (!defined('_GNUBOARD_')) exit; // 개별 페이지 접근 불가
include_once(G5_THEME_LIB_PATH.'/theme.shop.lib.php');
// add_stylesheet('css 구문', 출력순서); 숫자가 작을 수록 먼저 출력됨
add_stylesheet('<link rel="stylesheet" href="'.G5_SHOP_SKIN_URL.'/style.css">', 0);
?>

<!-- 상품진열 10 시작 { -->
<?php
for ($i=1; $row=sql_fetch_array($result); $i++) {
    if ($this->list_mod >= 2) { // 1줄 이미지 : 2개 이상
        if ($i%$this->list_mod == 0) $sct_last = 'sct_last'; // 줄 마지막
        else if ($i%$this->list_mod == 1) $sct_last = 'sct_clear'; // 줄 첫번째
        else $sct_last = '';
    } else { // 1줄 이미지 : 1개
        $sct_last = 'sct_clear';
    }

    if ($i == 1) {
        if ($this->css) {
            echo "<ul class=\"{$this->css}\">\n";
        } else {
            echo "<ul class=\"sct sct_10\">\n";
        }
    }

    echo "<li class=\"sct_li {$sct_last}\" style=\"width:{$this->img_width}px\">\n";

     echo "<div class=\"sct_img\">\n";
    if ($this->href) {
        echo "<a href=\"{$this->href}{$row['it_id']}\" class=\"sct_a\">\n";
    }

    if ($this->view_it_img) {
        echo get_it_image($row['it_id'], $this->img_width, $this->img_height, '', '', stripslashes($row['it_name']))."\n";
    }

    if ($this->href) {
        echo "</a>\n";
    }
    
    echo "</div>\n";

     echo "<div class=\"sct_txt\">\n";

    if ($this->href) {
        echo "<a href=\"{$this->href}{$row['it_id']}\" class=\"sct_name\">\n";
    }

    if ($this->view_it_name) {
        echo stripslashes($row['it_name'])."\n";
    }

    if ($this->href) {
        echo "</a>\n";
    }

    if ($this->view_it_cust_price || $this->view_it_price) {

        echo "<div class=\"sct_cost\">\n";

        if ($this->view_it_cust_price && $row['it_cust_price']) {
            echo "<strike>".display_price($row['it_cust_price'])."</strike>\n";
        }

        if ($this->view_it_price) {
            echo display_price(get_price($row), $row['it_tel_inq'])."\n";
        }

        echo "</div>\n";

    }

    echo "<div class=\"sct_icon_wr\">".item_icon2($row)."</div>\n";

    echo "<div class=\"sct_rvws\">\n";
    echo "<span class=\"sct_ws\"><i class=\"fa fa-heart\" aria-hidden=\"true\"></i><span class=\"sound_only\">위시리스트</span> ".number_format(get_wish_count($row['it_id']))."</span>\n";
    echo "<span class=\"sct_rv\"><i class=\"fa fa-commenting\" aria-hidden=\"true\"></i><span class=\"sound_only\">사용후기</span> ".number_format(get_use_count($row['it_id']))."</span>\n";

    echo "</div>\n";

   echo "</div>\n";
    echo "</li>\n";
}

if ($i > 1) echo "</ul>\n";

if($i == 1) echo "<p class=\"sct_noitem\">등록된 상품이 없습니다.</p>\n";
?>
<!-- } 상품진열 10 끝 -->
