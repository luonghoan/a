<?php
/**
 * The header for our theme.
 *
 * This is the template that displays all of the <head> section and everything up until <div id="content">
 *
 * @link https://developer.wordpress.org/themes/basics/template-files/#template-partials
 *
 * @package keyword
 */

?><!DOCTYPE html>
<html <?php language_attributes(); ?>>
<head>
<meta charset="<?php bloginfo( 'charset' ); ?>">
<meta name="viewport" content="width=device-width, initial-scale=1">
<meta http-equiv="X-UA-Compatible" content="IE=edge">
<meta name="HandheldFriendly" content="true">
<link rel="profile" href="http://gmpg.org/xfn/11">
<?php if (get_theme_mod('favicon', '') != null) { ?>
<link rel="icon" type="image/png" href="<?php echo esc_url( get_theme_mod('favicon', '') ); ?>" />
<?php } ?>
<link href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:400,400i,700,900" rel="stylesheet">
<?php wp_head(); ?>
<?php
	// Theme Color
	$primary_color = '#2da6e9';
	$secondary_color = '#ffbe02';
	$newsletter_bg_color = get_theme_mod('newsletter-bg-color', '');
	$footer_bg_color = '#4d626e';
?>
<style type="text/css" media="all">
	body,
	input,
	input[type="text"],
	input[type="email"],
	input[type="url"],
	input[type="search"],
	input[type="password"],
	textarea,
	table,
	.sidebar .widget_ad .widget-title,
	.site-footer .widget_ad .widget-title {
		font-family: "Source Sans Pro", "Helvetica Neue", Helvetica, Arial, sans-serif;
	}
	.footer-nav li a,
	.pagination .page-numbers,
	button,
	.btn,
	input[type="submit"],
	input[type="reset"],
	input[type="button"],
	.comment-form label,
	label,
	h1,h2,h3,h4,h5,h6 {
		font-family: "Source Sans Pro", "Helvetica Neue", Helvetica, Arial, sans-serif;
	}
	a,
	a:hover,
	.site-header .search-icon:hover span,
	.sf-menu li li a:hover,
	.entry-title a:hover,
	article.hentry .edit-link a,
	.author-box a,
	.page-content a,
	.entry-content a,
	.comment-author a,
	.comment-content a,
	.comment-reply-title small a:hover,
	.sidebar .widget a,
	.sidebar .widget ul li a:hover,
	#site-bottom a:hover,
	.author-box a:hover,
	.page-content a:hover,
	.entry-content a:hover,
	.content-loop .entry-title a:hover,
	article.hentry .edit-link a:hover,
	.comment-content a:hover,
	.pagination .page-numbers:hover,
	.pagination .page-numbers.current,
	.entry-tags .tag-links a:hover,
	.sorter a.current {
		color: <?php echo $primary_color; ?>;
	}
	#primary-menu li li a:hover,
	#primary-menu li li.current-menu-item a:hover,
	.widget_tag_cloud .tagcloud a:hover {
		color: <?php echo $primary_color; ?> !important;
	}
	.newsletter-widget input[type="button"],
	.newsletter-widget input[type="submit"],
	.newsletter-widget button {
		background-color: #67bd2e;
	}	
	button,
	.btn,
	input[type="submit"],
	input[type="reset"],
	input[type="button"],
	.sidebar .widget ul li:hover:before,
	.more-link a,
	.breadcrumbs {
		background-color: <?php echo $primary_color; ?>;
	}
	.slicknav_nav,
	#primary-nav li ul,
	.tag-links a:hover,
	.widget_tag_cloud .tagcloud a:hover  {
		border-color: <?php echo $primary_color; ?>;
	}
	.site-footer .widget_newsletter,
	.sidebar .widget_newsletter,
	.header-newsletter {
		background: <?php echo $newsletter_bg_color; ?>;	
	}

	.site-footer {
		background-color: <?php echo $footer_bg_color; ?>;	
	}

	.sf-menu li a:before,
	.site-footer {
		border-bottom-color: <?php echo $secondary_color; ?>;
	}
	.section-header h3 {
		color: <?php echo $secondary_color; ?>;
	}
</style>

</head>

<body <?php body_class(); ?>>
<div id="page" class="site">

	<header id="masthead" class="site-header <?php if ( get_theme_mod('header-search-on', true) != true ) { echo "no-header-search"; } ?> clear">

		<div class="container">

		<div class="site-branding">

			<?php if (get_theme_mod('logo', get_template_directory_uri().'/assets/img/logo.png') != null) { ?>
			
			<div id="logo">
				<span class="helper"></span>
				<a href="<?php echo esc_url( home_url( '/' ) ); ?>" rel="home">
					<img src="<?php echo get_theme_mod('logo', get_template_directory_uri().'/assets/img/logo.png'); ?>" alt=""/>
				</a>
			</div><!-- #logo -->

			<?php } else { ?>

			<div class="site-title">
				<h1><a href="<?php echo home_url(); ?>"><?php bloginfo('name'); ?></a></h1>
			</div><!-- .site-title -->

			<?php } ?>

		</div><!-- .site-branding -->		

		<nav id="primary-nav" class="primary-navigation">

			<?php 
				if ( has_nav_menu( 'primary' ) ) {
					wp_nav_menu( array( 'theme_location' => 'primary', 'menu_id' => 'primary-menu', 'menu_class' => 'sf-menu' ) );
				} else {
			?>

				<ul id="primary-menu" class="sf-menu">
					<li><a href="<?php echo home_url(); ?>/wp-admin/nav-menus.php"><?php echo __('Add menu for location: Primary Menu', 'keyword'); ?></a></li>
				</ul><!-- .sf-menu -->

			<?php } ?>

		</nav><!-- #primary-nav -->

		<div id="slick-mobile-menu"></div>
		
		<?php if ( get_theme_mod('header-cart-on', true) ) : ?>

			<?php if ( is_woocommerce_activated() ) { keyword_header_cart(); } ?>		
		
		<?php endif; ?>		

		<?php if ( get_theme_mod('header-search-on', true) ) : ?>
			
			<span class="search-icon">
				<span class="genericon genericon-search"></span>
				<span class="genericon genericon-close"></span>			
			</span>

			<div class="header-search">
				<form id="searchform" method="get" action="<?php echo esc_url( home_url( '/' ) ); ?>">
					<input type="search" name="s" class="search-input" placeholder="<?php echo __('Search for...', 'keyword'); ?>" autocomplete="off">
					<button type="submit" class="search-submit"><?php echo __('Search', 'keyword'); ?></button>		
				</form>
			</div><!-- .header-search -->

		<?php endif; ?>						

		</div><!-- .container -->

	</header><!-- #masthead -->	

	<div class="header-space"></div>

	<?php 
		if ( is_home() && (get_theme_mod('newsletter-position', 'choice-1') == 'choice-1') && (!get_query_var('paged')) ) {
			dynamic_sidebar( 'header-newsletter' ); 
		}
	?>	

	<?php
		if ( is_post_type_archive('coupon') || is_tax('coupon_category') ) {
	?>

	<div class="coupons-page-header clear">
		<h1><span><?php esc_html_e('Exclusive Deals & Coupon Codes', 'keyword'); ?></span></h1>

		<div class="entry-summary">
			<?php echo get_theme_mod('coupons-page-desc', 'You may enter description for the coupon page here.'); ?>
		</div><!-- .entry-summary -->

		<?php
		$cats = get_terms( 'coupon_category' );
		if ( $cats ) :

			// for taxonomy page
			$current = get_queried_object();
			$current_term = '';
			if ( isset( $current->term_id ) )
				$current_term = $current->term_id;
		?>
			<nav class="coupon-nav clear">
				<ul class="clear">
					<li <?php if ( ! $current_term ) echo ' class="' . esc_attr( 'current' ) . '"'; ?>><a href="<?php echo esc_url( get_post_type_archive_link( 'coupon' ) ); ?>"><?php _e( 'All', 'keyword' ); ?></a></li>

					<?php foreach ( $cats as $cat ) : ?>
						<li<?php if ( $current_term == $cat->term_id ) echo ' class="' . esc_attr( 'current' ) . '"'; ?>><a href="<?php echo esc_url( get_term_link( $cat->term_id ) ); ?>"><?php echo wp_kses_post( $cat->name ); ?></a></li>
					<?php endforeach; ?>
				</ul>
			</nav><!-- .coupon-nav -->
		<?php endif; ?>

	</div><!-- .coupons-page-header -->	

	<?php
		}
	?>	

<div id="content" class="site-content container clear">