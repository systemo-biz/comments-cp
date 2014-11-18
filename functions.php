<?php

//Свой волкер для списка комментов
// В будущем нужно будет его дописать так, чтобы комменты были двухуровневые как на Тостере и Енвато
class Walker_Comment_CP extends Walker_Comment {

	/**
	 * Start the list before the elements are added.
	 *
	 * @see Walker::start_lvl()
	 *
	 * @since 2.7.0
	 *
	 * @param string $output Passed by reference. Used to append additional content.
	 * @param int $depth Depth of comment.
	 * @param array $args Uses 'style' argument for type of HTML list.
	 */
	function start_lvl( &$output, $depth = 0, $args = array() ) {
		$GLOBALS['comment_depth'] = $depth + 1;

		switch ( $args['style'] ) {
			case 'div':
				break;
			case 'ol':
				$output .= '<ol class="children">' . "\n";
				break;
			default:
			case 'ul':
				$output .= '<ul class="children">' . "\n";
				break;
		}
	}

	/**
	 * End the list of items after the elements are added.
	 *
	 * @see Walker::end_lvl()
	 *
	 * @since 2.7.0
	 *
	 * @param string $output Passed by reference. Used to append additional content.
	 * @param int    $depth  Depth of comment.
	 * @param array  $args   Will only append content if style argument value is 'ol' or 'ul'.
	 */
	function end_lvl( &$output, $depth = 0, $args = array() ) {
		$GLOBALS['comment_depth'] = $depth + 1;

        
        
        
		switch ( $args['style'] ) {
			case 'div':
				break;
			case 'ol':
				$output .= "</ol><!-- .children -->\n";
				break;
			default:
			case 'ul':
				$output .= "</ul><!-- .children -->\n";
				break;
		}
	}

	/**
	 * Start the element output.
	 *
	 * @since 2.7.0
	 *
	 * @see Walker::start_el()
	 * @see wp_list_comments()
	 *
	 * @param string $output  Passed by reference. Used to append additional content.
	 * @param object $comment Comment data object.
	 * @param int    $depth   Depth of comment in reference to parents.
	 * @param array  $args    An array of arguments.
	 */
	function start_el( &$output, $comment, $depth = 0, $args = array(), $id = 0 ) {
		$depth++;
		$GLOBALS['comment_depth'] = $depth;
		$GLOBALS['comment'] = $comment;

		if ( !empty( $args['callback'] ) ) {
			ob_start();
			call_user_func( $args['callback'], $comment, $args, $depth );
			$output .= ob_get_clean();
			return;
		}

		if ( ( 'pingback' == $comment->comment_type || 'trackback' == $comment->comment_type ) && $args['short_ping'] ) {
			ob_start();
			$this->ping( $comment, $depth, $args );
			$output .= ob_get_clean();
		} elseif ( 'html5' === $args['format'] ) {
			ob_start();
			$this->html5_comment( $comment, $depth, $args );
			$output .= ob_get_clean();
		} else {
			ob_start();
			$this->comment( $comment, $depth, $args );
			$output .= ob_get_clean();
		}
	}

	/**
	 * Ends the element output, if needed.
	 *
	 * @since 2.7.0
	 *
	 * @see Walker::end_el()
	 * @see wp_list_comments()
	 *
	 * @param string $output  Passed by reference. Used to append additional content.
	 * @param object $comment The comment object. Default current comment.
	 * @param int    $depth   Depth of comment.
	 * @param array  $args    An array of arguments.
	 */
	function end_el( &$output, $comment, $depth = 0, $args = array() ) {
		if ( !empty( $args['end-callback'] ) ) {
			ob_start();
			call_user_func( $args['end-callback'], $comment, $args, $depth );
			$output .= ob_get_clean();
			return;
		}
		if ( 'div' == $args['style'] )
			$output .= "</div><!-- #comment-## -->\n";
		else
			$output .= "</li><!-- #comment-## -->\n";
	}

}






//Добавляем секцию с действиями над комментарием
function add_section_actions_to_comment_text($comment_text) {
    ob_start();
?>
<div class="comment_text_wrapper_before_action">
    <?php echo $comment_text ?>
</div>
<div class="comment_actions_cp">
    <ul class="nav nav-pills">
        <?php do_action('comment_actions_cp'); ?>
    </ul>
</div><!-- .comment_actions_cp --><?php
    $html = ob_get_contents();
    ob_get_clean();
    return $html;
}
add_filter('comment_text', 'add_section_actions_to_comment_text');

//Добавляем действия в секцию
function add_actions_to_comment_cp() {
?>

    <li><?php edit_comment_link( '<span class="edit-link glyphicon glyphicon-pencil"></span>' ); ?></li>
    <li><a href="<?php echo get_admin_url( null, 'post-new.php?post_type=cases&content=' . str_replace('#', '%23', get_comment_link( $comment->comment_ID ) )); ?>">Отправить в деле</a></li>

<?php
} add_action('comment_actions_cp', 'add_actions_to_comment_cp');

function add_class_to_comment_cp($classes){
    $classes[] = 'media';
    
    return $classes;
}
add_filter( 'comment_class', 'add_class_to_comment_cp');
