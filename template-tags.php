<?php
/**
 * Custom template tags for this theme.
 *
 * Eventually, some of the functionality here could be replaced by core features
 *
 * @package _sf
 */


/**
 * Template for comments and pingbacks.
 *
 * Used as a callback by wp_list_comments() for displaying the comments.
 */
if ( ! function_exists( '_sf_comment' ) ) :
function _sf_comment( $comment, $args, $depth ) {
	$GLOBALS['comment'] = $comment;
	switch ( $comment->comment_type ) :
		case 'pingback' :
		case 'trackback' :
	?>
	<li class="post pingback">
		<p><?php _e( 'Pingback:', '_sf' ); ?> <?php comment_author_link(); ?><?php edit_comment_link( __( 'Edit', '_sf' ), '<span class="edit-link">', '<span>' ); ?></p>
	<?php
			break;
		default :
	?>
	<li <?php comment_class(); ?> id="li-comment-<?php comment_ID(); ?>">
		<article id="comment-<?php comment_ID(); ?>" class="comment">
			<footer>
				<div class="comment-author vcard">
					<?php echo get_avatar( $comment, 40 ); ?>
					<?php printf( __( '%s <span class="says">says:</span>', '_sf' ), sprintf( '<cite class="fn">%s</cite>', get_comment_author_link() ) ); ?>
				</div><!-- .comment-author .vcard -->
				<?php if ( $comment->comment_approved == '0' ) : ?>
					<em><?php _e( 'Your comment is awaiting moderation.', '_sf' ); ?></em>
					<br />
				<?php endif; ?>

				<div class="comment-meta commentmetadata">
					<a href="<?php echo esc_url( get_comment_link( $comment->comment_ID ) ); ?>"><time datetime="<?php comment_time( 'c' ); ?>">
					<?php printf( _x( '%1$s at %2$s', '1: date, 2: time', '_sf' ), get_comment_date(), get_comment_time() ); ?>
					</time></a>
					<?php edit_comment_link( __( 'Edit', '_sf' ), '<span class="edit-link">', '<span>' ); ?>
				</div><!-- .comment-meta .commentmetadata -->
			</footer>

			<div class="comment-content"><?php comment_text(); ?></div>

			<div class="reply">
				<?php comment_reply_link( array_merge( $args, array( 'depth' => $depth, 'max_depth' => $args['max_depth'] ) ) ); ?>
			</div><!-- .reply -->
		</article><!-- #comment-## -->

	<?php
			break;
	endswitch;
}
endif; // ends check for _sf_comment()

if ( ! function_exists( '_sf_posted_on' ) ) :
/**
 * Prints HTML with meta information for the current post-date/time and author.
 */
function _sf_posted_on() {
	printf( __( 'Posted on <a href="%1$s" title="%2$s" rel="bookmark"><time class="entry-date" datetime="%3$s">%4$s</time></a><span class="byline"> by <a class="url fn n" href="%5$s" title="%6$s" rel="author">%7$s</a></span>', '_sf' ),
		esc_url( get_permalink() ),
		esc_attr( get_the_time() ),
		esc_attr( get_the_date( 'c' ) ),
		esc_html( get_the_date() ),
		esc_url( get_author_posts_url( get_the_author_meta( 'ID' ) ) ),
		esc_attr( sprintf( __( 'View all posts by %s', '_sf' ), get_the_author() ) ),
		get_the_author()
	);
}
endif; //! _sf_comment exists

if (! function_exists( '_sf_categorized_blog') ) :
/**
 * Returns true if a blog has more than 1 category
 */
function _sf_categorized_blog() {
	if ( false === ( $all_the_cool_cats = get_transient( 'all_the_cool_cats' ) ) ) {
		// Create an array of all the categories that are attached to posts
		$all_the_cool_cats = get_categories( array(
			'hide_empty' => 1,
		) );

		// Count the number of categories that are attached to the posts
		$all_the_cool_cats = count( $all_the_cool_cats );

		set_transient( 'all_the_cool_cats', $all_the_cool_cats );
	}

	if ( '1' != $all_the_cool_cats ) {
		// This blog has more than 1 category so _s_categorized_blog should return true
		return true;
	} else {
		// This blog has only 1 category so _s_categorized_blog should return false
		return false;
	}
}
endif; // !if _sf_categorized_blog exists

if (! function_exists ('_sf_category_transient_flusher') ) :
/**
 * Flush out the transients used in _s_categorized_blog
 */
function _sf_category_transient_flusher() {
	// Like, beat it. Dig?
	delete_transient( 'all_the_cool_cats' );
}
add_action( 'edit_category', '_sf_category_transient_flusher' );
add_action( 'save_post', '_sf_category_transient_flusher' );
endif; //! if _sf_category_transient_flusher exists

/**
 * Displays navigation to next/previous pages when applicable.
 *
 * From Twenty Twelve 1.0
 * @ package _sf
 * @ since 0.1
 */
 if ( ! function_exists( '_sf_content_nav' ) ) :
function _sf_content_nav( $html_id ) {
	global $wp_query;

	$html_id = esc_attr( $html_id );
	if ( $wp_query->max_num_pages > 1 ) : ?>
		<nav id="<?php echo $html_id; ?>" class="navigation" role="navigation">
			<h3 class="assistive-text"><?php _e( 'Post navigation', '_sf' ); ?></h3>
			<div class="nav-previous alignleft"><?php next_posts_link( __( '<span class="meta-nav">&larr;</span> Older posts', '_sf' ) ); ?></div>
			<div class="nav-next alignright"><?php previous_posts_link( __( 'Newer posts <span class="meta-nav">&rarr;</span>', '_sf' ) ); ?></div>
		</nav><!-- #<?php echo $html_id; ?> .navigation -->
	<?php endif;
}
endif; //! _sf_content_nav exists


/**
 * Displays navigation In masonry loop
 *
 * From Twenty Twelve 1.0
 * @ package _sf
 * @ since 1.1.2
 */
if ( ! function_exists( '_sf_masonry_nav' ) ) :
function _sf_masonry_nav( $html_id ) {
	global $wp_query;

	$html_id = esc_attr( $html_id );
	if ( $wp_query->max_num_pages > 1 ) : ?>
	<div class="row">
		<nav id="<?php echo $html_id; ?>" class="clearfix large-6 columns large-offset-3 small-offset-1 navigation masonry-nav panel radius" role="navigation">
			<h3 class="assistive-text" id="masonry-nav-title"><?php _e( 'More Posts', '_sf' ); ?></h3>
			<div id="masonry-navs">
				<div class="nav-previous alignleft"><?php next_posts_link( __( '<span class="meta-nav">&larr;</span> Older', '_sf' ) ); ?></div>
				<div class="nav-next alignright"><?php previous_posts_link( __( 'Newer <span class="meta-nav">&rarr;</span>', '_sf' ) ); ?></div>
			</div>
		</nav><!-- #<?php echo $html_id; ?> .navigation -->
	</div>
	<?php endif;
}
endif; //! _sf_masonry_nav exists