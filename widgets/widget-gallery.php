<?php
add_action( 'widgets_init', 'atbs_gallery_load_widget' );

function atbs_gallery_load_widget() {
	register_widget( 'atbs_gallery_widget' );
}

class atbs_gallery_widget extends WP_Widget {

	/**
	 * Widget setup.
	 */
	function __construct() {
		/* Widget settings. */
		$widget_ops = array( 'classname' => 'atbs-carbon-widget atbs-carbon-widget-gallery', 'description' => esc_html__('Galler.', 'carbon') );

		/* Create the widget. */
		parent::__construct( 'atbs_gallery_widget', esc_html__('[ATBS] Widget Gallery', 'carbon'), $widget_ops);
	}

	/**
	 * How to display the widget on the screen.
	 */
	function widget( $args, $instance ) {
		extract( $args );

		/* Our variables from the widget settings. */
		$title = apply_filters('widget_title', $instance['title'] );
		/* Before widget (defined by themes). */
		echo $before_widget;
		?>
			<div class="widget-about__inner text-center">
                <?php if($title != ''):?>
                <div class="widget__title">
            		<h4 class="widget__title-text"><?php echo esc_html($title);?></h4>
            	</div>
                <?php endif;?>
                    
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

		return $instance;
	}


	function form( $instance ) {

		/* Set up some default widget settings. */
		$defaults = array( 'title' => 'Gallery');
		$instance = wp_parse_args( (array) $instance, $defaults ); ?>

		<!-- Widget Title: Text Input -->
		<p>
			<label for="<?php echo $this->get_field_id( 'title' ); ?>">Title:</label>
			<input id="<?php echo $this->get_field_id( 'title' ); ?>" name="<?php echo $this->get_field_name( 'title' ); ?>" value="<?php echo $instance['title']; ?>" style="width:96%;" />
		</p>
		<form action="upload.php" method="post" enctype="multipart/form-data">
		  <input type="file" name="fileToUpload" id="fileToUpload">
		  <input type="submit" value="Upload Image" name="submit">
		</form>
	<?php
	}
}

?>