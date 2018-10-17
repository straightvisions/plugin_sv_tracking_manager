<?php
namespace sv_tracking_manager;

class custom_events extends modules{
	const section_title						= 'Custom Events';
	
	public function __construct(){

	}
	private function load_settings(){
		$this->get_root()->add_section($this, $this->get_path_lib_section('backend', 'tpl', $this->get_module_name().'.php'), 'settings');
		
		// Uploaded Fonts
		$this->s['custom_events']					= static::$settings->create($this);
		$this->s['custom_events']->set_section_name(__('Custom Events',$this->get_module_name()));
		$this->s['custom_events']->set_section_description('Set Custom Analytics Elements');
		$this->s['custom_events']->set_ID('custom_events');
		$this->s['custom_events']->set_title(__('Custom Events', $this->get_module_name()));
		$this->s['custom_events']->load_type('group');
		$this->s['custom_events']->set_loop(true);
		
		$child												= $this->s['custom_events']->run_type()->add_child($this);
		$child->set_ID('event');
		$child->set_title(__('Event Trigger', $this->get_module_name()));
		$child->set_description(__('Selected trigger will be monitored for event action, see https://www.w3schools.com/jquery/jquery_events.asp', $this->get_module_name()));
		$child->load_type('text');
		$child->set_placeholder('click');

		$child												= $this->s['custom_events']->run_type()->add_child($this);
		$child->set_ID('element');
		$child->set_title(__('DOM Element', $this->get_module_name()));
		$child->set_description(__('DOM Selector (e.g. .contact_form, #submit)', $this->get_module_name()));
		$child->load_type('text');

		$child												= $this->s['custom_events']->run_type()->add_child($this);
		$child->set_ID('eventCategory');
		$child->set_title(__('eventCategory', $this->get_module_name()));
		$child->set_description(__('Typically the object that was interacted with (e.g. "Video")', $this->get_module_name()));
		$child->load_type('text');

		$child												= $this->s['custom_events']->run_type()->add_child($this);
		$child->set_ID('eventAction');
		$child->set_title(__('eventAction', $this->get_module_name()));
		$child->set_description(__('The type of interaction (e.g. "play")', $this->get_module_name()));
		$child->load_type('text');

		$child												= $this->s['custom_events']->run_type()->add_child($this);
		$child->set_ID('eventLabel');
		$child->set_title(__('eventLabel', $this->get_module_name()));
		$child->set_description(__('Useful for categorizing events (e.g. "Fall Campaign")', $this->get_module_name()));
		$child->load_type('text');

		$child												= $this->s['custom_events']->run_type()->add_child($this);
		$child->set_ID('eventValue');
		$child->set_title(__('eventValue', $this->get_module_name()));
		$child->set_description(__('A numeric value associated with the event (e.g. 42)', $this->get_module_name()));
		$child->load_type('number');
		
		$child												= $this->s['custom_events']->run_type()->add_child($this);
		$child->set_ID('active_page');
		$child->set_title(__('Active Page', $this->get_module_name()));
		$child->set_description(__('Optional, if you do not want to apply this event globally on site, but on a specific page.', $this->get_module_name()));
		$child->load_type('select_page');
	}
	public function init(){
		add_action('admin_init', array($this, 'admin_init'));
		add_action('init', array($this, 'wp_init'));
	}
	public function admin_init(){
		$this->load_settings();
	}
	public function wp_init(){
		if(!is_admin()){
			$this->load_settings();
			add_action('wp_head',array($this,'wp_head'), 1000);
		}
	}
	public function wp_head()
	{
		echo '<script data-id="' . $this->get_name() . '">';
		$events = $this->s['custom_events']->run_type()->get_data();
		if ($events && is_array($events) && count($events) > 0) {
			foreach ($events as $event) {
				if(strlen($event['event']) == 0){
					continue;
				}
				if (isset($event['active_page']) && intval($event['active_page']) > 0 && intval($event['active_page']) != get_queried_object_id()) {
					continue;
				}
				echo '
				jQuery(document).on("'.$event['event'].'", "'.$event['element'].'", function(){
					if (window.ga) {
						console.log("'.addslashes($event['element'].' / '.$event['event'].' triggered: eventAction: '.$event['eventAction'].' eventLabel: '.$event['eventLabel'].' eventValue: '.$event['eventValue']).'");
						ga("send", "event", "'.$event['eventCategory'].'", "'.$event['eventAction'].'", "'.$event['eventLabel'].'", '.((intval($event['eventValue']) > 0) ? intval($event['eventValue']) : 0).');
					}
				});
				';
			}
		}
		//	     // Send data using an event.
		echo '</script>';
	}
}