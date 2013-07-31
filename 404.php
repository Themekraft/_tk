<?php
/**
 * The template for displaying 404 pages (Not Found).
 *
 * @package _tk
 */

get_header(); ?>
	
		<section class="error-404 not-found">

			<header class="page-header">
				<h2 class="page-title"><?php _e( 'Oops! That page can&rsquo;t be found.', '_tk' ); ?></h2>
			</header><!-- .page-header -->
	
			<div class="page-content">

				<p><?php _e( 'It looks like nothing was found at this location. Maybe try one of the links below or a search?', '_tk' ); ?></p>

				<?php get_search_form(); ?>

			</div><!-- .page-content -->

		</section><!-- .error-404 -->
		
	</div><!-- close .main-content-inner -->

<?php get_sidebar(); ?>
<?php get_footer(); ?>