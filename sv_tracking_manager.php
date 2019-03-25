<?php
	/*
	Plugin Name: SV Tracking Manager
	Plugin URI: https://straightvisions.com/
	Description: Manage Google Analytics
	Version: 3.0.0
	Author: Matthias Reuter
	Author URI: https://straightvisions.com
	Text Domain: sv_tracking_manager
	Domain Path: /languages
	*/
	
	namespace sv_tracking_manager;
	
	require_once('lib/core/core.php');
	
	class init extends \sv_core\core{
		const version							= 3001;
		const version_core_match				= 3014;
		
		public function __construct(){
			$this->setup(__NAMESPACE__,__FILE__);
			$this->set_section_title('SV Tracking Manager');
			$this->set_section_desc('Manage Google Analytics');
		}
	}
	
	$GLOBALS[__NAMESPACE__]			= new init();