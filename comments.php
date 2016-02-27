<?php
/**
 * The template for displaying Comments.
 *
 * The area of the page that contains both current comments
 * and the comment form. The actual display of comments is
 * handled by a callback to _sx_comment() which is
 * located in the includes/template-tags.php file.
 *
 * @package _sx
 */

/*
 * If the current post is protected by a password and
 * the visitor has not yet entered the password we will
 * return early without loading the comments.
 */
if ( post_password_required() )
	return;
?>

	<div id="comments" class="comments-area">

	<?php // You can start editing here -- including this comment! ?>

	<?php if ( have_comments() ) : ?>
		<header>
			<h2 class="comments-title">
				<?php
					printf( _nx( 'One thought on &ldquo;%2$s&rdquo;', '%1$s thoughts on &ldquo;%2$s&rdquo;', get_comments_number(), 'comments title', '_sx' ),
						number_format_i18n( get_comments_number() ), '<span>' . get_the_title() . '</span>' );
				?>
			</h2>
		</header>

		<?php if ( get_comment_pages_count() > 1 && get_option( 'page_comments' ) ) : // are there comments to navigate through ?>
		<nav id="comment-nav-above" class="comment-navigation" role="navigation">
			<h5 class="screen-reader-text"><?php _e( 'Comment navigation', '_sx' ); ?></h5>
			<ul class="pager">
				<li class="nav-previous"><?php previous_comments_link( __( '&larr; Older Comments', '_sx' ) ); ?></li>
				<li class="nav-next"><?php next_comments_link( __( 'Newer Comments &rarr;', '_sx' ) ); ?></li>
			</ul>
		</nav><!-- #comment-nav-above -->
		<?php endif; // check for comment navigation ?>

		<ol class="comment-list media-list">
			<?php
				/* Loop through and list the comments. Tell wp_list_comments()
				 * to use _sx_comment() to format the comments.
				 * If you want to overload this in a child theme then you can
				 * define _sx_comment() and that will be used instead.
				 * See _sx_comment() in includes/template-tags.php for more.
				 */
				wp_list_comments( array( 'callback' => '_sx_comment', 'avatar_size' => 50 ) );
			?>
		</ol><!-- .comment-list -->

		<?php if ( get_comment_pages_count() > 1 && get_option( 'page_comments' ) ) : // are there comments to navigate through ?>
		<nav id="comment-nav-below" class="comment-navigation" role="navigation">
			<h1 class="screen-reader-text"><?php _e( 'Comment navigation', '_sx' ); ?></h1>
			<div class="nav-previous"><?php previous_comments_link( __( '&larr; Older Comments', '_sx' ) ); ?></div>
			<div class="nav-next"><?php next_comments_link( __( 'Newer Comments &rarr;', '_sx' ) ); ?></div>
		</nav><!-- #comment-nav-below -->
		<?php endif; // check for comment navigation ?>

	<?php endif; // have_comments() ?>

	<?php
		// If comments are closed and there are comments, let's leave a little note, shall we?
		if ( ! comments_open() && '0' != get_comments_number() && post_type_supports( get_post_type(), 'comments' ) ) :
	?>
		<p class="no-comments"><?php _e( 'Comments are closed.', '_sx' ); ?></p>
	<?php endif; ?>

	<?php comment_form( $args = array(
			  'id_form'           => 'commentform',  // that's the wordpress default value! delete it or edit it ;)
			  'id_submit'         => 'commentsubmit',
			  'title_reply'       => __( 'Leave a Reply', '_sx' ),  // that's the wordpress default value! delete it or edit it ;)
			  'title_reply_to'    => __( 'Leave a Reply to %s', '_sx' ),  // that's the wordpress default value! delete it or edit it ;)
			  'cancel_reply_link' => __( 'Cancel Reply', '_sx' ),  // that's the wordpress default value! delete it or edit it ;)
			  'label_submit'      => __( 'Post Comment', '_sx' ),  // that's the wordpress default value! delete it or edit it ;)

			  'comment_field' =>  '<p><textarea placeholder="Start typing..." id="comment" class="form-control" name="comment" cols="45" rows="8" aria-required="true"></textarea></p>',

			  'comment_notes_after' => '<p class="form-allowed-tags">' .
				__( 'You may use these <abbr title="HyperText Markup Language">HTML</abbr> tags and attributes:', '_sx' ) .
				'</p><div class="alert alert-info">' . allowed_tags() . '</div>'

			  // So, that was the needed stuff to have bootstrap basic styles for the form elements and buttons

			  // Basically you can edit everything here!
			  // Checkout the docs for more: http://codex.wordpress.org/Function_Reference/comment_form
			  // Another note: some classes are added in the bootstrap-wp.js - ckeck from line 1

	));

	?>

</div><!-- #comments -->
