<?php

/**
 * Register all actions and filters for the plugin.
 *
 * Maintain a list of all hooks that are registered throughout
 * the plugin, and register them with the WordPress API. Call the
 * run function to execute the list of actions and filters.
 *
 * @package    Plugin_Name
 * @subpackage Plugin_Name/includes
 * @author     Your Name <email@example.com>
 */
class Plugin_Name_Loader {

	/**
	 * The array of actions registered with WordPress.
	 *
	 * @since    1.0.0
	 * @access   protected
	 * @var      array $actions The actions registered with WordPress to fire when the plugin loads.
	 */
	protected array $actions;

	/**
	 * The array of filters registered with WordPress.
	 *
	 * @since    1.0.0
	 * @access   protected
	 * @var      array $filters The filters registered with WordPress to fire when the plugin loads.
	 */
	protected array $filters;

	/**
	 * The array of shortcodes registered with WordPress.
	 *
	 * @since    1.0.0
	 * @access   protected
	 * @var      array $shortcodes The shortcodes registered with WordPress to fire when the plugin loads.
	 */
	protected array $shortcodes;

	/**
	 *
	 * @since 1.0.0
	 * @access private
	 * @var Plugin_Name_Loader
	 */
	private static Plugin_Name_Loader $instance;

	/**
	 * Initialize the collections used to maintain the actions and filters.
	 *
	 * @since    1.0.0
	 * @access private
	 */
	private function __construct() {

		$this->actions    = [];
		$this->filters    = [];
		$this->shortcodes = [];

	}

	/**
	 * Add a new action to the collection to be registered with WordPress.
	 *
	 * @param  string  $hook  The name of the WordPress action that is being registered.
	 * @param  object  $component  A reference to the instance of the object on which the action is defined.
	 * @param  string  $callback  The name of the function definition on the $component.
	 * @param  int  $priority  Optional. The priority at which the function should be fired. Default is 10.
	 * @param  int  $accepted_args  Optional. The number of arguments that should be passed to the $callback. Default is 1.
	 *
	 * @since    1.0.0
	 */
	public function add_action( string $hook, object $component, string $callback, int $priority = 10, int $accepted_args = 1 ): void {
		$this->actions = $this->add( $this->actions, $hook, $component, $callback, $priority, $accepted_args );
	}

	/**
	 * Add a new filter to the collection to be registered with WordPress.
	 *
	 * @param  string  $hook  The name of the WordPress filter that is being registered.
	 * @param  object  $component  A reference to the instance of the object on which the filter is defined.
	 * @param  string  $callback  The name of the function definition on the $component.
	 * @param  int  $priority  Optional. The priority at which the function should be fired. Default is 10.
	 * @param  int  $accepted_args  Optional. The number of arguments that should be passed to the $callback. Default is 1
	 *
	 * @since    1.0.0
	 */
	public function add_filter( string $hook, object $component, string $callback, int $priority = 10, int $accepted_args = 1 ): void {
		$this->filters = $this->add( $this->filters, $hook, $component, $callback, $priority, $accepted_args );
	}

	/**
	 * Add a new shortcode to the collection to be registered with WordPress
	 *
	 * @param  string  $tag  The name of the new shortcode.
	 * @param  object  $component  A reference to the instance of the object on which the shortcode is defined.
	 * @param  string  $callback  The name of the function that defines the shortcode.
	 *
	 * @since     1.0.0
	 */
	public function add_shortcode( string $tag, object $component, string $callback, $priority = 10, $accepted_args = 1 ): void {
		$this->shortcodes = $this->add( $this->shortcodes, $tag, $component, $callback, $priority, $accepted_args );
	}

	/**
	 * A utility function that is used to register the actions and hooks into a single
	 * collection.
	 *
	 * @param  array  $hooks  The collection of hooks that is being registered (that is, actions or filters).
	 * @param  string  $hook  The name of the WordPress filter that is being registered.
	 * @param  object  $component  A reference to the instance of the object on which the filter is defined.
	 * @param  string  $callback  The name of the function definition on the $component.
	 * @param  int  $priority  The priority at which the function should be fired.
	 * @param  int  $accepted_args  The number of arguments that should be passed to the $callback.
	 *
	 * @return   array                                  The collection of actions and filters registered with WordPress.
	 * @since    1.0.0
	 * @access   private
	 */
	private function add( array $hooks, string $hook, object $component, string $callback, int $priority, int $accepted_args ): array {
		$hooks[ $this->hook_index( $hook, $component, $callback ) ] = [
			'hook'          => $hook,
			'component'     => $component,
			'callback'      => $callback,
			'priority'      => $priority,
			'accepted_args' => $accepted_args
		];

		return $hooks;

	}

	/**
	 * Remove a hook.
	 *
	 * Hook must have been added by this class for this remover to work.
	 *
	 * Usage Plugin_Name_Loader::get_instance()->remove( $hook, $component, $callback );
	 *
	 * @param  string  $hook  The name of the WordPress filter that is being registered.
	 * @param  object  $component  A reference to the instance of the object on which the filter is defined.
	 * @param  string  $callback  The name of the function definition on the $component.
	 *
	 * @since      1.0.0
	 */
	public function remove( string $hook, object $component, string $callback ): void {
		$index = $this->hook_index( $hook, $component, $callback );
		if ( isset( $this->filters[ $index ] ) ) {
			remove_filter( $this->filters[ $index ]['hook'],
				array( $this->filters[ $index ]['component'], $this->filters[ $index ]['callback'] ) );
		}

		if ( isset( $this->actions[ $index ] ) ) {
			remove_action( $this->filters[ $index ]['hook'],
				array( $this->filters[ $index ]['component'], $this->filters[ $index ]['callback'] ) );
		}
	}

	/**
	 * Utility function for indexing $this->hooks
	 *
	 * @param  string  $hook  The name of the WordPress filter that is being registered.
	 * @param  object  $component  A reference to the instance of the object on which the filter is defined.
	 * @param  string  $callback  The name of the function definition on the $component.
	 *
	 * @return string
	 * @since       1.0.0
	 * @access      protected
	 */
	protected function hook_index( string $hook, object $component, string $callback ): string {
		return md5( $hook . get_class( $component ) . $callback );
	}


	/**
	 * Register the filters and actions with WordPress.
	 *
	 * @since    1.0.0
	 */
	public function run(): void {

		foreach ( $this->filters as $hook ) {
			add_filter( $hook['hook'], array( $hook['component'], $hook['callback'] ), $hook['priority'],
				$hook['accepted_args'] );
		}

		foreach ( $this->actions as $hook ) {
			add_action( $hook['hook'], array( $hook['component'], $hook['callback'] ), $hook['priority'],
				$hook['accepted_args'] );
		}

		foreach ( $this->shortcodes as $hook ) {
			add_shortcode( $hook['hook'], array( $hook['component'], $hook['callback'] ) );
		}

	}

	/**
	 * Get an instance of this class
	 *
	 * @return Plugin_Name_Loader
	 * @since 1.0.0
	 */
	public static function get_instance(): Plugin_Name_Loader {
		if ( is_null( self::$instance ) ) {
			self::$instance = new Plugin_Name_Loader();
		}

		return self::$instance;

	}

}
