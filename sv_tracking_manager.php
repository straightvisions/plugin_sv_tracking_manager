<?php
	/*
	Plugin Name: SV Tracking Manager
	Plugin URI: https://straightvisions.com/
	Description: Manage Google Analytics
	Version: 1.0.0
	Author: Matthias Reuter
	Author URI: https://straightvisions.com
	Text Domain: sv_tracking_manager
	Domain Path: /languages
	*/
	
	namespace sv_tracking_manager;
	
	require_once('lib/core/core.php');
	
	class init extends \sv_core\core{
		public function __construct(){
			$this->setup(__NAMESPACE__,__FILE__);
		}
	}
	
	$GLOBALS[__NAMESPACE__]			= new init();