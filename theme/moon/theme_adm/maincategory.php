<?php
include_once('_common.php');

$g5['title'] = '메인 카테고리설정';
include_once('./head.php');
?>

<form name="fcategory" id="fcategory" method="post" enctype="multipart/form-data" action="./maincategoryupdate.php">
<div class="btn_confirm"><input type="submit" value="저장" class="btn_save"></div>
<div class="con_left basic_cate">
    <div class="con_wr">
        <h2>기본 카테고리</h2>
        <?php include('./category.inc.php'); ?>
    </div>
</div>

<div class="con_right add_cate">
    <div class="con_wr">
        <h2>메인카테고리</h2>
        <p>최대 9개 등록가능합니다</p>
        <ul>
        <?php
        $save_file = G5_DATA_PATH.'/cache/theme/moon/maincategory.php';
        if(is_file($save_file))
            include($save_file);

        if(!empty($maincategory)) {
            foreach($maincategory as $key=>$val) {
                $sql = " select ca_id, ca_name from {$g5['g5_shop_category_table']} where ca_id = '$key' ";
                $row = sql_fetch($sql);
                if(!$row['ca_id'])
                    continue;

                $file = $val['file'];
                $is_file = is_file($file);
        ?>
            <li>
                <div class="add_cate_info">
                    <input type="hidden" name="ca_id[]" value="<?php echo $key; ?>">
                    <input type="checkbox" name="ca_chk[]" value="1"> <?php echo get_text($row['ca_name']); ?>
                </div>
                <?php if($is_file) { ?>
                <button type="button" class="btn_4 btn_open">배너 미리보기</button>
                <?php } else { ?>
                <button type="button" class="btn_2 btn_open"><span>배너추가</span></button>
                <?php } ?>
                <div class="file_add">
                    <input type="file" name="ca_bn_<?php echo $key; ?>">
                    <?php if($is_file) { ?>
                    <label for="bn_del_<?php echo $key; ?>">배너삭제</label>
                    <input type="checkbox" name="bn_del[<?php echo $key; ?>]" value="1">
                    <?php } ?>
                    <div class="bn_link"><label for="ca_link_<?php echo $key; ?>">링크주소</label><input type="text" name="ca_link[<?php echo $key; ?>]" class="frm_input" value="<?php echo $val['link']; ?>" size="40"></div>
                    <?php if($is_file) { ?>
                    <div class="bn_img"><img src="<?php echo str_replace(G5_DATA_PATH, G5_DATA_URL, $file); ?>"></div>
                    <?php } ?>
                    <span class="bn_di">(배너이미지 사이즈:240x320) </span>
                </div>
            </li>
        <?php
            }
        } else {
        ?>
            <li class="empty_li">등록된 카테고리가 없습니다.</li>
        <?php } ?>
        </ul>
        <div class="btn_edit"><button type="button" class="btn_4 cate_del">선택삭제</button></div>
    </div>
</div>
</form>

<script>
$(function(){
    $(document).on("click", ".btn_open", function() {
        $(this).next(".file_add").toggle();
    });

    $(".category_add").on("click", function() {
        var $this   = $(this);
        var ca_id   = $this.data("ca");
        var ca_name = $this.data("name");
        var li_cont = '<li>';
        li_cont += '<div class="add_cate_info"><input type="hidden" name="ca_id[]" value="'+ca_id+'"><input type="checkbox" name="ca_chk[]" value="1"> '+ca_name+'</div>';
        li_cont += '<button type="button" class="btn_2 btn_open"><span>배너추가</span></button>';
        li_cont += '<div class="file_add"><input type="file" name="ca_bn_'+ca_id+'"><div class="bn_link"><label for="ca_link_'+ca_id+'">링크주소</label><input type="text" name="ca_link['+ca_id+']" id="ca_link_'+ca_id+'" class="frm_input" size="40"></div><span class="bn_di">(배너이미지 사이즈:240x320)</span></div>';
        li_cont += '</li>';

        var $ul = $(".add_cate ul");
        var $li = $(".add_cate ul li").not(".empty_li");
        var dup = false;
        var max = 9;

        if($li.size() > 0) {
            if($li.size() >= max) {
                alert("더 이상 카테고리를 추가할 수 없습니다.");
                return false;
            }

            $li.each(function() {
                if($(this).find("input[name='ca_id[]']").val() == ca_id) {
                    dup = true;
                    return false;
                }
            });

            if(dup) {
                alert("이미 추가하신 카테고리입니다.");
                return false;
            }
        }

        if($(".add_cate ul li.empty_li").size() == 1)
            $(".add_cate ul li.empty_li").remove();

        $ul.append(li_cont);
    });

    $(".cate_del").on("click", function() {
        var $li  = $(".add_cate ul li");
        var $chk = $li.find("input[name='ca_chk[]']:checked");

        if($chk.size() < 1) {
            alert("삭제하실 카테고리를 하나 이상 선택해 주십시오.");
            return false;
        }

        $chk.each(function() {
            $(this).closest("li").remove();
        });

        if($(".add_cate ul li").size() == 0) {
            $(".add_cate ul").html('<li class="empty_li">등록된 카테고리가 없습니다.</li>');
        }
    });
});
</script>

<?php
include_once('./tail.php');
?>