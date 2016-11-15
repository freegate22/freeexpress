<?php
if (!defined("_GNUBOARD_")) exit; // 개별 페이지 접근 불가

// add_stylesheet('css 구문', 출력순서); 숫자가 작을 수록 먼저 출력됨
//add_stylesheet('<link rel="stylesheet" href="'.G5_SHOP_SKIN_URL.'/style.css">', 0);

$cart_action_url = G5_SHOP_URL.'/cartupdate.php';
?>

<!-- 장바구니 간략 보기 시작 { -->
    <form name="frmcartlist" method="post" action="<?php echo $cart_action_url; ?>">
    <input type="hidden" name="act" value="buy">

    <ul class="nav_cart qk_prdli">
    <?php
    $total_price = 0;
    $sql  = " select it_id, it_name
                from {$g5['g5_shop_cart_table']}
                where od_id = '".get_session('ss_cart_id')."'
                group by it_id ";
    $result = sql_query($sql);

    for ($i=0; $row=sql_fetch_array($result); $i++)
    {
        $price = 0;

        echo '<li>'.PHP_EOL;
        $it_name = get_text($row['it_name']);
        $it_img = get_it_image($row['it_id'], 80, 80, true);

        echo '<div class="cart_img qk_img">'.$it_img.'</div>'.PHP_EOL;
        echo '<div class="qk_txt">'.PHP_EOL;
        echo '<input type="hidden" name="it_id['.$i.']" value="'.$row['it_id'].'">'.PHP_EOL;
        echo '<input type="hidden" name="it_name['.$i.']" value="'.$it_name.'">'.PHP_EOL;
        echo '<label for="ct_chk_'.$i.'" class="sound_only">'.$it_name.' 선택</label>'.PHP_EOL;
        echo '<span class="sound_only"><input type="checkbox" name="ct_chk['.$i.']" value="1" id="ct_chk_'.$i.'" checked="checked" class="sound_only"></span>'.PHP_EOL;
        echo '<a href="./item.php?it_id='.$row['it_id'].'" class="qk_name">'.$it_name.'</a>'.PHP_EOL;

        // 상품별 옵션
        $sql2 = " select ct_option, ct_qty, (IF(io_type = 1, (io_price * ct_qty), ((ct_price + io_price) * ct_qty))) as price
                    from {$g5['g5_shop_cart_table']}
                    where od_id = '".get_session('ss_cart_id')."'
                      and it_id = '{$row['it_id']}'
                    order by ct_id ";
        $res2 = sql_query($sql2);

        for($k=0; $row2 = sql_fetch_array($res2); $k++) {
            echo '<div class="cart_op qk_opt">'.get_text($row2['ct_option']).''.PHP_EOL;
            echo '<span class="sound_only">수량</span> (+'.number_format($row2['ct_qty']).')</div>'.PHP_EOL;
            $price += (int)$row2['price'];
            $total_price += (int)$row2['price'];
        }

        echo '<div class="qk_prc">'.display_price($price).'</div>'.PHP_EOL;
        echo '</div>'.PHP_EOL;
        echo '<button class="cart_del prd_del" type="button" data-it_id="'.$row['it_id'].'"><i class="fa fa-times" aria-hidden="true"></i><span class="sound_only">삭제</button>'.PHP_EOL;
        echo '</li>'.PHP_EOL;
    }

    if ($i==0)
        echo '<li class="empty_list">장바구니 상품 없음</li>'.PHP_EOL;
    ?>
    </ul>
    <div class="cart_al">총 합계  <strong><?php echo number_format($total_price); ?> 원</strong></div>
    <div class="cart_btn qk_cart_btn">
        <a href="<?php echo G5_SHOP_URL; ?>/cart.php" class="qk_go_cart">장바구니 가기</a>
        <button type="submit" class="qk_go_buy">주문하기</button>
    </div>
    </form>

<script>
$(function () {
    $(".cart_del").on("click", function() {
        var it_id = $(this).data("it_id");
        var $wrap = $(this).closest("li");

        $.ajax({
            url: g5_theme_shop_url+"/ajax.cartdelete.php",
            type: "POST",
            data: {
                "it_id" : it_id
            },
            dataType: "json",
            async: true,
            cache: false,
            success: function(data, textStatus) {
                if(data.error != "") {
                    alert(data.error);
                    return false;
                }

                $wrap.remove();
            }
        });
    });
});
</script>
<!-- } 장바구니 간략 보기 끝 -->