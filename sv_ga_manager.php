<?php
	/*
	Plugin Name: SV GA Manager
	Plugin URI: https://straightvisions.com/
	Description: Manage Google Analytics
	Version: 1.0.0
	Author: Matthias Reuter
	Author URI: https://straightvisions.com
	*/

	class sv_ga_manager{
		public $path				= false;
		public $basename			= false;
		public $url					= false;
		public $version				= false;
		public $title				= 'SV GA Manager';
		public $name				= false;
		/**
		 * @desc			Load's requested libraries dynamicly
		 * @param	string	$name library-name
		 * @return			class object of the requested library
		 * @author			Matthias Reuter
		 * @since			1.0
		 * @ignore
		 */
		public function __get($name){
			if(file_exists($this->path.'lib/modules/'.$name.'.php')){
				require_once($this->path.'lib/modules/'.$name.'.php');
				$classname			= 'sv_ga_manager_'.$name;
				$this->$name		= new $classname($this);
				return $this->$name;
			}else{
				throw new Exception('Class '.$name.' could not be loaded (tried to load class-file '.$this->path.'lib/'.$name.'.php'.')');
			}
		}
		/**
		 * @desc			initialize plugin
		 * @author			Matthias Reuter
		 * @since			1.0
		 * @ignore
		 */
		public function __construct(){
			$this->path				= WP_PLUGIN_DIR.'/'.dirname(plugin_basename(__FILE__)).'/';
			$this->basename			= plugin_basename(__FILE__);
			$this->url				= plugins_url('' , __FILE__).'/';
			$this->version			= 1000;
			$this->name				= get_class($this);
			
			// language settings
			load_textdomain('sv_ga_manager', WP_LANG_DIR.'/plugins/sv_ga_manager-'.apply_filters('plugin_locale', get_locale(), 'sv_ga_manager').'.mo');
			load_plugin_textdomain('sv_ga_manager', false, dirname(plugin_basename(__FILE__)).'/lib/assets/lang/');
			
			$this->settings->init();							// load settings
			$this->hooks->init();								// load hooks
		}
	}
	
	$GLOBALS['sv_ga_manager']		= new sv_ga_manager();
?>