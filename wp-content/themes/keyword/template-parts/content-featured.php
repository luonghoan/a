<?php if (get_theme_mod('featured-content-on', 'true') == true ) { ?>

<?php		

	$args = array(
	'post_type'      => 'post',
	'posts_per_page' => get_theme_mod('featured-num', '4'),
    'meta_query' => array(
        array(
            'key'   => 'is_featured',
            'value' => 'true'
        	)
    	)				
	);

	// The Query
	$the_query = new WP_Query( $args );

	$i = 1;

	if ( $the_query->have_posts() && (!get_query_var('paged')) ) {	

?>


	<div id="featured-content">

		<div class="section-header">
			<h3><span><?php echo get_theme_mod('featured-content-heading', 'Headlines'); ?></span></h3>
		</div>

		<div class="featured-loop clear">

			<?php
				while ( $the_query->have_posts() ) : $the_query->the_post();
			?>	

			<div class="hentry">

				<?php if ( has_post_thumbnail() && ( get_the_post_thumbnail() != null ) ) { ?>
				<a class="thumbnail-link" href="<?php the_permalink(); ?>">
					<div class="thumbnail-wrap">
						<?php 
							the_post_thumbnail('medium_thumb');  
						?>
					</div><!-- .thumbnail-wrap -->
				</a>
				<?php } ?>
				
				<div class="entry-header clear">
					<div class="entry-meta">
						<span class="entry-category"><?php keyword_first_category(); ?></span> 	
						<span class="sep">&bullet;</span>	
						<span class="entry-date"><?php echo get_the_date('M d, Y'); ?></span>
					</div><!-- .entry-meta -->			
					<h2 class="entry-title"><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h2>
				</div><!-- .entry-header -->

			</div><!-- .hentry -->

			<?php

				$i++;
				endwhile;
			?>

		</div><!-- .featured-loop -->

		<div class="read-more">
			<a href="<?php echo get_theme_mod('featured-content-url', home_url().'/featured-news'); ?>"><?php esc_html_e('More', 'keyword'); ?> <?php echo get_theme_mod('featured-content-heading', 'Headlines'); ?></a>
		</div>

	</div><!-- #featured-content -->

	<?php
		} elseif  ( !$the_query->have_posts() && (!get_query_var('paged')) ) { // else has no featured posts
	?>


	<?php
		} //end if has featured posts
		wp_reset_postdata();				
	?>

	<?php } ?>