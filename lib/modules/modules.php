<?php
	namespace sv_google_analytics_manager;

	class modules extends init{
		/**
		 * @desc			Loads other classes of package
		 * @author			Matthias Reuter
		 * @since			1.0
		 * @ignore
		 */
		public function __construct(){

		}
		/**
		 * @desc			initialize actions and filters
		 * @return	void
		 * @author			Matthias Reuter
		 * @since			1.0
		 */
		public function init(){
			$this->woocommerce->init();
			
			/*add_action('admin_menu', array($this, 'get_settings_menu'));
			add_action('admin_enqueue_scripts', array($this, 'backend_scripts'));
			add_action('wp_enqueue_scripts', array($this, 'frontend_scripts'));
			add_filter('plugin_action_links', array($this,'plugin_action_links'), 10, 5);*/
			
			// @todo: we will re-add settings once we have a settings class in core
		}
	}
?>