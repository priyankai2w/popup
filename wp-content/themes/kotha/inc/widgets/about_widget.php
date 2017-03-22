<?php
/* About Widget */

add_action( 'widgets_init', 'kotha_about_load_widget' );

function kotha_about_load_widget() {
	register_widget( 'kotha_about_widget' );
}

class kotha_about_widget extends WP_Widget {

	/**
	 * Widget setup.
	 */
	function kotha_about_widget() {
		/* Widget settings. */
		$widget_ops = array( 'classname' => 'kotha_about_widget', 'description' => __('A widget that displays an About
		widget', 'kotha') );

		/* Widget control settings. */
		$control_ops = array( 'width' => 250, 'height' => 350, 'id_base' => 'kotha_about_widget' );

		/* Create the widget. */
		parent::__construct( 'kotha_about_widget', __('Kotha About Me', 'kotha'), $widget_ops, $control_ops );
	}

	/**
	 * How to display the widget on the screen.
	 */
	function widget( $args, $instance ) {
		extract( $args );

		/* Our variables from the widget settings. */
		$title = apply_filters('widget_title', $instance['title'] );
		$image = $instance['image'];
		$description = $instance['description'];
		
		/* Before widget (defined by themes). */
		echo $before_widget;

		/* Display the widget title if one was input (before and after defined by themes). */
		if ( $title )
			echo $before_title . $title . $after_title;

		?>
			
			<div class="about-widget">
			
			<?php if($image) : ?>
			<img class="img-responsive" src="<?php echo esc_url($image); ?>" alt="<?php echo esc_attr($title); ?>" />
			<?php endif; ?>
			
			<?php if($description) : ?>
			<div class="about-me-content"><?php echo esc_attr($description); ?></div>
			<?php endif; ?>	
			
			</div>
			
		<?php

		/* After widget (defined by themes). */
		echo $after_widget;
	}

	/**
	 * Update the widget settings.
	 */
	function update( $new_instance, $old_instance ) {
		$instance = $old_instance;

		/* Strip tags for title and name to remove HTML (important for text inputs). */
		$instance['title'] = strip_tags( $new_instance['title'] );
		$instance['image'] = strip_tags( $new_instance['image'] );
		$instance['description'] = strip_tags( $new_instance['description'] );

		return $instance;
	}


	function form( $instance ) {

		/* Set up some default widget settings. */
		$defaults = array( 'title' => __('About Me', 'kotha'), 'image' => '', 'description' => '');
		$instance = wp_parse_args( (array) $instance, $defaults ); ?>

		<!-- Widget Title: Text Input -->
		<p>
			<label for="<?php echo esc_attr($this->get_field_id( 'title' )); ?>"><?php _e('Title:', 'kotha') ?></label>
			<input id="<?php echo esc_attr($this->get_field_id( 'title' )); ?>" name="<?php echo esc_attr($this->get_field_name('title')); ?>" value="<?php echo esc_attr($instance['title']); ?>" style="width:96%;" />
		</p>
		
		<!-- image url -->
		<p>
			<label for="<?php echo esc_attr($this->get_field_id( 'image' )); ?>"><?php _e('Image URL:', 'kotha') ?></label>
			<input id="<?php echo esc_attr($this->get_field_id( 'image' )); ?>" name="<?php echo esc_attr($this->get_field_name('image')); ?>" value="<?php echo esc_url($instance['image']); ?>" style="width:96%;" /><br />
			<small><?php _e('Insert your image URL. Your image should be at least 300px wide for best result.', 'kotha') ?></small>
		</p>

		<!-- description -->
		<p>
			<label for="<?php echo esc_attr($this->get_field_id( 'description' )); ?>"><?php _e('About me text:', 'kotha') ?></label>
			<textarea id="<?php echo esc_attr($this->get_field_id( 'description' )); ?>" name="<?php echo esc_attr($this->get_field_name( 'description' )); ?>" style="width:95%;" rows="6"><?php echo esc_attr($instance['description']); ?></textarea>
		</p>


	<?php
	}
}

?>