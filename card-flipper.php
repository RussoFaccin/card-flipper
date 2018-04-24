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
  $thumb = '<img src="'.$value[0].'"/>';
  $field = '<input type="text" name="cardflipper_frente" value="'.$value[0].'">';
  $button = '<button class="cardflipper-upload">UPLOAD</button>';
  echo '<div class="option-item">'.$thumb.$field.$button.'</div>';
}
function cardflipper_verso_cb($post) {
  $value = get_post_custom_values('cardflipper_verso', $post->ID);
  $thumb = '<img src="'.$value[0].'"/>';
  $field = '<input type="text" name="cardflipper_verso" value="'.$value[0].'">';
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
  ADMIN SCRIPTS
################################################## */

// Enqueue Media
add_action('admin_enqueue_scripts', 'enqueue_media', 0 );
function enqueue_media() {
	wp_enqueue_media();
}

// Admin Footer
add_action( 'admin_footer', 'admin_footer');
function admin_footer() {
  ?><script>
    jQuery(document).ready(function($){
      var frame = wp.media({
        title: 'Select or Upload Media Of Your Chosen Persuasion',
        button: {
          text: 'Use this media'
        },
        multiple: false  // Set to true to allow multiple files to be selected
      });

      var fldUrl = null;

      frame.on('select', function(evt) {
        var attachment = frame.state().get('selection').first().toJSON().url;
        fldUrl.value = attachment;
      })

      var btnsUpload = document.querySelectorAll('.cardflipper-upload');
      btnsUpload.forEach(function(item, index) {
        item.addEventListener('click', function(evt) {
          evt.preventDefault();
          fldUrl = evt.target.previousElementSibling;
          frame.open();
        });
      });
    });
  </script><?php
}
?>