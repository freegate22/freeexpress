<?php
include_once('./_common.php');

$ca_id = preg_replace('#[^0-9a-z]#i', '', $_REQUEST['ca_id']);
$ca_id = strtolower($ca_id);

if(!$ca_id)
    die('<li class="li_error">분류코드가 넘어오지 않았습니다.</li>');

$sql = " select it_id, it_name from {$g5['g5_shop_item_table']} where it_use = '1' and (ca_id like '$ca_id%' or ca_id2 like '$ca_id%' or ca_id3 like '$ca_id%') order by it_order, it_id desc ";
$result = sql_query($sql);

for($i=0; $row=sql_fetch_array($result); $i++) {
    $name = get_text($row['it_name']);
    $img  = get_it_image($row['it_id'], 50, 50, false, '', $name);

    if($i == 0)
        $prd_1 = ' class="prd_1"';
    else
        $prd_1 = '';
?>
<li<?php echo $prd_1; ?>>
    <input type="hidden" name="it_id[]" value="<?php echo $row['it_id']; ?>">
    <span class="prd_img"><?php echo $img; ?></span>
    <span class="prd_name"><?php echo $name; ?></span>
    <button type="button" class="add_item">추가</button>
</li>
<?php
}
?>