<?php

class create_widget
{

  function __construct() {
    //add_action( 'widgets_init', array( $this, 'bk_register_sidebars' ) );
    add_action( 'widgets_init', array( $this, 'bk_register_sidebar' ) );
  }

  function bk_register_sidebar() {
    $args = array(
      'name'          => 'Sidebar',
      'id'            => 'bk-sidebar',
      'description'   => 'widget to display books of selected category',
      'before_widget' => '<li id="%1$s" class="widget %2$s">',
      'after_widget'  => '</li>',
      'before_title'  => '<h2 class="widgettitle">',
      'after_title'   => '</h2>'
    );

  register_sidebar( $args );

}


}

$createWidget = new create_widget();
