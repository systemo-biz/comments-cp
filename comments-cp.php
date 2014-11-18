<?php
/*
Plugin Name: Comments by CasePress
Plugin URI: http://casepress.org
Description: Добавляем комментарии со своим шаблоном
Version: 0.1
License: GPL
Author: CasePress
Author URI: http://casepress.org

=== RELEASE NOTES ===
2014-08-30 - v1.0 - first version
*/

/* 
This program is free software; you can redistribute it and/or modify
it under the terms of the GNU General Public License as published by
the Free Software Foundation; either version 2 of the License, or
(at your option) any later version.

This program is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
GNU General Public License for more details.

You should have received a copy of the GNU General Public License
along with this program; if not, write to the Free Software
Foundation, Inc., 59 Temple Place, Suite 330, Boston, MA 02111-1307 USA
Online: http://www.gnu.org/licenses/gpl.txt
*/

include_once 'functions.php';
include_once 'comment-template.php';

//Замена wp_editor
function comment_editor() {
  global $post;
 
  ob_start();
 
  wp_editor( '', 'comment', array(
    'textarea_rows' => 5,
    'teeny' => false,
    'media_buttons' => true,
    'quicktags' => true,
  ) );
 
  $editor = ob_get_contents();
 
  ob_end_clean();
 
  //make sure comment media is attached to parent post
  //$editor = str_replace( 'post_id=0', 'post_id='.get_the_ID(), $editor );
 
  return $editor;
}
add_filter( 'comment_form_field_comment', 'comment_editor' );

/*
Добавляем шаблон своего комментария
*/
add_filter( "comments_template", "casepress_comment_template" );

function casepress_comment_template( $comment_template ) {
    return dirname(__FILE__) . '/comments.php';
}

/*
Добавляем стили комментов в шапку
*/
function add_style_for_comments_cp(){
// правильный способ подключить стили и скрипты
	wp_enqueue_style( 'comment-cp', plugins_url('style.css', __FILE__) );
}
add_action( 'wp_enqueue_scripts', 'add_style_for_comments_cp' );




