<?php
/**
* Plugin name: WP Book
* Plugin URI: http://wpbook.com
* Description: Plugin - Assignment
* Version: 1.0.0
* Author: Shibil PM
* Author URI: http://shibil.com
* License: GPLv2 or later
* Text Domain: wp-book
*/

//Security to prevent directl access to the php file

defined( 'ABSPATH' ) or die( 'Error! You can\t access the file' );

/****************************************
   1. Creating a custom post type Book
*****************************************/

function bk_create_cpt() {
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
  'show_ui'             => true,
  'show_in_menu'        => true,
  'show_in_nav_menus'   => true,
  'show_in_admin_bar'   => true,
  'has_archive'         => true,
  'can_export'          => true,
  'exclude_from_search' => false,
 //'taxonomies' => array( 'genres' ),
  'rewrite' => array( 'slug' => 'book' ),
  'publicly_queryable'  => true,
  'capability_type'     => 'page'
  );

//Registering Custom Post Type

register_post_type( 'books', $args );

}

//hook into the init action and call bk_create_cpt when it fires

add_action('init', 'bk_create_cpt', 0 );

/**************************************************************
    2. Creating a custom hierarchical taxonomy Book Category
***************************************************************/

function bk_create_ht() {

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

//Registering The Taxanomy

register_taxonomy('subjects',array('books'), array(
  'hierarchical' => true,
  'labels' => $labels,
  'show_ui' => true,
  'show_in_rest' => true,
  'show_admin_column' => true,
  'query_var' => true,
  'rewrite' => array( 'slug' => 'categories' )
));

}

//hook into the init action and call bk_create_ht when it fires

add_action( 'init', 'bk_create_ht', 0 );

/**************************************************************
    3. Creating a custom non-hierarchical taxonomy Book Tag
***************************************************************/

function bk_create_nht() {

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

//Register The Taxanomy

register_taxonomy('custom',array('books'), array(
  'hierarchical' => false,
  'labels' => $labels,
  'show_ui' => true,
  'show_in_rest' => true,
  'show_admin_column' => true,
  'query_var' => true,
  'rewrite' => array( 'slug' => 'tags' )
));

}

//hook into the init action and call bk_create_nht when it fires

add_action( 'init', 'bk_create_nht', 0 );


/***************************************************************
   4. Creating a custom meta box to save book meta information
****************************************************************/

function bk_create_meta_box() {

  add_meta_box( 'bk-cpt-mtbox', 'Details Metabox', 'bk_mtbox_function', 'books', 'side', 'high' );

}

add_action( 'add_meta_boxes', 'bk_create_meta_box' );

function bk_mtbox_function($post) {
  wp_nonce_field( plugin_basename( __FILE__ ), 'bk_mtbox_function_nonce' );
  echo '<label for="book_author"></label><br>';
  echo '<input type="text" id="book_author" name="book_author" placeholder="Author" /><br>';
  echo "<br>";
  echo '<label for="book_price"></label><br>';
  echo '<input type="text" id="book_price" name="book_price" placeholder="Enter the Price" /><br>';
  echo "<br>";
  echo '<label for="book_publisher"></label><br>';
  echo '<input type="text" id="book_publisher" name="book_publisher" placeholder="Publisher" /><br>';
}

add_action( 'save_post', 'bk_create_meta_box_save');

function bk_create_meta_box_save( $post_id ) {

  if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE )
  return;

  if ( !wp_verify_nonce( $_POST['bk_mtbox_function_nonce'], plugin_basename( __FILE__ ) ) )
  return;

  if ( 'page' == $_POST['post_type'] ) {
    if ( !current_user_can( 'edit_page', $post_id ) )
    return;
  } else {
    if ( !current_user_can( 'edit_post', $post_id ) )
    return;
  }
  $book_price = $_POST['book_price'];
  update_post_meta( $post_id, 'product_price', $book_price );
  $book_author = $_POST['book_author'];
  update_post_meta( $post_id, 'book_author', $book_author );
  $book_publisher = $_POST['book_publisher'];
  update_post_meta( $post_id, 'book_publisher', $book_publisher );
}
