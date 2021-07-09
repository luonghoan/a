<div class="entry-meta clear">
	<span class="entry-author"><a href="<?php echo get_author_posts_url( get_the_author_meta( 'ID' ), get_the_author_meta( 'user_nicename' ) ); ?>"><?php echo get_avatar( get_the_author_meta( 'ID' ), 80 ); ?></a>  <span><?php esc_html_e('Written by:', 'keyword'); ?></span> <?php the_author_posts_link(); ?></span>
	<span class="entry-date"><span><?php esc_html_e('Posted on:', 'keyword'); ?></span> <?php echo get_the_date(); ?></span>
</div><!-- .entry-meta -->