<?php

namespace Ivy\Vmeca\Models\CustomPosts;

use Ivy\Mu\Models\Abstracts\AbstractCustomPostModel;
use Ivy\Mu\Models\MetaFieldModel;
use Ivy\Mu\Models\ValueTypes\PathType;
use Ivy\Mu\Models\ValueTypes\TextType;
use Ivy\Vmeca\Models\Settings\SettingModel;
use Ivy\Vmeca\Models\ValueObjects\WpItemInfo;
use Ivy\Vmeca\Models\ValueObjects\WpNetworkSiteInfo;
use Ivy\Vmeca\Models\ValueTypes\WpItemInfoType;
use Ivy\Vmeca\Models\ValueTypes\WpNetworkSiteInfoType;
use Ivy\Vmeca\Services\LocalShellExecutorService;
use Ivy\Vmeca\Services\Ssh2HelperService;
use Ivy\Vmeca\Services\WpCliHelperService;
use function Ivy\Vmeca\Functions\prefixed;


/**
 * 적용사례 커스텀 타입 생성
 */
class AppliedModel extends AbstractCustomPostModel
{

    private $searchNumberField;
    private $otherProductUrlField;
    private $otherProductTitleField;
    private $otherProductImageField;

    public static function getPostType()
    {
        return prefixed('applied');
    }

    /**
     * Applied Case CPT 생성
     */
    public function getPostTypeArgs()
    {
        return [
            'labels'      => [
                'name'               => __('Applications', 'vmeca'),
                'singular_name'      => __('Applied Case', 'vmeca'),
                'add_new'            => __('사례 등록하기', 'vmeca'),
                'add_new_item'       => __('사례 등록', 'vmeca'),
                'edit_item'          => __('사례 편집', 'vmeca'),
                'all_items'          => __('모든 사례', 'vmeca'),
                'new_item'           => __('New Applied Case', 'vmeca'),
                'view_item'          => __('View Application', 'vmeca'),
                'search_items'       => __('Search Applied Case', 'vmeca'),
                'not_found'          => __('No Applied Case found', 'vmeca'),
                'not_found_in_trash' => __('No Applied Case found in Trash', 'vmeca'),
                'menu_name'          => __('Applied Case', 'vmeca'),
            ],
            'description' => __('The Application post type of VMECA', 'vmeca'),
            'public'      => true,
            'supports'    => ['title', 'editor', 'thumbnail', 'excerpt'],
            //지원해주는 것 / title : 제목, editor : content, thumbnail : 특성이미지, excerpt : 요약
            'menu_icon'   => 'dashicons-welcome-write-blog', //change the wordpress menu icon
            'has_archive' => true,
        ];
    } // end function getPostTypeArgs

    /**
     * 관련 상품 아이디 필드 값
     */
    public function getFieldSearchProductNumber()
    {
        if (is_null($this->searchNumberField)) {
            $this->searchNumberField = new MetaFieldModel(
                prefixed('search_product_number'),
                array(
                    'label'       => __('product number', 'vmeca'),
                    'description' => __('product number', 'vmeca'),
                    'valueType'   => new TextType(),
                )
            );
        }

        return $this->searchNumberField;
    } //end function getFieldSearchProductNumber

    /**
     * 관련 상품 url
     */
    public function getFieldOtherProductUrl()
    {
        if (is_null($this->otherProductUrlField)) {
            $this->otherProductUrlField = new MetaFieldModel(
                prefixed('other_url'),
                array(
                    'label'       => __('other product url', 'vmeca'),
                    'description' => __('other product url', 'vmeca'),
                    'valueType'   => new TextType(),
                )
            );
        }

        return $this->otherProductUrlField;
    } //end function getFieldOtherProductUrl

    /**
     * 관련 상품명
     */
    public function getFieldOtherProductTitle()
    {
        if (is_null($this->otherProductTitleField)) {
            $this->otherProductTitleField = new MetaFieldModel(
                prefixed('other_product_title'),
                array(
                    'label'       => __('other product title', 'vmeca'),
                    'description' => __('other product title', 'vmeca'),
                    'valueType'   => new TextType(),
                )
            );
        }

        return $this->otherProductTitleField;
    } //end function getFieldOtherProductTitle

    /**
     * 관련 상품 이미지
     */
    public function getFieldOtherProductImage()
    {
        if (is_null($this->otherProductImageField)) {
            $this->otherProductImageField = new MetaFieldModel(
                prefixed('other_product_image'),
                array(
                    'label'       => __('other product image', 'vmeca'),
                    'description' => __('other product image / now images : ' . get_post_meta(get_the_ID(), 'vmeca_other_product_image', true), 'vmeca'),
                    'valueType'   => new PathType(),
                )
            );
        }

        return $this->otherProductImageField;
    } //end function getFieldOtherProductImage


}
