<?php
	/**
	 * @author			Matthias Reuter
	 * @package			settings
	 * @copyright		2007-2017 Matthias Reuter
	 * @link			https://straightvisions.com/
	 * @since			1.0
	 * @license			This is no free software. See license.txt or https://straightvisions.com/
	 */
	class sv_ga_manager_settings extends sv_ga_manager{
		public $core									= NULL;
		public $settings_default						= false;
		public $settings								= false;
		
		/**
		 * @desc			Loads other classes of package and defines available settings
		 * @author			Matthias Reuter
		 * @since			1.0
		 * @ignore
		 */
		public function __construct($core){
			$this->core									= isset($core->sv_ga_manager) ? $core->sv_ga_manager : $core; // loads common classes
			$this->name									= $this->core->name;
			
			$this->settings_default						= array(
				$this->name								=> 0,
				'common'								=> array(
					'TRACKING_ID'						=> array(
						'name'							=> __('Tracking ID', 'sv_ga_manager'),
						'type'							=> 'text',
						'placeholder'					=> '',
						'desc'							=> __('You are also able to set Analytics ID via wp-config constant <strong>SV_GA_MANAGER_ANALYTICS_ID</strong>.', 'sv_ga_manager'),
						'value'							=> '',
					),'SCRIPT_URL'						=> array(
						'name'							=> __('Script URL', 'sv_ga_manager'),
						'type'							=> 'text',
						'placeholder'					=> '',
						'desc'							=> __('You can change this Analytics Script URL to something custom, if required.', 'sv_ga_manager'),
						'value'							=> '//www.google-analytics.com/analytics.js',
					)
				),'events'								=> array(
					'CLICK'								=> array(
						'name'							=> __('Click Events', 'sv_ga_manager'),
						'type'							=> 'text',
						'placeholder'					=> '',
						'desc'							=> __('Insert each event in a new line. Event settings are separated via commas, e.g. <strong>elementID,eventCategory,eventLabel</strong>', 'sv_ga_manager'),
						'value'							=> '',
					),'CLICK_JS'						=> array(
						'value'							=> '',
					)
				)
			);
		}
		/**
		 * @desc			initialize settings and set constants for IPBWI API
		 * @return	void
		 * @author			Matthias Reuter
		 * @since			1.0
		 */
		public function init(){
			// update settings
			$this->set_settings();
			
			// get settings
			$this->get_settings();
		}
		/**
		 * @desc			update settings
		 * @return	void
		 * @author			Matthias Reuter
		 * @since			1.0
		 */
		public function set_settings(){
			if(isset($_POST[$this->name])){
				if($_POST[$this->name] == 1){
					$options = get_option($this->name);
					
					if($options && is_array($options)){
						$data						= array_replace_recursive($this->settings_default,$options,$_POST);
						$data						= $this->remove_inactive_checkbox_fields($data);
						$this->settings				= $data;
					}else{
						$data						= array_replace_recursive($this->settings_default,$_POST);
						$data						= $this->remove_inactive_checkbox_fields($data);
						$this->settings				= $data;
					}
					
					// update javascript
					$base_path						= trailingslashit(wp_upload_dir()['basedir']).$this->name.'/';
					$file_path						= $base_path.'sv_analytics.js';
					if(!is_dir($base_path)){
						mkdir($base_path);
					}
					file_put_contents($file_path,''); // empty file first
					
					$tracking_id					= defined('SV_GA_MANAGER_ANALYTICS_ID') ? SV_GA_MANAGER_ANALYTICS_ID : $this->settings['common']['TRACKING_ID']['value'];
					if(strlen($tracking_id) > 0){
file_put_contents($file_path,'
	(function(i,s,o,g,r,a,m){i["GoogleAnalyticsObject"]=r;i[r]=i[r]||function(){
	(i[r].q=i[r].q||[]).push(arguments)},i[r].l=1*new Date();a=s.createElement(o),
	m=s.getElementsByTagName(o)[0];a.async=1;a.src=g;m.parentNode.insertBefore(a,m)
	})(window,document,"script","'.(strlen($this->settings['common']['SCRIPT_URL']['value']) > 1 ? $this->settings['common']['SCRIPT_URL']['value'] : $this->settings_default['common']['SCRIPT_URL']['value']).'","ga");

	ga("create", "'.$tracking_id.'", "'.$_SERVER['HTTP_HOST'].'");
	ga("set", "anonymizeIp", true);
	ga("send", "pageview");

',
FILE_APPEND);
					}
					
					$events							= $this->settings['events']['CLICK']['value'];
					
					if(strlen($events) > 0){
						$events						= explode("\n",$events);
						if(is_array($events) && count($events) > 0){
							file_put_contents($file_path,'jQuery(window).on("load",function(){',FILE_APPEND);
							foreach($events as $event){
								$event_data			= explode(',',$event);
								if(count($event_data) == 3){
file_put_contents($file_path,'
	jQuery("body").on("click", "#'.trim($event_data[0]).'", function(){
		ga("send", "event", "'.trim($event_data[1]).'", "click", "'.trim($event_data[2]).'", "0");
	});

',FILE_APPEND);
								}
							}
							file_put_contents($file_path,'});',FILE_APPEND);
						}
					}
					
					update_option($this->name,$this->settings, true);
				}
			}
		}
		/**
		 * @desc			if checkbox fields are unchecked, update value to 0
		 * @param	int		$data settings data
		 * @return	array	updated settings data
		 * @author			Matthias Reuter
		 * @since			1.0
		 */
		private function remove_inactive_checkbox_fields($data){
			foreach($data as $group_name => $group){
				if(is_array($group)){
					foreach($group as $field_name => $field){
						if(isset($field['type']) && $field['type'] == 'checkbox'){
							$data[$group_name][$field_name]['value'] = (isset($_POST[$group_name][$field_name]['value']) ? 1 : 0);
						}
					}
				}
			}
			return $data;
		}
		/**
		 * @desc			get settings
		 * @return	array	settings array
		 * @author			Matthias Reuter
		 * @since			1.0
		 */
		public function get_settings(){
			if($this->settings){
				return $this->settings;
			}else{
				$this->settings = array_replace_recursive($this->settings_default,(array)get_option($this->name));
				return $this->settings;
			}
		}
		/**
		 * @desc			get default settings
		 * @return	array	default settings
		 * @author			Matthias Reuter
		 * @since			1.0
		 */
		public function get_settings_default(){
			return $this->settings_default;
		}
		/**
		 * @desc			define settings menu
		 * @return	void
		 * @author			Matthias Reuter
		 * @since			1.0
		 */
		public function get_settings_menu(){
			add_menu_page(
				$this->title,																// page title
				$this->title,																// menu title
				'activate_plugins',															// capability
				$this->name,																// menu slug
				function(){ require_once($this->core->path.'lib/tpl/backend.php'); },		// callable function
				$this->core->url.'lib/assets/img/logo_icon.png'								// icon url
			);
		}
		/**
		 * @desc			output the plugin action links
		 * @param	array	$actions default plugin action links
		 * @param	string	$plugin_file plugin's file name
		 * @return	array	updated plugin action links
		 * @author			Matthias Reuter
		 * @since			1.0
		 */
		public function plugin_action_links($actions, $plugin_file){
			if($this->core->basename == $plugin_file){
				$links				= array(
										'settings'				=> '<a href="admin.php?page='.$this->name.'">'.__('Settings', 'sv_ga_manager').'</a>',
										'support'				=> '<a href="https://straightvisions.com/community/" target="_blank">'.__('Support', 'sv_ga_manager').'</a>',
										'documentation'			=> '<a href="https://straightvisions.com/" target="_blank">'.__('Website', 'sv_ga_manager').'</a>',
				);
				$actions			= array_merge($links, $actions);
			}
			return $actions;
		}
		/**
		 * @desc			ACP scripts and styles
		 * @param	string	$hook location in WP Admin
		 * @return	void	
		 * @author			Matthias Reuter
		 * @since			1.0
		 */
		public function backend_scripts($hook){
			if($hook == 'toplevel_page_'.$this->name){
				wp_enqueue_style($this->name, $this->core->url.'lib/assets/css/backend.css');
			}
		}
		public function frontend_scripts(){
			//var_dump(SPDSGVOSettings::get('ga_enable_analytics')); die('end');
			if(!class_exists('SPDSGVOSettings') || SPDSGVOSettings::get('ga_enable_analytics') === '1'){
				$base_path						= trailingslashit(wp_upload_dir()['basedir']).$this->name.'/';
				$file_path						= $base_path.'sv_analytics.js';
				$base_url						= trailingslashit(wp_upload_dir()['baseurl']).$this->name.'/';
				$file_url						= $base_url.'sv_analytics.js';
				
				if(file_exists($file_path)){
					wp_enqueue_script($this->name.'_sv_analytics.js', $file_url, array('jquery'), filemtime($file_path));
				}
			}
		}
	}
?>
