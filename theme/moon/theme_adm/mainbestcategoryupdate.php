<?php
include_once('./_common.php');

$save_file = G5_DATA_PATH.'/cache/theme/moon/mainbestcategory.php';

$count  = count($_POST['ca_id']);
$ca_ids = array();

for($i=0; $i<$count; $i++) {
    $ca_id = substr(trim($_POST['ca_id'][$i]), 0, 2);

    $sql = " select count(*) as cnt from {$g5['g5_shop_category_table']} where ca_id = '$ca_id' and length(ca_id) = '2' ";
    $row = sql_fetch($sql);

    if(!$row['cnt'])
        continue;

    if(in_array($ca_id, $ca_ids))
        continue;

    $ca_ids[] = $ca_id;
}

// 캐시파일로 저장
$cache_fwrite = true;
if($cache_fwrite) {
    $handle = fopen($save_file, 'w');
    $cache_content = "<?php\nif (!defined('_GNUBOARD_')) exit;";
    $cache_content .= "\n\n\$mainbestcategory=".var_export($ca_ids, true).";";
    fwrite($handle, $cache_content);
    fclose($handle);
}

goto_url('./mainbestcategory.php');
?>