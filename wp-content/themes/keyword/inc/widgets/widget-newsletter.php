<?php
/**
 * Newsletter widget.
 *
 * @package    keyword
 * @author     HappyThemes
 * @copyright  Copyright (c) 2017, HappyThemes
 * @license    http://www.gnu.org/licenses/gpl-2.0.html
 * @since      1.0.0
 */
class keyword_Newsletter_Widget extends WP_Widget {

	/**
	 * Sets up the widgets.
	 *
	 * @since 1.0.0
	 */
	public function __construct() {

		// Set up the widget options.
		$widget_options = array(
			'classname'   => 'widget_newsletter',
			'description' => esc_html__( 'Display newsletter subscribe form.', 'keyword' ),
			'customize_selective_refresh' => true
		);

		// Create the widget.
		parent::__construct(
			'keyword-newsletter',                                    // $this->id_base
			esc_html__( '&raquo; Newsletter', 'keyword' ), // $this->name
			$widget_options                                     // $this->widget_options
		);

		$this->alt_option_name = 'widget_newsletter';
	}

	/**
	 * Outputs the widget based on the arguments input through the widget controls.
	 *
	 * @since 1.0.0
	 */
	public function widget( $args, $instance ) {
		if ( ! isset( $args['widget_id'] ) ) {
			$args['widget_id'] = $this->id;
		}

		// Set up default value
		$title   = ( ! empty( $instance['title'] ) ) ? $instance['title'] : '';
		$form_code = ( ! empty( $instance['form_code'] ) ) ? $instance['form_code'] : '';

		// Output the theme's $before_widget wrapper.
		echo $args['before_widget'];

		// If the title not empty, display it.
		if ( $title ) {
			echo $args['before_title'] . apply_filters( 'widget_title', $title, $instance, $this->id_base ) . $args['after_title'];
		}

			// Display the ad banner.
			if ( $form_code ) {
				echo '<div class="newsletter-widget">' . $form_code . '</div>';
			}

		// Close the theme's widget wrapper.
		echo $args['after_widget'];

	}

	/**
	 * Updates the widget control options for the particular instance of the widget.
	 *
	 * @since 1.0.0
	 */
	public function update( $new_instance, $old_instance ) {
		$instance            = $old_instance;
		$instance['title']   = sanitize_text_field( $new_instance['title'] );
		if ( current_user_can( 'unfiltered_html' ) ) {
			$instance['form_code'] = $new_instance['form_code'];
		} else {
			$instance['form_code'] = wp_kses_post( $new_instance['form_code'] );
		}
		return $instance;
	}

	/**
	 * Displays the widget control options in the Widgets admin screen.
	 *
	 * @since 1.0.0
	 */
	public function form( $instance ) {
		$title   = isset( $instance['title'] ) ? esc_attr( $instance['title'] ) : '';
		$form_code = isset( $instance['form_code'] ) ? esc_textarea( $instance['form_code'] ) : '';
	?>

		<p>
			<label for="<?php echo $this->get_field_id( 'title' ); ?>">
				<?php esc_html_e( 'Title', 'keyword' ); ?>
			</label>
			<input type="text" class="widefat" id="<?php echo $this->get_field_id( 'title' ); ?>" name="<?php echo $this->get_field_name( 'title' ); ?>" value="<?php echo $title; ?>" />
		</p>

		<p>
			<label for="<?php echo $this->get_field_id( 'form_code' ); ?>">
				<?php esc_html_e( 'Subscribe Form Code', 'keyword' ); ?> <span style="display: block;color: #9f9f9f;">See <a href="https://www.happythemes.com/demo/keyword/wp-content/themes/keyword/form-example.txt" target="_blank">example code</a>.</span>
			</label>
			<textarea class="widefat" name="<?php echo $this->get_field_name( 'form_code' ); ?>" id="<?php echo $this->get_field_id( 'form_code' ); ?>" cols="30" rows="6"><?php echo $form_code; ?></textarea>
		</p>

	<?php

	}

}
