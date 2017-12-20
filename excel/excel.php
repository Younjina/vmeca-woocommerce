<?php
error_reporting(E_ALL);
ini_set('memory_limit', -1); // 엑셀 가져오다가 뻗을 수 있으므로 메모리는 무한대로~
set_time_limit(0);


/** 엑셀데이터 취급점 등록 시작 **/

$categories = array();
$terms      = get_terms('stores_category', 'orderby=count&hide_empty=0');
if (!empty($terms) && !is_wp_error($terms)) {
    foreach ($terms as $term) {
        $categories[$term->name] = $term->term_id;
    }
}

// $filterSubset = new MyReadFilter();

// $upfile_path = './Logic.xlsx'; // 취급점 엑셀파일
if (($handle = fopen($upfile_path, "r")) !== false) {
    while (($rows = fgetcsv($handle, 1000, ";")) !== false) {
        $post_id = intval($rows[0]);

        if ($rows[0] && !$post_id)
            continue;

        if ($rows[2] == '')
            continue;

        // echo 'A:'. $rows[0] .', '; // ID
        // echo 'B:'. $rows[1] .', '; // 분류
        // echo 'C:'. $rows[2] .', '; // 취급점명
        // echo 'D:'. $rows[3] .', '; // 주소
        // echo 'E:'. $rows[4] .', '; // 전화번호
        // echo 'F:'. $rows[5] .', '; // 위도
        // echo 'G:'. $rows[6] .'<br />'; // 경도

        if ($post_id) {
            $post = get_post($post_id);
            if (!$post->ID || $post->post_type != 'stores')
                $post_id = null;
        }
        // continue;

        // Gather post data.
        $_post = array(
            'post_title'    => $rows[2],
            'post_content'  => '',
            'post_type'     => 'stores',
            'post_status'   => 'publish',
            'post_author'   => 2,
            'post_category' => array(),
        );

        if ($post_id) {
            // Update the post into the database.
            $_post['ID'] = $post_id;
            wp_update_post($_post);
            // print_r( $_post );
            // echo '<br /><br />';
        } else {
            // Insert the post into the database.
            $post_id = wp_insert_post($_post);
        }

        // $_mapdaum_LatLng['lng'] = $rows[6];
        // $_mapdaum_LatLng['lat'] = $rows[5];

        update_post_meta($post_id, '_mapdaum_address', $rows[3]);
        update_post_meta($post_id, '_mapdaum_tel', $rows[4]);
        // if ( $rows[5] )
        // 	update_post_meta( $post_id, '_mapdaum_lat', $rows[5] );
        // if ( $rows[6] )
        // 	update_post_meta( $post_id, '_mapdaum_lng', $rows[6] );

        if ($rows[1]) {
            if (array_key_exists($rows[1], $categories)) {
                // 카테고리 저장
                wp_set_post_terms($post_id, array($categories[$rows[1]]), 'stores_category', true);
            } else {
                // 카테고리 생성 후 저장
                $term_id = wp_create_category($rows[1]);
                wp_set_post_terms($post_id, array($term_id), 'stores_category', true);
                $categories[$rows[1]] = $term_id;
            }
        }

        $_mapdaum_LatLng = get_daum_addr_geo($rows[3]);
        if (sizeof($_mapdaum_LatLng)) {
            update_post_meta($post_id, '_mapdaum_lat', $_mapdaum_LatLng['lat']);
            update_post_meta($post_id, '_mapdaum_lng', $_mapdaum_LatLng['lng']);
        }

        // print_r( $geo );
        // echo '<br />';
        // exit;
    }

    @unlink($upfile_path);

}
/** 엑셀데이터 취급점 등록 끝 **/


// 다음 API 주소 -> 좌표
function get_daum_addr_geo_local($addr)
{
    // https://developers.daum.net/services/apis/local
    // http://apis.map.daum.net/web/sample/coord2addr/
    $mapdaum_LatLng = array();

    $daum_api_url = 'https://apis.daum.net/local/geo/addr2coord';
    $daum_api_key = 'd567f597cfe0f794d58fb3749bc3c277';

    $request = $daum_api_url . '?apikey=' . $daum_api_key . '&q=' . urlencode($addr) . '&output=json';
    // https://apis.daum.net/local/geo/addr2coord?apikey=cef8b6140586bef56f1075827bc5183d&q=서울특별시 성북구 종암로 113 1층&output=json
    //
    $daum_data = file_get_contents($request, 1000000);
    $data      = json_decode($daum_data, true);

    if (isset($data['channel']['item'][0])) {
        $mapdaum_LatLng['lng'] = $data['channel']['item'][0]['point_x'];
        $mapdaum_LatLng['lat'] = $data['channel']['item'][0]['point_y'];
    }

    return $mapdaum_LatLng;
}


?>