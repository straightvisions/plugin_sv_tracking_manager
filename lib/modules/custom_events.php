<?php
namespace sv_tracking_manager;

class custom_events extends modules{
	private static $settings					= array();
	
	public function __construct(){
		// Uploaded Fonts
		static::$settings['custom_events']					= static::$settings->create($this);
		static::$settings['custom_events']->set_section_name(__('Custom Events',$this->get_module_name()));
		static::$settings['custom_events']->set_section_description('Set Custom Analytics Elements');
		static::$settings['custom_events']->set_ID('custom_events');
		static::$settings['custom_events']->set_title(__('Custom Events', $this->get_module_name()));
		static::$settings['custom_events']->load_type('group');
		static::$settings['custom_events']->set_loop(true);
		static::$settings['custom_events']->set_callback(array($this,'array'));
		
		$child												= static::$settings['custom_events']->add_child($this);
		$child->set_ID('element');
		$child->set_title(__('DOM Element', $this->get_module_name()));
		$child->load_type('text');
		
		$child												= static::$settings['custom_events']->add_child($this);
		$child->set_ID('eventCategory');
		$child->set_title(__('eventCategory', $this->get_module_name()));
		$child->load_type('text');
		
		$child												= static::$settings['custom_events']->add_child($this);
		$child->set_ID('eventAction');
		$child->set_title(__('eventAction', $this->get_module_name()));
		$child->load_type('text');
		
		$child												= static::$settings['custom_events']->add_child($this);
		$child->set_ID('eventLabel');
		$child->set_title(__('eventLabel', $this->get_module_name()));
		$child->load_type('text');
		
		$child												= static::$settings['custom_events']->add_child($this);
		$child->set_ID('eventValue');
		$child->set_title(__('eventValue', $this->get_module_name()));
		$child->load_type('number');
		
	}
	public function init(){
		add_action('admin_init', array($this, 'admin_init'));
		add_action('init', array($this, 'wp_init'));
	}
	public function admin_init(){
		$this->load_settings();
	}
	public function wp_init(){
		add_action('wp_head', array($this, 'wp_head'));
		add_action('admin_menu', array($this, 'menu'));
		$this->module_enqueue_scripts();
		
		if(!is_admin()){
			$this->load_settings();
		}
	}
	public function menu(){
		add_submenu_page(
			$this->get_prefix(),																	// parent slug
			__('Custom Events', $this->get_module_name()),											// page title
			__('Custom Events', $this->get_module_name()),											// menu title
			'manage_options',																		// capability
			$this->get_prefix(),																	// menu slug
			function(){ require_once($this->get_path('lib/tpl/backend_custom_events.php')); }				// callable function
		);
	}
	public function array(){
	
	}
}