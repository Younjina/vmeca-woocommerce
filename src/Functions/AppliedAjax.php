<?php

add_action('wp_ajax_search_related_product', 'searchRelatedProduct');

/**
 * 관련 상품 아이디 반환
 */
function searchRelatedProduct()
{
    if (!isset($_POST['nonce']) || !wp_verify_nonce($_POST['nonce'], $_POST['nonce_param'])) { //nonce비교
        wp_send_json_error("Security check");
    }

    global $wpdb;
    global $where;
    global $order;

    $result_html = '';

    $productTitle = (isset($_POST['search_product'])) ? $_POST['search_product'] : '';

    $where   .= " AND {$wpdb->posts}.post_title LIKE %s ";
    $value[] = '%' . $productTitle . '%';
    $query   = " SELECT {$wpdb->posts}.post_title, {$wpdb->posts}.ID 
					FROM {$wpdb->posts}  
					WHERE 1=1 
					{$where}
						AND {$wpdb->posts}.post_type = 'product' 
						AND (({$wpdb->posts}.post_status = 'publish')) 
					GROUP BY {$wpdb->posts}.ID 
					ORDER BY $order {$wpdb->posts}.post_date DESC "; //상품 관련 파일 찾는 쿼리


    $rows = $wpdb->get_results($wpdb->prepare($query, $value), ARRAY_A);

    // die(json_encode($rows));
    wp_send_json_success($rows);
} //end ajax function searchRelatedProduct
