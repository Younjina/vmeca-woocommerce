<?php

namespace Ivy\Vmeca\Initiators\Board;

use Ivy\Mu\Initiators\AutoHookInitiator; //action or filter or shortcode 등을 자동호출해줌 

/**
 * 적용사례 사용자 페이지
 */
class BoardAppliedInitiator extends AutoHookInitiator
{

    /**
     * 우커머스 관련 상품 출력 숏코드
     * [related_product]
     * count : 화면에 출력되는 상품 최대 갯수
     * kind : cat - category기준, number : 상품 아이디 기준
     */
    public function shortcode_related_product($atts)
    {
        $defaults = array(
            'count' => 5,
            'kind'  => 'cat', //number or cat
        );//기본 값
        $atts     = wp_parse_args($atts, $defaults);
        $postId   = get_the_ID();

        $contents = '';

        if ($atts['kind'] == 'number') { //상품 아이디 기준 - 하나씩 다 출력
            $productNumber = get_post_meta($postId, 'vmeca_search_product_number', true);
            if ($productNumber != '') {
                ob_start();
                $contents .= '<h3>Related Product</h3>';
                $contents .= do_shortcode("[products ids='" . $productNumber . "' number='" . $atts['count'] . "']");
                $contents .= ob_get_contents();
                ob_end_clean();
            }
        } else { //상품 카테고리 기준 - 해당 카테고리만 출력됨
            $productCatId = get_post_meta($postId, 'product_cat', true);
            if ($productCatId != '') {
                ob_start(); //출력 버퍼 시작
                $contents .= '<h3>Related Product Category</h3>';
                $contents .= do_shortcode('[product_categories ids="' . $productCatId . '"]');
                $contents .= ob_get_contents(); //출력 버퍼 지우지 않으면서 내용을 얻습니다.
                ob_end_clean(); //최근 출력 버퍼의 내용을 버리고 출력 버퍼링을 종료합니다.
                //ob_end_clean()함수를 사용하면 출력할 내용이 모두 사라지기 때문에 ob_get_contents()위 함수로 내용을 저장합니다.
                //출력 버퍼 깨끗하게 가져오기
                //위 처리를 해주지 않으면 글 위에 출력됩니다.
            }
        }

        return $contents;
    } //end shortcode function shortcode_related_product

} //end class BoardAppliedInitiator
