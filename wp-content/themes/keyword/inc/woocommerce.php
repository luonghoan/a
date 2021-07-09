<?php
/**
 * Woocommerce Compatibility 
 *
 * @package keyword
 */


if ( !class_exists('WooCommerce') )
    return;

/**
 * Declare support
 */
/* WooCommerce */
remove_action( 'woocommerce_before_main_content', 'woocommerce_output_content_wrapper', 10);
remove_action( 'woocommerce_after_main_content', 'woocommerce_output_content_wrapper_end', 10);
add_action('woocommerce_before_main_content', 'my_theme_wrapper_start', 10);
add_action('woocommerce_after_main_content', 'my_theme_wrapper_end', 10);

remove_action( 'woocommerce_after_shop_loop_item', 'woocommerce_template_loop_add_to_cart', 10 );
remove_action( 'woocommerce_before_shop_loop_item', 'woocommerce_template_loop_product_link_open', 10 );
remove_action( 'woocommerce_after_shop_loop_item', 'woocommerce_template_loop_product_link_close', 5 );
remove_action( 'woocommerce_before_shop_loop_item_title', 'woocommerce_template_loop_product_thumbnail', 10 );
remove_action( 'woocommerce_before_shop_loop_item_title', 'woocommerce_show_product_loop_sale_flash', 10 );


add_action( 'woocommerce_before_shop_loop_item_title', 'keyword_image_wrapper_open', 5 );
add_action( 'woocommerce_before_shop_loop_item_title', 'woocommerce_template_loop_product_link_open', 11 );
add_action( 'woocommerce_before_shop_loop_item_title', 'woocommerce_template_loop_product_thumbnail', 12 );
add_action( 'woocommerce_before_shop_loop_item_title', 'woocommerce_template_loop_product_link_close', 13 );
add_action( 'woocommerce_before_shop_loop_item_title', 'keyword_loop_product_actions', 15 );
add_action( 'keyword_product_actions_before', 'woocommerce_template_loop_add_to_cart', 10 );
add_action( 'woocommerce_before_shop_loop_item_title', 'keyword_image_wrapper_close', 20 );

add_action( 'woocommerce_shop_loop_item_title', 'woocommerce_template_loop_product_link_open', 9 );
add_action( 'woocommerce_shop_loop_item_title', 'woocommerce_template_loop_product_link_close', 11 );

function my_theme_wrapper_start() {
  echo '<section id="main">';
}

function my_theme_wrapper_end() {
  echo '</section>';
}

add_action( 'after_setup_theme', 'woocommerce_support' );
function woocommerce_support() {
    add_theme_support( 'woocommerce' );
    add_theme_support('wc-product-gallery-zoom');
    add_theme_support('wc-product-gallery-lightbox');
    add_theme_support( 'wc-product-gallery-slider');    
}

if ( ! function_exists( 'keyword_header_cart' ) ) :
/**
 * Display Header Cart
 *
 * @since  1.0.0
 */
function keyword_header_cart() {
        // Show hide cart based on user selected on Customizer.
        $show = get_theme_mod('header-cart-on', true);
        if ( !$show ) {
            return;
        }
    ?>
        <div class="header-cart">
            <span class="cart-box">
                <?php keyword_cart_link(); ?>
            </span>
            <?php the_widget( 'WC_Widget_Cart', 'title=' ); ?>
        </div>
    <?php
}
endif;

if ( ! function_exists( 'keyword_cart_link' ) ) :
/**
 * Cart link
 *
 * @since  1.0.0
 */

function keyword_cart_link() { ?>
    <a class="cart-contents" href="<?php echo esc_url( wc_get_cart_url() ); ?>" title="<?php esc_html_e( 'View your shopping cart', 'keyword' ); ?>">
        <span class="cart-data">
            <span class="total"><?php esc_html_e('Cart', 'keyword'); ?> / <?php echo wp_kses_data( WC()->cart->get_cart_subtotal() ); ?></span>
            <span class="count"><?php echo wp_kses_data( WC()->cart->get_cart_contents_count() );?></span>
        </span>    
    </a>
    <?php
}
endif;

/**
 * Variable products button
 */
function keyword_single_variation_add_to_cart_button() {
    global $product;
    ?>
    <div class="woocommerce-variation-add-to-cart variations_button">
        <?php
            do_action( 'woocommerce_before_add_to_cart_quantity' );

            woocommerce_quantity_input( array(
                'input_value' => isset( $_POST['quantity'] ) ? wc_stock_amount( $_POST['quantity'] ) : 1,
            ) );

            do_action( 'woocommerce_after_add_to_cart_quantity' );
        ?>
        <button type="submit" class="roll-button cart-button"><?php echo esc_html( $product->single_add_to_cart_text() ); ?></button>
        <input type="hidden" name="add-to-cart" value="<?php echo absint( $product->get_id() ); ?>" />
        <input type="hidden" name="product_id" value="<?php echo absint( $product->get_id() ); ?>" />
        <input type="hidden" name="variation_id" class="variation_id" value="0" />
    </div>
     <?php
}
add_action( 'woocommerce_single_variation', 'keyword_single_variation_add_to_cart_button', 21 );

/**
 * Cart Fragments
 * Ensure cart contents update when products are added to the cart via AJAX
 *
 * @since  1.0.0
 */
function keyword_cart_link_fragment( $fragments ) {
    global $woocommerce;

    ob_start();

    keyword_cart_link();

    $fragments['a.cart-contents'] = ob_get_clean();

    return $fragments;
}
if ( defined( 'WC_VERSION' ) && version_compare( WC_VERSION, '2.3', '>=' ) ) {
    add_filter( 'woocommerce_add_to_cart_fragments', 'keyword_cart_link_fragment' );
} else {
    add_filter( 'add_to_cart_fragments', 'keyword_cart_link_fragment' );
}

if ( ! function_exists( 'keyword_loop_product_actions' ) ) :
/**
 * Add product actions, consist of:
 * - before hook (add to cart hooked here)
 * - popup image full
 * - after hook
 *
 * @since  1.0.0
*/
function keyword_loop_product_actions() {
    ?>
    <div class="product-actions">
        <?php do_action( 'keyword_product_actions_before' ); ?>
        <?php do_action( 'keyword_product_actions_after' ); ?>
    </div>
    <?php
}
endif;

if ( ! function_exists( 'keyword_image_wrapper_open' ) ) :
/**
 * Sorting wrapper
 *
 * @since   1.0.0
 */
function keyword_image_wrapper_open() {
    echo '<div class="img-wrapper">';
}
endif;

if ( ! function_exists( 'keyword_image_wrapper_close' ) ) :
/**
 * Sorting wrapper close
 *
 * @since   1.0.0
 */
function keyword_image_wrapper_close() {
    echo '</div>';
}
endif;

/**
 * Number of products
 */
//function keyword_woocommerce_products_number() {
 //   $default = get_option( 'posts_per_page' );
 //   $number  = get_theme_mod( 'swc_products_number', $default);

 //   return $number;
//}
//add_filter( 'loop_shop_per_page', 'keyword_woocommerce_products_number', 20 );
function keyword_products_per_page() {
    return intval( apply_filters( 'keyword_products_per_page', 12 ) );
}
add_filter( 'loop_shop_per_page', 'keyword_products_per_page' );


/**
 * Returns true if current page is shop, product archive or product tag
 */
function keyword_wc_archive_check() {
    if ( is_shop() || is_product_category() || is_product_tag() ) {
        return true;
    } else {
        return false;
    }
}

/**
 * Remove sidebar from all archives
 */
function keyword_remove_wc_sidebar_archives() {
    $archive_check = keyword_wc_archive_check();
    $rs_archives = get_theme_mod( 'swc_sidebar_archives' );
    $rs_products = get_theme_mod( 'swc_sidebar_products' );

    //if ( ( $rs_archives && $archive_check ) || ( $rs_products && is_product() ) ) {
        remove_action('woocommerce_sidebar', 'woocommerce_get_sidebar', 10);
    //} 
}
add_action( 'wp', 'keyword_remove_wc_sidebar_archives' );
