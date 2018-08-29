<?php
	/**
	 * @author			Matthias Reuter
	 * @package			hooks
	 * @copyright		2007-2017 Matthias Reuter
	 * @link			https://straightvisions.com/
	 * @since			1.0
	 * @license			This is no free software. See license.txt or https://straightvisions.com/
	 */
	class sv_ga_manager_hooks extends sv_ga_manager{
		public $core				= NULL;
		
		/**
		 * @desc			Loads other classes of package
		 * @author			Matthias Reuter
		 * @since			1.0
		 * @ignore
		 */
		public function __construct($core){
			$this->core				= isset($core->core) ? $core->core : $core; // loads common classes
		}
		/**
		 * @desc			initialize actions and filters
		 * @return	void
		 * @author			Matthias Reuter
		 * @since			1.0
		 */
		public function init(){
			$this->actions();
			$this->filters();
		}
		/**
		 * @desc			initialize actions
		 * @return	void
		 * @author			Matthias Reuter
		 * @since			1.0
		 */
		public function actions(){
			add_action('admin_menu', array($this->core->settings, 'get_settings_menu'));
			add_action('admin_enqueue_scripts', array($this->core->settings, 'backend_scripts'));
			add_action('wp_enqueue_scripts', array($this->core->settings, 'frontend_scripts'));
		}
		/**
		 * @desc			initialize filters
		 * @return	void
		 * @author			Matthias Reuter
		 * @since			1.0
		 */
		public function filters(){
			add_filter('plugin_action_links', array($this->core->settings,'plugin_action_links'), 10, 5);
		}
	}
?>