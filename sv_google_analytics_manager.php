<?php
	/*
	Plugin Name: SV Google Analytics Manager
	Plugin URI: https://straightvisions.com/
	Description: Manage Google Analytics
	Version: 1.0.0
	Author: Matthias Reuter
	Author URI: https://straightvisions.com
	Text Domain: sv_qualified_vat_check
	Domain Path: /languages
	*/
	
	namespace sv_google_analytics_manager;
	
	require_once('lib/core/core.php');
	
	class init extends \sv_core\core{
		public function __construct(){
			$this->setup(__NAMESPACE__,__FILE__);
		}
	}
	
	$GLOBALS[__NAMESPACE__]			= new init();