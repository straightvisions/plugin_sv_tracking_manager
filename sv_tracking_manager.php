<?php
	
	/*
	Plugin Name: SV Tracking Manager
	Description: Manage Tracking Codes
	Version: 1.3.06
	Plugin URI: https://straightvisions.com/
	Author: straightvisions GmbH
	Author URI: https://straightvisions.com
	Text Domain: sv_tracking_manager
	Domain Path: /languages
	*/
	
	namespace sv_tracking_manager;
	
	require_once('lib/core/core.php');
	
	class init extends \sv_core\core{
		const version							= 1306;
		const version_core_match				= 3126;
		
		public function __construct(){
			$this->setup(__NAMESPACE__,__FILE__);
			$this->set_section_title('SV Tracking Manager');
			$this->set_section_desc('Manage Google Analytics');
		}
	}
	
	$GLOBALS[__NAMESPACE__]			= new init();