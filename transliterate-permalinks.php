<?php
/*
Plugin Name: Transliterate Permalinks 
Description: This plugin allows to transliterate non-latin slugs
Author: Lilith Zakharyan
Text Domain: tp
Version: 1.0
*/

define( 'TP_PLUGIN_DIR', plugin_dir_path( __FILE__ ) );

class TP {

	/**
	 * A reference to an instance of this class.
	 */
	private static $instance;

	public $tp_plugin;

	/**
	 * Returns an instance of this class. 
	 */
	public static function get_instance() {

		if ( null == self::$instance ) {
			self::$instance = new TP();
		} 

		return self::$instance;

	} 

	/**
	 * Initializes the plugin by setting filters and administration functions.
	 */
	private function __construct() {

		add_action( 'admin_enqueue_scripts', array( $this, 'scripts' ) );

		require TP_PLUGIN_DIR . 'class-tp-options.php';
		new TP_Options;

		require TP_PLUGIN_DIR . 'class-tp-functions.php';
		$this->tp_plugin = new TP_Plugin;

		if(is_admin()) {
			add_action('wp_ajax_transliterate_all', array($this->tp_plugin, 'transliterate_all'));
		}

		add_action('save_post', array($this, 'check_post_types'), 10, 2);
		$this->hook_taxonomies();

	} 

	function hook_taxonomies() {
		$taxonomies = get_option('tp_options')['taxonomies'];
		if(!$taxonomies) return;
		foreach ($taxonomies as $tax) {
			add_action("saved_" . $tax, array($this, 'transliterate_tax'), 10, 4);
		}
	}

	function transliterate_tax($term_id, $tt_id, $update, $args) {
		$term = get_term($term_id, $tt_id);
		$slug = $this->tp_plugin->transliterate_permalinks($term->slug);
		wp_update_term($term_id, $term->taxonomy, array('slug' => $slug));
	}

	function check_post_types($post_ID, $post) {
		$ptypes = get_option('tp_options')['post_types'];
		if(in_array($post->post_type, $ptypes)) {
			add_filter('sanitize_title', array($this->tp_plugin, 'transliterate_permalinks'));
		}
		//todo taxonomies
	}

	public static function tp_load_textdomain() {
		load_plugin_textdomain( 'tp', false, dirname( plugin_basename( __FILE__ ) ) . '/langutpes' ); 
	}


	function scripts() {
		wp_enqueue_script('tp_script', plugin_dir_url(__FILE__).'assets/tp_script.js', array('jquery'), null, true);

//		wp_enqueue_style('tp_style', plugin_dir_url(__FILE__).'assets/tp_style.css');
	}
} 

add_action( 'plugins_loaded', array( 'tp', 'get_instance' ), 11 );
add_action('plugins_loaded', array('tp', 'tp_load_textdomain'), 10);

