<?php
namespace sv_tracking_manager;

/**
 * @version         1.000
 * @author			straightvisions GmbH
 * @package			sv100
 * @copyright		2019 straightvisions GmbH
 * @link			https://straightvisions.com
 * @since			1.000
 * @license			See license.txt or https://straightvisions.com
 */

class cache extends modules{
	private $base_path					= false;
	private $base_url					= false;

	public function init(){
		$this->base_path				= trailingslashit(trailingslashit(wp_upload_dir()['basedir']).$this->get_root()->get_prefix());
		$this->base_url					= trailingslashit(trailingslashit(wp_upload_dir()['baseurl']).$this->get_root()->get_prefix());
	}
	public function get_base_path(): string{
		return $this->base_path;
	}
	public function get_base_url(): string{
		return $this->base_url;
	}
	public function get_file_path(string $ID): string{
		return $this->get_base_path().md5($ID).'.js';
	}
	public function get_file_url(string $ID): string{
		return $this->get_base_url().md5($ID).'.js';
	}
	public function save(string $ID, string $content){
		if(!is_dir($this->get_base_path())){
			mkdir($this->get_base_path());
		}

		if(file_put_contents($this->get_file_path($ID),$content) !== false){
			return true;
		}else{
			return false;
		}
	}
}