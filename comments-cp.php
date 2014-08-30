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


add_filter( 'comment_form_field_comment', 'comment_editor' );
 
function comment_editor() {
  global $post;
 
  ob_start();
 
  wp_editor( '', 'comment', array(
    'textarea_rows' => 5,
    'teeny' => false,
    'quicktags' => false,
    'media_buttons' => true,
    'quicktags' => true,
  ) );
 
  $editor = ob_get_contents();
 
  ob_end_clean();
 
  //make sure comment media is attached to parent post
  //$editor = str_replace( 'post_id=0', 'post_id='.get_the_ID(), $editor );
 
  return $editor;
}


/*
Добавляем стили комментов в шапку
*/
add_action('wp_head', 'add_style_for_comments_cp');
function add_style_for_comments_cp(){
?>
<style type="text/css" id="comments_cp">

#wp-comment-wrap {
	border-color: rgba(0, 0, 0, 0.2);
border-width: 1px;
border-style: dashed;
padding: 5px;
margin: 5px 0px;
}

.comment {
	padding: 10px;
}

.comment.even {
	background-color: ghostwhite;
}

.comment_actions_cp .nav>li>a {
padding: 1px 15px;
}

.comment_actions_cp {
	visibility: hidden;
}

.comment:hover .comment_actions_cp {
	visibility: visible;
}

.comment p {
margin: 0 0 10px;
}

#respond .wp-switch-editor {
	height: 26px;
}

</style>
<?php
}

/*
Добавляем шаблон своего комментария
*/
add_filter( "comments_template", "casepress_comment_template" );

function casepress_comment_template( $comment_template ) {
    return dirname(__FILE__) . '/comments.php';
}

function comment_cp( $comment, $args, $depth ) {
	$GLOBALS['comment'] = $comment;

	if ( 'pingback' == $comment->comment_type || 'trackback' == $comment->comment_type ) : ?>

	<li id="comment-<?php comment_ID(); ?>" <?php comment_class(); ?>>
		<div class="comment-body">
			<?php _e( 'Pingback:', 'alienship' ); ?> <?php comment_author_link(); ?> <?php edit_comment_link( __( 'Edit', 'alienship' ), '<span class="edit-link">', '</span>' ); ?>
		</div>

	<?php else : ?>

	<li id="comment-<?php comment_ID(); ?>" <?php comment_class( empty( $args['has_children'] ) ? '' : 'parent' ); ?>>
		<div id="div-comment-<?php comment_ID(); ?>" class="comment-body-cp">
			<div class="comment_top_meta_cp">
				<span class="author_avatar_cp">
					<?php if ( 0 != $args['avatar_size'] ) echo get_avatar( $comment, $args['avatar_size'] ); ?>
				</span>
				<span class="comments_links_cp">
					<span class="author_link_cp">
						<?php echo get_comment_author_link() ?>
					</span>
					<span class="comment-link-cp">
						(<a href="<?php echo esc_url( get_comment_link( $comment->comment_ID ) ); ?>">
							<time datetime="<?php comment_time( 'c' ); ?>">
								<?php echo get_comment_date() . ' | ' . get_comment_time() ?>
							</time>
						</a>)
					</span>
				</span>
				<span class="actions_comment_cp">
					
				</span><!-- .comment-metadata -->
			</div><!-- .comment-author -->
			<div class="comment_text_cp">
				<?php comment_text(); ?>

				<?php if ( '0' == $comment->comment_approved ) : ?>
				<p class="comment-awaiting-moderation"><?php _e( 'Your comment is awaiting moderation.', 'alienship' ); ?></p>
				<?php endif; ?>
			</div><!-- .comment-content -->			
			<div class="comment_actions_cp">
				<ul class="nav nav-pills">
				  	<li><?php edit_comment_link( '<span class="edit-link glyphicon glyphicon-pencil"></span>' ); ?></li>
				  	<li><a href="<?php echo get_admin_url( null, 'post-new.php?post_type=cases&content=' . str_replace('#', '%23', get_comment_link( $comment->comment_ID ) )); ?>">Отправить</a></li>
					<?php do_action('comment_actions_cp'); ?>
				</ul>				
			</div><!-- .comment-meta -->


		</div><!-- .comment-body -->

	<?php
	endif;
}