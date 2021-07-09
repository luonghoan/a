<?php $class = ( $wp_query->current_post + 1 === $wp_query->post_count ) ? 'clear last' : 'clear'; ?>

<div id="post-<?php the_ID(); ?>" <?php post_class( $class ); ?>>	

	<?php if ( has_post_thumbnail() && ( get_the_post_thumbnail() != null ) ) { ?>
		<a class="thumbnail-link" href="<?php the_permalink(); ?>">
			<div class="thumbnail-wrap">
				<?php 
					the_post_thumbnail('post_thumb'); 
				?>
			</div><!-- .thumbnail-wrap -->
		</a>
	<?php } ?>	
	
	<header class="entry-header">

		<?php get_template_part( 'template-parts/entry', 'meta' ); ?>
		
		<h2 class="entry-title"><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h2>		

	<div class="entry-category">
		<span>
			<?php esc_html_e('Category:', 'keyword'); ?>
			<?php keyword_first_category(); ?>
		</span> 
	</div> 	

	</header>

	<div class="entry-summary">
		<?php the_excerpt(); ?>	
	</div><!-- .entry-summary -->

	<footer class="entry-footer">
		<div class="read-more">
			<a href="<?php the_permalink(); ?>"><?php esc_html_e('Continue reading', 'keyword'); ?></a>
		</div>

		<div class='entry-comment'>

			<?php comments_popup_link( __('0 Comment', 'keyword'), __('1 Comment', 'keyword'), __('% Comments', 'keyword'), 'comments-link', __('Comments off', 'keyword') ); ?>
		</div>

	</footer>

</div><!-- #post-<?php the_ID(); ?> -->