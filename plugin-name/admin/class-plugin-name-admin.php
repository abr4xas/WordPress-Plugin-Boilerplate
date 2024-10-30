<?php

/**
 * The admin-specific functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the admin-specific stylesheet and JavaScript.
 *
 * @package    Plugin_Name
 * @subpackage Plugin_Name/admin
 * @author     Your Name <email@example.com>
 */
class Plugin_Name_Admin {

	/**
	 * The ID of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string $plugin_name The ID of this plugin.
	 */
	private string $plugin_name;

	/**
	 * The version of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string $version The current version of this plugin.
	 */
	private string $version;

	/**
	 * Initialize the class and set its properties.
	 *
	 * @param  string  $plugin_name  The name of this plugin.
	 * @param  string  $version  The version of this plugin.
	 *
	 * @since    1.0.0
	 */
	public function __construct( string $plugin_name, string $version ) {

		$this->plugin_name = $plugin_name;
		$this->version     = $version;

	}

	/**
	 * Register the stylesheets for the admin area.
	 *
	 *  You can use the $hook parameter to filter for a particular page,
	 *  for more information see the codex,
	 * @link https://codex.wordpress.org/Plugin_API/Action_Reference/admin_enqueue_scripts
	 *
	 * @param  String  $hook  A screen id to filter the current admin page
	 *
	 * @since    1.0.0
	 */
	public function enqueue_styles( string $hook ): void {

		wp_enqueue_style(
			$this->plugin_name,
			plugin_dir_url( __FILE__ ) . 'css/plugin-name-admin.css',
			[],
			$this->version,
		);

	}

	/**
	 * Register the JavaScript for the admin area.
	 *
	 *   You can use the $hook parameter to filter for a particular page,
	 *   for more information see the codex,
	 * @link https://codex.wordpress.org/Plugin_API/Action_Reference/admin_enqueue_scripts
	 *
	 * @param  String  $hook  A screen id to filter the current admin page
	 *
	 * @since    1.0.0
	 */
	public function enqueue_scripts( string $hook ): void {

		wp_enqueue_script(
			$this->plugin_name,
			plugin_dir_url( __FILE__ ) . 'js/plugin-name-admin.js',
			[ 'jquery' ],
			$this->version,
			false
		);

	}

	/**
	 * Example daily event.
	 */
	public function run_daily_event() {

		// do something every day
	}
}
