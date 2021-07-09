<?php
/*
 * Template Name: Featured Posts
 *
 * The template for displaying all featured posts.
 *
 * @link https://codex.wordpress.org/Template_Hierarchy
 *
 * @package keyword
 */

get_header(); ?>
			
	<div id="primary" class="content-area">

		<?php
			
			$args = array(
			'post_type'      => 'post',
			//'posts_per_page' => '2',
			'paged' => $paged,
		    'meta_query' => array(
		        array(
		            'key'   => 'is_featured',
		            'value' => 'true'
		        	)
		    	)				
			);

			// The Query

			$temp = $wp_query;
			$wp_query= null;
			$wp_query = new WP_Query( $args );
			//$wp_query->query('paged='.$paged);
		?>

		<div id="main" class="site-main clear">
			
			<div class="breadcrumbs clear">
				<h1>
					<span><?php echo get_theme_mod('featured-content-heading', 'Headlines'); ?></span>
				</h1>	
			</div><!-- .archive-breadcrumbs -->		
				
			<div id="recent-content" class="content-<?php if(get_theme_mod('loop-style','choice-1') == 'choice-1') { echo 'loop'; } elseif(get_theme_mod('loop-style','choice-1') == 'choice-2') { echo 'grid clear'; }  else { echo 'list'; } ?>">

				<?php

				if ( have_posts() ) :	
				
				$i = 1;

				/* Start the Loop */
				while ( have_posts() ) : the_post();

					if (get_theme_mod('loop-style','choice-1') == 'choice-1') {

						get_template_part('template-parts/content', 'loop');

					} elseif (get_theme_mod('loop-style','choice-1') == 'choice-2') {

						get_template_part('template-parts/content', 'grid');

					} else {

						get_template_part('template-parts/content', 'list');

					}

					$i++;

				endwhile;

				else :

					get_template_part( 'template-parts/content', 'none' );

				endif; 

				?>

			</div><!-- #recent-content -->
			
		</div><!-- #main -->
		<?php

			global $wp_version;

			if ( $wp_version >= 4.1 ) :

				the_posts_pagination( array( 'prev_text' => _x( 'Previous', 'previous post', 'keyword' ) ) );
			
			endif;

		?>

		<?php $wp_query = null; $wp_query = $temp;?>

	</div><!-- #primary -->

<?php get_sidebar(); ?>
<?php get_footer(); ?>
