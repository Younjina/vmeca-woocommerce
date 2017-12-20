<?php

namespace Ivy\Vmeca\Models\Taxonomies;

use Ivy\Mu\Models\Abstracts\AbstractTaxonomyModel;
use Ivy\Vmeca\Models\CustomPosts\AppliedModel;
use function Ivy\Vmeca\Functions\prefixed;

/**
 * 적용사례 태그 생성
 */
class AppliedTagModel extends AbstractTaxonomyModel
{

    public static function getTaxonomy()
    {
        return prefixed('applied-tag', 1);
    }

    public function getTaxonomyArgs()
    {
        return [
            'labels'             => [
                'name'                       => __('Applied Case Tags', 'vmeca'),
                'singular_name'              => __('Applied Case Tag', 'vmeca'),
                'menu_name'                  => __('Tags', 'vmeca'),
                'all_items'                  => __('Tags', 'vmeca'),
                'edit_item'                  => __('Edit Application Tag', 'vmeca'),
                'view_item'                  => __('View Application Tag', 'vmeca'),
                'update_item'                => __('Update Tag', 'vmeca'),
                'add_new_item'               => __('Add New Tag', 'vmeca'),
                'new_item_name'              => __('New Tag', 'vmeca'),
                'parent_item'                => __('Parent Tag', 'vmeca'),
                'parent_item_colon'          => __('Parent Tag:', 'vmeca'),
                'search_items'               => __('Search Tag', 'vmeca'),
                'popular_items'              => __('Popular Tag', 'vmeca'),
                'separate_items_with_commas' => __('Separate tag with commas', 'vmeca'),
                'add_or_remove_items'        => __('Add or remove tag', 'vmeca'),
                'choose_from_most_used'      => __('Choose from the most used tag', 'vmeca'),
                'not_found'                  => __('No tag found', 'vmeca'),
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
            'hierarchical'       => false, //true - category, false - tag
        ];
    } // end function getTaxonomyArgs   

    public function getObjectType()
    {
        return AppliedModel::getPostType();
    }

} //end class AppliedTagModel
