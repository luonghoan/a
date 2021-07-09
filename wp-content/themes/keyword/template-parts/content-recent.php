<div id="recent-content" class="content-loop">

	<?php

	if ( have_posts() ) :	
	
	$i = 1;

	/* Start the Loop */
	while ( have_posts() ) : the_post();

		get_template_part('template-parts/content', 'loop');

		$i++;

	endwhile;

	else :

		get_template_part( 'template-parts/content', 'none' );

	endif; 

	?>

</div><!-- #recent-content -->	

<?php get_template_part('template-parts/pagination', ''); ?>