<?php

require_once('book-class.php');

add_action('init', array('create_book', 'bk_create_cpt'), 0 );
