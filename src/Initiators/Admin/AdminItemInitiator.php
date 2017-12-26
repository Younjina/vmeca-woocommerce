<?php

namespace Ivy\Vmeca\Initiators\Admin;

use Ivy\Mu\Initiators\AutoHookInitiator;
use function Ivy\Mu\Functions\enqueueScript;
use function Ivy\Mu\Functions\genericTag;
use function Ivy\Mu\Functions\getJSUrl;
use function Ivy\Mu\Functions\selectTag;
use function Ivy\Vmeca\Functions\getAllCategoryTermId;
use function Ivy\Vmeca\Functions\getAlpha;
use function Ivy\Vmeca\Functions\getCategorySuctionCup;
use function Ivy\Vmeca\Functions\getMetafieldNameSuctionCup;
use function Ivy\Vmeca\Functions\getMetafieldSuctionCup;
use function Ivy\Vmeca\Functions\getParentCategory;
use function Ivy\Vmeca\Functions\getVmecaProductType;
use function Ivy\Vmeca\Functions\matchSlugFunctionName;

//action or filter or shortcode 등을 자동호출해줌

/**
 * 관리자 상품 훅 관련 클래스
 */
class AdminItemInitiator extends AutoHookInitiator
{
    /**
     * product 포스트 메타일 때 메타 박스 추가하기
     */
    public function action_add_meta_boxes_product()
    {
        remove_meta_box("pyre_woocommerce_options", "product", "advanced");
        remove_meta_box("product_catdiv", 'product', 'side');

        add_meta_box('show_product_category_option_field', __('Enter category and additional meta fields', 'vmeca'), array($this, 'showProductCategoryOptionField'), 'product', 'advanced'); //카테고리 및 추가 메타 필드 입력
    } //end function action_add_meta_boxes_product

    /**
     * 카테고리 및 추가 메타 필드 입력
     */
    public function showProductCategoryOptionField()
    {
        $postId   = get_the_ID();
        $category = getCategorySuctionCup(); //suction cup 카테고리 배열

        $categoryParent1     = array();
        $categoryParent1[''] = '최상위 카테고리 선택';
        foreach (getAllCategoryTermId('', '0') as $key => $value) :
            $categoryParent1[$value] = $key;
        endforeach;

        $choiceSelect = wp_get_post_terms($postId, 'product_cat');

        $select_1 = selectTag(
            $categoryParent1,
            $choiceSelect[0]->term_id,
            array(
                'id'    => 'category_parent_1',
                'name'  => 'category_parent_1',
                'style' => 'width:200px; float:left;',
            ),
            array(),
            true //echo
        );

        $select_2 = selectTag(
            array('' => __('Select Parent Category', 'vmeca')), //상위 카테고리 선택
            $choiceSelect[1]->term_id,
            array(
                'id'    => 'category_parent_2',
                'name'  => 'category_parent_2',
                'style' => 'width:200px; float:left; margin-left:1%;',
            ),
            array(),
            true //echo
        );

        $select_3 = selectTag(
            array('' => __('Select Product Line', 'vmeca')), //제품군 선택'
            $choiceSelect[2]->term_id,
            array(
                'id'    => 'category_parent_3',
                'name'  => 'category_parent_3',
                'style' => 'width:200px; float:left; margin-left:1%;',
            ),
            array(),
            true //echo
        );

        echo '<br/><br/><br/><div id="custom_meta_field"></div>';

        enqueueScript(
            'main',
            getJSUrl($this->getLauncher(), 'main.js'),
            array('jquery'),
            $this->getLauncher()->getVersion(),
            true,
            'main',
            array(
                'ajaxUrl'        => admin_url('admin-ajax.php'),
                'categoryNonce'  => wp_create_nonce('create_select_category_3'),
                'metaFieldNonce' => wp_create_nonce('create_meta_field'),
                'postId'         => get_the_ID(),
                'choiceSelect1'  => $choiceSelect[0]->term_id,
                'choiceSelect2'  => $choiceSelect[1]->term_id,
                'choiceSelect3'  => $choiceSelect[2]->term_id,
            )
        );
    } //end function showProductCategoryOptionField

    /**
     * 포스트 저장 - 우커머스 커스텀 메타 필드 저장하기
     */
    public function action_save_post()
    {
        $postId = get_the_ID(); //[int]post id

        if ($_POST['post_type'] == 'product') :

            if ((isset($_POST['category_parent_1']) && $_POST['category_parent_1'] != '')) :
                wp_set_post_terms($postId, array($_POST['category_parent_3'], $_POST['category_parent_2'], $_POST['category_parent_1']), 'product_cat', false);
            endif;

            if (isset($_POST['category_parent_1'])) :
                $getTerm      = get_term($_POST['category_parent_1']);
                $functionName = matchSlugFunctionName($getTerm->slug);

                $metaField = call_user_func('\\Ivy\\Vmeca\\Functions\\getMetafieldName' . $functionName);
                foreach ($metaField as $key => $value) {
                    if (isset($_POST[$key])) {
                        update_post_meta($postId, $key, $_POST[$key]);
                    }
                }
            endif;
        endif;
    } //end function action_save_post

    /**
     * 우커머스 카테고리 메타 필드 추가 함수
     */
    public function action_product_cat_edit_form_fields()
    {
        $termId   = $_GET['tag_ID'];
        $category = get_category($termId);

        if ($category->parent == 30) : //suction cup 카테고리의 하위카테고리인 경우에만 ==> 컵 모양 선택할 수 있도록 수정하기
            echo '<br/>';
            $shape            = getMetafieldSuctionCup('cup_shape');
            $shapeOptions     = array();
            $shapeSelect      = get_term_meta($termId, 'category_cup_shape', true);
            $shapeOptions[''] = __('Cup Shape', 'vmeca'); //컵 모양
            for ($i = 0; $i < count($shape); $i++) :
                $optionKey                = str_replace(' ', '-', $shape[$i]);
                $optionValue              = str_replace('-', ' ', $shape[$i]);
                $shapeOptions[$optionKey] = $optionValue;
            endfor;
            $span = genericTag(
                'label',
                array('style' => 'margin-right:22%; font-weight:bold;'),
                __('Cup Shape', 'vmeca'),
                true,
                false,
                true //echo
            );

            $select = selectTag(
                $shapeOptions, // array($key => $value,,)
                $shapeSelect,
                array(
                    'id'       => 'category_cup_shape',
                    'name'     => 'category_cup_shape[]',
                    'multiple' => 'multiple',
                    'class'    => 'category_cup_shape',
                    'style'    => 'width:200px;',
                ),
                array(),
                true //echo
            );
        endif; //end if 최상위 카테고리
    } //end add function action_product_cat_edit_form_fields

    /**
     * 상품 카테고리 편집 버튼 클릭시
     */
    public function action_edited_product_cat($termId)
    {
        if (isset($_POST['category_cup_shape'])) : //카테고리 컵 모양 저장
            update_term_meta($termId, 'category_cup_shape', $_POST['category_cup_shape']);
        endif;
    }

    /**
     * 관리자 메뉴 관리
     * - 엑셀 올리기 메뉴
     * - 상품 옵션 관리
     */
    public function action_admin_menu()
    {
        add_submenu_page('edit.php?post_type=product', __('Option Management', 'vmeca'), __('Option Management', 'vmeca'), 'manage_options', 'vmeca_option', array($this, 'vmecaProductOptionField'));//옵션 관리
        add_submenu_page('edit.php?post_type=product', __('Upload Excel File', 'vmeca'), __('Upload Excel File', 'vmeca'), 'manage_options', 'vmeca_excel', array($this, 'vmecaProductExcelUpload')); //엑셀 올리기
    }

    /**
     * 옵션 관리 - 탭에 맞는 옵션 값 입력 필드 출력
     */
    public function vmecaProductOptionField()
    {
        $tab         = (isset($_GET['tab'])) ? $_GET['tab'] : 'suctionCup';
        $optionField = call_user_func('\\Ivy\\Vmeca\\Functions\\' . 'getMetafieldName' . $tab);
        ?>
      <div class="wrap columns-2 seed-csp4">
        <h2 class="nav-tab-wrapper" style="padding-left:20px">
            <?php
            foreach (getVmecaProductType() as $key => $value) : ?>
              <a class="nav-tab <?php echo($tab == $key ? 'nav-tab-active' : ''); ?>"
                 href="?post_type=product&page=vmeca_option&amp;tab=<?= $key ?>"><?= esc_attr($value . ' setting', 'vmeca') ?></a>
            <?php endforeach; ?>
        </h2>
        <div id="poststuff">
          <div id="post-body" class="metabox-holder">
            <h1><?= $tab; ?></h1>
            <form method="post" novalidate="novalidate">
              <div id="vmeca_option_setting" style="display: block;">
                <input type="hidden" name="option_page" value="vmeca_option"/>
                <input type="hidden" name="action" value="vmeca-option-update"/>
                <input type="hidden" name="tab" value="<?= $tab ?>"/>
                  <?php wp_nonce_field('vmeca-option-setting'); ?>
                <div class="postbox seedprod-postbox">
                  <h3 class="hndle"><?= esc_attr('설정 - 각 항목에 띄어쓰기 없이 ,로 구분하여 값을 입력하시길 바랍니다.', 'vmeca') ?></h3>
                  <div class="inside">
                    <table class="form-table">
                      <tbody>
                      <?php foreach ($optionField as $key => $value) : $optionValue = get_option('option_' . $key); ?>
                        <tr>
                          <td style="width:10%;"><?= $value ?></td>
                          <td style="width:90%;"><input type="text" name="option_<?= $key ?>"
                                                        value="<?= $optionValue ?>" style="width:90%;"/></td>
                        </tr>
                      <?php endforeach; ?>
                      <tr>
                        <td colspan="2">
                          <p class="submit"><input type="submit" name="submit" id="submit" class="button button-primary"
                                                   value="<?= _e('Save changes', 'vmeca') ?>"></p>
                        </td>
                      </tr>
                      </tbody>
                    </table>
                  </div><!--inside-->
                </div>
              </div> <!-- #vmeca_option_setting -->
            </form>
          </div>
        </div><!-- poststuff -->
      </div>
        <?php
    } //end function vmecaProductOptionField

    /**
     * 엑셀 올리기 페이지
     */
    public function vmecaProductExcelUpload()
    {
        ?>
      <div>
        <form method="POST" enctype="multipart/form-data">
          <input type="file" name="excelFile" value=""><br/>
          <input type="submit" value="<?= _e('Upload Excel File', 'vmeca') ?>"/>
        </form>
      </div>
        <?php
    } //end function vmecaProductExcelUpload

    /**
     * 엑셀 파일 읽기
     */
    public function action_admin_init()
    {
        if (isset($_POST['action']) && $_POST['action'] == 'vmeca-option-update') :
            if (isset($_POST['tab'])) :
                foreach (call_user_func('\\Ivy\\Vmeca\\Functions\\' . 'getMetafieldName' . $_POST['tab']) as $key => $value) :
                    if (isset($_POST['option_' . $key]) && $_POST['option_' . $key] != '') :
                        update_option('option_' . $key, $_POST['option_' . $key]);
                    endif;
                endforeach;
            endif;
        endif; //save the vmeca option field value

        if (isset($_FILES['excelFile']['name']) && $_FILES['excelFile']['name']) :
            $uploadDir     = wp_upload_dir();
            $tmpFile       = $_FILES['excelFile']['tmp_name'];
            $upfile_path   = $uploadDir['basedir'] . "/Logic.csv";
            $inputFileName = $uploadDir['basedir'] . "/Logic.xls";

            // 업로드가 안된다면 에러메세지 출력하고 죽어버립니다.
            $isSuccess = move_uploaded_file($tmpFile, $inputFileName) or wp_die($_FILES['excelFile']['error']);

            if ($isSuccess) :
                error_reporting(E_ALL);
                ini_set('memory_limit', -1); // 엑셀 가져오다가 뻗을 수 있으므로 메모리는 무한대로~
                set_time_limit(0);

                if (!class_exists('PHPExcel_Reader_Excel2007')) :
                    require_once VMECA_ABSPATH . '/excel/Classes/PHPExcel/IOFactory.php';
                endif;

                $objReader = new \PHPExcel_Reader_Excel2007();

                $objPHPExcel = $objReader->load($inputFileName);

                $sheetData = $objPHPExcel->getActiveSheet()->toArray(null, true, true, true); //excel download content -- 데이터 가져오기
                $this->putExcelFileCon($sheetData); //엑셀 파일 출력 기능 수정 후에
            endif;
        endif;
    } //end function action_admin_init

    /**
     * 엑셀 데이타 상품으로 만들기
     * [array]$sheetData : 엑셀 데이타 값 배열
     */
    public function putExcelFileCon($sheetData)
    {
        error_reporting(E_ALL);
        ini_set('memory_limit', -1); // 엑셀 가져오다가 뻗을 수 있으므로 메모리는 무한대로~
        set_time_limit(0);

        $category        = getCategorySuctionCup(); //suction cup 카테고리 배열
        $suctionCategory = '';
        $postId          = 7000;
        $alpha           = getAlpha(); //알파벳

        $categories = array();

        $categories     = getAllCategoryTermId();
        $postIdAry      = array();
        $category_1_ary = array();
        $category_2_ary = array();
        $category_3_ary = array();
        $postCategories = array();
        for ($i = 1429; $i <= 1429; $i++) : //count($sheetData);

            $postId++;

            if ($postId) {
                $post = get_post($postId);
                if (is_object($post)) {
                    if (!$post->ID || $post->post_type != 'product') {
                        $postId = null;
                    }
                } else {
                    $postId = null;
                }
            }// postId;

            $_post = array(
                'post_title'   => $sheetData[$i]['A'], //제품명
                'post_content' => '',
                'post_type'    => 'product',
                'post_status'  => 'publish',
                'post_author'  => 2,
            );

            $postId = wp_insert_post($_post);

            $alphaCnt = 1;

            foreach (getMetafieldNameSuctionCup() as $key => $value) : //메타 필드 값 저장
                $metaValue  = explode(',', $sheetData[$i][$alpha[$alphaCnt]]);
                $updateMeta = array();

                $metaValue = array_map('trim', $metaValue);

                if ($alpha[$alphaCnt] == 'B') { //diameter인 경우에만
                    $updateMeta = array_map('\\Ivy\\Vmeca\\Functions\\replace_mm', $metaValue); // '4x20 mm' ==> '4X20 '
                } else {
                    $updateMeta = array_map('\\Ivy\\Vmeca\\Functions\\replace_blank', $metaValue); //' '  => '-'
                }

                update_post_meta($postId, $key, array_map('trim', $updateMeta));
                $alphaCnt++;
            endforeach;

            $suctionCategory = '';

            foreach ($categories as $key => $value) :
                if (strpos($sheetData[$i]['A'], $key) !== false) {
                    $suctionCategory = $key;
                }
            endforeach;//카테고리

            if ($suctionCategory != '') :
                $category_3              = $categories[$suctionCategory];
                $category_2              = getParentCategory($category_3);
                $category_1              = getParentCategory($category_2);
                $postIdAry[]             = $postId;
                $category_1_ary[]        = $category_1;
                $category_2_ary[]        = $category_2;
                $category_3_ary[]        = $category_3;
                $postCategories[$postId] = array('category_1' => $category_1, 'category_2' => $category_2, 'category_3' => $category_3);
            endif;

        endfor;//for

        foreach ($postCategories as $key => $value) { //카테고리 업데이트
            wp_set_post_terms($key, array($value['category_1'], $value['category_2'], $value['category_3']), 'product_cat');
        }
    } //end function putExcelFileCon

} //end class AdminItemInitiator
