<?php
/**
 * Add function to widgets_init that'll load our widget.
 */
add_action( 'widgets_init', 'bk_register_widget_category_titles' );

function bk_register_widget_category_titles() {
	register_widget( 'bk_widget_category_titles' );
}

/**
 * This class handles everything that needs to be handled with the widget:
 * the settings, form, display, and update.  Nice!
 *
 */
class bk_widget_category_titles extends WP_Widget {

	/**
	 * Widget setup.
	 */
	function __construct() {
		/* Widget settings. */
		$widget_ops = array( 'classname' => 'atbs-widget widget', 'description' => esc_html__('Displays Category Titles.', 'carbon') );

		
		/* Create the widget. */
		parent::__construct( 'bk_widget_category_titles', esc_html__('[ATBS] Widget Category Tiles', 'carbon'), $widget_ops);
	}

    
	/**
	 *display the widget on the screen.
	 */
	function widget( $args, $instance ) {
		extract( $args );
        
        $widget_opts = array();
        $title       = $instance['title'];
        $headingStyle = strip_tags($instance['heading_style']);
        $category_ids =  explode( ',', $instance['category_ids'] );
        
        echo ($before_widget);
        
        if($headingStyle) {
            $headingClass = Atbs_Core::bk_get_widget_heading_class($headingStyle);
        }else {
            $headingClass = '';
        }   
       	$category_style       = strip_tags($instance['category_style']);
		if($category_style == 'category_style_6'):
			$moduleClass = ' carbon-widget-topic-6 carbon-widget-topic-4';
			$ClassListing ='posts-list flex-box flex-box-1i flex-space-30 posts-list-tablet-2i';
       	elseif($category_style == 'category_style_4'):
       		$moduleClass = ' carbon-widget-topic-4';
       		$ClassListing ='posts-list flex-box flex-box-1i flex-space-30 posts-list-tablet-2i';
       	elseif($category_style == 'category_style_3'):
       		$moduleClass = ' carbon-widget-topic-3';
       		$ClassListing ='posts-list';
       	elseif($category_style == 'category_style_2'):
       		$moduleClass = ' carbon-widget-topic-2';
       		$ClassListing ='posts-list list-space-md list-seperated';
       	else:
       		$moduleClass = ' carbon-widget-topic-1';
       		$ClassListing ='posts-list flex-box flex-box-2i flex-space-20 posts-list-tablet-2i';
       	endif;
       	echo '<div class="atbs-carbon-widget '.$moduleClass.'">';    
        echo '<div class="widget-wrap">';          
		if ( $title ) { echo Atbs_Widget::bk_get_widget_heading($title, $headingClass); }
        ?>
        <div class="widget__inner">
            <div class="<?php echo esc_attr($ClassListing); ?>">
                <?php
                if( isset($instance['category_ids']) && !empty($instance['category_ids'])):
                	echo Atbs_Widget::get_category_tiles($category_ids, $category_style);
                endif;
                ?>
            </div>
        </div>
        </div>
        </div>
        <?php
        /* After widget (defined by themes). */
		echo ($after_widget);
	}
	
	/**
	 * update widget settings
	 */
	function update($new_instance, $old_instance) {
		$instance = $old_instance;
        $instance['title']          = $new_instance['title'];
        $instance['heading_style']  = strip_tags($new_instance['heading_style']);
        $instance['category_style']  = strip_tags($new_instance['category_style']);
        $instance['category_ids']   = $new_instance['category_ids'];

		return $instance;
	}

	/**
	 * Displays the widget settings controls on the widget panel.
	 * Make use of the get_field_id() and get_field_name() function
	 * when creating your form elements. This handles the confusing stuff.
	 */
	function form( $instance ) {
		$defaults = array('title' => 'Categories', 'heading_style' => 'default', 'category_ids' => '');
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
		    <label for="<?php echo esc_attr($this->get_field_id( 'category_style' )); ?>"><?php esc_attr_e('Category Style :', 'carbon'); ?></label>
		    <select class="widefat" id="<?php echo esc_attr($this->get_field_id( 'category_style' )); ?>" name="<?php echo esc_attr($this->get_field_name( 'category_style' )); ?>" >
			    <option value="category_style_1" <?php if( !empty($instance['category_style']) && $instance['category_style'] == 'category_style_1' ) echo 'selected="selected"'; else echo ""; ?>><?php esc_attr_e('Category style 1', 'carbon'); ?></option>
			    <option value="category_style_2" <?php if( !empty($instance['category_style']) && $instance['category_style'] == 'category_style_2' ) echo 'selected="selected"'; else echo ""; ?>><?php esc_attr_e('Category style 2', 'carbon'); ?></option>
			    <option value="category_style_3" <?php if( !empty($instance['category_style']) && $instance['category_style'] == 'category_style_3' ) echo 'selected="selected"'; else echo ""; ?>><?php esc_attr_e('Category style 3', 'carbon'); ?></option>
			    <option value="category_style_4" <?php if( !empty($instance['category_style']) && $instance['category_style'] == 'category_style_4' ) echo 'selected="selected"'; else echo ""; ?>><?php esc_attr_e('Category style 4', 'carbon'); ?></option>
				<option value="category_style_6" <?php if( !empty($instance['category_style']) && $instance['category_style'] == 'category_style_6' ) echo 'selected="selected"'; else echo ""; ?>><?php esc_attr_e('Category style 5', 'carbon'); ?></option>
			 </select>
	    </p>
        <p>
		    <label for="<?php echo esc_attr($this->get_field_id( 'category_ids' )); ?>"><?php esc_attr_e('Categories: (Separate category ids by the comma. e.g. 1,2):','carbon') ?></label>
		    <input type="text" class="widefat" id="<?php echo esc_attr($this->get_field_id( 'category_ids' )); ?>" name="<?php echo esc_attr($this->get_field_name( 'category_ids' )); ?>" value="<?php if( !empty($instance['category_ids']) ) echo esc_attr($instance['category_ids']); ?>" />
	    </p>
<?php
	}
}
?>
