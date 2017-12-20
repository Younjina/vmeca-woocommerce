<?php
//ajax product item function file
use function Ivy\Mu\Functions\genericTag;
use function Ivy\Mu\Functions\selectTag;
use function Ivy\Vmeca\Functions\getCatSlug;
use function Ivy\Vmeca\Functions\matchSlugFunctionName;
use function Ivy\Vmeca\Functions\mmToinch;
use function Ivy\Vmeca\Functions\replace_mm;

add_action('wp_ajax_get_list_category', 'getListCategory');

/**
 * 우커머스 상품 카테고리를 select box 옵션 값 형태로 반환
 */
function getListCategory()
{

    $termId = (isset($_POST['term_id'])) ? $_POST['term_id'] : '';

    if (!isset($_POST['nonce']) || !wp_verify_nonce($_POST['nonce'], 'create_select_category_3')) {
        die("Security check");
    }

    $terms = get_terms('product_cat', 'orderby=term_id&hide_empty=0&parent=' . $termId);

    $list = array();
    $no   = 0;
    foreach ($terms as $key => $value) {
        $list[$no]['term_id'] = $value->term_id;
        $list[$no]['name']    = __($value->name, 'vmeca');
        $no++;
    }

    die(json_encode($list));
} //end ajax function getListCategory

add_action('wp_ajax_get_custom_field', 'getCustomField');

/**
 * 커스텀 메타 필드 화면에 출력해주기 - 최상위 카테고리에 맞는 옵션필드가 출력됩니다.
 */
function getCustomField()
{
    if (!isset($_POST['nonce']) || !wp_verify_nonce($_POST['nonce'], 'create_meta_field')) {
        die("Security check");
    }

    $termId = (isset($_POST['term_id'])) ? $_POST['term_id'] : '';
    $postId = (isset($_POST['post_id'])) ? $_POST['post_id'] : '';
    $html   = '';

    if ($termId != '') { //최상위 카테고리 아이디가 있는 경우 -- 선택한 경우
        $slug         = getCatSlug($termId); //카테고리 아이디에 해당되는 슬러그
        $functionName = matchSlugFunctionName($slug); //슬러그에 해당되는 메타필드 함수 명

        $metafieldNameFunc = 'getMetafieldName' . $functionName; //메타필드 select 명
        $metafieldFunc     = 'getMetafield' . $functionName; //메타 필드 option값

        $cnt = 0;

        $metafield = call_user_func('\\Ivy\\Vmeca\\Functions\\' . $metafieldNameFunc);

        $html .= '<table style="width:80%;">';
        foreach ($metafield as $key => $value) { //select 명
            $cnt++;
            ${$key}              = call_user_func('\\Ivy\\Vmeca\\Functions\\' . $metafieldFunc, $key);
            ${$key . '_options'} = array();
            ${$key . 'selected'} = get_post_meta($postId, $key, true);


            $mm = (ICL_LANGUAGE_CODE == 'us') ? ' inch' : ' mm';

            for ($i = 0; $i < count(${$key}); $i++) { //setting select option value
                if ($key == 'cup_diameter') {
                    $optionKey   = trim(${$key}[$i]);
                    $optionValue = explode('x', ${$key}[$i]);

                    //미국 -> inch, others -> mm
                    $optionValue = array_map('\\Ivy\\Vmeca\\Functions\\mmToinch', $optionValue);

                    $optionValue = implode('x', $optionValue) . $mm;
                } else {
                    $optionKey   = str_replace(' ', '-', ${$key}[$i]);
                    $optionValue = ${$key}[$i];
                    if (strpos($optionValue, 'mm')) {
                        $optionValue = mmToinch(replace_mm($optionValue)) . $mm;
                    }
                }
                ${$key . '_options'}[$optionKey] = $optionValue;
            }

            $span = genericTag(
                'span',
                array(),
                $value,
                true,
                false,
                false //echo
            );

            $select = selectTag(
                ${$key . '_options'}, // array($key => $value,,)
                ${$key . 'selected'},
                array('id' => $key, 'name' => $key . '[]', 'multiple' => "multiple", 'class' => $key, 'style' => 'width:200px;'),
                array(),
                false //echo
            );

            if ($cnt % 2 != 0) {
                $html .= "<tr><th style='width:30%;'>" . $span . "</th><td style='width:30%;'>" . $select . "</td>";
            } else {
                $html .= "<th style='width:30%;'>" . $span . "</th><td style='width:30%;'>" . $select . "</td></tr>";
            }
        } //end foreach
        $html .= '</table>';
    }

    die($html);
} //end ajax function getCustomField
