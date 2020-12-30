<?php

class create_shortcode
{

	function __construct() {

		add_shortcode( 'book', array( $this, 'bk_shortcode' ) );

	}

	function bk_shortcode( $attr ) {

		ob_start();

		//Adding attributes
		extract( shortcode_atts(
				array(
					'id' => 1,
					'author_name' => "",
					'year' => 2000,
					'category' => "",
					'tag' => "",
				 	'publisher' => ""
				), $attr
		) );

		//Define query parameters
		$options = array(
			'p' => $id,
			'author_name' => $author_name,
			'year' => $year,
			'category_name' => $category,
			'tag' => $tag,
			'publisher' => $publisher
		);

		$query = new WP_Query( $options );

    // run the loop based on the query
    if ( $query->have_posts() ) {
			?>
      <ul>
          <ul>
              <li id="post-<?php the_ID(); ?>">><?php echo the_post(); ?></li>
          </ul>
      </ul>
    	<?php
        $myvariable = ob_get_clean();
        return $myvariable;
    }

	}

}

$createShortCode = new create_shortcode();
