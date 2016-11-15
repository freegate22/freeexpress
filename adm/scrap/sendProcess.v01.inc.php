<?php

include_once("_common.php");

include_once("_ycart.lib.php");


########
$_get_detail_path = "/Users/User_Products_detailPush.php";

if( isset($_POST['tServ']) && trim($_POST['tServ']) != '' ){
    $_get_detail_url = 'http://' . trim($_POST['tServ']) . $_get_detail_path ;
}else{
    $_get_detail_url = "http://ntos.co.kr" . $_get_detail_path;
}

########
$_db_item_name = 'g5_shop_item';
$_db_opt_name = 'g5_shop_item_option';


$_db_setting_name = 'g5_shop_default' ; // 설정 가져오기. skin / 배송비 / 포인트 등.



######################################################################
sql_query("set names utf8");


// base setting.
$res_setting = sql_query(" SELECT * FROM ".$_db_setting_name." ");
$setting_info = sql_fetch_array($res_setting) ;


$it_skin = $setting_info['de_shop_skin'] ; 
$it_mobile_skin = $setting_info['de_shop_mobile_skin'] ; 

switch($setting_info['de_send_cost_case']){
    case '무료' : $it_sc_type = 1 ;
        break;
    default : $it_sc_type = 0 ;
        break;
}


$prodExn = explode("\n",$_POST['prodArr']);

foreach ( $prodExn as $key => $prodCol ) {

    $prodCol = unserialize(base64_decode($prodCol));


    // 이미지 처리
    $img_link = $prodCol['prod_img_link'];
    $product_id = $prodCol['product_id'];


    // 이미지 사이즈 조정 기능 2015-01-07
    $_is_make_image_frame = trim($prodCol['prod_make_image_frame']);


    // 상품상세정보
    $url = $_get_detail_url . '#'. $product_id . '-' . $_POST['tServ'] ;
    $param = array('cust_id'=>$prodCol['cust_id'],'product_id'=>$product_id);

    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_POSTFIELDS, $param);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true); // 바로 출력 없음.
    curl_setopt($ch, CURLOPT_DNS_USE_GLOBAL_CACHE, false); // use local?
    curl_setopt($ch, CURLOPT_DNS_CACHE_TIMEOUT, 2);
    curl_setopt($ch, CURLOPT_IPRESOLVE, CURL_IPRESOLVE_V4);
    $prod_detail = curl_exec($ch);
    curl_close($ch);



    $goodsnm = $prodCol['prod_name'];
    $goodscd = $prodCol['prod_code'];

    $goods_price = $prodCol['prod_price'] ;


        $consumer_price = $goods_price;

        $prod_price_org = $prodCol['prod_price_org']; // 소비자가(계산된가격). 2015-01-28 - 일단 항목만.
        if( $prod_price_org ) $consumer_price = $prod_price_org;


        // 세일가 적용. 2015-02-27
        $sell_price_org = $prodCol['sell_price_org']; // 소비자가=원가격 / 세일가=판매가(goods_price)
        if( $sell_price_org ) $consumer_price = $sell_price_org;


    // shop pack - data 2015-07-20
    $site_price_org = $prodCol['prod_supply_price'] ; // 판매가. 부정확? 옵션가격등
    $site_domain = $prodCol['p_brand'] ;
    $site_prod_url = $prodCol['prod_url'] ;
    $site_img_link = $prodCol['prod_img_link'] ;
    $site_prod_name = $prodCol['site_prod_name'] ;


    $brandnm = $prodCol['prod_brand'];
    $brandnm = normalize($brandnm); // euro -> english ??
    $brandnm = preg_replace('/[\x00-\x1F\x7f-\xFF]/', '', $brandnm);
    
    // if( trim($brandnm) ) $brandno = getProdBrandno($brandnm) ; // via _godo.lib.php

    //$goodsnm = $row['site'];
    $imgUrl = $prodCol['prod_img_link'];
    $_is_img_force_update = $prodCol['prod_img_force_update']; // 'force_update' => only value
    // http://stackoverflow.com/questions/1176904/php-how-to-remove-all-non-printable-characters-in-a-string
    // $prod_detail = preg_replace('/[\x00-\x1F\x7f-\xFF]/', '', $prod_detail);
    // $goodsExplain = iconv("UTF-8","CP949",$prod_detail);
    // $goodsExplain = iconv("UTF-8","EUC-KR",$prod_detail);
    $goodsExplain = $prod_detail ;



    $sql_common = $_db_item_name ;

    /**
        @breif prod exist check.
    */
    $_is_send_prod_code = false;
    if ( $prodCol['send_prod_code'] ) { // 1:1 이겠지.
        $sql_chk = " SELECT it_id, it_img1 FROM ".$sql_common." WHERE it_id = '".$prodCol['send_prod_code']."' ";
        $res_chk = sql_query($sql_chk);
        if ( $prod_info = sql_fetch_array($res_chk) ) {
            $_is_send_prod_code = true;
        }
    }

    $_is_priceChangable = true ; // || $prodCol['prod_stock_in'] === 'stockIn' 해제할때는 가격 재수정(변동될 수 있음)
    if( $_is_send_prod_code === true &&               // 재전송시
            ( $prodCol['prod_selling_auto'] === 'N' ||    // 수동은 무조건 가격변동 XX
                $prodCol['prod_stock_out'] === 'stockOut'   // 품절시는 제외.
            )
        ){
        $_is_priceChangable = false;
    }// update / stock condition


    // 예외처리. 2013-10-24 , stockIn 은 일단 상황봐서.
    if( $_is_send_prod_code === false && $prodCol['prod_stock_out'] === 'stockOut' ){
        // stockout 명령인데, 상품은 없다. 이런경우는 등록은 하지 않는다. 그냥 skip
        $resultData = array(  'result'=>'finish',
                                                    'message'=>'미전송상품',
                                                    'insert_id'=>$prodCol['send_prod_code'], // -> null 으로 만들어야 할까? 에러발생?? 저장값에 이상이 생김.
                                                    'cust_id'=>$prodCol['cust_id'],
                                                    'product_id'=>$product_id, 
                                                );
        echo ntosCustSendResultMessage($resultData); // via _godo.lib.php
        exit;
    }// end if exit



    // it_id => 'n' + time + rand 3
    if ( $_is_send_prod_code === true ) {
        $insert_id = $prodCol['send_prod_code'];
    } else {
        $insert_id = 'n' . time() . rand(111,999) ; // 3 rand.
    }


###############################################
        // insert / update 공통으로 처리할 항목.
        $sql_set_common = '' ;

        $sql_set_common .= " , it_maker='".sql_real_escape_string($brandnm)."' "; // 
        $sql_set_common .= " , it_brand='".sql_real_escape_string($brandnm)."' "; // 

// use shop pack - 2015-07-20 - mail
        $sql_set_common .= " , it_9_subj='prod_code' "; // prod_code
        $sql_set_common .= " , it_9='".$goodscd."' ";

        $sql_set_common .= " , it_8_subj='price-org' "; // price-org
        $sql_set_common .= " , it_8='".$site_price_org."' ";

        // ca_id / ca_id2 / ca_id3 = 바뀔 수 있다.
        $sql_set_common .= " , ca_id='".$prodCol['prod_category']."' "; // category


/*        // 기본값 지정할 부분. = 처음만. 2014-12-22 - 추후 등록만....
        $sql_set_common .= " , it_skin='". $it_skin ."' "; 
        $sql_set_common .= " , it_mobile_skin='". $it_mobile_skin ."' "; 

        // 고객별 별도 처리 - 추후 설정값?
        $sql_set_common .= " , it_point_type='0' "; // point? 설정금액 0 / 판매가기준 설정비율 1 / 구매가기준 설정비율 2
        $sql_set_common .= " , it_point='0' "; // point 값 % - 엔토스 설정값으로 처리?

        $sql_set_common .= " , it_sc_type='$it_sc_type' "; // base , 1 - free , 2 - cond , 3 - cost
        $sql_set_common .= " , it_sc_method='0' "; // base 0 - before , 1 - after , 2 - select
*/

        // 초기 등록만 .
        $sql_set = " SET ";
        $sql_set .= " it_name='".sql_real_escape_string($goodsnm)."' ";
        $sql_set .= " , it_explan='".sql_real_escape_string($goodsExplain)."' ";

        $sql_set .= " , it_use='1' "; // 진열 1 / 0
        $sql_set .= " , it_soldout='0' "; // 품절 1 / 0


        // 기본값 지정할 부분. = 처음만. 2014-12-22
        $sql_set .= " , it_skin='". $it_skin ."' "; 
        $sql_set .= " , it_mobile_skin='". $it_mobile_skin ."' "; 

        // $sql_set .= " , it_point_type='0' "; // point? 설정금액 0 / 판매가기준 설정비율 1 / 구매가기준 설정비율 2
        // $sql_set .= " , it_point='0' "; // point 값 % - 엔토스 설정값으로 처리?
        // 고객별 별도 처리 - 추후 설정값?
        $sql_set .= " , it_point_type='0' "; // point? 설정금액 0 / 판매가기준 설정비율 1 / 구매가기준 설정비율 2
        $sql_set .= " , it_point='0' "; // point 값 % - 엔토스 설정값으로 처리?

        $sql_set .= " , it_sc_type='$it_sc_type' "; // base , 1 - free , 2 - cond , 3 - cost
        $sql_set .= " , it_sc_method='0' "; // base 0 - before , 1 - after , 2 - select


        $sql_set .= " , it_stock_qty='20' ";
        $sql_set .= " , it_noti_qty='0' ";

        $sql_set .= " , it_time=now() ";
        $sql_set .= " , it_update_time=now() ";
        $sql_set .= " , it_price='$goods_price' "; 
        $sql_set .= " , it_cust_price='$consumer_price' "; // 2015-03-05

// use shop pack - 2015-07-20 - fix - one time
        $sql_set .= " , it_7_subj='site_domain' "; // site_domain
        $sql_set .= " , it_7='".$site_domain."' ";

        $sql_set .= " , it_6_subj='prod-url' "; // prod-url
        $sql_set .= " , it_6='".$site_prod_url."' ";

        $sql_set .= " , it_5_subj='image-url' "; // image-url
        $sql_set .= " , it_5='".$site_img_link."' ";

        $sql_set .= " , it_4_subj='prod-name' "; // prod-name
        $sql_set .= " , it_4='".$site_prod_name."' ";

        $sql_set .= " , it_id = '".$insert_id."' "; // new

        // it_info_gubun => prod noti 
        // $sql_set .= " , it_info_gubun='' ";
        // $sql_set .= " , it_info_value='' ";

        if ( $_is_send_prod_code === true ) {
            $sql_update_where = array();
            $sql_update_where[] = " it_id = '".$insert_id."' ";
            $sql_update_where = " WHERE ".implode(" AND ",$sql_update_where);

            $sql_update_set = ' SET ';
            $sql_update_set .= " it_update_time=now() ";
            if( $prodCol['prod_selling_auto'] !== 'N' ){ // 2013-10-02
                $sql_update_set .= " , it_name='".sql_real_escape_string($goodsnm)."' ";
                $sql_update_set .= " , it_explan='".sql_real_escape_string($goodsExplain)."' ";

                if ( $_is_priceChangable === true ) { // 2013-10-02 - 가격변동 가능.
                    $sql_update_set .= " , it_price='$goods_price' ";
                    $sql_update_set .= " , it_cust_price='$consumer_price' "; // 2015-03-05
                }
            }// 수동은 업데이트 제외.

            if ( $prodCol['prod_stock_out'] === 'stockOut' ) $sql_update_set .= " , it_soldout = '1' "; 
            else if ( $prodCol['prod_stock_in'] === 'stockIn' ) $sql_update_set .= " , it_soldout = '0' "; 
            
            $sql = " UPDATE ".$sql_common . $sql_update_set . $sql_set_common . $sql_update_where ;

        } else {
            $sql = " INSERT INTO ".$sql_common . $sql_set . $sql_set_common ;
        } // end if.

        //echo('<div>'.$sql.'</div>'); //exit;
        $res = sql_query($sql);

// db fail
if( !$res ){
    $resultData = array(  'result'=>'fail',
                            'message'=>'DB Fail' . ':'.$sql . sql_error_info(),
                            'insert_id'=>'', // -> null 으로 만들어야 할까? 에러발생?? 저장값에 이상이 생김.
                            'cust_id'=>$prodCol['cust_id'],
                            'product_id'=>$product_id, 
                        );
    echo ntosCustSendResultMessage($resultData); // via _godo.lib.php
    exit;
}// end if



/////////////////////////////////////////////////////////// 이미지처리 - 이미지를 체크한다. 2014-05-26
    // $it_img_dir = "../../data/item/";
$it_img_dir = G5_DATA_PATH.'/item';


    // 새방식. 
        // new path.
        $img_sub_dir = substr($insert_id, -3); // 999 까지 공통 폴더. 분산이 잘될지?


if( $_is_send_prod_code === false || $prod_info['it_img1'] == '' ){
    $org_file_name = $insert_id . '/' . $insert_id . '.jpg' ;
}else{
    $org_file_name = $prod_info['it_img1'] ; // 저장이름.
}


    $file_path = $it_img_dir . '/' . $org_file_name ;
    $save_img_dir = $it_img_dir.'/'.$insert_id ;


    // 새방식. 
        $file_path = $it_img_dir .'/'. $img_sub_dir .'/'. $org_file_name ;
        $save_img_dir_uppath = $it_img_dir.'/'. $img_sub_dir ;
        $save_img_dir = $save_img_dir_uppath .'/'.$insert_id ;

        $file_path_old = $it_img_dir . '/' . $org_file_name ;
        $save_img_dir_old = $it_img_dir.'/'.$insert_id ;


$ssl = false ;
if( preg_match('@^https://@Us', $img_link) ) { // https check.
    $ssl = true ;
}// end reset.


if ( $_is_send_prod_code === false 
        || ( $_is_send_prod_code === true && 
                 ( $_is_img_force_update === 'force_update' ||  // 강제 업데이트. - 전송시 명령.
                     !file_exists($file_path) ||     // 저장파일이 없거나
                     filesize($file_path)<100 )       //  사이즈가 0 100 이하. byte.
                ) 
        ) {
    // 이미지처리


// 특정 사이트는 user agent 를 체크한다.
// wget -dv -U '' xx
// curl -v --user-agent '' xx
    $tmp_file_path = $it_img_dir . '/' . $insert_id . '.jpg';

    $fp = fopen($tmp_file_path, 'w');
    $ch = curl_init($img_link); // image path.
    if( ini_get('open_basedir') == '' && ini_get('safe_mode') == 'Off' ){
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true); // URL 이 바뀌는 경우. 304 move ..
        curl_setopt($ch, CURLOPT_MAXREDIRS, 2); // 너무 많지 않게.
    }else{
        // get url.
        $new_url = curl_get_follow_url($ch);
        curl_close($ch);
        $ch = curl_init($new_url); // image path.
    }
    // curl_setopt($ch, CURLOPT_USERAGENT,'Mozilla/5.0 (Windows; U; Windows NT 5.1; en-US; rv:1.8.1.13) Gecko/20080311 Firefox/2.0.0.13');
    curl_setopt($ch, CURLOPT_USERAGENT,'Mozilla/5.0 (Windows NT 6.2; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/29.0.1547.2 Safari/537.36');
    curl_setopt($ch, CURLOPT_FILE, $fp);

    //SSL Settings - 무조건 하면 에러나나?
    if( $ssl === true ){
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE); 
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, FALSE); 
    }
    
    $data = curl_exec($ch);
    curl_close($ch);
    fclose($fp);


    $_dir = $it_img_dir ;
    if( $_is_make_image_frame != '' ){
        $new_img_filename = $insert_id . '.M.jpg';
        $n_img_info = makeImageFrame($tmp_file_path, $new_img_filename, $_dir, 500, 500) ;
        if( $n_img_info[0] == 500 && $n_img_info[1] == 500 ){
            // end if. change.
            rename($_dir.$new_img_filename, $tmp_file_path);
        }else{
            // fail. skip.
            if( file_exists($_dir.$new_img_filename) ) unlink($_dir.$new_img_filename);
        }
    }// new 



    // 이미지업로드
    if( $_is_send_prod_code === true && $org_file_name) {
            $file_img1 = $it_img_dir.'/'.$org_file_name;
            @unlink($file_img1);
            delete_item_thumbnail(dirname($file_img1), basename($file_img1));
    }

    // 새방식. 
        // 기존 폴더 삭제 후 새폴더 재생성.
        is_dir($save_img_dir_old) && @rmdir($save_img_dir_old);
        !is_dir($save_img_dir_uppath) && @mkdir($save_img_dir_uppath);
        // new path.
        if( $_is_send_prod_code === true && $org_file_name) {
            $file_img1 = $it_img_dir.'/'.$img_sub_dir.'/'.$org_file_name;
            @unlink($file_img1);
            delete_item_thumbnail(dirname($file_img1), basename($file_img1));
        }

    $it_img1 = ntos_it_img_upload($tmp_file_path, $insert_id . '.jpg', $save_img_dir);


    $sql_set = " SET ";
    $sql_set .= " it_img1='".$it_img1."' ";

/*  $sql_set .= " , img_s='".$img_s."' ";
    $sql_set .= " , img_m='".$img_m."' ";
    $sql_set .= " , img_l='".$img_l."' ";
    $sql_set .= " , img_mobile='".$img_mobile."' ";
*/
    $sqli = " UPDATE ".$sql_common.' '.$sql_set." WHERE it_id='".$insert_id."' ";
    sql_query($sqli);
} // end if. update 때는 굳이 이미지 처리 작업 필요 없음.
else{
/*  // 이미지가 이미 있는 경우 - 이미지 사이즈를 체크한다.(원본만.) 당분간만 돌린다.
    // 용량이 아니라 width*height 체크 - 400 이상으로 바꾼다.(큰쪽을 400이상으로...)
    $check_size_limit = 400; // 400 이하는 400 으로 조정.
    $size = getimagesize($file_path);
    $check_default_width = $check_default_height = 0;
    if( $size[0] >= $size[1] ) {
        $check_default_size = $size[0];
        $check_default_width = 400 ;
    }else{
        $check_default_size = $size[1];
        $check_default_height = 400 ;
    }
    if( $check_default_size < $check_size_limit ){ // 작으면 사이즈 조정
        thumbnail($file_path , $file_path_t . '.l.jpg' , $check_default_width, $check_default_height, 0, 80); // 크기는 동일
        if( file_exists( $file_path_t . '.l.jpg' ) && filesize( $file_path_t . '.l.jpg' ) > 0 ) {
            // 원본은 삭제 하고, 이름 바꾸기.
            unlink($file_path);
            rename($file_path_t . '.l.jpg', $file_path );
        }
    }// end if size limit
*/
}// end if size check.

    /////////////////////////////////////////////////////////// 이미지처리 끝.




///////////////////////////////////////////////////////////// 옵션처리
    if ( $prodCol['prod_opt'] ) {

        // 옵션을 삭제먼저 후 재 등록.
        if ( $_is_send_prod_code === true ) {
            // sql_query(" DELETE FROM ". $_db_opt_name ." WHERE it_id='".$insert_id."' "); // 가격옵션.
            // 삭제하면 sno 값이 over 된다. 개편필요.
            $sql_opt = " SELECT io_no,io_id,io_type FROM ". $_db_opt_name ." WHERE it_id='".$insert_id."' ";
            $res_opt = sql_query($sql_opt);
            $exist_opt1_arr = array();
            $exist_opt2_arr = array();
            while( $row_opt = sql_fetch_array($res_opt) ){
                // md5 key 처리로 문제 없을까?
                $opt_key_sha1 = sha1($row_opt['io_id']);
                if( $row_opt['io_type'] == 0 ) $exist_opt1_arr[$opt_key_sha1] = $row_opt['io_no'];
                if( $row_opt['io_type'] == 1 ) $exist_opt2_arr[$opt_key_sha1] = $row_opt['io_no'];
            }

        } // end if.

        #########################################
        // 먼저 가격옵션을 먼저 처리.
        // 색상/사이즈 순서로. 고정형.2013-10-13
            $_is_noOptions = true;
            $prod_price = $goods_price; // 가격은 동일하다고 보고.
            $prod_opt_item_arr = array(); // 조합형. 2단계.
            if( is_array($prodCol['prod_opt']['prod_color']) || is_array($prodCol['prod_opt']['prod_size']) ) {
                // 가격옵션

                if( is_array($prodCol['prod_opt']['prod_color']) ) {
                    foreach($prodCol['prod_opt']['prod_color'] as $key1 => $opt_arr1 ){
                        // 조합형 처리.
                        // 2014-01-02:가격처리, opt2 가격은 무시?
                        $opt_price = ($opt_arr1['opt_price']>0&&$opt_arr1['opt_price']!=$prod_price)? $opt_arr1['opt_price']-$prod_price:0; 
                        if( is_array($prodCol['prod_opt']['prod_size']) ) {
                            foreach($prodCol['prod_opt']['prod_size'] as $key2 => $opt_arr2 ){
                                // 조합형 처리.
                                $prod_opt_item_arr[] = array('opt_name1'=>$opt_arr1['opt_name'], 'opt_name2'=>$opt_arr2['opt_name'], 'opt_price'=>$opt_price, ) ;
                            }
                        }// end if.
                        else{
                            $prod_opt_item_arr[] = array('opt_name1'=>$opt_arr1['opt_name'], 'opt_name2'=>'', 'opt_price'=>$opt_price, ) ;
                        }

                    }// end foreach.
                }// end if.
                else{ // color 가 없으면 size 만.
                    if( is_array($prodCol['prod_opt']['prod_size']) ) {
                        foreach($prodCol['prod_opt']['prod_size'] as $key2 => $opt_arr2 ){
                            // 조합형 처리.
                            $opt_price = ($opt_arr2['opt_price']>0&&$opt_arr2['opt_price']!=$prod_price)? $opt_arr2['opt_price']-$prod_price:0; 
                            $prod_opt_item_arr[] = array('opt_name1'=>$opt_arr2['opt_name'], 'opt_name2'=>'', 'opt_price'=>$opt_price, ) ;
                        }
                    }// end if.
                }// end if color or size

                if( isset($prodCol['prod_opt_name']['prod_color']) ) {
                    if( is_array($prodCol['prod_opt']['prod_color']) ) $opt_name_arr[] = $prodCol['prod_opt_name']['prod_color'];
                    unset($prodCol['prod_opt_name']['prod_color']);
                }
                if( isset($prodCol['prod_opt_name']['prod_size']) ) {
                    if( is_array($prodCol['prod_opt']['prod_size']) ) $opt_name_arr[] = $prodCol['prod_opt_name']['prod_size'];
                    unset($prodCol['prod_opt_name']['prod_size']);
                }
                
                if( count($prod_opt_item_arr)>0 ){
                    $_is_noOptions = false;
                    foreach ( $prod_opt_item_arr as $key => $opt_arr ) { // opt_arr 로 넘어온다.
                        $opt1 = $opt_arr['opt_name1'] ;
                        $opt2 = $opt_arr['opt_name2'] ;
                        $opt_name = $opt1 . ( ($opt2!='')? ' / '.$opt2 : '' );
                        $opt_price = $opt_arr['opt_price'] ; // krw 변환후 넘어온다. - 일단 가격옵션쪽은 동일처리.

                        $_is_opt_update = false;
                        $opt_key_sha1 = sha1($opt_name);
                        if( $_is_send_prod_code === true && isset($exist_opt1_arr[$opt_key_sha1]) ){
                            $_is_opt_update = true;
                            $opt_update_id = $exist_opt1_arr[$opt_key_sha1] ; // sno
                            unset($exist_opt1_arr[$opt_key_sha1]); // 남은것 삭제.
                        }

                        if( $_is_opt_update == true ){
                            $sql_opt = " UPDATE  ". $_db_opt_name ." SET it_id='".$insert_id."', io_type=0, io_id='".$opt_name."', io_price='".$opt_price."', io_stock_qty='10', io_use=1 WHERE io_no='".$opt_update_id."' ";
                            sql_query($sql_opt);

                        }else{
                            $sql_opt = " INSERT INTO  ". $_db_opt_name ." SET it_id='".$insert_id."', io_type=0, io_id='".$opt_name."', io_price='".$opt_price."', io_stock_qty='10', io_use=1 ";
                            sql_query($sql_opt);
                        }

                    } // end foreach.
                }// end if. options.

                if( $_is_send_prod_code === true && count($exist_opt1_arr) ){ // 남아 있으면 제거.
                    foreach($exist_opt1_arr as $opt_sno_key ){
                        sql_query(" DELETE FROM ". $_db_opt_name ." WHERE io_no='".$opt_sno_key."' "); // 추가옵션.
                    }// end foreach.
                }


            } // end foreach.

            #########################################
            $addno = 1;
            $addprice = 0; // 옵션 추가가격, 현재는 미설정 - 가격도 추가 필요.
            $step = 0;

            if( count($prodCol['prod_opt_name']) ){ // 추가 옵션이 있으면.
                foreach ( $prodCol['prod_opt_name'] as $prod_opt_code => $prod_opt_name ) {
                    // 가격옵션 / 추가옵션 구분 형태.
                    $prod_opt_arr = $prodCol['prod_opt'][$prod_opt_code] ;
                    if( count($prod_opt_arr) ){ // 값이 존재해야 - 추가.
                        $add_opt_name_arr[] = $prod_opt_name.'^'; // 옵션명 - gd_goods - addoptnm 입력값

                        foreach ( $prod_opt_arr as $key => $opt_arr ) { // opt_arr 로 넘어온다.
                            $opt_name = $opt_arr['opt_name'] ;
                            $opt_price = $opt_arr['opt_price'] ; // krw 변환후 넘어온다.

                            $_is_opt_update = false;
                            $opt_key_sha1 = sha1($opt_name);
                            if( $_is_send_prod_code === true && isset($exist_opt2_arr[$opt_key_sha1]) ){
                                $_is_opt_update = true;
                                $opt_update_id = $exist_opt2_arr[$opt_key_sha1] ; // sno
                                unset($exist_opt2_arr[$opt_key_sha1]); // 남은것 삭제.
                            }

                            if( $_is_opt_update == true ){
                                $sql_opt = " UPDATE  ". $_db_opt_name ." SET it_id='".$insert_id."', io_type=1, io_id='".$opt_name."', io_price='".$opt_price."', io_stock_qty='10', io_use=1 WHERE io_no='".$opt_update_id."' ";
                                sql_query($sql_opt);

                            }else{

                                $sql_opt = " INSERT INTO  ". $_db_opt_name ." SET it_id='".$insert_id."', io_type=1, io_id='".$opt_name."', io_price='".$opt_price."', io_stock_qty='10', io_use=1 ";
                                sql_query($sql_opt);
                            }

                        } // end foreach.

                        $addno++;
                        $step++;
                    }// end if

                } // end foreach.

                if( $_is_send_prod_code === true && count($exist_opt2_arr) ){ // 남아 있으면 제거.
                    foreach($exist_opt2_arr as $opt_sno_key ){
                        sql_query(" DELETE FROM ". $_db_opt_name ." WHERE io_no='".$opt_sno_key."' "); // 추가옵션.
                    }// end foreach.
                }

            } // end if add opt

            #########################################

            #########################################
            $opt_name = implode("|",$opt_name_arr);
            $add_opt_name = ( count($add_opt_name_arr) ) ? implode("|",$add_opt_name_arr) : "";

            $sqlu_opt = " UPDATE ".$sql_common." SET it_option_subject='".$opt_name."', it_supply_subject='".$add_opt_name."' WHERE it_id='".$insert_id."' ";
            sql_query($sqlu_opt);      

    } // end if.



} // end foreach.
#########################

// 에러관련 메시지-
$message = '완료' ;

// 결과
$result = 'finish' ;

// $result_pattern = "@<div id='ResultCode'>(?P<resultCode>[^<]+)</div>.*<div id='ResultProductID'>(?P<ResultProductID>[^<]+)</div><div id='ResultCust_id'>(?P<ResultCust_id>[^<]+)</div><div id='ResultCustProduct_id'>(?P<ResultCustProduct_id>[^<]+)</div>@Us" ;
//  $param = array('cust_id'=>$prodCol['cust_id'],'product_id'=>$product_id);


$resultData = array(  'result'=>$result,
                                            'message'=>$message,
                                            'insert_id'=>$insert_id,
                                            'cust_id'=>$prodCol['cust_id'],
                                            'product_id'=>$product_id, 
                                        );
echo ntosCustSendResultMessage($resultData); // via _godo.lib.php


// echo "<div id='ResultCode'>$result</div><div id='ResultMessage'>$message</div>"; // 줄바꿈 없이 붙여쓰기.
// echo "<div id='ResultProductID'>$insert_id</div><div id='ResultCust_id'>".$prodCol['cust_id']."</div><div id='ResultCustProduct_id'>".$product_id."</div>" ; // 줄바꿈 없이 붙여쓰기.
// echo $result ;

// end file
