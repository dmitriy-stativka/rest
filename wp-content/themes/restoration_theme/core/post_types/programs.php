<?php

register_post_type('programs', array(
    'labels'             => array(
      'name'               => 'Programs',
      'singular_name'      => 'Programs',
      'add_new'            => 'Add program',
      'add_new_item'       => 'Add new program',
      'edit_item'          => 'Edit program',
      'new_item'           => 'New program',
      'view_item'          => 'View program',
      'search_items'       => 'Search program',
      'not_found'          => 'Not found',
      'not_found_in_trash' => 'No items were found in the basket',
      'parent_item_colon'  => '',
      'menu_name'          => 'Programs'
      ),
    'public'             => true,
    'publicly_queryable' => true,
    'show_ui'            => true,
    'show_in_menu'       => true,
    'query_var'          => true,
    'rewrite'            => true,
    'capability_type'    => 'post',
    'has_archive'        => false,
    'hierarchical'       => false,
    'menu_position'      => null,
    'supports'            => array( 'title', 'comments'  )
  ));