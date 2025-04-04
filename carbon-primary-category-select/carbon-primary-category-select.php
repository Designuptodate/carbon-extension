<?php
/**
 * Plugin Name: Primary Category Meta Box
 * Version: 1.0
 */

// Prevent loading this file directly.
defined( 'ABSPATH' ) || exit;

define( 'PRIMARY_CATEGORY_PLUGIN_URL', plugin_dir_url( __FILE__ ) );

// Enqueue scripts and styles based on the active editor (Classic or Gutenberg).
function carbon_primary_category_field_enqueue_script( $hook ) {
    wp_enqueue_style( 'carbon_primary_category_field_style', PRIMARY_CATEGORY_PLUGIN_URL . 'css/primary-category-field.css', array(), '' );
    // Check if we are on the post editor screen.
    if ( 'post.php' === $hook || 'post-new.php' === $hook ) {
        global $post_type;

        // Check if Gutenberg (Block Editor) is active for the post type.
        if ( use_block_editor_for_post_type( $post_type ) ) {
            // Enqueue the Gutenberg script.
            wp_enqueue_script(
                'carbon-gutenberg-primary-category',
                PRIMARY_CATEGORY_PLUGIN_URL . 'js/gutenberg-editor-primary-category.js',
                array('jquery', 'wp-data', 'wp-edit-post'),
                filemtime(plugin_dir_path(__FILE__) . 'js/gutenberg-editor-primary-category.js'),
                true
            );
        } else {
            // Enqueue the Classic Editor script.
            wp_enqueue_script(
                'carbon-classic-primary-category',
                PRIMARY_CATEGORY_PLUGIN_URL . 'js/classic-editor-primary-category.js',
                array('jquery'),
                filemtime(plugin_dir_path(__FILE__) . 'js/classic-editor-primary-category.js'),
                true
            );
        }
    }
}
add_action( 'admin_enqueue_scripts', 'carbon_primary_category_field_enqueue_script' );

// Register meta field for Gutenberg compatibility.
function carbon_register_post_meta() {
    register_post_meta( 'post', 'carbon_primary_category', array(
        'show_in_rest' => true,
        'type' => 'string',
        'single' => true,
        'sanitize_callback' => 'sanitize_text_field',
        'auth_callback' => function() {
            return current_user_can( 'edit_posts' );
        },
    ) );
}
add_action( 'init', 'carbon_register_post_meta' );

// Add the meta box in the Classic Editor.
add_action( 'add_meta_boxes', 'add_primary_category_metabox' );

// Add the meta box only for the Classic Editor.
function add_primary_category_metabox() {
    if ( ! use_block_editor_for_post_type( get_post_type() ) ) {
        add_meta_box(
            'carbon_primary_category',
            'Primary Category',
            'carbon_primary_category_metabox_callback',
            'post',
            'side',
            'default'
        );
    }
}

// Callback for rendering the meta box.
function carbon_primary_category_metabox_callback( $post ) {
    // Security field for nonce
    wp_nonce_field( 'save_primary_category', 'carbon_primary_category_nonce' );

    // Get the current primary category value
    $selected_primary_category = get_post_meta( $post->ID, 'carbon_primary_category', true );

    // Get post categories
    $post_categories = wp_get_post_categories( $post->ID, array( 'fields' => 'ids' ) );

    // Start dropdown
    echo '<div id="carbon-primary-category-dropdown">';
    echo '<select id="carbon_primary_category_select" name="carbon_primary_category" style="width:100%;">';
    echo '<option value="">' . esc_html__( 'Select a Category', 'carbon' ) . '</option>';

    if ( ! empty( $post_categories ) ) {
        foreach ( $post_categories as $c ) {
            $cat = get_category( $c );
            if ( ! empty( $cat ) && ! is_wp_error( $cat ) ) {
                echo sprintf(
                    '<option value="%s" %s>%s</option>',
                    esc_attr( $cat->term_id ),
                    selected( $selected_primary_category, $cat->term_id, false ),
                    esc_html( $cat->name )
                );
            }
        }
    }

    echo '</select>';
    echo '</div>';
}

// Save meta field when the post is saved.
function save_primary_category_meta( $post_id ) {
    if ( ! isset( $_POST['carbon_primary_category_nonce'] ) || ! wp_verify_nonce( $_POST['carbon_primary_category_nonce'], 'save_primary_category' ) ) {
        return; // Nonce check failed
    }
    if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) {
        return; // Autosave check
    }
    if ( isset( $_POST['post_type'] ) && 'page' === $_POST['post_type'] ) {
        if ( ! current_user_can( 'edit_page', $post_id ) ) {
            return;
        }
    } elseif ( ! current_user_can( 'edit_post', $post_id ) ) {
        return;
    }
    if ( isset( $_POST['carbon_primary_category'] ) ) {
        update_post_meta( $post_id, 'carbon_primary_category', sanitize_text_field( $_POST['carbon_primary_category'] ) );
    }
}
add_action( 'save_post', 'save_primary_category_meta' );
