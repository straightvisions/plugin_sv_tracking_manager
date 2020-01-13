<?php
namespace sv_tracking_manager;

class facebook extends modules{
	public function __construct(){

	}
	public function init(){
		$this->set_section_title('Facebook');
		$this->set_section_desc('see <a href="https://developers.facebook.com/docs/facebook-pixel/" target=_blank">Facebook-Pixel</a>');
		$this->set_section_type('settings');

		$this->get_root()->add_section($this);

		$this->s['activate']						= static::$settings->create($this)
			->set_ID('activate')
			->set_title(__('Activate Facebook Pixel', $this->get_module_name()))
			->load_type('checkbox');

		$this->s['pixel_ID']				= static::$settings->create($this)
			->set_ID('pixel_ID')
			->set_title(__('Pixel ID', $this->get_module_name()))
			->load_type('text')
			->set_placeholder('99999999');

		add_action('init', array($this, 'wpdsgvo_pixel_id'));

		add_action( 'wp_head', array( $this, 'wp_head' ), 991 );
	}
	public function wpdsgvo_pixel_id(){
		if(class_exists('\SPDSGVOSettings') && \SPDSGVOSettings::get('fb_enable_pixel') === '1') {
			$ga_code = \SPDSGVOSettings::get('fb_pixel_code', '');
			if($ga_code == '' || \SPDSGVOSettings::get('own_code') !== '1') {

				$this->s['pixel_ID']->set_placeholder(__('Code retrieved by Shapepress WP DSGVO-Plugin:',$this->get_root()->get_prefix()).' '.\SPDSGVOSettings::get('fb_pixel_number'))
				->set_disabled(true)
				->set_default_value(\SPDSGVOSettings::get('fb_pixel_number'));
			}
		}
	}
	public function wp_head(){
		$pixel_id = (strlen($this->s['pixel_ID']->run_type()->get_data()) > 0) ? $this->s['pixel_ID']->run_type()->get_data() : $this->s['pixel_ID']->run_type()->get_default_value();

		if(
			$this->s['activate']->run_type()->get_data() &&
			$pixel_id
		) {
			echo '
			<script data-id="' . $this->get_name() . '">
				/* ' . $this->get_name() . ' */
  !function(f,b,e,v,n,t,s)
  {if(f.fbq)return;n=f.fbq=function(){n.callMethod?
  n.callMethod.apply(n,arguments):n.queue.push(arguments)};
  if(!f._fbq)f._fbq=n;n.push=n;n.loaded=!0;n.version="2.0";
  n.queue=[];t=b.createElement(e);t.async=!0;
  t.src=v;s=b.getElementsByTagName(e)[0];
  s.parentNode.insertBefore(t,s)}(window, document,"script",
  "https://connect.facebook.net/en_US/fbevents.js");
  fbq("init", "' . $pixel_id . '");
  fbq("track", "PageView");
			</script>
		';
		}
	}
}