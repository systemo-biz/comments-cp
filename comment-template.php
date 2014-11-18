<?php


/**
 * Функция генерации комментария при выводе в список
 * @param [[Type]] $comment [Данные комментария]
 * @param [[Type]] $args    [Аргументы]
 * @param [[Type]] $depth   [Глубина]
 */
function comment_cp( $comment, $args, $depth ) {
	$GLOBALS['comment'] = $comment;

	if ( 'pingback' == $comment->comment_type || 'trackback' == $comment->comment_type ) : ?>

	<li id="comment-<?php comment_ID(); ?>" <?php comment_class(); ?>>
		<div class="comment-body">
			<?php _e( 'Pingback:', 'alienship' ); ?> <?php comment_author_link(); ?> <?php edit_comment_link( __( 'Edit', 'alienship' ), '<span class="edit-link">', '</span>' ); ?>
		</div>

	<?php else : ?>

	<li id="comment-<?php comment_ID(); ?>" <?php comment_class( empty( $args['has_children'] ) ? '' : 'parent' ); ?>>
		<div id="div-comment-<?php comment_ID(); ?>" class="comment-body-cp panel panel-default">
			<div class="comment_top_meta_cp panel-heading">
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
			</div><!-- .comment-author -->
			<div class="comment_text_cp panel-body">
				<?php comment_text(); ?>

				<?php if ( '0' == $comment->comment_approved ) : ?>
				<p class="comment-awaiting-moderation"><?php _e( 'Your comment is awaiting moderation.', 'alienship' ); ?></p>
				<?php endif; ?>
			</div><!-- .comment-content -->		
            <div class="panel-footer comment_actions_cp">
                <ul class="nav nav-pills">
                     
                    <?php do_action('comment_actions_cp'); ?>
                </ul>
            </div>
		</div><!-- .comment-body -->
        <?php
            comment_reply_link( array_merge( $args, array(
                'add_below' => 'div-comment',
                'depth'     => $depth,
                'max_depth' => $args['max_depth'],
                'before'    => '<div class="reply">',
                'after'     => '</div>',
            ) ) );
        ?>
    <?php
	endif;
}