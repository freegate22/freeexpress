<?php
if (!defined("_GNUBOARD_")) exit; // 개별 페이지 접근 불가

$sct_sort_href = $_SERVER['SCRIPT_NAME'].'?';
if($ca_id)
    $sct_sort_href .= 'ca_id='.$ca_id;
else if($ev_id)
    $sct_sort_href .= 'ev_id='.$ev_id;
if($skin)
    $sct_sort_href .= '&amp;skin='.$skin;
$sct_sort_href .= '&amp;sort=';

// add_stylesheet('css 구문', 출력순서); 숫자가 작을 수록 먼저 출력됨
add_stylesheet('<link rel="stylesheet" href="'.G5_SHOP_CSS_URL.'/style.css">', 0);
?>

<!-- 상품 정렬 선택 시작 { -->
<section id="sct_sort">
    <h2>상품 정렬</h2>

    <select id="ssch_sort"  onChange="window.location.href=this.value">
        <option value="<?php echo $sct_sort_href; ?>it_price&amp;sortodr=asc">낮은가격순</option>
        <option value="<?php echo $sct_sort_href; ?>it_price&amp;sortodr=desc">높은가격순</option>
        <option value="<?php echo $sct_sort_href; ?>it_name&amp;sortodr=asc">상품명순</option>
    </select>
</section>
<!-- } 상품 정렬 선택 끝 -->