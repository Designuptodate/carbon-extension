<?php
add_action( 'widgets_init', 'bk_register_widget_advertisement' );

function bk_register_widget_advertisement() {
	register_widget( 'atbs_advertisement_widget' );
}

class atbs_advertisement_widget extends WP_Widget {

	/**
	 * Widget setup.
	 */
	function __construct() {
		/* Widget settings. */
		$widget_ops = array( 'classname' => 'atbs-widget widget', 'description' => esc_html__('Displays Advertisement', 'carbon') );

		/* Create the widget. */
		parent::__construct( 'atbs_advertisement_widget', esc_html__('[ATBS] Widget Advertisement', 'carbon'), $widget_ops);
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
			<div class="atbs-carbon-widget atbs-widget-advertisement">
				<div class="widget-wrap">
	                <?php 
	                $headingStyle = $instance['heading_style'];
			        if($headingStyle) {
			            $headingClass = Atbs_Core::bk_get_widget_heading_class($headingStyle);
			        }else {
			            $headingClass = '';
			        }

			        if ( $title ) { echo Atbs_Widget::bk_get_widget_heading($title, $headingClass); }
	                ?>
	    			<div class="widget__inner">
	                    <ul class="advertisement__viewport">
			                <?php 
			                $Number = !empty( $instance['entries'] ) ? $instance['entries'] : 0; 

			                for ( $index = 1; $Number >= $index; $index++) {

			                    $advertisementSRC = 'advertisement_src_'.$index;
			                    $advertisementLink = 'advertisement_link_'.$index;

			                    $valueAdvertisementSRC = $instance[$advertisementSRC] ?? '';
			                    $valueAdvertisementLink = $instance[$advertisementLink] ?? '';

			                    if ( !empty($valueAdvertisementSRC) ):

			                        echo '<li class="advertisement__slide" style="--i: '.$index.'; background-image: url('.$valueAdvertisementSRC.'); " >
			                        <a href="'.$valueAdvertisementLink.'"><div class="advertisement__snapper"></div></a>
			                        </li>';
			                    endif;
			                }

			                ?>

	                    </ul>
	                </div>
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

		$instance['entries']    = intval(strip_tags($new_instance['entries']));

        $Number = $instance['entries'];

        for ( $index = 1; $Number >= $index; $index++) {

            $src = 'advertisement_src_'.$index;
            $instance[$src]    = $new_instance[$src];

            $link = 'advertisement_link_'.$index;
            $instance[$link]    = $new_instance[$link];
        }

		return $instance;
	}


	function form( $instance ) {

		/* Set up some default widget settings. */
		$defaults = array('title' => 'Advertisement', 'heading_style' => 'default', 'entries' => 4);
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
            <label for="<?php echo esc_attr($this->get_field_id( 'entries' )); ?>"><strong><?php esc_html_e('[Optional] Number of entries to display: ', 'carbon'); ?></strong>
            </label>
            <input class="widefat" type="text" id="<?php echo esc_attr($this->get_field_id('entries')); ?>" name="<?php echo esc_attr($this->get_field_name('entries')); ?>" value="<?php echo esc_attr($instance['entries']); ?>"/></p>
        </p>

	    <?php 
            $Number = !empty( $instance['entries'] ) ? $instance['entries'] : 0;
            if( $Number != 0):

                for ( $index = 1; $Number >= $index; $index++) {

                    $advertisementSRC = 'advertisement_src_'.$index;
                    $advertisementLink = 'advertisement_link_'.$index;

                    $valueadvertisementSRC = $instance[$advertisementSRC] ?? '';
                    $valueadvertisementLink = $instance[$advertisementLink] ?? '';

        ?>
                <p class="atbs-field">
                    <input class="widefat" type="text" id="<?php echo esc_attr($this->get_field_id($advertisementSRC)); ?>" name="<?php echo esc_attr($this->get_field_name($advertisementSRC)); ?>" placeholder="Advertisement Image SRC" value="<?php echo esc_attr($valueadvertisementSRC); ?>"/>
                    <input class="widefat" type="text" id="<?php echo esc_attr($this->get_field_id($advertisementLink)); ?>" name="<?php echo esc_attr($this->get_field_name($advertisementLink)); ?>" placeholder="Advertisement Link" value="<?php echo esc_attr($valueadvertisementLink); ?>"/>
                    <button class="atbs-remove" type="button"><?php echo  esc_html_e('Remove','carbon') ?></button>
                </p>
        <?php 
                }
            endif;

        ?>    


	    <script type="text/javascript">        
            var $ = jQuery;
            jQuery(document).ready(function() {

                var buttonRemove = $('.atbs-remove');
                buttonRemove.click(function() {
                    var parentP = $(this).parent('p'); 
                    $(parentP).find('input').val('');
                });  
                       
            });
        </script>

	<?php
	}
}

?>