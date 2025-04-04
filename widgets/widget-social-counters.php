<?php
/**
 * Add function to widgets_init that'll load our widget.
 */
add_action( 'widgets_init', 'bk_register_widget_social_counters' );

function bk_register_widget_social_counters() {
    register_widget( 'bk_widget_social_counters' );
}

/**
 * This class handles everything that needs to be handled with the widget:
 * the settings, form, display, and update.  Nice!
 *
 */
class bk_widget_social_counters extends WP_Widget {

    /**
     * Widget setup.
     */
    function __construct() {
        /* Widget settings. */
        $widget_ops = array( 'classname' => 'atbs-widget', 'description' => esc_html__('Displays Social Item Counters.', 'carbon') );

        /* Create the widget. */
        parent::__construct( 'bk_widget_social_counters', esc_html__('[ATBS] Widget Social Counters', 'carbon'), $widget_ops);
    }
    
    /**
     *display the widget on the screen.
     */
    function widget( $args, $instance ) {
        extract( $args );
        
        $widget_opts = array();
        $title = $instance['title'];
        $heading_style = $instance['heading_style'];

        $layout_style = $instance['layout_style'];
        
        if(isset($instance['facebook_url']) && $instance['facebook_url']) :      
            $socialItems['facebook']['url']     = $instance['facebook_url'];
        endif;
        if(isset($instance['twitter_url']) && $instance['twitter_url']) :      
            $socialItems['twitter']['url']     = $instance['twitter_url'];
        endif;
        if(isset($instance['youtube_channel']) && $instance['youtube_channel']) :      
            $socialItems['youtube']['url']     = $instance['youtube_channel'];
            $socialItems['youtube']['api']     = isset($instance['youtube_api']) ? $instance['youtube_api'] : '';
        endif;
        if(isset($instance['dribbble_url']) && $instance['dribbble_url']) :      
            $socialItems['dribbble']['url']     = $instance['dribbble_url'];
        endif;
        if(isset($instance['pinterest_url']) && $instance['pinterest_url']) :      
            $socialItems['pinterest']['url']     = $instance['pinterest_url'];
        endif;
        if ( $heading_style ) {
            $heading_class = Atbs_Core::bk_get_widget_heading_class( $heading_style );
        } else {
            $heading_class = '';
        }

        if ( $layout_style ) {
            $layout_style = $layout_style;
        } else {
            $layout_style = 'style-1';
        }
        // Update Database
        if(!empty($socialItems)):
            Atbs_Widget::bk_update_social_json($socialItems);
        endif;
        
        echo ($before_widget);
        
       
        ?>
        <div class="widget atbs-carbon-widget atbs-widget-social-counter <?php echo esc_attr($layout_style) ?>">
            <?php 
                if ( $title ) {
                    echo Atbs_Widget::bk_get_widget_heading( $title, $heading_class );
                }

            ?>
            <div class="widget__inner">
                <ul class="list-unstyled">
                    <?php
                        if ( !empty($socialItems)  ) :
                            foreach ( $socialItems as $socialItem => $socialVal ) :
                                if($layout_style == 'style-2'):
                                    echo Atbs_Widget::bk_socialItem__counters_2_render( $socialItem );
                                elseif($layout_style == 'style-3'):
                                    echo Atbs_Widget::bk_socialItem__counters_3_render( $socialItem );
                                elseif($layout_style == 'style-4'):
                                    echo Atbs_Widget::bk_socialItem__counters_4_render( $socialItem );
                                elseif($layout_style == 'style-5'):
                                    echo Atbs_Widget::bk_socialItem__counters_5_render( $socialItem );
                                elseif($layout_style == 'style-6'):
                                    echo Atbs_Widget::bk_socialItem__counters_6_render( $socialItem );
                                else:
                                    echo Atbs_Widget::bk_socialItem__counters_render( $socialItem );
                                endif;
                            endforeach;
                        endif;
                    ?>
                </ul>
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
        $instance['title']                 = $new_instance['title'];
        $instance['heading_style']         = strip_tags($new_instance['heading_style']);
        $instance['layout_style']          = strip_tags($new_instance['layout_style']);
        $instance['facebook_url']          = strip_tags($new_instance['facebook_url']);
        $instance['twitter_url']           = strip_tags($new_instance['twitter_url']);
        $instance['youtube_channel']= strip_tags($new_instance['youtube_channel']);
        $instance['youtube_api']= strip_tags($new_instance['youtube_api']);
        $instance['dribbble_url']   = strip_tags($new_instance['dribbble_url']);
        $instance['pinterest_url']  = strip_tags($new_instance['pinterest_url']);
        return $instance;
    }

    /**
     * Displays the widget settings controls on the widget panel.
     * Make use of the get_field_id() and get_field_name() function
     * when creating your form elements. This handles the confusing stuff.
     */
    function form( $instance ) {
        $defaults = array(
            'title'             => 'Stay Connected', 
            'heading_style'     => 'default', 
            'layout_style'      => 'style-1', 
            'facebook_url'      => '', 
            'twitter_url'       => '',
                        'youtube_channel'   => '',
                        'youtube_api'       => '',
                        'dribbble_url'      => '',
                        'pinterest_url'     => '',
                        );
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
            <label for="<?php echo esc_attr($this->get_field_id( 'layout_style' )); ?>"><?php esc_attr_e('Layout Style:', 'carbon'); ?></label>
            <select class="widefat" id="<?php echo esc_attr($this->get_field_id( 'layout_style' )); ?>" name="<?php echo esc_attr($this->get_field_name( 'layout_style' )); ?>" >
                <!-- select-item  -->
                <option value="style-1" <?php if( !empty($instance['layout_style']) && $instance['layout_style'] == 'style-1' ) echo 'selected="selected"'; else echo ""; ?>><?php esc_attr_e('Layout Style 1', 'carbon'); ?></option>
                <!-- .select-item  -->
                <option value="style-2" <?php if( !empty($instance['layout_style']) && $instance['layout_style'] == 'style-2' ) echo 'selected="selected"'; else echo ""; ?>><?php esc_attr_e('Layout Style 2', 'carbon'); ?></option>
                <!-- .select-item  -->
                <option value="style-3" <?php if( !empty($instance['layout_style']) && $instance['layout_style'] == 'style-3' ) echo 'selected="selected"'; else echo ""; ?>><?php esc_attr_e('Layout Style 3', 'carbon'); ?></option>
                <!-- .select-item  -->
                <option value="style-4" <?php if( !empty($instance['layout_style']) && $instance['layout_style'] == 'style-4' ) echo 'selected="selected"'; else echo ""; ?>><?php esc_attr_e('Layout Style 4', 'carbon'); ?></option>
                <!-- .select-item  -->
                <option value="style-5" <?php if( !empty($instance['layout_style']) && $instance['layout_style'] == 'style-5' ) echo 'selected="selected"'; else echo ""; ?>><?php esc_attr_e('Layout Style 5', 'carbon'); ?></option>
                <!-- .select-item  -->
                <option value="style-6" <?php if( !empty($instance['layout_style']) && $instance['layout_style'] == 'style-6' ) echo 'selected="selected"'; else echo ""; ?>><?php esc_attr_e('Layout Style 6', 'carbon'); ?></option>
            </select>
        </p>
        
        <p>
            <label for="<?php echo esc_attr($this->get_field_id( 'facebook_url' )); ?>"><strong><?php esc_html_e('Facebook URL:', 'carbon'); ?></strong></label>
            <input type="text" class="widefat" id="<?php echo esc_attr($this->get_field_id('facebook_url')); ?>" name="<?php echo esc_attr($this->get_field_name('facebook_url')); ?>" value="<?php if( !empty($instance['facebook_url']) ) echo esc_attr($instance['facebook_url']); ?>" />
            <i><?php esc_attr_e('eg. https://www.facebook.com/envato','carbon') ?></i>
        </p>
        
        <p>
            <label for="<?php echo esc_attr($this->get_field_id( 'twitter_url' )); ?>"><strong><?php esc_html_e('Twitter URL:', 'carbon'); ?></strong></label>
            <input type="text" class="widefat" id="<?php echo esc_attr($this->get_field_id('twitter_url')); ?>" name="<?php echo esc_attr($this->get_field_name('twitter_url')); ?>" value="<?php if( !empty($instance['twitter_url']) ) echo esc_attr($instance['twitter_url']); ?>" />
            <i><?php esc_attr_e('eg. https://www.twitter.com/envato','carbon') ?></i>
        </p>
        
        <p>
            <label for="<?php echo esc_attr($this->get_field_id( 'youtube_channel' )); ?>"><strong><?php esc_html_e('Youtube Channel ID:', 'carbon'); ?></strong></label>
            <input type="text" class="widefat" id="<?php echo esc_attr($this->get_field_id('youtube_channel')); ?>" name="<?php echo esc_attr($this->get_field_name('youtube_channel')); ?>" value="<?php if( !empty($instance['youtube_channel']) ) echo esc_attr($instance['youtube_channel']); ?>" />
            <i><a href="https://commentpicker.com/youtube-channel-id.php"><?php esc_attr_e('Get your Channel ID by this tool: https://commentpicker.com/youtube-channel-id.php','carbon') ?></a></i>
        </p>
        
        <p>
            <label for="<?php echo esc_attr($this->get_field_id( 'youtube_api' )); ?>"><strong><?php esc_html_e('Youtube API Key:', 'carbon'); ?></strong></label>
            <input type="text" class="widefat" id="<?php echo esc_attr($this->get_field_id('youtube_api')); ?>" name="<?php echo esc_attr($this->get_field_name('youtube_api')); ?>" value="<?php if( !empty($instance['youtube_api']) ) echo esc_attr($instance['youtube_api']); ?>" />
            <i><a href="https://console.developers.google.com/"><?php esc_attr_e('Get the API Key here: https://console.developers.google.com/','carbon') ?></a></i>
        </p>
        
        <p>
            <label for="<?php echo esc_attr($this->get_field_id( 'dribbble_url' )); ?>"><strong><?php esc_html_e('Dribbble URL:', 'carbon'); ?></strong></label>
            <input type="text" class="widefat" id="<?php echo esc_attr($this->get_field_id('dribbble_url')); ?>" name="<?php echo esc_attr($this->get_field_name('dribbble_url')); ?>" value="<?php if( !empty($instance['dribbble_url']) ) echo esc_attr($instance['dribbble_url']); ?>" />
            <i><?php esc_attr_e('eg. https://dribbble.com/envato','carbon') ?></i>
        </p>
        
        <p>
            <label for="<?php echo esc_attr($this->get_field_id( 'pinterest_url' )); ?>"><strong><?php esc_html_e('Pinterest URL:', 'carbon'); ?></strong></label>
            <input type="text" class="widefat" id="<?php echo esc_attr($this->get_field_id('pinterest_url')); ?>" name="<?php echo esc_attr($this->get_field_name('pinterest_url')); ?>" value="<?php if( !empty($instance['pinterest_url']) ) echo esc_attr($instance['pinterest_url']); ?>" />
            <i><?php esc_attr_e('eg. https://pinterest.com/envato','carbon') ?></i>
        </p>
        
<?php
    }
}
?>
