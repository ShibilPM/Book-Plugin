<?php

class create_book
{

	function __construct() {
		add_action( 'init', array( $this, 'bk_create_cpt' ) );
		add_action( 'init', array( $this, 'bk_create_ht' ) );
		add_action( 'init', array( $this, 'bk_create_nht' ) );

	}

  public function bk_create_cpt() {
    $labels = array(
      'name' => __( 'Books', 'bk' ),
      'singular_name' => __( 'Book', 'bk' ),
      'add_new' => __( 'New Book', 'bk' ),
      'add_new_item' => __( 'Add New Book', 'bk' ),
      'edit_item' => __( 'Edit Book', 'bk' ),
      'new_item' => __( 'New Book', 'bk' ),
      'view_item' => __( 'View Books', 'bk' ),
      'search_items' => __( 'Search Books', 'bk' ),
      'not_found' =>  __( 'No Books Found', 'bk' ),
      'not_found_in_trash' => __( 'No Books found in Trash', 'bk' ),
    );
    $args = array(
      'labels' => $labels,
      'has_archive' => true,
      'public' => true,
      'hierarchical' => false,
      'supports' => array(
        'title',
        'editor',
        'excerpt',
        'custom-fields',
        'thumbnail',
        'page-attributes'
      ),
      'show_ui' => true,
      'show_in_menu' => true,
      'show_in_nav_menus' => true,
      'show_in_admin_bar' => true,
      'has_archive' => true,
      'can_export' => true,
      'exclude_from_search' => false,
      'rewrite' => array( 'slug' => 'book' ),
      'publicly_queryable' => true,
      'capability_type' => 'post',
      'show_in_rest' => true,
    );

    //Registering Custom Post Type
    register_post_type( 'books', $args );

  }

  /**************************************************************
      2. Creating a custom hierarchical taxonomy Book Category
  ***************************************************************/

  public function bk_create_ht() {

  // Adding new taxonomy, hierarchical like categories
    $labels = array(
      'name' => _x( 'Categories', 'taxonomy general name' ),
      'singular_name' => _x( 'Category', 'taxonomy singular name' ),
      'search_items' =>  __( 'Search Category' ),
      'all_items' => __( 'All Categories' ),
      'parent_item' => __( 'Parent Category' ),
      'parent_item_colon' => __( 'Parent Category:' ),
      'edit_item' => __( 'Edit Category' ),
      'update_item' => __( 'Update Category' ),
      'add_new_item' => __( 'Add New Category' ),
      'new_item_name' => __( 'New Category Name' ),
      'menu_name' => __( 'Book Category' ),
    );

    $args = array(
      'hierarchical' => true,
      'labels' => $labels,
      'show_ui' => true,
      'show_in_rest' => true,
      'show_admin_column' => true,
      'query_var' => true,
      'rewrite' => array( 'slug' => 'categories' )
    );

		//Registering The Taxanomy
    register_taxonomy('subjects',array('books'), $args);

  }

  /**************************************************************
      3. Creating a custom non-hierarchical taxonomy Book Tag
  ***************************************************************/

  public function bk_create_nht() {

    // Adding new taxonomy, Non-hierarchical like Tags
    $labels = array(
      'name' => _x( 'Tags', 'taxonomy general name' ),
      'singular_name' => _x( 'Tag', 'taxonomy singular name' ),
      'search_items' =>  __( 'Search Tags' ),
      'all_items' => __( 'All Tags' ),
      'parent_item' => null,
      'parent_item_colon' => null,
      'edit_item' => __( 'Edit Tags' ),
      'update_item' => __( 'Update Tag' ),
      'add_new_item' => __( 'Add New Tag' ),
      'new_item_name' => __( 'New Tag Name' ),
      'menu_name' => __( 'Book Tag' ),
    );


    $args = array(
      'hierarchical' => false,
      'labels' => $labels,
      'show_ui' => true,
      'show_in_rest' => true,
      'show_admin_column' => true,
      'query_var' => true,
      'rewrite' => array( 'slug' => 'tags' )
    );

		//Register The Taxanomy
    register_taxonomy('custom',array('books'), $args);

  }

}

$createBook = new create_book();
