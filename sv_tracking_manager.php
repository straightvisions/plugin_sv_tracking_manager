<?php
	/*
	Plugin Name: SV Tracking Manager
	Plugin URI: https://straightvisions.com/
	Description: Manage Google Analytics
	Version: 1.0.1
	Author: Matthias Reuter
	Author URI: https://straightvisions.com
	Text Domain: sv_tracking_manager
	Domain Path: /languages
	*/
	
	namespace sv_tracking_manager;
	
	require_once('lib/core/core.php');
	
	class init extends \sv_core\core{
		const version							= 1001;
		const version_core_match				= 1003;
		const section_title						= 'SV Tracking Manager';
		const section_desc						= 'Manage Google Analytics';
		
		public function __construct(){
			$this->setup(__NAMESPACE__,__FILE__);
		}
	}
	
	$GLOBALS[__NAMESPACE__]			= new init();