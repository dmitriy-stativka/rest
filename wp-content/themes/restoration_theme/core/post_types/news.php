<?php

register_post_type('news', array(
    'labels'             => array(
      'name'               => 'News',
      'singular_name'      => 'News',
      'add_new'            => 'Add new',
      'add_new_item'       => 'Add new',
      'edit_item'          => 'Edit new',
      'new_item'           => 'New new',
      'view_item'          => 'View new',
      'search_items'       => 'Search new',
      'not_found'          => 'Not found',
      'not_found_in_trash' => 'No items were found in the basket',
      'parent_item_colon'  => '',
      'menu_name'          => 'News'
      ),
    'public'             => true,
    'publicly_queryable' => true,
    'show_ui'            => true,
    'show_in_menu'       => true,
    'query_var'          => true,
    'rewrite'            => array('slug' => 'community/news'),
    'capability_type'    => 'post',
    'has_archive'        => true,
    'hierarchical'       => false,
    'menu_position'      => null,
    'supports'            => array( 'title', 'editor', 'comments', 'thumbnail', 'excerpt', 'custom-fields', 'category', 'page-attributes')
  ));




  function insights_taxonomy()
{
    register_taxonomy('insights_category', array('news'),
        array(
            'labels' => array(
                'name' => __('Categories', 'textdomain'),
                'singular_name' => __('Category', 'textdomain'),
                'search_items' => __('Search Categories', 'textdomain'),
                'all_items' => __('All Categories', 'textdomain'),
                'edit_item' => __('Edit Category', 'textdomain'),
                'update_item' => __('Update Category', 'textdomain'),
                'add_new_item' => __('Add New Category', 'textdomain'),
                'new_item_name' => __('New Category', 'textdomain'),
                'menu_name' => __('Categories', 'textdomain'),
            ),
            'hierarchical' => true,
            'rewrite' => array('slug' => 'insights_category'),
            'show_admin_column'     => true,
            'show_in_rest' => true
        ));
}
add_action('init', 'insights_taxonomy');