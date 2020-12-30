<?php

class create_meta_box
{

	function __construct() {
	
		add_action( 'add_meta_boxes', array( $this, 'bk_create_meta_box' ) );
		add_action( 'save_post', array( $this, 'bk_create_meta_box_save' ) );

	}

	/***************************************************************
        4. Creating a custom meta box to save book meta information
	****************************************************************/

	function bk_create_meta_box() {

  	//adding a meta box
  	add_meta_box( 'bk-cpt-mtbox',
                	'Details Metabox',
                	array( $this, 'bk_mtbox_function' ),
                	'books',
                	'side',
                	'high'
              	);

	}



	function bk_mtbox_function( $post ) {
  	wp_nonce_field( 'bk_nonce_check', 'bk_nonce_check_value' );
  	echo 'Enter Name Of Author:<br>';
  	echo '<label for="book_author"></label>';
  	echo '<input type="text" id="book_author" name="book_author" placeholder="Author" /><br>';
  	echo "Enter The Price:<br>";
  	echo '<label for="book_price"></label>';
  	echo '<input type="text" id="book_price" name="book_price" placeholder="Enter the Price" /><br>';
  	echo "Enter The Publisher Name:<br>";
  	echo '<label for="book_publisher"></label>';
  	echo '<input type="text" id="book_publisher" name="book_publisher" placeholder="Publisher" /><br>';
	}



	function bk_create_meta_box_save( $post_id ) {


		// Check if our nonce is set.
		if (!isset($_POST['bk_nonce_check_value']))
			return $post_id;

		$nonce = $_POST['bk_nonce_check_value'];

		// Verify that the nonce is valid.
		if (!wp_verify_nonce($nonce, 'bk_nonce_check'))
			return $post_id;

		if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE)
			return $post_id;

		// Check the user's permissions.
		if ('page' == $_POST['post_type']) {

			if (!current_user_can('edit_page', $post_id))
				return $post_id;

		} else {

				if (!current_user_can('edit_post', $post_id))
					return $post_id;
		}


  	$book_price = sanitize_text_field( $_POST['book_price'] );
    	update_post_meta( $post_id, 'book_price', $book_price );

  	$book_author = sanitize_text_field( $_POST['book_author'] );
  	update_post_meta( $post_id, 'book_author', $book_author );

  	$book_publisher = sanitize_text_field( $_POST['book_publisher'] );
  	update_post_meta( $post_id, 'book_publisher', $book_publisher );

	}

}

$createMetaBox = new create_meta_box();

/***********************************************************************************
*   5. Create custom meta table and save all book meta information in that table   *
************************************************************************************/

class create_meta_table
{

	function __construct() {

		register_activation_hook( __FILE__, array( $this, 'bk_create_tb' ) );
		add_action( 'plugins_loaded', array( $this, 'bk_create_tb' ) );
		add_action('init', array( $this, 'bkmeta_integrate_wpdb' ) );
		add_action( 'switch_blog', array( $this, 'bkmeta_integrate_wpdb' ) );

	}

	function bk_create_tb() {

		global $wpdb;

		$table_name = $wpdb->prefix . 'bkmeta';

		$charset_collate = $wpdb->get_charset_collate();

		$max_index_length = 191;

		$sql = "CREATE TABLE $table_name (
			meta_id bigint(20) unsigned NOT NULL auto_increment,
			book_id bigint(20) unsigned NOT NULL default '0',
			meta_key varchar(255) default NULL,
			meta_value longtext,
			PRIMARY KEY  (meta_id),
			KEY book (book_id),
			KEY meta_key (meta_key($max_index_length))
		) $charset_collate;";

		require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
		dbDelta( $sql );

	}

	function bkmeta_integrate_wpdb() {
		global $wpdb;

		$wpdb->bkmeta = $wpdb->prefix . 'bkemeta';
		$wpdb->tables[] = 'bkmeta';

		return;
	}

	function add_book_meta($book_id, $meta_key, $meta_value, $unique = false) {

		return add_metadata( 'book', $book_id, $meta_key, $meta_value, $unique );

	}

	function delete_book_meta($book_id, $meta_key, $meta_value = '') {

		return delete_metadata( 'book', $book_id, $meta_key, $meta_value );

	}

	function get_book_meta($book_id, $key = '', $single = false) {

		return get_metadata( 'book', $book_id, $key, $single );

	}

	function update_book_meta($book_id, $meta_key, $meta_value, $prev_value = '') {

		return update_metadata( 'book', $book_id, $meta_key, $meta_value, $prev_value );

	}

}

$createtable = new create_meta_table();
