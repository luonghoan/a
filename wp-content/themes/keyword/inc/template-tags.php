<?php
/**
 * Custom template tags for this theme.
 *
 * Eventually, some of the functionality here could be replaced by core features.
 *
 * @package keyword
 */

if ( ! function_exists( 'is_happy_coupons_activated' ) ) :
/**
 * Query Happy Coupons activation
 *
 * @since  1.0.0
 */
function is_happy_coupons_activated() {
    return class_exists( 'Happy_Coupons' ) ? true : false;
}
endif;

if ( ! function_exists( 'is_woocommerce_activated' ) ) :
/**
 * Query WooCommerce activation
 *
 * @since  1.0.0
 */
function is_woocommerce_activated() {
    return class_exists( 'woocommerce' ) ? true : false;
}
endif;

/**
 * Get Post Views.
 */
if ( ! function_exists( 'keyword_get_post_views' ) ) :

function keyword_get_post_views($postID){
    $count_key = 'post_views_count';
    $count = get_post_meta($postID, $count_key, true);
    if($count==''){
        delete_post_meta($postID, $count_key);
        add_post_meta($postID, $count_key, '0');
        return '<span class="view-count">0</span> View';
    }
    return '<span class="view-count">' . number_format($count) . '</span> ' . __('Views', 'keyword');
}

endif;

/**
 * Set Post Views.
 */
if ( ! function_exists( 'keyword_set_post_views' ) ) :

function keyword_set_post_views($postID) {
    $count_key = 'post_views_count';
    $count = get_post_meta($postID, $count_key, true);
    if($count==''){
        $count = 0;
        delete_post_meta($postID, $count_key);
        add_post_meta($postID, $count_key, '0');
    }else{
        $count++;
        update_post_meta($postID, $count_key, $count);
    }
}

endif;

/**
 * Search Filter 
 */
if ( ! function_exists( 'keyword_search_filter' ) && ( ! is_admin() ) ) :

function keyword_search_filter($query) {
	if ($query->is_search) {
		$query->set('post_type', 'post');
	}
	return $query;
}

add_filter('pre_get_posts','keyword_search_filter');

endif;

/**
 * Filter the except length to 20 characters.
 *
 * @param int $length Excerpt length.
 * @return int (Maybe) modified excerpt length.
 */
if ( ! function_exists( 'keyword_custom_excerpt_length' ) ) :

function keyword_custom_excerpt_length( $length ) {
    return get_theme_mod('entry-excerpt-length', '45');
}
add_filter( 'excerpt_length', 'keyword_custom_excerpt_length', 999 );

endif;

/**
 * Customize excerpt more.
 */
if ( ! function_exists( 'keyword_excerpt_more' ) ) :

function keyword_excerpt_more( $more ) {
   return '... ';
}
add_filter( 'excerpt_more', 'keyword_excerpt_more' );

endif;

/**
 * Add custom meta box.
 */
if ( ! function_exists( 'keyword_add_custom_meta_box' ) ) :

function keyword_add_custom_meta_box()
{
    add_meta_box("demo-meta-box", "Post Options", "keyword_custom_meta_box_markup", "post", "side", "high", null);
}

add_action("add_meta_boxes", "keyword_add_custom_meta_box");

endif;
/**
 * Displaying fields in a custom meta box.
 */
if ( ! function_exists( 'keyword_custom_meta_box_markup' ) ) :

function keyword_custom_meta_box_markup($object)
{
    wp_nonce_field(basename(__FILE__), "meta-box-nonce");

    ?>
        <div>
            <label for="is_featured"><?php echo __('Featured this post on homepage', 'keyword'); ?> </label>
            <?php
                $checkbox_value = get_post_meta($object->ID, "is_featured", true);

                if($checkbox_value == "")
                {
                    ?>
                        <input name="is_featured" type="checkbox" value="true">
                    <?php
                }
                else if($checkbox_value == "true")
                {
                    ?>  
                        <input name="is_featured" type="checkbox" value="true" checked>
                    <?php
                }
            ?>
        </div>
    <?php  
}

endif;

/**
 * Storing Meta Data.
 */
if ( ! function_exists( 'keyword_save_custom_meta_box' ) ) :

function keyword_save_custom_meta_box($post_id, $post, $update)
{
    if (!isset($_POST["meta-box-nonce"]) || !wp_verify_nonce($_POST["meta-box-nonce"], basename(__FILE__)))
        return $post_id;

    if(!current_user_can("edit_post", $post_id))
        return $post_id;

    if(defined("DOING_AUTOSAVE") && DOING_AUTOSAVE)
        return $post_id;

    $slug = "post";
    if($slug != $post->post_type)
        return $post_id;

    $meta_box_text_value = "";
    $meta_box_textarea_value = "";
    $meta_box_checkbox_value = "";

    if(isset($_POST["is_featured"]))
    {
        $meta_box_checkbox_value = $_POST["is_featured"];
    }   
    update_post_meta($post_id, "is_featured", $meta_box_checkbox_value);
}

add_action("save_post", "keyword_save_custom_meta_box", 10, 3);

endif;

/**
 * Display the first (single) category of post.
 */
if ( ! function_exists( 'keyword_first_category' ) ) :
function keyword_first_category() {
    $category = get_the_category();
    if ($category) {
      echo '<a href="' . get_category_link( $category[0]->term_id ) . '" title="' . sprintf( __( "View all posts in %s", 'keyword' ), $category[0]->name ) . '" ' . '>' . $category[0]->name.'</a> ';
    }    
}
endif;

/**
 * Returns true if a blog has more than 1 category.
 *
 * @return bool
 */
if ( ! function_exists( 'keyword_categorized_blog' ) ) :

function keyword_categorized_blog() {
    if ( false === ( $all_the_cool_cats = get_transient( 'keyword_categories' ) ) ) {
        // Create an array of all the categories that are attached to posts.
        $all_the_cool_cats = get_categories( array(
            'fields'     => 'ids',
            'hide_empty' => 1,
            // We only need to know if there is more than one category.
            'number'     => 2,
        ) );

        // Count the number of categories that are attached to the posts.
        $all_the_cool_cats = count( $all_the_cool_cats );

        set_transient( 'keyword_categories', $all_the_cool_cats );
    }

    if ( $all_the_cool_cats > 1 ) {
        // This blog has more than 1 category so keyword_categorized_blog should return true.
        return true;
    } else {
        // This blog has only 1 category so keyword_categorized_blog should return false.
        return false;
    }
}

endif;

/**
 * Flush out the transients used in keyword_categorized_blog.
 */
if ( ! function_exists( 'keyword_category_transient_flusher' ) ) :

function keyword_category_transient_flusher() {
    if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) {
        return;
    }
    // Like, beat it. Dig?
    delete_transient( 'keyword_categories' );
}
add_action( 'edit_category', 'keyword_category_transient_flusher' );
add_action( 'save_post',     'keyword_category_transient_flusher' );

endif;

/**
 * Disable specified widgets.
 */
if ( ! function_exists( 'keyword_disable_specified_widgets' ) ) :

function keyword_disable_specified_widgets( $sidebars_widgets ) {

    if ( isset($sidebars_widgets['header-newsletter']) ) {
        if ( is_array($sidebars_widgets['header-newsletter']) ) {
               foreach($sidebars_widgets['header-newsletter'] as $i => $widget) {
                    if( (strpos($widget, 'keyword-newsletter') === false) ) {
                       unset($sidebars_widgets['header-newsletter'][$i]);
                    }
               }
        }     
    }

    return $sidebars_widgets;
}
add_filter( 'sidebars_widgets', 'keyword_disable_specified_widgets' );

endif;

/** 
 * Create a new page on theme activation.
 */
if (isset($_GET['activated']) && is_admin()){
    add_action('init', 'keyword_create_initial_pages');
}

if ( ! function_exists( 'keyword_create_initial_pages' ) ) :

function keyword_create_initial_pages() {

    $pages = array( 
         // Page Title and URL (a blank space will end up becomeing a dash "-")
    //   'All Categories' => array(
    //      // Page Content           // Template to use (if left blank the default template will be used)
    //     'Browse our latest videos by category' => 'page-templates/all-categories.php'),

        'Featured News' => array(
            'Browse our featured posts' => 'page-templates/featured-posts.php'),

    );

    foreach($pages as $page_url_title => $page_meta) {

        $id = get_page_by_title($page_url_title);

        foreach ($page_meta as $page_content=>$page_template){

            $page = array(
                'post_type'   => 'page',
                'post_title'  => $page_url_title,
                'post_name'   => $page_url_title,
                'post_status' => 'publish',
                'post_content' => $page_content,
                'post_author' => 1,
                'post_parent' => ''
            );

            if(!isset($id->ID)){
                $new_page_id = wp_insert_post($page);
                if(!empty($page_template)){
                    update_post_meta($new_page_id, '_wp_page_template', $page_template);
                }
            }
        }
    }
}

endif;

if ( ! function_exists( 'set_posts_per_page_for_coupon_cpt' ) ) :

function set_posts_per_page_for_coupon_cpt( $query ) {
  if ( !is_admin() && $query->is_main_query() && is_post_type_archive( 'coupon' ) ) {
    $query->set( 'posts_per_page', '20' );
  }
}
add_action( 'pre_get_posts', 'set_posts_per_page_for_coupon_cpt' );

endif;

if ( ! function_exists( 'keyword_custom_toolbar_link' ) ) :

function keyword_custom_toolbar_link($wp_admin_bar) {
    $args = array(
        'id' => 'happythemes',
        'title' => 'Upgrade to Pro Theme', 
        'href' => 'https://www.happythemes.com/wordpress-themes/keyword/', 
        'meta' => array(
            'class' => 'happythemes', 
            'title' => '',
            'target'=> '_blank',
            )
    );
    $wp_admin_bar->add_node($args);
}
add_action('admin_bar_menu', 'keyword_custom_toolbar_link', 999);

endif;

