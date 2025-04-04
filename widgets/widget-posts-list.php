<?php
/**
 * Add function to widgets_init that'll load our widget.
 */
add_action( 'widgets_init', 'bk_register_widget_posts_list' );

function bk_register_widget_posts_list() {
	register_widget( 'bk_widget_posts_list' );
}

/**
 * This class handles everything that needs to be handled with the widget:
 * the settings, form, display, and update.  Nice!
 *
 */
class bk_widget_posts_list extends WP_Widget {

	/**
	 * Widget setup.
	 */
	function __construct() {
		/* Widget settings. */
		$widget_ops = array( 'classname' => 'atbs-widget', 'description' => esc_html__('Displays Posts List.', 'carbon') );

		/* Create the widget. */
		parent::__construct( 'bk_widget_posts_list', esc_html__('[ATBS] Widget Posts List', 'carbon'), $widget_ops);
	}
    
	/**
	 *display the widget on the screen.
	 */
	function widget( $args, $instance ) {
		extract( $args );
        
        $widget_opts = array();
        $title = $instance['title'];
        $headingStyle = $instance['heading_style'];
        $widgetModule = $instance['widget_module'];
        $widgetClass = Atbs_Widget::bk_get_widget_module_class($widgetModule);
        
        if($headingStyle) {
            $headingClass = Atbs_Core::bk_get_widget_heading_class($headingStyle);
        }else {
            $headingClass = '';
        }
        
        $widget_opts['offset']      = !empty( $instance['offset'] )     ? $instance['offset'] : 0;
        $widget_opts['category_id'] = !empty( $instance['category_id'] )? $instance['category_id'] : 0;
        $widget_opts['category_ids'] = !empty( $instance['category_ids'] )? $instance['category_ids'] : '';
        $widget_opts['tags']        = !empty( $instance['tags'] )       ? $instance['tags'] : '';
        $widget_opts['entries']     = !empty( $instance['entries'] )    ? $instance['entries'] : 4;	
        $widget_opts['orderby']     = !empty( $instance['orderby'] )    ? $instance['orderby'] : 'date';
        $the_query =  Atbs_Widget::bk_widget_query($widget_opts);
        $widgetMeta = array();
        $widgetMeta = Atbs_Widget::bk_widget_meta($widget_opts['orderby']);
        
        echo ($before_widget);
        
        echo '<div class="atbs-carbon-widget '.$widgetClass.'"><div class="widget-wrap">';
        //line-bottom-two-line
		if ( $title ) { echo Atbs_Widget::bk_get_widget_heading($title, $headingClass); }
        
		if ( $the_query -> have_posts() ) :
            // if($widget_style == 'boxed') echo '<div class="widget__inner">';
            switch ( $widgetModule ) {
                case 'atbs-post-5' :
                    echo '<div class="widget__inner">';
                    echo '<div class="posts-list flex-box flex-space-30 flex-box-1i posts-list-tablet-2i">';
                    echo Atbs_Widget::bk_listing_posts_5_render($the_query, $widgetMeta);
                    echo '</div>';
                    echo '</div>';
                    break;
            	case 'atbs-post-4' :
                    echo '<div class="widget__inner">';
                    echo '<div class="posts-list flex-box flex-space-30 flex-box-1i posts-list-tablet-2i">';
                    echo Atbs_Widget::bk_listing_posts_4_render($the_query, $widgetMeta);
                    echo '</div>';
                    echo '</div>';
                    break;
                case 'atbs-post-3' :
                    echo '<div class="widget__inner">';
                    echo '<div class="posts-list flex-box flex-space-30 flex-box-1i posts-list-tablet-2i">';
                    echo Atbs_Widget::bk_listing_posts_3_render($the_query, $widgetMeta);
                    echo '</div>';
                    echo '</div>';
                    break;
                case 'atbs-post-2' :
                    echo '<div class="widget__inner">';
                    echo '<div class="posts-list flex-box flex-space-30 flex-box-1i posts-list-tablet-2i">';
                    echo Atbs_Widget::bk_listing_posts_2_render($the_query, $widgetMeta);
                    echo '</div>';
                    echo '</div>';
                    break;
                case 'atbs-post-1' :
                    echo '<div class="widget__inner">';
                    echo '<div class="posts-list list-seperated list-space-md posts-list-tablet-2i">';
                    echo Atbs_Widget::bk_listing_posts_1_render($the_query, $widgetMeta);
                    echo '</div>';
                    echo '</div>';
                    break;
                    
                default :
                    echo '<div class="widget__inner">';
                    echo '<div class="posts-list flex-box flex-space-30 flex-box-1i posts-list-tablet-2i">';
                    echo Atbs_Widget::bk_listing_posts_3_render($the_query, $widgetMeta);
                    echo '</div>';
                    echo '</div>';
                    break;
            }
            // if($widget_style == 'boxed') echo '</div>';
		endif;
        ?>
    <?php
        echo '</div><!-- .widget-wrap --></div><!-- End Widget Module-->';
        /* After widget (defined by themes). */
		echo ($after_widget);
        wp_reset_postdata();
	}
	
	/**
	 * update widget settings
	 */
	function update($new_instance, $old_instance) {
		$instance = $old_instance;
        $instance['title']      = $new_instance['title'];
        $instance['heading_style'] = strip_tags($new_instance['heading_style']);
        $instance['widget_module'] = strip_tags($new_instance['widget_module']);
        $instance['widget_style'] = strip_tags($new_instance['widget_style']);
		$instance['entries']    = intval(strip_tags($new_instance['entries']));
        $instance['offset']     = intval(strip_tags($new_instance['offset']));
        $instance['category_id']= strip_tags($new_instance['category_id']);
        $instance['category_ids']= strip_tags($new_instance['category_ids']);
        $instance['tags']       = strip_tags($new_instance['tags']);
        $instance['orderby']    = strip_tags($new_instance['orderby']);
		return $instance;
	}

	/**
	 * Displays the widget settings controls on the widget panel.
	 * Make use of the get_field_id() and get_field_name() function
	 * when creating your form elements. This handles the confusing stuff.
	 */
	function form( $instance ) {
		$defaults = array('title' => 'Posts List', 'heading_style' => 'default', 'widget_module' => 'indexed-posts-a', 'widget_style' => 'normal', 'entries' => 5, 'offset' => 0, 'category_id' => 'all', 'category_ids' => '', 'tags' => '', 'orderby' => 'date');
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
		    <label for="<?php echo esc_attr($this->get_field_id( 'widget_module' )); ?>"><?php esc_attr_e('Widget Module:', 'carbon'); ?></label>
		    <select class="widefat" id="<?php echo esc_attr($this->get_field_id( 'widget_module' )); ?>" name="<?php echo esc_attr($this->get_field_name( 'widget_module' )); echo $this->get_field_name( 'widget_module' ); ?>" >
		    	<option value="atbs-post-1" <?php if( !empty($instance['widget_module']) && $instance['widget_module'] == 'atbs-post-1' ) echo 'selected="selected"'; else echo ""; ?>><?php esc_attr_e('Listing style 1', 'carbon'); ?></option>
		    	<option value="atbs-post-2" <?php if( !empty($instance['widget_module']) && $instance['widget_module'] == 'atbs-post-2' ) echo 'selected="selected"'; else echo ""; ?>><?php esc_attr_e('Listing style 2', 'carbon'); ?></option>
			    <option value="atbs-post-3" <?php if( !empty($instance['widget_module']) && $instance['widget_module'] == 'atbs-post-3' ) echo 'selected="selected"'; else echo ""; ?>><?php esc_attr_e('Listing style 3', 'carbon'); ?></option>
			    <option value="atbs-post-4" <?php if( !empty($instance['widget_module']) && $instance['widget_module'] == 'atbs-post-4' ) echo 'selected="selected"'; else echo ""; ?>><?php esc_attr_e('Listing style 4', 'carbon'); ?></option>
                <option value="atbs-post-5" <?php if( !empty($instance['widget_module']) && $instance['widget_module'] == 'atbs-post-5' ) echo 'selected="selected"'; else echo ""; ?>><?php esc_attr_e('Listing style 5', 'carbon'); ?></option>
            </select>
        </p>
        <p><label for="<?php echo esc_attr($this->get_field_id( 'entries' )); ?>"><strong><?php esc_html_e('[Optional] Number of entries to display: ', 'carbon'); ?></strong></label>
		<input class="widefat" type="text" id="<?php echo esc_attr($this->get_field_id('entries')); ?>" name="<?php echo esc_attr($this->get_field_name('entries')); ?>" value="<?php echo esc_attr($instance['entries']); ?>"/></p>
        <p><label for="<?php echo esc_attr($this->get_field_id( 'offset' )); ?>"><strong><?php esc_html_e('[Optional] Offet Posts number: ', 'carbon'); ?></strong></label>
		<input class="widefat" type="text" id="<?php echo esc_attr($this->get_field_id('offset')); ?>" name="<?php echo esc_attr($this->get_field_name('offset')); ?>" value="<?php echo esc_attr($instance['offset']); ?>" /></p>
        <p>
			<label for="<?php echo esc_attr($this->get_field_id('category_id')); ?>"><strong><?php esc_html_e('Filter by Category: ','carbon');?></strong></label> 
			<select id="<?php echo esc_attr($this->get_field_id('category_id')); ?>" name="<?php echo esc_attr($this->get_field_name('category_id')); ?>" class="widefat categories">
				<option value='all' <?php if ('all' == $instance['category_id']) echo 'selected="selected"'; ?>><?php esc_html_e( 'All Categories', 'carbon'); ?></option>
				<?php $categories = get_categories('hide_empty=1&type=post'); ?>
				<?php foreach($categories as $category) { ?>
				<option value='<?php echo esc_attr($category->term_id); ?>' <?php if ($category->term_id == $instance['category_id']) echo 'selected="selected"'; ?>><?php echo esc_attr($category->cat_name); ?></option>
				<?php } ?>
			</select>
		</p> 
        <p>
		    <label for="<?php echo esc_attr($this->get_field_id( 'category_ids' )); ?>"><?php esc_attr_e('[Optional] Multiple Category: (Separate category ids by the comma. e.g. 1,2):','carbon') ?></label>
		    <input type="text" class="widefat" id="<?php echo esc_attr($this->get_field_id( 'category_ids' )); ?>" name="<?php echo esc_attr($this->get_field_name( 'category_ids' )); ?>" value="<?php if( !empty($instance['category_ids']) ) echo esc_attr($instance['category_ids']); ?>" />
	    </p>
        <p>
		    <label for="<?php echo esc_attr($this->get_field_id( 'tags' )); ?>"><?php esc_attr_e('[Optional] Tags(Separate tags by the comma. e.g. tag1,tag2):','carbon') ?></label>
		    <input type="text" class="widefat" id="<?php echo esc_attr($this->get_field_id( 'tags' )); ?>" name="<?php echo esc_attr($this->get_field_name( 'tags' )); ?>" value="<?php if( !empty($instance['tags']) ) echo esc_attr($instance['tags']); ?>" />
	    </p>
        <p>
		    <label for="<?php echo esc_attr($this->get_field_id( 'orderby' )); ?>"><?php esc_attr_e('Order By:', 'carbon'); ?></label>
		    <select class="widefat" id="<?php echo esc_attr($this->get_field_id( 'orderby' )); ?>" name="<?php echo esc_attr($this->get_field_name( 'orderby' )); ?>" >
			    <option value="date" <?php if( !empty($instance['orderby']) && $instance['orderby'] == 'date' ) echo 'selected="selected"'; else echo ""; ?>><?php esc_attr_e('Latest Posts', 'carbon'); ?></option>
			    <option value="comment_count" <?php if( !empty($instance['orderby']) && $instance['orderby'] == 'comment_count' ) echo 'selected="selected"'; else echo ""; ?>><?php esc_attr_e('Popular Post by Comments', 'carbon'); ?></option>
			    <option value="view_count" <?php if( !empty($instance['orderby']) && $instance['orderby'] == 'view_count' ) echo 'selected="selected"'; else echo ""; ?>><?php esc_attr_e('Popular Post by Views', 'carbon'); ?></option>
                <option value="top_review" <?php if( !empty($instance['orderby']) && $instance['orderby'] == 'top_review' ) echo 'selected="selected"'; else echo ""; ?>><?php esc_attr_e('Best Review', 'carbon'); ?></option>
			    <option value="modified" <?php if( !empty($instance['orderby']) && $instance['orderby'] == 'modified' ) echo 'selected="selected"'; else echo ""; ?>><?php esc_attr_e('Modified', 'carbon'); ?></option>

			    <option value="rand" <?php if( !empty($instance['orderby']) && $instance['orderby'] == 'rand' ) echo 'selected="selected"'; else echo ""; ?>><?php esc_attr_e('Random Post', 'carbon'); ?></option>
			    <option value="alphabetical_asc" <?php if( !empty($instance['orderby']) && $instance['orderby'] == 'alphabetical_asc' ) echo 'selected="selected"'; else echo ""; ?>><?php esc_attr_e('Alphabetical A->Z', 'carbon'); ?></option>
			    <option value="alphabetical_decs" <?php if( !empty($instance['orderby']) && $instance['orderby'] == 'alphabetical_decs' ) echo 'selected="selected"'; else echo ""; ?>><?php esc_attr_e('Alphabetical Z->A', 'carbon'); ?></option>
		    </select>
	    </p>       
<?php
	}
}
?>
