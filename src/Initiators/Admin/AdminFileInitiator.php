<?php

namespace Ivy\Vmeca\Initiators\Admin;

use Ivy\Mu\Initiators\AutoHookInitiator;
use Ivy\Mu\Models\ValueTypes\DummyValueType;
use Ivy\Mu\Views\FieldWidgets\GenericInput;
use Ivy\Vmeca\Functions as VmecaFunctions; //action or filter or shortcode 등을 자동호출해줌

/**
 * 관리자 파일 등록 관련 훅
 */
class AdminFileInitiator extends AutoHookInitiator
{
    /**
     * post type이 delightful-download를 사용한 경우에만 파일 업로드하는 사용자명 저장하는 함수
     */
    public function action_save_post()
    {

        $postId  = get_the_ID(); //[int]post id
        $user_id = get_current_user_id(); //[int]user id

        if ($_POST['post_type'] == 'dedo_download') {
            $user              = get_userdata($user_id); //[array]user data
            $user_display_name = $user->data->display_name; //[String]user display name

            update_post_meta($postId, 'user_display_name', $user_display_name);
            update_post_meta($postId, 'update_date_time', date('Y-m-d-H:i:s'));

            if ($_POST['product_cat'] != '' && isset($_POST['product_cat'])) {
                update_post_meta($postId, 'product_cat', $_POST['product_cat']);
            }
        }
    } //end function action_save_post

    /**
     * delightful-download 포스트 메타일 때 메타 박스 추가하기
     */
    public function action_add_meta_boxes_dedo_download()
    {
        add_meta_box('show_dedo_download_author', __('File Writer', 'vmeca'), array($this, 'showFileAuthor'), 'dedo_download', 'side');
        add_meta_box('choice_woo_product_cat_file', __('Select Product Category', 'vmeca'), array($this, 'choice_woo_product_cat_file'), 'dedo_download', 'advanced');
    }

    /**
     * 최근에 파일 수정한 사람 이름, 수정일자 출력하는 메타박스
     */
    public function showFileAuthor()
    {
        $postId         = get_the_ID();
        $userName       = get_post_meta($postId, 'user_display_name', true);
        $updateDateTime = get_post_meta($postId, 'update_date_time', true);

        echo __('Recent Writer', 'vmeca') . ' : ' . $userName; //최근 수정자
        echo '<br/>' . __('Update Date', 'vmeca') . ' : ' . $updateDateTime; //수정 일자
    } //end function showFileAuthor

    /**
     * 파일 - 우커머스 상품 카테고리 선택 메타박스 호출
     */
    public function choice_woo_product_cat_file()
    {
        VmecaFunctions\choiceWooProductCat();
    }


} //end class AdminFileInitiator
