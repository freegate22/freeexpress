<?php
include_once('./_common.php');

$ca_id = preg_replace("/[\<\>\'\"\\\'\\\"\%\=\(\)\/\^\*\s]/", "", $_GET['ca_id']);

// 분류 Best Item
$limit = $default['de_type4_list_mod'] * $default['de_type4_list_row'];
$best_skin = G5_SHOP_SKIN_PATH.'/'.$default['de_type4_list_skin'];

$sql = " select *
            from {$g5['g5_shop_item_table']}
            where ( ca_id like '$ca_id%' or ca_id2 like '$ca_id%' or ca_id3 like '$ca_id%' )
              and it_use = '1'
              and it_type4 = '1'
            order by it_order, it_id desc
            limit 0, $limit ";

$list = new item_list($best_skin, $default['de_type4_list_mod'], $default['de_type4_list_row'], $default['de_type4_img_width'], $default['de_type4_img_height']);
$list->set_query($sql);
$list->set_view('it_img', true);
$list->set_view('it_id', false);
$list->set_view('it_name', true);
$list->set_view('it_basic', false);
$list->set_view('it_price', true);
echo $list->run();
?>