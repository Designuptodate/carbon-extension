<?php
/*
Plugin Name: Carbon Extension
Plugin URI: https://themesific.com/
Description: Carbon Extension Plugin (more functional, widgets, etc.)
Author: Themesific
Version: 1.0.0
Author URI: https://themesific.com/
*/
if (!defined('CARBON_FUNCTIONS_PLUGIN_DIR')) {
    define('CARBON_FUNCTIONS_PLUGIN_DIR', plugin_dir_path( __FILE__ ) );
}
include(CARBON_FUNCTIONS_PLUGIN_DIR."/widgets/widget-about.php");
include(CARBON_FUNCTIONS_PLUGIN_DIR."/widgets/widget-posts-list.php");
include(CARBON_FUNCTIONS_PLUGIN_DIR."/widgets/widget-most-commented.php");
include(CARBON_FUNCTIONS_PLUGIN_DIR."/widgets/widget-review-list.php");
include(CARBON_FUNCTIONS_PLUGIN_DIR."/widgets/widget-social.php");
include(CARBON_FUNCTIONS_PLUGIN_DIR."/widgets/widget-subscribe.php");
include(CARBON_FUNCTIONS_PLUGIN_DIR."/widgets/widget-category-tiles.php");
include(CARBON_FUNCTIONS_PLUGIN_DIR."/widgets/widget-tags.php");
include(CARBON_FUNCTIONS_PLUGIN_DIR."/widgets/widget-social-counters.php");
include(CARBON_FUNCTIONS_PLUGIN_DIR."/widgets/widget-advertisement.php");

function check_activate_plugin() {
    if ( is_plugin_active( 'redux-framework/redux-framework.php' )) {
        deactivate_plugins('redux-framework/redux-framework.php');
    }
    if ( is_plugin_active( 'mb-term-meta/mb-term-meta.php' )) {
        deactivate_plugins('mb-term-meta/mb-term-meta.php');
    }
    if ( is_plugin_active( 'meta-box-conditional-logic/meta-box-conditional-logic.php' )) {
        deactivate_plugins('meta-box-conditional-logic/meta-box-conditional-logic.php');
    }
    if ( is_plugin_active( 'meta-box-group/meta-box-group.php' )) {
        deactivate_plugins('meta-box-group/meta-box-group.php');
    }
    if ( is_plugin_active( 'mb-settings-page/mb-settings-page.php' )) {
        deactivate_plugins('mb-settings-page/mb-settings-page.php');
    }
    if ( is_plugin_active( 'atbs-sidebar-generator/atbs-sidebar-generator.php' )) {
        deactivate_plugins('atbs-sidebar-generator/atbs-sidebar-generator.php');
    }
}
add_action( 'admin_init', 'check_activate_plugin' );

if ( !class_exists( 'ReduxFramework' ) && file_exists( plugin_dir_path( __FILE__ ) . '/redux-framework/redux-core/framework.php' ) ) {
    require_once( plugin_dir_path( __FILE__ ) . '/redux-framework/redux-core/framework.php' );
    //Redux::disable_demo();
}

if ( file_exists( plugin_dir_path( __FILE__ ) . '/mb-term-meta/mb-term-meta.php' ) ) {
    require_once( plugin_dir_path( __FILE__ ) . '/mb-term-meta/mb-term-meta.php' );
}

if ( file_exists( plugin_dir_path( __FILE__ ) . '/meta-box-conditional-logic/meta-box-conditional-logic.php' ) ) {
    require_once( plugin_dir_path( __FILE__ ) . '/meta-box-conditional-logic/meta-box-conditional-logic.php' );
}

if ( file_exists( plugin_dir_path( __FILE__ ) . '/meta-box-group/meta-box-group.php' ) ) {
    require_once( plugin_dir_path( __FILE__ ) . '/meta-box-group/meta-box-group.php' );
}

if ( file_exists( plugin_dir_path( __FILE__ ) . '/mb-settings-page/mb-settings-page.php' ) ) {
    require_once( plugin_dir_path( __FILE__ ) . '/mb-settings-page/mb-settings-page.php' );
}

if ( file_exists( plugin_dir_path( __FILE__ ) . '/atbs-sidebar-generator/atbs-sidebar-generator.php' ) ) {
    require_once( plugin_dir_path( __FILE__ ) . '/atbs-sidebar-generator/atbs-sidebar-generator.php' );
}

require_once( plugin_dir_path( __FILE__ ) . '/carbon-primary-category-select/carbon-primary-category-select.php' );

if ( ! function_exists( 'bk_contact_data' ) ) {  
    function bk_contact_data($contactmethods) {
    
        unset($contactmethods['aim']);
        unset($contactmethods['yim']);
        unset($contactmethods['jabber']);
        $contactmethods['publicemail'] = 'Public Email';
        $contactmethods['twitter'] = 'Twitter URL';
        $contactmethods['facebook'] = 'Facebook URL';
        $contactmethods['youtube'] = 'Youtube URL';
         
        return $contactmethods;
    }
}
add_filter('user_contactmethods', 'bk_contact_data');

/**-------------------------------------------------------------------------------------------------------------------------
 * atbs_carbon_login_page
 */
if ( ! function_exists( 'atbs_carbon_login_page' ) ) {
    function atbs_carbon_login_page() {
        $atbs_option = get_option('atbs_option');

        if (!empty($atbs_option['carbon-login-register-page']) && ($atbs_option['carbon-login-register-page'] == 1)) {
            add_action('login_head',function(){
                get_header();
                echo '
                    <div class="site-content">
                        <div class="container">
                            <div class="atbs-carbon-login-page">
                ';
            });

            add_action('login_footer',function(){
                        echo '</div>';
                    echo '</div>';
                echo '</div>';
                get_footer();
            });
        }
    }
}
/**-------------------------------------------------------------------------------------------------------------------------
 * atbs_extension_single_entry_interaction
 */
if ( ! function_exists( 'atbs_extension_single_entry_interaction' ) ) {
    function atbs_extension_single_entry_interaction($postID) {
       ?>
        <div class="entry-interaction entry-interaction--horizontal">
            <div class="entry-interaction__left">
                <div class="post-sharing post-sharing--simple">
                    <ul>
                        <?php echo Atbs_Single::bk_entry_interaction_share($postID);?>
                    </ul>
                </div>
            </div>
        
            <div class="entry-interaction__right">
                <?php echo Atbs_Single::bk_entry_interaction_comments($postID);?>
            </div>
        </div>
    <?php
    }
}
/**-------------------------------------------------------------------------------------------------------------------------
 * atbs_extension_single_entry_interaction__sticky_share_box
 */
if ( ! function_exists( 'atbs_extension_single_entry_interaction__sticky_share_box' ) ) {
    function atbs_extension_single_entry_interaction__sticky_share_box($postID, $class= '') {
        ?>
        <div class="socials-share-box">
            <?php echo Atbs_Single::carbon_get_share_label();?>
            <ul class="<?php echo esc_html($class);?>">
            <?php echo Atbs_Single::bk_entry_interaction_share_svg($postID, $class); ?>
            </ul>
        </div>
        <?php 
    }
}
/**-------------------------------------------------------------------------------------------------------------------------
 * logen_extension_single_entry_interaction for mobile
 */
if ( ! function_exists( 'atbs_extension_single_entry_share_box' ) ) {
    function atbs_extension_single_entry_share_box($postID, $class= '') {
       ?>
        <div class="single-content-share <?php echo esc_html($class);?> social-share-single-mobile">
            <ul class="social-share text-center">
                <?php echo Atbs_Single::bk_entry_interaction_share($postID);?>
            </ul>
        </div>
    <?php
    }
}
/**-------------------------------------------------------------------------------------------------------------------------
 * logen_extension_single_entry_interaction for desktop
 */
if ( ! function_exists( 'atbs_extension_single_entry_share_box_1' ) ) {
    function atbs_extension_single_entry_share_box_1($postID, $class= '') {
       ?>
        <div class="single-content-share social-share <?php echo esc_html($class);?> social-share-single-desktop">
            <ul class="social-list text-center">
                <?php echo Atbs_Single::bk_entry_interaction_share_svg($postID);?>
            </ul>
        </div>
    <?php
    }
}
// Disables the block editor from managing widgets in the Gutenberg plugin.
add_filter( 'gutenberg_use_widgets_block_editor', '__return_false' );
// Disables the block editor from managing widgets.
add_filter( 'use_widgets_block_editor', '__return_false' );

function carbon_registration_notice__warning() {
    $carbon_validation = get_option( 'carbon_validation');
    $carbon_validationCode = get_option( 'carbon_validateCode');
    if(empty($carbon_validation) && empty($carbon_validationCode)):
    ?>
    <div class="carbon-notice notice notice-warning" style="background-color: lavenderblush; padding: 20px 0 20px 15px;">
        <strong>
			<span style="display: block; margin: 0 0.5em 0 0; clear: both; font-size: 14px;">
				<?php esc_html_e("To get started, please ensure that you've installed the 'Carbon Activate License' plugin by going to Appearance -> Install Plugins.)", "carbon");?>
			</span>
			<span style="display: block; margin: 0.8em 0.5em 0 0; clear: both; font-size: 14px;">
				<?php esc_html_e("Once installed, click on 'Carbon Registration' and enter your purchase code to unlock all the amazing features, receive timely updates, and access our support.", "carbon");?>
			</span>
            <span style="display: block; margin: 0.8em 0.5em 0 0; clear: both; font-size: 14px;">
                <a href="https://help.market.envato.com/hc/en-us/articles/202822600-Where-Is-My-Purchase-Code-" target="_blank"><?php esc_html_e("Instruction to get the purchase code", "carbon");?></a>
            </span>
			<span style="display: block; margin: 0.8em 0.5em 0 0; clear: both; font-size: 14px;">
				<?php esc_html_e("Thank you for choosing Carbon!", "carbon");?>
			</span>
		</strong>
    </div>
    <?php
    endif;
}
add_action( 'admin_notices', 'carbon_registration_notice__warning' );