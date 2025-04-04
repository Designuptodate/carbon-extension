<?php
if (!defined('SBGEN_URL')) {
    define('SBGEN_URL', plugin_dir_url( __FILE__ ) );
}
if( ! class_exists('sbgenerator') ){
	
	class sbgenerator{
	
		var $sidebars  = array();
		var $stored    = "";
		var $title	= "";
	    
		// load needed stuff on widget page
		function init(){
			$this->stored	= 'sbgen_sidebars';
			$this->title = esc_html__('Sidebar Generator','carbon');
			add_action('load-widgets.php', array(&$this, 'load_assets') , 5 );
			add_action('widgets_init', array(&$this, 'register_custom_sidebars') , 100 );
			add_action('wp_ajax_sbgen_ajax_delete_custom_sidebar', array(&$this, 'delete_sidebar_area') , 100 );
		}
		
		//load css, js and add hooks to the widget page
		function load_assets(){
			add_action('admin_print_scripts', array(&$this, 'template_add_widget_field') );
			add_action('load-widgets.php', array(&$this, 'add_sidebar_area'), 100);
			
			wp_enqueue_script('sbgen_sidebar' , SBGEN_URL.'assets/js/sbgen-sidebar.js');
			wp_enqueue_style('sbgen_sidebar' , SBGEN_URL.'assets/css/sbgen-sidebar.css');
		}
		
		//widget form template
		function template_add_widget_field(){
			$nonce =  wp_create_nonce ('sbgen-delete-sidebar');
			$nonce = '<input type="hidden" name="sbgen-delete-sidebar" value="'.$nonce.'" />';

			echo "\n<script type='text/html' id='sbgen-add-widget'>";
			echo "\n  <form class='sbgen-add-widget' method='POST'>";
			echo "\n  <h3>". esc_html($this->title) ."</h3>";
			echo "\n    <span class='input_wrap'><input type='text' value='' placeholder = '".esc_html__('Enter Name of the new Widget Area', 'carbon')."' name='sbgen-add-widget' /></span>";
			echo "\n    <input class='button' type='submit' value='".esc_html__('Add Widget Area', 'carbon')."' />";
			echo "\n    ".$nonce;
			echo "\n  </form>";
			echo "\n</script>\n";
		}

		//add sidebar area to the db
		function add_sidebar_area(){
			if(!empty($_POST['sbgen-add-widget'])){
				$this->sidebars = get_option($this->stored);
				$name = $this->get_name($_POST['sbgen-add-widget']);

				if(empty($this->sidebars)){
					$this->sidebars = array($name);
				}
				else{
					$this->sidebars = array_merge($this->sidebars, array($name));
				}

				update_option($this->stored, $this->sidebars);
				wp_redirect( admin_url('widgets.php') );
				die();
			}
		}
		
		//delete sidebar area from the db
		function delete_sidebar_area(){
			check_ajax_referer('sbgen-delete-sidebar');

			if(!empty($_POST['name'])){
				$name = stripslashes($_POST['name']);
				$this->sidebars = get_option($this->stored);

				if(($key = array_search($name, $this->sidebars)) !== false){
					unset($this->sidebars[$key]);
					update_option($this->stored, $this->sidebars);
					echo "sidebar-deleted";
				}
			}

			die();
		}
		
		
		//checks the user submitted name and makes sure that there are no colitions
		function get_name($name){
			if(empty($GLOBALS['wp_registered_sidebars'])) return $name;

			$taken = array();
			foreach ( $GLOBALS['wp_registered_sidebars'] as $sidebar ){
				$taken[] = $sidebar['name'];
			}

			if(empty($this->sidebars)) $this->sidebars = array();
			$taken = array_merge($taken, $this->sidebars);

			if(in_array($name, $taken)){
				$counter  = substr($name, -1);  
				$new_name = "";

				if(!is_numeric($counter)){
					$new_name = $name . " 1";
				}
				else{
					$new_name = substr($name, 0, -1) . ((int) $counter + 1);
				}

				$name = $this->get_name($new_name);
			}

			return $name;
		}
		
		
		
		//register custom sidebar areas
		function register_custom_sidebars(){
            if( class_exists('atbs_core') ){
                $atbs_option = Atbs_Core::bk_get_global_var('atbs_option');
            }else {
                return;
            }
            if(isset($atbs_option['bk-default-widget-heading'])) {
                $headingStyle = $atbs_option['bk-default-widget-heading'];
            }else {
                $headingStyle = '';
            }
            if($headingStyle) {
                $headingClass = Atbs_Core::bk_get_widget_heading_class($headingStyle);
            }else {
                $headingClass = 'block-heading--line';
            }
			if(empty($this->sidebars)) $this->sidebars = get_option($this->stored);

			$args = array(
                'before_widget' => '<div id="%1$s" class="widget %2$s">',
        		'after_widget' => '</div>',
        		'before_title' => '<div class="widget__title block-heading  '.$headingClass.'"><h4 class="widget__title-text">',
        		'after_title' => '</h4></div>',
			);
				
			$args = apply_filters('sbgen_custom_widget_args', $args);

			if(is_array($this->sidebars)){
				foreach ($this->sidebars as $sidebar){	
					$args['name']  = $sidebar;
					$args['id']  = sanitize_title($sidebar);
					$args['class'] = 'sbgen-custom';
					register_sidebar($args);
				}
			}
		}
		
	}
    $loader = new sbgenerator();
	$loader->init();
}