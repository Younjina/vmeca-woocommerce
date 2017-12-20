<?php

namespace Ivy\Vmeca\Initiators\Admin;

use Ivy\Mu\Functions as MuFunctions;
use Ivy\Mu\Initiators\AutoHookInitiator;
use Ivy\Mu\Models\MetaFieldModel;
use Ivy\Mu\Models\ValueTypes\DummyValueType;
use Ivy\Mu\Views\FieldWidgets\GenericInput;
use Ivy\Vmeca\Functions as VmecaFunctions; //action or filter or shortcode 등을 자동호출해줌

class AdminItemInitiator extends AutoHookInitiator
{
//	protected $disabled=true; //true면 실행되지 않음

    //add the product type select box option
    /*	public function filter_product_type_selector($types){
            $product_type = VmecaFunctions\getVmecaProductType();

            $vmeca_types = array();
            foreach ($product_type as $key => $value) {
                $vmeca_types[ $key ] = __( $value, 'vmeca' );
            }

            return array_merge($vmeca_types, $types); //apply_filters( 'vmeca_suction_cup',  );
        }*/

    /**
     * 상품 옵션 값 추가 필터 함수
     */
    /*	public function filter_10_1_woocommerce_product_data_tabs($tabs) {
            //unset($tabs['general']); //우커머스 일반 탭 제거
            //return($tabs);
            $product_type_var = get_post_meta( get_the_ID(), 'product-type', true);

            $product_type = VmecaFunctions\getVmecaProductType();

            foreach ($product_type as $key => $value) {
                ${$key.'_options'} = array(
                    'label'  => __( $value.'Meta Field', 'VMECA' ),
                    'target' => $key.'_options',
                    'class'  => array( 'show_if_'.$key )
                );
            }

            array_push( $tabs['shipping']['class'], 'hide_if_vacuum' );

            $return_tabs = array( 'suction_cup_options' => $suction_cup_options, 'vacuum_pad_options' => $vacuum_pad_options, 'speeder_module_options' => $speeder_module_options, 'vgrip_system_options'=> $vgrip_system_options, 'accessories_options' => $accessories_options, 'conveyor_options' => $conveyor_options, 'other_options' => $other_options );

            return array_merge($return_tabs, $tabs);

        } *///end function filter_10_1_woocommerce_product_data_tabs


    /**
     * 상품 옵션 필드 추가 함수
     */
    /*	public function action_woocommerce_product_data_panels( ) {
            global $post;

            echo "<div id='suction_cup_options' class='panel woocommerce_options_panel'>"; //상품 옵션 값인 경우에만 출력하기

            echo '<table style="margin:25px; width:80%;">';
            foreach (VmecaFunctions\getMetafieldNameSuctionCup() as $key => $value) {
                ${$key} = VmecaFunctions\getMetafieldSuctionCup($key);
                ${$key.'_options'} = array();
                ${$key.'selected'} = get_post_meta($post->ID, $key, true);

                ${$key.'_options'}[''] = $value;
                for ($i=0; $i < count(${$key}); $i++) {
                    ${$key.'_options'}[${$key}[$i]] = ${$key}[$i];
                }
                //echo '<span style="float:left;">'.$key.'</span>';
                $span = MuFunctions\genericTag(
                    'span',
                    array(),
                    $value,
                    true,
                    false,
                    false //echo
                );

                $select = MuFunctions\selectTag(
                    ${$key.'_options'}, // array($key => $value,,)
                    ${$key.'selected'},
                    array('id' => $key, 'name' => $key, 'class' => $key, 'style' => 'width:200px;' ),
                    array(),
                    false //echo
                );
                ?>
                    <tr>
                        <th style="width:20%;"><?php echo $span;?></th>
                        <td style="width:80%;"><?php echo $select;?></td>
                    </tr>
                <?php
            }
            echo '</table>';
            echo "</div>";


        }*/ //end action function action_woocommerce_product_data_panels

    /**
     * suction cup 저장시 post_meta값 저장하기
     */
    /*	public function action_woocommerce_process_product_meta_suction_cup() {

            global $post;

            foreach (VmecaFunctions\getMetafieldNameSuctionCup() as $key => $value) {
                if( isset($_POST[$key]) ) {
                    update_post_meta($post->ID, $key, $_POST[$key]);
                }
            }

            if( isset($_POST['product-type']) ){
                update_post_meta( $post->ID, 'product-type', $_POST['product-type'] );
            }

        }*/ // end action function action_save_post


    /**
     * delightful-download 포스트 메타일 때 메타 박스 추가하기
     */
    public function action_add_meta_boxes_product()
    {
        remove_meta_box("pyre_woocommerce_options", "product", "advanced");
        remove_meta_box("product_catdiv", 'product', 'side');

        add_meta_box('show_product_category_option_field', __('카테고리 및 옵션 필드 선택'), array($this, 'show_product_category_option_field'), 'product', 'advanced');
    }

    public function show_product_category_option_field()
    {
        $post_id = get_the_ID();

        $nonce_md_category   = wp_create_nonce('create_select_category_3');
        $nonce_md_meta_field = wp_create_nonce('create_meta_fiedl');

        $category_parent_1     = array();
        $category_parent_1[''] = '상위 카테고리 선택';
        foreach (VmecaFunctions\getAllCategoryTermId('', '0') as $key => $value) {
            $category_parent_1[$value] = $key;
        }

        $choice_select = wp_get_post_terms(get_the_ID(), 'product_cat', array('orderby' => 'term_id', 'order' => 'ASC'));

        $select_1 = MuFunctions\selectTag(
            $category_parent_1,
            $choice_select[0]->term_id,
            array('id' => 'category_parent_1', 'name' => 'category_parent_1', 'style' => 'width:200px;'),
            array(),
            true //echo
        );

        $select_2 = MuFunctions\selectTag(
            array(),
            $choice_select[1]->term_id,
            array('id' => 'category_parent_2', 'name' => 'category_parent_2', 'style' => 'width:200px; display:none;'),
            array(),
            true //echo
        );

        $select_3 = MuFunctions\selectTag(
            array(),
            $choice_select[2]->term_id,
            array('id' => 'category_parent_3', 'name' => 'category_parent_3', 'style' => 'width:200px; display:none;'),
            array(),
            true //echo
        );

        echo '<div id="custom_meta_field"></div>';

        ?>
      <script type="text/javascript">
          jQuery(document).ready(function ($) {

              var select_1 = "<?= $choice_select[0]->term_id?>";
              var select_2 = "<?= $choice_select[1]->term_id?>";
              var select_3 = "<?= $choice_select[2]->term_id?>";

              if (select_2 != '') {
                  md_category(select_1, 'category_parent_2', '상위 카테고리 선택', select_2);
              }
              if (select_3 != '') {
                  md_category(select_2, 'category_parent_3', '제품군 선택', select_3); //3번째 셀렉트 박스 만드는 함수 호출
              }

              if (select_1 != '' && select_2 != '' && select_3 != '') {
                  md_meta_field(select_1, 'custom_meta_field');
              }

              $("#category_parent_1").change(function () {
                  var term_id = $("#category_parent_1").val();

                  md_category(term_id, 'category_parent_2', '상위 카테고리 선택'); //2번째 셀렉트 박스 만드는 함수 호출
              }); //change event

              $("#category_parent_2").change(function () {
                  var term_id = $("#category_parent_2").val();
                  md_category(term_id, 'category_parent_3', '제품군 선택'); //3번째 셀렉트 박스 만드는 함수 호출
              });

              $("#category_parent_3").change(function () {
                  var category_1 = $("#category_parent_1").val();

                  md_meta_field(category_1, 'custom_meta_field');
              });

              function md_category(term_id='', obj_id='', first_sel_text='', selected_data='') { //셀렉트 박스 만드는 함수
                  var nonce = "<?=$nonce_md_category?>";

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
                              jQuery.each(response, function (i, item) {
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

              function md_meta_field(term_id='', obj_id='') {
                  var nonce = "<?=$nonce_md_meta_field?>";
                  var post_id = "<?=$post_id?>";

                  if (term_id != '') {
                      $.ajax({
                          type: "POST",
                          dataType: "html", //html 형태로 받아오기  //default : text
                          url: "<?php echo admin_url('admin-ajax.php'); ?>",
                          data: {
                              'action': 'get_custom_field',
                              'term_id': term_id,
                              'post_id': post_id,
                              'nonce': nonce
                          },
                          success: function (response) {
                              $("#custom_meta_field").html(response);
                          },
                          error: function (r) {
                              alert(JSON.stringify(r));
                              alert("Failed");
                          }
                      });//end call ajax
                  }
              }


          });
      </script>
        <?php
    } //end function show_product_category_option_field

    public function action_save_post()
    {

        $post_id = get_the_ID(); //[int]post id
        $user_id = get_current_user_id(); //[int]user id

        if ($_POST['post_type'] == 'product') {

            if ((isset($_POST['category_parent_1']) && $_POST['category_parent_1'] != '') && (isset($_POST['category_parent_2']) && $_POST['category_parent_2'] != '') && (isset($_POST['category_parent_3']) && $_POST['category_parent_3'] != '')) {
                wp_set_post_terms($post_id, array($_POST['category_parent_1'], $_POST['category_parent_2'], $_POST['category_parent_3']), 'product_cat', true);
            }

            if (isset($_POST['category_parent_1']) && $_POST['category_parent_1'] == 30) {
                /*print_r($_POST);
                        exit;*/
                foreach (VmecaFunctions\getMetafieldNameSuctionCup() as $key => $value) {
                    if (isset($_POST[$key])) {
                        update_post_meta($post_id, $key, $_POST[$key]);
                    }
                }
            }

        }
    } //end function action_save_post


    public function action_admin_menu()
    { //엑셀 올리기 메뉴

        add_submenu_page('edit.php?post_type=product', __('엑셀 올리기', 'menu-test'), __('엑셀 올리기', 'menu-test'), 'manage_options', 'vmeca_product', array($this, 'vmeca_product_excel_upload'));
    }

    public function vmeca_product_excel_upload()
    { //엑셀 올리기 페이지
        ?>
      <div>
        <form method="POST" enctype="multipart/form-data">
          <input type="file" name="excelFile" value=""><br/>
          <input type="submit" value="Vmeca Excel upload"/>
        </form>
      </div>
        <?php
    }

    /**
     * 엑셀 파일 읽기
     */
    public function action_admin_init()
    {

        if (isset($_FILES['excelFile']['name']) && $_FILES['excelFile']['name']) {
            $upload_dir    = wp_upload_dir();
            $tmp_file      = $_FILES['excelFile']['tmp_name'];
            $upfile_path   = $upload_dir['basedir'] . "/Logic.csv";
            $inputFileName = $upload_dir['basedir'] . "/Logic.xls";

            // 업로드가 안된다면 에러메세지 출력하고 죽어버립니다.
            $is_success = move_uploaded_file($tmp_file, $inputFileName) or wp_die($_FILES['excelFile']['error']);

            if ($is_success) {
                error_reporting(E_ALL);
                ini_set('memory_limit', -1); // 엑셀 가져오다가 뻗을 수 있으므로 메모리는 무한대로~
                set_time_limit(0);

                if (!class_exists('PHPExcel_Reader_Excel2007')) {
                    require_once VMECA_ABSPATH . '/excel/Classes/PHPExcel/IOFactory.php';
                }

                $objReader = new \PHPExcel_Reader_Excel2007();

                $objPHPExcel = $objReader->load($inputFileName);

                $sheetData = $objPHPExcel->getActiveSheet()->toArray(null, true, true, true); //excel download content -- 데이터 가져오기
                // print_r($sheetData[0]);
//	            var_dump($sheetData);
//	            exit;
                $this->put_excel_file_con($sheetData); //엑셀 파일 출력 기능 수정 후에
            }
        }
    }


    public function put_excel_file_con($sheetData)
    {
        error_reporting(E_ALL);
        ini_set('memory_limit', -1); // 엑셀 가져오다가 뻗을 수 있으므로 메모리는 무한대로~
        set_time_limit(0);

        $category         = VmecaFunctions\getCategorySuctionCup(); //suction cup 카테고리 배열
        $suction_category = '';
        $post_id          = 0;
        $alpha            = VmecaFunctions\getAlpha(); //알파벳

        $args = array(
            'post_type' => 'product',
            'orderby'   => 'post_date',
            'order'     => 'DESC',
        );

        $recently_posts = get_posts($args);
        foreach ($recently_posts as $post) {
            $post_id = $post->ID;
        } //최근 저장된 포스트 아이디 구하기
        $post_id = 7000;

        $categories = array();

        $terms = get_terms('product_cat', 'orderby=count&hide_empty=0');

        if (!empty($terms) && !is_wp_error($terms)) {
            foreach ($terms as $term) {
                $categories[$term->name] = $term->term_id;
            }
        }

        //$sheetData[$i]['A'] - 제품명 //$sheetData[$i]['B'] - 제품 크기 cup_diameter //$sheetData[$i]['C'] - material//$sheetData[$i]['D'] - cup_shape //$sheetData[$i]['E'] - hardness //$sheetData[$i]['F'] - fitting_size //$sheetData[$i]['G'] - fitting_type //$sheetData[$i]['H'] - thread_type //$sheetData[$i]['I'] - fitting_option //$sheetData[$i]['J'] - etc //$suction_category - 카테고리

        for ($i = 2; $i <= 2; $i++) { //count($sheetData);

            $post_id++;

            if ($post_id) {
                $post = get_post($post_id);
                if (is_object($post)) {
                    if (!$post->ID || $post->post_type != 'product') {
                        $post_id = null;
                    }
                } else {
                    $post_id = null;
                }
            }// post_id;

            $_post = array(
                'post_title'    => $sheetData[$i]['A'], //제품명
                'post_content'  => '',
                'post_type'     => 'product',
                'post_status'   => 'publish',
                'post_category' => array(''),//$suction_category
                'post_author'   => 2,
            );

            if ($post_id) {
                // Update the post into the database.
                $_post['ID'] = $post_id;
                wp_update_post($_post);
            } else {
                // Insert the post into the database.
                $post_id = wp_insert_post($_post);
            }

            $alpha_cnt = 1;

            foreach (VmecaFunctions\getMetafieldNameSuctionCup() as $key => $value) { //메타 필드 값 저장
                update_post_meta($post_id, $key, $sheetData[$i][$alpha[$alpha_cnt]]);
                $alpha_cnt++;
            }

            $suction_category = '';
            for ($j = 0; $j < count($category); $j++) {
                if (strpos($sheetData[$i]['A'], $category[$j]) !== false) {
                    $suction_category = $category[$j];
                }
            } //카테고리

            if ($suction_category != '') {

                $category_2_child = get_category($categories[$suction_category]);
                $category_2       = $category_2_child->parent; //상위 카테고리

                $category_1_child = get_category($category_2);
                $category_1       = $category_1_child->parent; //최상위 카테고리

                wp_set_post_terms($post_id, array($category_1, $category_2, $categories[$suction_category]), 'product_cat', true);
            }

            $suction_category = '';

        }//for

//		exit;
    } //end function put_excel_file_con

} //end class AdminItemInitiator
