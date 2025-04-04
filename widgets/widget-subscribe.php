<?php
/**
 * Add function to widgets_init that'll load our widget.
 */
add_action( 'widgets_init', 'bk_register_widget_subscribe' );

function bk_register_widget_subscribe() {
	register_widget( 'bk_widget_subscribe' );
}

/**
 * This class handles everything that needs to be handled with the widget:
 * the settings, form, display, and update.  Nice!
 *
 */
class bk_widget_subscribe extends WP_Widget {

	/**
	 * Widget setup.
	 */
	function __construct() {
		/* Widget settings. */
		$widget_ops = array( 'classname' => 'atbs-widget carbon-widget-subscribe-1', 'description' => esc_html__('Displays Subscribe Form.', 'carbon') );

		/* Create the widget. */
		parent::__construct( 'bk_widget_subscribe', esc_html__('[ATBS] Widget Subscribe', 'carbon'), $widget_ops);
	}
    
	/**
	 *display the widget on the screen.
	 */
	function widget( $args, $instance ) {
		extract( $args );
        
        $widget_opts = array();
        $title       = $instance['title'];
        $shortcode   = $instance['mailchim_shortcode'];
        echo ($before_widget);
        echo '<div class="widget atbs-carbon-widget">';
        echo '<div class="widget-wrap">';
        $headingStyle = $instance['heading_style'];
        if($headingStyle) {
            $headingClass = Atbs_Core::bk_get_widget_heading_class($headingStyle);
        }else {
            $headingClass = '';
        }

        if ( $title ) { echo Atbs_Widget::bk_get_widget_heading($title, $headingClass); }
        echo '<div class="widget__inner">';
        echo '<div class="subscribe-form">';
        if($shortcode != ''):
        echo do_shortcode($shortcode);
        endif;
        echo '</div><!-- .subscribe-form -->';
        echo '</div><!-- .widget__inner -->';
        
        echo '</div><!-- .widget-wrap -->';
        echo '</div><!-- .widget -->';
        
        /* After widget (defined by themes). */
		echo ($after_widget);
	}
	
	/**
	 * update widget settings
	 */
	function update($new_instance, $old_instance) {
		$instance = $old_instance;
        $instance['title']              = $new_instance['title'];
        $instance['heading_style'] = strip_tags($new_instance['heading_style']);
        $instance['mailchim_shortcode'] = $new_instance['mailchim_shortcode'];
		return $instance;
	}

	/**
	 * Displays the widget settings controls on the widget panel.
	 * Make use of the get_field_id() and get_field_name() function
	 * when creating your form elements. This handles the confusing stuff.
	 */
	function form( $instance ) {
		$defaults = array('title' => 'Subscribe Us','heading_style' => 'default', 'mailchim_shortcode' => '');
		$instance = wp_parse_args((array) $instance, $defaults);
	?>
    <p>
		<label for="<?php echo esc_attr($this->get_field_id( 'title' )); ?>"><strong><?php esc_html_e('[Optional] Title:', 'carbon'); ?></strong></label>
		<input type="text" class="widefat" id="<?php echo esc_attr($this->get_field_id('title')); ?>" name="<?php echo esc_attr($this->get_field_name('title')); ?>" value="<?php if( !empty($instance['title']) ) echo esc_attr($instance['title']); ?>" />
	</p>
    <p>
	    <label for="<?php echo esc_attr($this->get_field_id( 'heading_style' )); ?>"><?php esc_attr_e('Heading Style:', 'carbon'); ?></label>
	    <select class="widefat" id="<?php echo esc_attr($this->get_field_id( 'heading_style' )); ?>" name="<?php echo esc_attr($this->get_field_name( 'heading_style' )); ?>" >
		    <option value="default" <?php if( !empty($instance['heading_style']) && $instance['heading_style'] == 'default' ) echo 'selected="selected"'; else echo ""; ?>><?php esc_attr_e('Default - From Theme Option', 'carbon'); ?></option>
            <option value="widget-title-style-1" <?php if( !empty($instance['heading_style']) && $instance['heading_style'] == 'widget-title-style-1' ) echo 'selected="selected"'; else echo ""; ?>><?php esc_attr_e('Heading Style 1', 'carbon'); ?></option>
            <option value="widget-title-style-2" <?php if( !empty($instance['heading_style']) && $instance['heading_style'] == 'widget-title-style-2' ) echo 'selected="selected"'; else echo ""; ?>><?php esc_attr_e('Heading Style 2', 'carbon'); ?></option>
            <option value="widget-title-style-2-center" <?php if( !empty($instance['heading_style']) && $instance['heading_style'] == 'widget-title-style-2-center' ) echo 'selected="selected"'; else echo ""; ?>><?php esc_attr_e('Heading Style 2 Center', 'carbon'); ?></option>
            <option value="widget-title-style-2-right" <?php if( !empty($instance['heading_style']) && $instance['heading_style'] == 'widget-title-style-2-right' ) echo 'selected="selected"'; else echo ""; ?>><?php esc_attr_e('Heading Style 2 Right', 'carbon'); ?></option>
            <option value="widget-title-style-3" <?php if( !empty($instance['heading_style']) && $instance['heading_style'] == 'widget-title-style-3' ) echo 'selected="selected"'; else echo ""; ?>><?php esc_attr_e('Heading Style 3', 'carbon'); ?></option>
            <option value="widget-title-style-4" <?php if( !empty($instance['heading_style']) && $instance['heading_style'] == 'widget-title-style-4' ) echo 'selected="selected"'; else echo ""; ?>><?php esc_attr_e('Heading Style 4', 'carbon'); ?></option>
		</select>
    </p>
    <p>
		<label for="<?php echo esc_attr($this->get_field_id( 'mailchim_shortcode' )); ?>"><strong><?php esc_html_e('Mailchimp Shortcode:', 'carbon'); ?></strong></label>
		<input type="text" class="widefat" id="<?php echo esc_attr($this->get_field_id('mailchim_shortcode')); ?>" name="<?php echo esc_attr($this->get_field_name('mailchim_shortcode')); ?>" value="<?php if( !empty($instance['mailchim_shortcode']) ) echo esc_attr($instance['mailchim_shortcode']); ?>" />
    </p>
<?php
	}
}
?>
