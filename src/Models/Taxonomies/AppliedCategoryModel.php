<?php

namespace Ivy\Vmeca\Models\Taxonomies;

use Ivy\Mu\Models\Abstracts\AbstractTaxonomyModel;
use Ivy\Vmeca\Models\CustomPosts\AppliedModel;
use function Ivy\Vmeca\Functions\prefixed;

/**
 * 적용사례 카테고리 생성
 */
class AppliedCategoryModel extends AbstractTaxonomyModel
{

    public static function getTaxonomy()
    {
        return prefixed('applied-category', 1);
    }

    public function getTaxonomyArgs()
    {
        return [
            'labels'             => [
                'name'                       => __('Applied Case Categories', 'vmeca'),
                'singular_name'              => __('Applied Case Category', 'vmeca'),
                'menu_name'                  => __('Categories', 'vmeca'),
                'all_items'                  => __('Categories', 'vmeca'),
                'edit_item'                  => __('Edit Application Category', 'vmeca'),
                'view_item'                  => __('View Application Category', 'vmeca'),
                'update_item'                => __('Update Category', 'vmeca'),
                'add_new_item'               => __('Add New Category', 'vmeca'),
                'new_item_name'              => __('New Category', 'vmeca'),
                'parent_item'                => __('Parent Category', 'vmeca'),
                'parent_item_colon'          => __('Parent Category:', 'vmeca'),
                'search_items'               => __('Search Category', 'vmeca'),
                'popular_items'              => __('Popular Category', 'vmeca'),
                'separate_items_with_commas' => __('Separate category with commas', 'vmeca'),
                'add_or_remove_items'        => __('Add or remove category', 'vmeca'),
                'choose_from_most_used'      => __('Choose from the most used category', 'vmeca'),
                'not_found'                  => __('No category found', 'vmeca'),
            ],
            'public'             => true,
            'publicly_queryable' => true,
            'show_ui'            => true,
            'show_in_menu'       => true,
            'show_in_nav_menus'  => true,
            'show_in_rest'       => false,
            'show_tagcloud'      => false,
            'show_in_quick_edit' => true,
            'show_admin_column'  => true,
            'description'        => '',
            'hierarchical'       => true, //true - category, false - tag
        ];
    } // end function getTaxonomyArgs   

    public function getObjectType()
    {
        return AppliedModel::getPostType();
    }

} //end class ApplyCategoryModel
