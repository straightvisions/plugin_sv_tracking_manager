<?php
namespace sv_tracking_manager;

class custom_events extends modules{
	private static $s					= array();
	
	public function __construct(){

	}
	private function load_settings(){
		// Uploaded Fonts
		static::$s['custom_events']					= static::$settings->create($this);
		static::$s['custom_events']->set_section_name(__('Custom Events',$this->get_module_name()));
		static::$s['custom_events']->set_section_description('Set Custom Analytics Elements');
		static::$s['custom_events']->set_ID('custom_events');
		static::$s['custom_events']->set_title(__('Custom Events', $this->get_module_name()));
		static::$s['custom_events']->load_type('group');
		static::$s['custom_events']->set_loop(true);
		//static::$settings['custom_events']->set_callback(array($this,'array'));

		$child												= static::$s['custom_events']->run_type()->add_child($this);
		$child->set_ID('element');
		$child->set_title(__('DOM Element', $this->get_module_name()));
		$child->set_description(__('DOM Selector (e.g. .contact_form, #submit)', $this->get_module_name()));
		$child->load_type('text');

		$child												= static::$s['custom_events']->run_type()->add_child($this);
		$child->set_ID('eventCategory');
		$child->set_title(__('eventCategory', $this->get_module_name()));
		$child->set_description(__('Typically the object that was interacted with (e.g. "Video")', $this->get_module_name()));
		$child->load_type('text');

		$child												= static::$s['custom_events']->run_type()->add_child($this);
		$child->set_ID('eventAction');
		$child->set_title(__('eventAction', $this->get_module_name()));
		$child->set_description(__('The type of interaction (e.g. "play")', $this->get_module_name()));
		$child->load_type('text');

		$child												= static::$s['custom_events']->run_type()->add_child($this);
		$child->set_ID('eventLabel');
		$child->set_title(__('eventLabel', $this->get_module_name()));
		$child->set_description(__('Useful for categorizing events (e.g. "Fall Campaign")', $this->get_module_name()));
		$child->load_type('text');

		$child												= static::$s['custom_events']->run_type()->add_child($this);
		$child->set_ID('eventValue');
		$child->set_title(__('eventValue', $this->get_module_name()));
		$child->set_description(__('A numeric value associated with the event (e.g. 42)', $this->get_module_name()));
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
		add_action('admin_menu', array($this, 'menu'));
		
		if(!is_admin()){
			$this->load_settings();
		}
	}
	public function menu(){
		add_menu_page(
			'SV Tracking Manager',
			'SV Tracking Manager',
			'manage_options',
			$this->get_prefix(),
			'',
			$this->get_root()->get_url_lib_core('assets/logo_icon.png'),
			2
		);
		add_submenu_page(
			$this->get_prefix(),																	// parent slug
			__('Custom Events', $this->get_module_name()),											// page title
			__('Custom Events', $this->get_module_name()),											// menu title
			'manage_options',																		// capability
			$this->get_prefix(),																	// menu slug
			function(){ require_once($this->get_root()->get_path_lib_section('tpl','backend','custom_events.php')); }				// callable function
		);
	}
	public function array(){
	
	}
}