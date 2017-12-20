<?php

namespace Ivy\Vmeca\Initiators\Admin;

use Ivy\Mu\Initiators\Abstracts\AbstractCustomPostAdminInitiator;
use Ivy\Mu\Models\MetaFieldModel;
use Ivy\Vmeca\Views\Admin\Metaboxes\AppliedRelatedProductView;
use function Ivy\Mu\Functions\getWPGlobalVariable;
use function Ivy\Vmeca\Functions\choiceWooProductCat;

/**
 * 적용사례 관리자 페이지 관련 훅
 */
class AdminAppliedInitiator extends AbstractCustomPostAdminInitiator
{
    protected $model;

    /** @var MetaFieldModel - 관련 상품 아이디 */
    private $searchNumberField;


    /** @var MetaFieldModel - 제품 외 직접 지정 url */
    private $otherProductUrlField;

    /** @var MetaFieldModel - 제품 외 직접 지정 타이틀 */
    private $otherProductTitleField;

    /** @var MetaFieldModel - 제품 외 직접 지정 이미지 */
    private $otherProductImageField;

    /**
     * 적용사례 추가시 세팅 - 메타박스 추가, 메타박스 메타필드 값 세팅
     */
    public function setUpInitiator($launcher, $args = array())
    {
        $args = array_merge(
            $args,
            array(
                'model'     => 'applied_model', //어떤 포스트메타인지
                'keywords'  => array(
                    'metaBoxes' => true,
                    'savePost'  => true,
                ),
                'metaBoxes' => array(
                    AppliedRelatedProductView::getClass(),
                ), //메타박스 추가하기 
            )
        );
        parent::setUpInitiator($launcher, $args);

        $this->model = $this->queryModel($this->args['model']);

        $this->searchNumberField = $this->model->getFieldSearchProductNumber(); //관련 상품 아이디(,로 구분)

        $this->otherProductUrlField = $this->model->getFieldOtherProductUrl(); //관련 상품 url

        $this->otherProductTitleField = $this->model->getFieldOtherProductTitle(); //관련 상품 제목

        $this->otherProductImageField = $this->model->getFieldOtherProductImage(); //관련 상품 이미지

    } //end function setUpInitiator

    /**
     * 포스트 타이틀 수정
     */
    public function filter_10_2_enter_title_here($title, $post)
    {
        if ($post->post_type == $this->getModel()->getPostType()) { //post_type이 vmeca_applied인 경우에만 타이틀 수정하기
            $title = __("Applied Case", 'vmeca'); //적용 사례
        }

        return $title;
    }

    /**
     * 필요없는 메타 박스 제거
     */
    public function action_add_meta_boxes_vmeca_applied()
    {
        remove_meta_box('pyre_post_options', 'vmeca_applied', 'advanced'); //avada option 삭제
        add_meta_box('choice_woo_product_cat_applied', __('Select Product Category'), array($this, 'choiceWooProductCatApplied'), 'vmeca_applied', 'advanced');
    }

    /**
     * form enctype설정
     */
    public function action_post_edit_form_tag()
    {
        $typeNow = getWPGlobalVariable('typenow');

        if ($typeNow == $this->model->getPostType()) {
            echo 'enctype="multipart/form-data"';
        }
    } //end action function action_post_edit_form_tag

    /**
     * 다운로드 파일 저장시
     */
    public function action_save_post()
    {
        $postId = get_the_ID();
        if ($_POST['post_type'] == 'vmeca_applied') {
            if (isset($_FILES['vmeca_other_product_image']['name']) && $_FILES['vmeca_other_product_image']['name'] != '') { //이미지 파일 저장
                $uploadDir = wp_upload_dir();
                $tmpFile   = $_FILES['vmeca_other_product_image']['tmp_name'];
                $fileName  = $_FILES['vmeca_other_product_image']['name'];
                $ext       = substr(strrchr($fileName, "."), 1);

                if (strcasecmp($ext, 'jpg') == 0 || strcasecmp($ext, 'png') == 0) {
                    $inputFileName = $uploadDir['basedir'] . '/' . date('Y') . '/' . date('m') . '/' . $fileName;
                    move_uploaded_file($tmpFile, $inputFileName);
                    update_post_meta(get_the_ID(), 'vmeca_other_product_image', $uploadDir['url'] . '/' . $fileName);
                }
            }
        }
        if ($_POST['product_cat'] != '' && isset($_POST['product_cat'])) { //product category 저장하기
            update_post_meta($postId, 'product_cat', $_POST['product_cat']);
        }
    }

    /**
     * 적용사례 관련 상품 카테고리 메타박스 함수 호출
     */
    public function choiceWooProductCatApplied()
    {
        choiceWooProductCat();
    } //end function choiceWooProductCatApplied

} //end class AdminAppliedInitiator
