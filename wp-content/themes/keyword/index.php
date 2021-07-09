<?php get_header(); ?>

	<div id="primary" class="content-area clear">	
			
		<main id="main" class="site-main clear">

			<?php
				get_template_part('template-parts/content', 'recent');
				get_template_part('template-parts/content', 'featured');
			?>
			
		</main><!-- .site-main -->

	</div><!-- #primary -->

<?php get_sidebar(); ?>
<?php get_footer(); ?>
