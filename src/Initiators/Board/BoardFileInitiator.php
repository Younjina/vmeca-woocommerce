<?php

namespace Ivy\Vmeca\Initiators\Board;

use Ivy\Mu\Initiators\AutoHookInitiator;
use function Ivy\Mu\Functions\selectTag;
use function Ivy\Vmeca\Functions\getAllCategoryTermId;
use function Ivy\Vmeca\Functions\getFileCategory;
use function Ivy\Vmeca\Functions\pagination; //action or filter or shortcode 등을 자동호출해줌


/**
 * 다운로드 파일 사용자 페이지
 */
class BoardFileInitiator extends AutoHookInitiator
{
    //ddownload_list
    public function shortcode_download_file_list($atts)
    {
        global $wpdb;

        $defaults = array(
            'row' => 10,
        ); // 기본 값

        $atts = shortcode_atts($defaults, $atts);
        $row  = $atts['row'];

        $contents = '';

        ob_start();

        $nonceMdCategory = wp_create_nonce('create_select_category_3');

        $category_1     = array();
        $category_1[''] = __('최상위 카테고리 선택', 'vmeca');
        foreach (getAllCategoryTermId('', '0') as $key => $value) {
            $category_1[$value] = $key;
        }

        $fileCatList     = array();
        $fileCatList[''] = __('Select File Category', 'vmeca'); //'파일 카테고리 선택';
        foreach (getFileCategory() as $key => $value) {
            $fileCatList[$value] = $key;
        }

        $sel_cat_1    = (isset($_GET['category_1'])) ? $_GET['category_1'] : '';
        $sel_cat_2    = (isset($_GET['category_2'])) ? $_GET['category_2'] : '';
        $sel_cat_3    = (isset($_GET['category_3'])) ? $_GET['category_3'] : '';
        $fileCategory = (isset($_GET['file_category'])) ? $_GET['file_category'] : '';

        $left = $inner = $where = "";

        if ($sel_cat_3) {
            $inner = "INNER JOIN {$wpdb->postmeta} AS mt ON ( p.ID = mt.post_id ) ";
            $where = " AND ( (mt.meta_key = 'product_cat' AND mt.meta_value = '%d') ) ";
            $value = $sel_cat_3;
        }

        if ($fileCategory) {
            $left  = "LEFT JOIN {$wpdb->term_relationships} ON (p.ID = {$wpdb->term_relationships}.object_id)
					   LEFT JOIN {$wpdb->term_taxonomy} ON ({$wpdb->term_relationships}.term_taxonomy_id = {$wpdb->term_taxonomy}.term_taxonomy_id)";
            $where .= "AND {$wpdb->term_taxonomy}.term_id IN(" . esc_sql($fileCategory) . ") 
					  AND {$wpdb->term_taxonomy}.taxonomy = 'ddownload_category'";
        }

        $paged = (get_query_var('paged')) ? get_query_var('paged') : 1;
        $page  = ($paged - 1) * 15;
        $query = " SELECT p.ID
						FROM {$wpdb->posts} p
						{$inner}
						{$left}
						WHERE 1=1 
						{$where}
							AND p.post_type = 'dedo_download'
							AND p.post_status = 'publish' 
						GROUP BY p.ID 
						ORDER BY $order p.post_date DESC 
						LIMIT $page, $row"; //상품 최하위 카테고리와 같은 파일을 찾는 쿼리

        $rows = $wpdb->get_results($wpdb->prepare($query, $value), ARRAY_A);

        $totalQuery = "SELECT count(p.ID)
							FROM {$wpdb->posts} p
							{$inner}
							{$left}
							WHERE 1=1 
							{$where}
								AND p.post_type = 'dedo_download'
								AND p.post_status = 'publish'
							ORDER BY $order p.post_date DESC";

        $totCnt  = $wpdb->get_var($wpdb->prepare($totalQuery, $value)); //상품 최하위 카테고리와 같은 파일을 찾는 쿼리
        $totPage = ceil($totCnt / $row);

        $contents .= '<form method="get" name="filter_file_category">';

        $contents .= selectTag(
            $fileCatList,
            $fileCategory,
            array('id' => 'file_category', 'name' => 'file_category', 'style' => 'width:200px; float:left;'),
            array(),
            false //echo
        );

        $contents .= selectTag(
            $category_1,
            $sel_cat_1,
            array('id' => 'category_1', 'name' => 'category_1', 'class' => 'file_category', 'style' => 'width:200px; float:left; margin-left:1%;'),
            array(),
            false //echo
        );

        $contents .= selectTag(
            array('' => __('Select Parent Category', 'vmeca')),
            $sel_cat_2,
            array('id' => 'category_2', 'name' => 'category_2', 'class' => 'file_category', 'style' => 'width:200px; float:left; margin-left:1%;'),
            array(),
            false //echo
        );

        $contents .= selectTag(
            array('' => __('Select Product Line', 'vmeca')),
            $sel_cat_3,
            array('id' => 'category_3', 'name' => 'category_3', 'class' => 'file_category', 'style' => 'width:200px; float:left; margin-left:1%;'),
            array(),
            false //echo
        );
        $contents .= '<br/><br/><input type="submit" id="file_filter" value="filter"/></form><br/><br/>';
        ?>
      <script type="text/javascript">
          jQuery(document).ready(function ($) {
              var select_1 = "<?= $sel_cat_1?>";
              var select_2 = "<?= $sel_cat_2?>";
              var select_3 = "<?= $sel_cat_3?>";

              if (select_2 != '') {
                  md_category_file(select_1, 'category_2', "<?= _e('Select Parent Category', 'vmeca')?>", select_2);
              }
              if (select_3 != '') {
                  md_category_file(select_2, 'category_3', "<?= _e('Select Product Line', 'vmeca')?>", select_3); //3번째 셀렉트 박스 만드는 함수 호출
              }

              $("#category_1").change(function () {
                  var term_id = $("#category_1").val();

                  $("#category_3").val('').prop('selected', true);
                  $("#category_3").css('display', 'none');
                  md_category_file(term_id, 'category_2', "<?= _e('Select Parent Category', 'vmeca')?>"); //2번째 셀렉트 박스 만드는 함수 호출
              }); //change event

              $("#category_2").change(function () {
                  var term_id = $("#category_2").val();
                  md_category_file(term_id, 'category_3', "<?= _e('Select Product Line', 'vmeca')?>"); //3번째 셀렉트 박스 만드는 함수 호출
              });

              /**
               * 셀렉트 박스 만드는 함수
               * [int]term_id : 상위 카테고리 아이디
               * [string]obj_id : 카테고리 select box id
               * [string]first_sel_text : select box default option value
               * [int]selected_data : select box selected value
               */
              function md_category_file(term_id, obj_id, first_sel_text, selected_data) { //셀렉트 박스 만드는 함수
                  var nonce = "<?=$nonceMdCategory?>";

                  if (term_id != '') {
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

                              html += '<option value="">' + first_sel_text + '</option>'
                              $.each(response, function (i, item) {
                                  html += '<option value="' + item.term_id + '">' + item.name + '</option>';
                              });
                              obj.innerHTML = html;
                              $("#" + obj_id).css('display', 'block');

                              if (selected_data != '') {
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

        //$contents .= do_shortcode('[ddownload id="4440"]');
        $contents .= '<ul class="file_list_ul">';
        if (!empty($rows)) {
            foreach ($rows as $key => $value) {
                $contents .= '<li class="file_list_li">' . do_shortcode('[ddownload style="link" id="' . $value['ID'] . '"]') . '</li>';
            }
        } else {
            $contents .= __('No search results found.', 'vmeca');//do_shortcode("[ddownload_list]");
            //검색 결과가 없습니다.
        }

        $contents .= '</ul>';

        $contents .= pagination($totPage, $paged, $row);
        $contents .= ob_get_contents();
        ob_end_clean();

        return $contents;
    }

} //end class BoardAppliedInitiator
