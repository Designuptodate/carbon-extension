<?php
add_action( 'widgets_init', 'atbs_about_load_widget' );

function atbs_about_load_widget() {
	register_widget( 'atbs_about_widget' );
}

class atbs_about_widget extends WP_Widget {

	/**
	 * Widget setup.
	 */
	function __construct() {
		/* Widget settings. */
		$widget_ops = array( 'classname' => 'atbs-carbon-widget carbon-widget-author-1', 'description' => esc_html__('About us/me information.', 'carbon') );

		/* Create the widget. */
		parent::__construct( 'atbs_about_widget', esc_html__('[ATBS] Widget About', 'carbon'), $widget_ops);
	}

	/**
	 * How to display the widget on the screen.
	 */
	function widget( $args, $instance ) {
		extract( $args );

		/* Our variables from the widget settings. */
		$title = apply_filters('widget_title', $instance['title'] );
		$authorID = $instance['author_id'];
        if($authorID == '') {
            return;
        }
		
		/* Before widget (defined by themes). */
		echo $before_widget;

		$bk_author_bio = get_the_author_meta('description', $authorID);
        $bk_author_email = get_the_author_meta('publicemail', $authorID);
        $bk_author_name = get_the_author_meta('display_name', $authorID);
        $bk_author_tw = get_the_author_meta('twitter', $authorID);
        $bk_author_fb = get_the_author_meta('facebook', $authorID);
        $bk_author_yo = get_the_author_meta('youtube', $authorID);
        $bk_author_www = get_the_author_meta('url', $authorID);

		?>
			<div class="widget-wrap">
                <?php 
                $headingStyle = $instance['heading_style'];
		        if($headingStyle) {
		            $headingClass = Atbs_Core::bk_get_widget_heading_class($headingStyle);
		        }else {
		            $headingClass = '';
		        }

		        if ( $title ) { echo Atbs_Widget::bk_get_widget_heading($title, $headingClass); }

		        if($authorID == 0):
		        	$authorID = get_the_author_meta('ID');
		        endif;
                ?>
    			<div class="widget__inner">
	    			<div class="widget__author">
	                    <div class="author__avatar">
	                        <?php echo get_avatar( $authorID, $size = '150', '', $bk_author_name, array('class' => '') );?>     
	                    </div>
	                    <div class="author__text">
	                    	<div class="author__name">
	                        	<a class="entry-author__name" rel="author" title="<?php echo esc_attr($bk_author_name);?>" href="<?php echo esc_url(get_author_posts_url($authorID));?>"><?php echo esc_html($bk_author_name);?></a>
	                        </div>
                            <div class="author__bio">
                                <?php echo esc_html($bk_author_bio); ?>
                            </div>
		                    <div class="author__social">
		    					<ul class="author-social-list social-list social-list--sm list-horizontal">
		    					   <?php
		                            if (($bk_author_email != NULL) || ($bk_author_www != NULL) || ($bk_author_tw != NULL) || ($bk_author_fb != NULL) ||($bk_author_yo != NULL)) {
		                                if ($bk_author_email != NULL) { echo '<li><a href="mailto:'. esc_attr($bk_author_email) .'"><i class="mdicon mdicon-mail_outline"></i><span class="sr-only">e-mail</span></a></li>'; } 
		                                if ($bk_author_www != NULL) { echo ' <li><a href="'. esc_url($bk_author_www) .'" target="_blank"><i class="mdicon mdicon-public"></i><span class="sr-only">Website</span></a></li>'; } 
		                                if ($bk_author_tw != NULL) { echo ' <li><a href="'. esc_url($bk_author_tw).'" target="_blank" ><i class="mdicon mdicon-twitter"></i><span class="sr-only">Twitter</span></a></li>'; } 
		                                if ($bk_author_fb != NULL) { echo ' <li><a href="'. esc_url($bk_author_fb) . '" target="_blank" ><i class="mdicon mdicon-facebook"></i><span class="sr-only">Facebook</span></a></li>'; }
		                                if ($bk_author_yo != NULL) { echo ' <li><a href="http://www.youtube.com/user/'. esc_attr($bk_author_yo) . '" target="_blank" ><i class="mdicon mdicon-youtube"></i><span class="sr-only">Youtube</span></a></li>'; }
		                            }   
		                           ?>	
		                        </ul>
		    				</div>
	                    </div>
	                </div><!-- .widget__author -->
                </div>
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
		$instance['heading_style'] = strip_tags($new_instance['heading_style']);
		$instance['author_id'] = strip_tags( $new_instance['author_id'] );

		return $instance;
	}


	function form( $instance ) {

		/* Set up some default widget settings. */
		$defaults = array( 'title' => 'About Me', 'heading_style' => 'default', 'author_id' => 0);
		$instance = wp_parse_args( (array) $instance, $defaults ); ?>

		<!-- Widget Title: Text Input -->
		<p>
			<label for="<?php echo $this->get_field_id( 'title' ); ?>">Title:</label>
			<input id="<?php echo $this->get_field_id( 'title' ); ?>" name="<?php echo $this->get_field_name( 'title' ); ?>" value="<?php echo $instance['title']; ?>" style="width:96%;" />
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
			<label for="<?php echo $this->get_field_id( 'author_id' ); ?>">Author ID:</label>
			<input id="<?php echo $this->get_field_id( 'author_id' ); ?>" name="<?php echo $this->get_field_name( 'author_id' ); ?>" value="<?php echo $instance['author_id']; ?>" style="width:96%;" />
		</p>


	<?php
	}
}

?>