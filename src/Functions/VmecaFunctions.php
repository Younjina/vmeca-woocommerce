<?php

namespace Ivy\Vmeca\Functions;

use function Ivy\Mu\Functions\selectTag;

// meta value 출력 방식
// back-end : -로 구분
// front-end : ' '로 구분 

/**
 * Vmeca Product Type 배열 리턴 함수
 */
function getVmecaProductType()
{
    $productType = array('SuctionCup'    => 'Suction Cup',
                         'VacuumPump'    => 'Vacuum Pump',
                         'VGripSystem'   => 'VGrip System',
                         'VacuumPad'     => 'Vacuum Pad',
                         'SpeederModule' => 'Speeder Module',
                         'VgripSystem'   => 'Vgrip System',
                         'Accessories'   => 'Accessories',
                         'Conveyor'      => 'Conveyor',
                         'Other'         => 'Others');

    return $productType;
}

/**
 * Suction Cup 메타필드 배열 리턴 함수
 * [String]$field_name : suction cup 메타 필드명
 */
function getMetafieldSuctionCup($fieldName = '')
{
    $suctionCups = array(
        'cup_diameter'   => explode(',', get_option('option_' . 'cup_diameter')),
        'cup_shape'      => explode(',', get_option('option_' . 'cup_shape')),
        'material'       => explode(',', get_option('option_' . 'material')),
        'hardness'       => explode(',', get_option('option_' . 'hardness')),
        'fitting_size'   => explode(',', get_option('option_' . 'fitting_size')),
        'fitting_type'   => explode(',', get_option('option_' . 'fitting_type')),
        'thread_type'    => explode(',', get_option('option_' . 'thread_type')),
        'fitting_option' => explode(',', get_option('option_' . 'fitting_option')),
        'etc'            => explode(',', get_option('option_' . 'etc')),
    );

    if ($fieldName != '') { //필드명이 있는 경우
        return $suctionCups[$fieldName];
    } else { //없는 경우 모두 반환
        return $suctionCups;
    }
} //end function getMetafieldSuctionCup

/**
 * Suction Cup 메타필드 종료
 * [String]$key : suction cup 메타필드 키 ==> 있는 경우 key name값만 반환, 없는 경우 전체 배열 리턴
 */
function getMetafieldNameSuctionCup($key = '')
{
    $name = array('cup_diameter'   => 'Cup Diameter',
                  'cup_shape'      => 'Cup Shape',
                  'material'       => 'Material',
                  'hardness'       => 'Hardness',
                  'fitting_size'   => 'Fitting Size',
                  'fitting_type'   => 'Fitting Type',
                  'thread_type'    => 'Thread Type',
                  'fitting_option' => 'Fitting Option',
                  'etc'            => 'Etc');

    if ($key != '') {
        return $name[trim($key)];
    } else {
        return $name;
    }
}

/**
 * suction cup 카테고리 목록
 */
function getCategorySuctionCup()
{
    return array('VB5', 'VB6X', 'VB8', 'VB10', 'VB12', 'VB15', 'VB17', 'VB20', 'VB30', 'VB40', 'VB50', 'VB75', 'VB75B', 'VB110', 'VB110B', 'VB150', 'VB20M', 'VB30M', 'VB50M', 'VBL15', 'VBL20', 'VBL30', 'VBL35M', 'VBL40', 'VBL40B', 'VBL50', 'VOBL35X90', 'VBU35', 'VBU45', 'VBU55', 'VBX35', 'VBX45', 'VBX55', 'BL5040', 'VOU4X10', 'VOU4X20', 'VOU6X10', 'VOU6X20', 'VOU8X20', 'VOU8X30', 'VOU10X30', 'VOU15X45', 'VOU20X60', 'VU1.5X', 'VU2', 'VU2X', 'VU3', 'VU3K', 'VU4', 'VU4K', 'VU6', 'VU8', 'VU10', 'VU15', 'VU20', 'VU25', 'VU30', 'VU30-X', 'VU40', 'VU50', 'VU80', 'FF10', 'FF15', 'FF20', 'FF25', 'FF30', 'FF15X', 'FF20X', 'VD30', 'VD40', 'VD50', 'VD60', 'VD70', 'VD85', 'VD85X', 'VD90F', 'VFC50', 'VFC60', 'VFC60X1', 'VFC75', 'VFC75X1', 'VFC75X2', 'VFC90', 'VFC100', 'FCF30', 'FCF40', 'FCF50', 'FCF60', 'FCF70', 'FCF80', 'FCF100', 'FCF125', 'VDF25', 'VDF30', 'VDF40', 'VDF50', 'VDF60', 'VDF80', 'VDF100', 'VBF25', 'VBF30', 'VBF40', 'VBF50', 'VBF60', 'VBF80', 'VBF100', 'VOBF30X60', 'VOBF40X80', 'VOBF55X110', 'VOC11X23', 'VOC35X90', 'VOC35X110', 'VOC60X140', 'VOC60X180', 'VF15', 'VF20', 'VF25', 'VF30', 'VF40', 'VF50', 'VF50X2', 'VF75', 'VF90', 'VF110', 'VF150', 'VF200', 'VF300', 'LF150', 'LF200', 'LF250', 'LF300', 'VS30X80', 'VS35', 'VS60', 'VS100', 'VS150', 'VS200', 'VS300', 'VS400', 'KPS-1', 'KPS-2', 'KPS-3', 'KPS-4', 'KPS-5', 'KPS-5-15', 'KPS-6', 'KPS-7', 'KPS-8', 'KPS-9');
} //end function getCategorySuctionCup

//Pump Option Meta Field
function getMetaFieldVacuumPump($fieldName = '')
{
    $pump = array(
        'pump_size'                => explode(',', get_option('option_' . 'pump_size')),
        'vacuum_level'             => explode(',', get_option('option_' . 'vacuum_level')),
        'stackable'                => explode(',', get_option('option_' . 'stackable')),
        'individual'               => explode(',', get_option('option_' . 'individual')),
        'type_of_vacuum_generator' => explode(',', get_option('option_' . 'type_of_vacuum_generator')),
    );

    if ($fieldName != '') {
        return $pump[$fieldName];
    } else {
        return $pump;
    }
}

function getMetaFieldNameVacuumPump($key = '')
{
    $name = array(
        'pump_size'                => 'Pump Size',
        'vacuum_level'             => 'Vacuum Level',
        'stackable'                => 'Stackable',
        'individual'               => 'Individual',
        'type_of_vacuum_generator' => 'Type of Vacuum Generator',
    );

    if ($key != '') {
        return $name[trim($key)];
    } else {
        return $name;
    }
}

//Gripper Option Meta Field
function getMetaFieldVGripSystem($fieldName = '')
{
    $gripper = array(
        'pad_type'         => explode(',', get_option('option_' . 'pad_type')),
        'vacuum_generator' => explode(',', get_option('option_' . 'vacuum_generator')),
    );

    if ($fieldName != '') {
        return $gripper[$fieldName];
    } else {
        return $gripper;
    }
}

function getMetaFieldNameVGripSystem($key = '')
{
    $name = array(
        'pad_type'         => 'Pad Type',
        'vacuum_generator' => 'Vacuum Generator',
    );

    if ($key != '') {
        return $name[trim($key)];
    } else {
        return $name;
    }
}

/**
 * 알바벳 리턴 함수
 */
function getAlpha()
{
    return array('A', 'B', 'C', 'D', 'E', 'F', 'G', 'H', 'I', 'J', 'K', 'L', 'M', 'N', 'O', 'P', 'Q', 'R', 'S', 'T', 'U', 'V', 'W', 'X', 'Y', 'Z');
}

/**
 * 모든 카테고리 term_id리턴
 * [String]$name : 카테고리 명
 * [int]$parentTermId : 상위 카테고리 아이디 ==> 상위 카테고리의 하위카테고리 배열 반환
 */
function getAllCategoryTermId($name = '', $parentTermId = '')
{
    $categories = array();
    $parentStr  = ($parentTermId != '') ? '&parent=' . $parentTermId : '';
    $terms      = get_terms('product_cat', 'orderby=term_id&hide_empty=0' . $parentStr);
    if (!empty($terms) && !is_wp_error($terms)) {
        foreach ($terms as $term) {
            $categories[$term->name] = $term->term_id;
        }
    }

    if ($name != '') {
        return $categories[$name];
    } else {
        return $categories;
    }
} //end function getAllCategoryTermId

/**
 * 모든 카테고리 명 리턴
 * [int]$termId : 카테고리 아이디
 */
function getAllCategoryTermName($termId = '')
{
    $categories = array();
    $terms      = get_terms('product_cat', 'orderby=term_id&hide_empty=0');
    if (!empty($terms) && !is_wp_error($terms)) {
        foreach ($terms as $term) {
            $categories[$term->term_id] = $term->slug;
        }
    }

    if ($termId != '') {
        return $categories[$termId];
    } else {
        return $categories;
    }
} //end function getAllCategoryTermName

/**
 * 상위 카테고리 아이디와 메타 필드 가져올 함수 명 매칭 시켜주기
 * [int]$termId : 상위 카테고리 아이디
 */
function matchTermIdFunctionName($termId = '')
{
    $function_name = array('30' => 'suction_cup', '421' => 'suction_cup', '64' => 'vacuum_pump');

    if ($termId != '') {
        return $function_name[$termId];
    } else {
        return $function_name;
    }
}

/**
 * 슬러그에 해당되는 메타 필드 가져올 함수 명 매칭 시켜주기
 * [string]$slug : 카테고리 슬러그
 */
function matchSlugFunctionName($slug = '')
{
    $slug_ary    = array(
        'suction-cup'   => 'suctionCup',
        'vacuum-pump'   => 'vacuumPump',
        'v-grip-system' => 'vGripSystem',
    );
    $result_name = '';
    foreach ($slug_ary as $key => $value) {
        if (strpos($slug, $key) !== false) {
            $result_name = $value;
        }
    }

    return $result_name;
}

/**
 * 상위 카테고리 리턴
 * [int]$termId : 하위 카테고리 아이디
 */
function getParentCategory($termId = '')
{
    $child_category  = get_category($termId);
    $parent_category = $child_category->parent; //상위 카테고리

    return $parent_category;
}

/**
 * 카테고리 아이디 사용하여 slug얻어내기
 * [int]$termId : 카테고리 아이디
 */
function getCatSlug($termId)
{
    $category = &get_term((int)$termId);
    return $category->slug;
}

/**
 * 우커머스 상품 카테고리 선택 메타박스
 */
function choiceWooProductCat()
{
    global $wpdb;

    $post_id           = get_the_ID();
    $nonce_md_category = wp_create_nonce('create_select_category_3');

    $product_cat  = get_post_meta($post_id, 'product_cat', true); //term_id
    $parent_cat_2 = getParentCategory($product_cat); //두번째 카테고리
    $parent_cat_1 = getParentCategory($parent_cat_2); //상위카테고리

    $category_parent_1     = array();
    $category_parent_1[''] = __('최상위 카테고리 선택', 'vmeca');
    foreach (getAllCategoryTermId('', '0') as $key => $value) {
        $category_parent_1[$value] = $key;
    }

    $choice_select = wp_get_post_terms(get_the_ID(), 'product_cat', array('orderby' => 'term_id', 'order' => 'ASC'));

    $select_1 = selectTag(
        $category_parent_1,
        $parent_cat_1,
        array('id' => 'category_parent_1', 'style' => 'width:200px; float:left;'),
        array(),
        true //echo
    );

    $select_2 = selectTag(
        array('' => __('Select Parent Category', 'vmeca')),
        $parent_cat_2,
        array('id' => 'category_parent_2', 'style' => 'width:200px; float:left; margin-left:1%;'),
        array(),
        true //echo
    );

    $select_3 = selectTag(
        array('' => __('Select Product Line', 'vmeca')),
        $product_cat,
        array('id' => 'category_parent_3', 'name' => 'product_cat', 'style' => 'width:200px; float:left; margin-left:1%;'),
        array(),
        true //echo
    );

    echo '<br/><br/>';
    ?>
  <script type="text/javascript">
      jQuery(document).ready(function ($) {

          var select_1 = "<?= $parent_cat_1?>";
          var select_2 = "<?= $parent_cat_2?>";
          var select_3 = "<?= $product_cat?>";

          md_category($('#category_parent_1').val(), 'category_parent_2', "<?php _e('Select Parent Category', 'vmeca')?>");
          md_category($('#category_parent_2').val(), 'category_parent_3', "<?php _e('Select Product Line', 'vmeca');?>");

          $("#category_parent_1").change(function () {
              $("#category_parent_3").val('').prop('selected', true);
              md_category($(this).val(), 'category_parent_2', '<?php _e("Select Parent Category", "vmeca"); ?>'); //2번째 셀렉트 박스 만드는 함수 호출
          }); //change event

          $("#category_parent_2").change(function () {
              md_category($(this).val(), 'category_parent_3', '<?php _e("Select Product Line", "vmeca"); ?>'); //3번째 셀렉트 박스 만드는 함수 호출
          });

          /**
           * 셀렉트 박스 만드는 함수
           * [int]term_id : 상위 카테고리 아이디
           * [string]obj_id : 카테고리 select box id
           * [string]first_sel_text : select box default option value
           * [int]selected_data : select box selected value
           */
          function md_category(term_id, obj_id, first_sel_text, selected_data) { //셀렉트 박스 만드는 함수
              var nonce = "<?=$nonce_md_category?>";

              if (term_id !== '') {
                  $.ajax({
                      type: "POST",
                      dataType: "json", //배열형태로 받아오기  //default : text
                      url: "<?php echo admin_url('admin-ajax.php'); ?>",
                      data: {
                          'action': 'get_list_category',
                          'term_id': term_id,
                          'nonce': nonce
                      },
                      success: function (response) {
                          var obj = document.getElementById(obj_id);
                          obj.innerHTML = '';
                          var html = '';

                          html += '<option value="">' + first_sel_text + '</option>';
                          jQuery.each(response, function (i, item) {
                              html += '<option value="' + item.term_id + '">' + item.name + '</option>';
                          });
                          obj.innerHTML = html;
                          $("#" + obj_id).css('display', 'block');

                          if (selected_data !== '') {
                              $("#" + obj_id).val(selected_data).prop('selected', true);
                          }
                      },
                      error: function (r) {
                          alert(JSON.stringify(r));
                          alert("Failed");
                      }
                  });//end call ajax
              }//end if
          } //end function md_category
      });
  </script>
    <?php
} //end function choiceWooProductCat

/**
 * 'mm' => ''
 * [String]$key : 바꿀 문자열
 */
function replace_mm($key)
{
    return str_replace('mm', '', $key);
}

/**
 * ' ' => '-'
 * [String]$key : 바꿀 문자열
 */
function replace_blank($key)
{
    return str_replace(' ', '-', $key);
}

/**
 * mm => inch 변환
 * [int]$num : 인치로 바꿀 숫자
 */
function mmToinch($num)
{
    if (ICL_LANGUAGE_CODE == 'us') { //USA인 경우
        return $num * 0.0393701; //1mm => 0.0393701inch
    } else {
        return $num;
    }
}

function getFileCategory($name = '')
{
    $categories = array();
    $terms      = get_terms('ddownload_category', 'orderby=term_id&hide_empty=0');
    if (!empty($terms) && !is_wp_error($terms)) {
        foreach ($terms as $term) {
            $categories[$term->name] = $term->term_id;
        }
    }

    if ($name != '') {
        return $categories[$name];
    } else {
        return $categories;
    }
} //end function getFileCategory
