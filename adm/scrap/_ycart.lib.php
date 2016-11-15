<?php


################################################################
// ntos 쪽 lib

function ntosCustSendResultMessage($resultData){
  $result = $resultData['result'];
  $message = $resultData['message'];
  $insert_id = $resultData['insert_id'];
  $cust_id = $resultData['cust_id'];
  $product_id = $resultData['product_id'];

  $send = '';
  $send .= "<div id='ResultCode'>$result</div><div id='ResultMessage'>$message</div>"; // 줄바꿈 없이 붙여쓰기.
  $send .= "<div id='ResultProductID'>$insert_id</div><div id='ResultCust_id'>$cust_id</div><div id='ResultCustProduct_id'>$product_id</div>" ; // 줄바꿈 없이 붙여쓰기.
  return $send;
}// end func


// redirect 처리 필요.
function curl_get_follow_url(/*resource*/ $ch, /*int*/ &$maxredirect = null) {
    $mr = $maxredirect === null ? 5 : intval($maxredirect);
    curl_setopt($ch, CURLOPT_FOLLOWLOCATION, false);
    if ($mr > 0) {
        $newurl = curl_getinfo($ch, CURLINFO_EFFECTIVE_URL);

        $rch = curl_copy_handle($ch);
        curl_setopt($rch, CURLOPT_HEADER, true);
        curl_setopt($rch, CURLOPT_NOBODY, true);
        curl_setopt($rch, CURLOPT_FORBID_REUSE, false);
        curl_setopt($rch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($rch, CURLOPT_CONNECTTIMEOUT, 2); 
        curl_setopt($rch, CURLOPT_TIMEOUT, 3); 
        do {
            curl_setopt($rch, CURLOPT_URL, $newurl);
            $header = curl_exec($rch);
            if (curl_errno($rch)) {
                $code = 0;
            } else {
                $code = curl_getinfo($rch, CURLINFO_HTTP_CODE);
                if ($code == 301 || $code == 302) {
                    preg_match('/Location:(.*?)\n/', $header, $matches);
                    $newurl = trim(array_pop($matches));
                } else {
                    $code = 0;
                }
            }
        } while ($code && --$mr);
        curl_close($rch);
        if (!$mr) {
            if ($maxredirect === null) {
                trigger_error('Too many redirects. When following redirects, libcurl hit the maximum amount.', E_USER_WARNING);
            } else {
                $maxredirect = 0;
            }
            return false;
        }
    }
    return $newurl;
}

################################################################
// 고도몰쪽 구현 lib

function normalize ($string) {
    $table = array(
        'Š'=>'S', 'š'=>'s', 'Đ'=>'Dj', 'đ'=>'dj', 'Ž'=>'Z', 'ž'=>'z', 'Č'=>'C', 'č'=>'c', 'Ć'=>'C', 'ć'=>'c',
        'À'=>'A', 'Á'=>'A', 'Â'=>'A', 'Ã'=>'A', 'Ä'=>'A', 'Å'=>'A', 'Æ'=>'A', 'Ç'=>'C', 'È'=>'E', 'É'=>'E',
        'Ê'=>'E', 'Ë'=>'E', 'Ì'=>'I', 'Í'=>'I', 'Î'=>'I', 'Ï'=>'I', 'Ñ'=>'N', 'Ò'=>'O', 'Ó'=>'O', 'Ô'=>'O',
        'Õ'=>'O', 'Ö'=>'O', 'Ø'=>'O', 'Ù'=>'U', 'Ú'=>'U', 'Û'=>'U', 'Ü'=>'U', 'Ý'=>'Y', 'Þ'=>'B', 'ß'=>'Ss',
        'à'=>'a', 'á'=>'a', 'â'=>'a', 'ã'=>'a', 'ä'=>'a', 'å'=>'a', 'æ'=>'a', 'ç'=>'c', 'è'=>'e', 'é'=>'e',
        'ê'=>'e', 'ë'=>'e', 'ì'=>'i', 'í'=>'i', 'î'=>'i', 'ï'=>'i', 'ð'=>'o', 'ñ'=>'n', 'ò'=>'o', 'ó'=>'o',
        'ô'=>'o', 'õ'=>'o', 'ö'=>'o', 'ø'=>'o', 'ù'=>'u', 'ú'=>'u', 'û'=>'u', 'ý'=>'y', 'ý'=>'y', 'þ'=>'b',
        'ÿ'=>'y', 'Ŕ'=>'R', 'ŕ'=>'r',
    );
    
    return strtr($string, $table);
}



################################################################
// 고도몰쪽 lib


// 상품이미지 업로드
function ntos_it_img_upload($srcfile, $filename, $dir)
{
    if($filename == '')
        return '';

    $size = @getimagesize($srcfile);
    if($size[2] < 1 || $size[2] > 3)
        return '';

    if(!is_dir($dir)) {
        @mkdir($dir, G5_DIR_PERMISSION);
        @chmod($dir, G5_DIR_PERMISSION);
    }

    $filename = preg_replace("/\s+/", "", $filename);
    $filename = preg_replace("/[#\&\+\-%@=\/\\:;,'\"\^`~\|\!\?\*\$#<>\(\)\[\]\{\}]/", "", $filename);

    $filename = preg_replace_callback(
                          "/[가-힣]+/",
                          create_function('$matches', 'return base64_encode($matches[0]);'),
                          $filename);

    ntos_upload_file($srcfile, $filename, $dir);

    $file = str_replace(G5_DATA_PATH.'/item/', '', $dir.'/'.$filename);

    return $file;
}


// 파일을 업로드 함
function ntos_upload_file($srcfile, $destfile, $dir)
{
    if ($destfile == "") return false;
    // 업로드 한후 , 퍼미션을 변경함
    @rename($srcfile, $dir.'/'.$destfile);
    @chmod($dir.'/'.$destfile, G5_FILE_PERMISSION);
    return true;
}




### 외부 호스팅 이미지 유효성 체크
function imgage_check($src){
  $url = parse_url($src);

  $fp = fsockopen($url[host],80,$errno,$errstr,10);

  if($fp){
    socket_set_timeout($fp, 3);
    if(fputs($fp,"POST ".$url[path]." HTTP/1.0\r\n"."Host: ".$url[host]."\r\n"."User-Agent: Web 0.1\r\n"."\r\n")){
      while(!feof($fp)){
        $data .= fread($fp,1024);
      }
      if(stristr($data,"Content-Type: image")){
        return true;
      }
    }
    fclose($fp);
  }
  return false;
}



// image - XX size fix 2015-01-07
function makeImageFrame($file, $save_filename, $save_path, $max_width, $max_height, $is_always_resize=true ) {

    // 전송받은 이미지 정보를 받는다
    $img_info = getImageSize($file);

    // 전송받은 이미지의 포맷값 얻기 (gif, jpg png)
    if($img_info[2] == 1) {
        $src_img = ImageCreateFromGif($file);
    } else if($img_info[2] == 2) {
        $src_img = ImageCreateFromJPEG($file);
    } else if($img_info[2] == 3) {
        $src_img = ImageCreateFromPNG($file);
    } else {
        return 0;
    }

    // 전송받은 이미지의 실제 사이즈 값얻기
    $img_width = $img_info[0];
    $img_height = $img_info[1];

    $base_max_width = $max_width;
    $base_max_height = $max_height;


// print_r($img_info);


    // 가로가 작은 경우 - 세로는 범위의 측정..필요.
    if( $img_width <= $max_width ) {
        if( $img_height <= $max_height ) {
            // 2015-03-02 - 둘다 작은경우 최대값으로 늘린다.
            if( $is_always_resize == true ){
                if( $img_width >= $img_height ){ // 가로 기준
                    $max_height = ceil( ($max_width / $img_width) * $img_height ) ;
                }else{ // 세로 기준.
                    $max_width = ceil( ($max_height / $img_height) * $img_width ) ;
                }
            }else{ // 작은값으로 처리.
                $max_width = $img_width ;
                $max_height = $img_height ;
            }
        }else{
            // 여기서 max_height 로 처리필요. 그대로 처리.
    //      $max_height = $img_height ;
            $max_width = ceil( ($max_height / $img_height) * $img_width ) ;
        }
    }else if( $img_width > $max_width ){
        $check_max_height = ceil( ($max_width / $img_width) * $img_height ) ;

        // 바뀐 check_max_height 를 확인.  max_height 보다 
        if( $check_max_height <= $max_height ) {
            // max_width 는 그대로.. 
            $max_height = $check_max_height ;
        }else{
            // max_height 는 그대로.. 
            $max_width = ceil( ($max_height / $img_height) * $img_width ) ;
        }
    }
    // 가로를 확인 - 세로도 확인 필요.

    $quality = '100';
    if( $max_width > 1000 ) $quality = '90' ;


    // 새로운 트루타입 이미지를 생성
    $src_img2 = imagecreatetruecolor($max_width, $max_height);

    // R255, G255, B255 값의 색상 인덱스를 만든다
    $white_bg = ImageColorAllocate($src_img2, 255, 255, 255);
    imagefill($src_img2, 0, 0, $white_bg);

    // 1차로 이미지 비율을 줄이고.
    ImageCopyResampled($src_img2, $src_img, 0, 0, 0, 0, $max_width, $max_height, ImageSX($src_img),ImageSY($src_img) );



    // 새로운 트루타입 이미지를 생성
    $dst_img = imagecreatetruecolor($base_max_width, $base_max_height);

    // R255, G255, B255 값의 색상 인덱스를 만든다
    $white_bg = ImageColorAllocate($dst_img, 255, 255, 255);
    imagefill($dst_img, 0, 0, $white_bg);

/*
    imagesavealpha($dst_img, true);
    imagealphablending($dst_img, false);
    $transparent = imagecolorallocatealpha($dst_img, 0, 0, 0, 127);
    imagefill($dst_img, 0, 0, $transparent);
*/

    // 이미지를 비율별로 만든후 새로운 이미지 생성
    ImageCopyResampled($dst_img, $src_img2, 
            round(($base_max_width-$max_width)/2), round(($base_max_height-$max_height)/2), 
            0, 0, 
            $max_width, $max_height ,
            $max_width, $max_height
            );
            // ImageSX($src_img),ImageSY($src_img)

/*
    ImageInterlace($src_img2);
    ImageJPEG($src_img2, $save_path.'test1.jpg',$quality);
*/

    // 알맞는 포맷으로 저장
    if($img_info[2] == 1) {
        ImageInterlace($dst_img);
        ImageGif($dst_img, $save_path.$save_filename);
    } else if($img_info[2] == 2) {
        ImageInterlace($dst_img);
        ImageJPEG($dst_img, $save_path.$save_filename,$quality);
    } else if($img_info[2] == 3) {
        ImagePNG($dst_img, $save_path.$save_filename);
    }

    // 임시 이미지 삭제
    ImageDestroy($dst_img);
    ImageDestroy($src_img2);
    ImageDestroy($src_img);

    return getImageSize($save_path.$save_filename);

} // end func






// end file

