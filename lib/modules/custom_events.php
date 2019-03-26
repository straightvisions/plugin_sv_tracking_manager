<?php
namespace sv_tracking_manager;

class custom_events extends modules{
	public function __construct(){
		$this->set_section_title('Custom Events');
		$this->set_section_desc('Set Custom Analytics Elements');
		$this->set_section_type('settings');
	}
	private function load_settings(){
		$this->get_root()->add_section($this);
		
		// Custom Events Groups
		$this->s['custom_events']					= static::$settings->create($this)
			->set_ID('custom_events')
			->set_title(__('Custom Events', $this->get_module_name()))
			->load_type('group');

		$child										= $this->s['custom_events']->run_type()->add_child($this)
			->set_ID('entry_label')
			->set_title(__('Entry Label', $this->get_module_name()))
			->set_description(__('This Label will be used as Entry Title for this Settings Group.', $this->get_module_name()))
			->load_type('text')
			->set_placeholder('Entry #...');

		$child										= $this->s['custom_events']->run_type()->add_child($this)
			->set_ID('event')
			->set_title(__('Event Trigger', $this->get_module_name()))
			->set_description(__('Selected trigger will be monitored for event action, see https://www.w3schools.com/jquery/jquery_events.asp', $this->get_module_name()))
			->load_type('text')
			->set_placeholder('click');

		$child												= $this->s['custom_events']->run_type()->add_child($this)
			->set_ID('element')
			->set_title(__('DOM Element', $this->get_module_name()))
			->set_description(__('DOM Selector (e.g. .contact_form, #submit)', $this->get_module_name()))
			->load_type('text')
			->set_placeholder('html')
			->set_default_value('html');
		
		$child												= $this->s['custom_events']->run_type()->add_child($this)
			 ->set_ID('scroll_percentage')
			 ->set_title(__('Scroll Percentage', $this->get_module_name()))
			 ->set_description(__('Requires Event Trigger set to "scroll". This Event will be triggered once scrolling has reached percentage of the DOM element set above. Use "html" as element if you want to track scroll-status auf the whole page. When no percentage is set, event triggers when element is in view.', $this->get_module_name()))
			->load_type('number')
			->set_min(0)
			->set_max(100);

		$child												= $this->s['custom_events']->run_type()->add_child($this)
			->set_ID('eventCategory')
			->set_title(__('eventCategory', $this->get_module_name()))
			->set_description(__('Typically the object that was interacted with (e.g. "Video")', $this->get_module_name()))
			->load_type('text');

		$child												= $this->s['custom_events']->run_type()->add_child($this)
			->set_ID('eventAction')
			->set_title(__('eventAction', $this->get_module_name()))
			->set_description(__('The type of interaction (e.g. "play")', $this->get_module_name()))
			->load_type('text');

		$child												= $this->s['custom_events']->run_type()->add_child($this)
			->set_ID('eventLabel')
			->set_title(__('eventLabel', $this->get_module_name()))
			->set_description(__('Useful for categorizing events (e.g. "Fall Campaign")', $this->get_module_name()))
			->load_type('text');

		$child												= $this->s['custom_events']->run_type()->add_child($this)
			->set_ID('eventValue')
			->set_title(__('eventValue', $this->get_module_name()))
			->set_description(__('A numeric value associated with the event (e.g. 42)', $this->get_module_name()))
			->load_type('number');
		
		$child												= $this->s['custom_events']->run_type()->add_child($this)
			->set_ID('active_page')
			->set_title(__('Active Page', $this->get_module_name()))
			->set_description(__('Optional, if you do not want to apply this event globally on site, but on a specific page.', $this->get_module_name()))
			->load_type('select_page');
		
		$child												= $this->s['custom_events']->run_type()->add_child($this)
			 ->set_ID('non_interaction')
			 ->set_title(__('Non Interaction', $this->get_module_name()))
			 ->set_description(__('Custom Events will reduce bounce rate in Analytics. Activate this to avoid reducing bounce rate by this event.', $this->get_module_name()))
			 ->load_type('checkbox');
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
				// This custom function checks if the given element is in view
				echo '
				window.onload = function () {
					jQuery.fn.isInView = function() {
					    var win         = jQuery( window );
					    var viewport    = {
					        top : win.scrollTop(),
					        left : win.scrollLeft()
					    };
					    viewport.right  = viewport.left + win.width();
					    viewport.bottom = viewport.top + win.height();
					    
					    var bounds      = this.offset();
					    bounds.right    = bounds.left + this.outerWidth();
					    bounds.bottom   = bounds.top + this.outerHeight();
					    
					    return ( ! ( viewport.right < bounds.left || viewport.left > bounds.right || viewport.bottom < bounds.top || viewport.top > bounds.bottom ) );
					    
					};
					
					
					jQuery.fn.scroll_percentage = function(name) {
						var elementTop = jQuery(this).offset().top;
						var elementHeight = jQuery(this).height();
						var elementBottom = elementHeight;
						
						var windowHeight	= jQuery(window).height();
						var scrollTop		= jQuery(window).scrollTop()-elementTop;
						var elementScroll	= scrollTop+windowHeight;

						var percentage = elementScroll / elementBottom * 100;
						/*
						if(name = ".selector"){
							console.log(name);
							console.log("scrollTop: "+scrollTop);
							console.log("elementScroll: "+elementScroll);
							console.log("elementBottom: "+elementBottom);
							console.log("percentage: "+percentage);
							console.log("###");
						}
						*/
						return Math.round(percentage);
					};
					';
		$events = $this->s['custom_events']->run_type()->get_data();
		if ($events && is_array($events) && count($events) > 0) {
			foreach ($events as $event_id => $event) {
				if(strlen($event['event']) == 0){
					continue;
				}
				if (isset($event['active_page']) && intval($event['active_page']) > 0 && intval($event['active_page']) != get_queried_object_id()) {
					continue;
				}
				
				if (isset($event['non_interaction']) && intval($event['non_interaction']) > 0) {
					$non_interaction			= ', { nonInteraction: true }';
				}else{
					$non_interaction			= '';
				}

				if ( $event['event'] == 'scroll' ) {
					echo 'var '.$this->get_prefix($event_id).' = false';
					
					echo '
					jQuery( document ).on( "scroll", function() {
						if ( window.ga ) {
						';
					
					if ($event['scroll_percentage'] == '' || $event['scroll_percentage'] == '0') {
						echo '
								if( !'.$this->get_prefix($event_id).' && jQuery( "' . $event['element'] . '" ).get(0) && jQuery( "' . $event['element'] . '" ).isInView() ) {
									'.$this->get_prefix($event_id).' = true;
									ga("send", "event", "' . $event['eventCategory'] . '", "' . $event['eventAction'] . '", "' . $event['eventLabel'] . '", ' . ( ( intval( $event['eventValue'] ) > 0 ) ? intval( $event['eventValue'] ) : 0 ) . $non_interaction . ');
								}
						';
					// check for scroll percentage
					}else{
						echo '
								if( !'.$this->get_prefix($event_id).' && jQuery( "' . $event['element'] . '" ).get(0) && jQuery( "' . $event['element'] . '" ).scroll_percentage("'.$event['element'].'") >=  ' . intval($event['scroll_percentage']) . ') {
									'.$this->get_prefix($event_id).' = true;
									ga("send", "event", "' . $event['eventCategory'] . '", "' . $event['eventAction'] . '", "' . $event['eventLabel'] . '", ' . ( ( intval( $event['eventValue'] ) > 0 ) ? intval( $event['eventValue'] ) : 0 ) . $non_interaction . ');
								}
								';
					}
				
					echo '
						}
					});';
				} else {
					echo '
					jQuery(document).on("'.$event['event'].'", "'.$event['element'].'", function(){
						if (window.ga) {
							ga("send", "event", "'.$event['eventCategory'].'", "'.$event['eventAction'].'", "'.$event['eventLabel'].'", '.((intval($event['eventValue']) > 0) ? intval($event['eventValue']) : 0).$non_interaction.');
						}
					});
					';
				}
			}
		}
		//	     // Send data using an event.
		echo '}</script>';
	}
}