<?php
if (!defined('_GNUBOARD_')) exit; // 개별 페이지 접근 불가

function item_icon2($it)
{
    global $g5;

    $icon = '<span class="sct_icon">';
    // 품절
    if (is_soldout($it['it_id']))
        $icon .= '<img src="'.G5_THEME_IMG_URL.'//icon_soldout.gif" alt="품절">';

    if ($it['it_type1'])
        $icon .= '<img src="'.G5_THEME_IMG_URL.'/icon_hit.gif" alt="히트상품">';

    if ($it['it_type2'])
        $icon .= '<img src="'.G5_THEME_IMG_URL.'/icon_rec.gif" alt="추천상품">';

    if ($it['it_type3'])
        $icon .= '<img src="'.G5_THEME_IMG_URL.'/icon_new.gif" alt="최신상품">';

    if ($it['it_type4'])
        $icon .= '<img src="'.G5_THEME_IMG_URL.'/icon_best.gif" alt="인기상품">';

    if ($it['it_type5'])
        $icon .= '<img src="'.G5_THEME_IMG_URL.'/icon_discount.gif" alt="할인상품">';

    // 쿠폰상품
    $sql = " select count(*) as cnt
                from {$g5['g5_shop_coupon_table']}
                where cp_start <= '".G5_TIME_YMD."'
                  and cp_end >= '".G5_TIME_YMD."'
                  and (
                        ( cp_method = '0' and cp_target = '{$it['it_id']}' )
                        OR
                        ( cp_method = '1' and ( cp_target IN ( '{$it['ca_id']}', '{$it['ca_id2']}', '{$it['ca_id3']}' ) ) )
                      ) ";
    $row = sql_fetch($sql);
    if($row['cnt'])
        $icon .= '<img src="'.G5_THEME_IMG_URL.'/icon_cp.gif" alt="쿠폰상품">';

    $icon .= '</span>';

    return $icon;
}

function get_item_event_info($it_id)
{
    global $g5;

    $data = array();

    $sql = " select distinct ev_id from {$g5['g5_shop_event_item_table']} where it_id = '$it_id' ";
    $result = sql_query($sql);

    for($i=0; $row=sql_fetch_array($result); $i++) {
        // 이벤트정보
        $sql = " select ev_id, ev_subject from {$g5['g5_shop_event_table']} where ev_id = '{$row['ev_id']}' and ev_use = '1' ";
        $ev  = sql_fetch($sql);
        if(!$ev['ev_id'])
            continue;

        // 배너이미지
        $file = G5_DATA_PATH.'/event/'.$ev['ev_id'].'_m';
        if(!is_file($file))
            continue;

        $subject = $ev['ev_subject'];
        $img     = str_replace(G5_DATA_PATH, G5_DATA_URL, $file);

        $data[] = array('ev_id' => $row['ev_id'], 'subject' => $subject, 'img' => $img);
    }

    return $data;
}
?>