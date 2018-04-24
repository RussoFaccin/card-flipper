<?php
/*
Plugin Name: Card Flipper
Plugin URI: http://www.rodrigorusso.com.br
description: Card Flipper
Version: 1.0
Author: RussoFaccin
Author URI: http://www.rodrigorusso.com.br
License: GPL2
*/

/* ##################################################
  POST TYPE
################################################## */

add_action( 'init', 'create_cardflipper_post_type' );

function create_cardflipper_post_type() {
  $labels = [
    'name' => 'Card Flipper',
    'singular_name' => 'Card',
    'add_new_item' => 'Add New Card',
    'edit_item' => 'Edit Card'
  ];
  $args = [
    'labels' => $labels,
    'description' => 'Awesome Card Flipper',
    'public' => true,
    'publicly_queryable' => true,
    'menu_position' => 5,
    'menu_icon' => 'dashicons-tickets-alt',
    'supports' => 'title',
    'show_in_rest' => true
  ];
  register_post_type('card_flipper', $args);
}

/* ##################################################
  SETTINGS
################################################## */

add_action( 'add_meta_boxes', 'add_meta_boxes');

function add_meta_boxes() {
  // Card Frente
  add_meta_box(
    'cardflipper_frente',
    'Card Frente',
    'cardflipper_frente_cb',
    'card_flipper'
  );

  // Card Verso
  add_meta_box(
    'cardflipper_verso',
    'Card Verso',
    'cardflipper_verso_cb',
    'card_flipper'
  );
}

function cardflipper_frente_cb($post) {
  $value = get_post_custom_values('cardflipper_frente', $post->ID);
  $thumb = '<img class="card-thumb" src="'.$value[0].'"/>';
  $field = '<input type="hidden" name="cardflipper_frente" value="'.$value[0].'">';
  $button = '<button class="cardflipper-upload">UPLOAD</button>';
  echo '<div class="option-item">'.$thumb.$field.$button.'</div>';
}
function cardflipper_verso_cb($post) {
  $value = get_post_custom_values('cardflipper_verso', $post->ID);
  $thumb = '<img class="card-thumb" src="'.$value[0].'"/>';
  $field = '<input type="hidden" name="cardflipper_verso" value="'.$value[0].'">';
  $button = '<button class="cardflipper-upload">UPLOAD</button>';
  echo '<div class="option-item">'.$thumb.$field.$button.'</div>';
}

/* ##################################################
  SAVE POST
################################################## */

add_action( 'save_post', 'on_save_post');

function on_save_post($postid) {
  $fld_cardflipper_frente = $_POST['cardflipper_frente'];
  $fld_cardflipper_verso = $_POST['cardflipper_verso'];
  update_post_meta($postid, 'cardflipper_frente', $fld_cardflipper_frente);
  update_post_meta($postid, 'cardflipper_verso', $fld_cardflipper_verso);
}

/* ##################################################
  ADMIN SCRIPTS && STYLES
################################################## */

add_action('admin_enqueue_scripts', 'enqueue_media', 0 );

function enqueue_media() {
  wp_enqueue_media();
  // CSS
  wp_enqueue_style( 'cardflipper_admin_css', plugins_url('css/cardflipper.css', __FILE__) );
  // JS
  wp_enqueue_script( 'cardflipper_admin_js', plugins_url('js/card-flipper.js', __FILE__) );
}

/* ##################################################
  SHRTCODE
################################################## */

add_shortcode( 'CardFlipper', 'cardflipper_code' );

function cardflipper_code( $atts ){
  $args = array('post_type' => 'card_flipper', 'posts_per_page' => -1);
	$loop = new WP_Query( $args );
	$html = null;
	while ( $loop->have_posts() ) : $loop->the_post();
		$post_id = get_the_ID();
    $imgfrente = get_post_meta($post_id, 'cardflipper_frente', true);
    $imgverso = get_post_meta($post_id, 'cardflipper_verso', true);
    $html .= '<div class="card-item"><div class="card-wraper"><div class="card-face"><img class="verso" src="'.$imgverso.'"></div><div class="card-face"><img class="frente" src="'.$imgfrente.'"></div></div></div>';
	endwhile;
	return '<div id="cardflipper-container">'.$html.'</div>';
}
?>