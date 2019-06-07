<?php
namespace sv_tracking_manager;

class linkedin extends modules{
	public function __construct(){

	}
	public function init(){
		$this->set_section_title('LinkedIn');
		$this->set_section_desc('see <a href="https://www.linkedin.com/help/linkedin/answer/67595/linkedin-conversion-tracking-ubersicht?lang=en" target=_blank">mouseflow.com</a>');
		$this->set_section_type('settings');

		$this->get_root()->add_section($this);

		$this->s['activate']				= static::$settings->create($this)
			->set_ID('activate')
			->set_title(__('Activate LinkedIn Tracking', $this->get_module_name()))
			->load_type('checkbox');

		$this->s['pixel_ID']				= static::$settings->create($this)
			->set_ID('pixel_ID')
			->set_title(__('Pixel ID', $this->get_module_name()))
			->load_type('text')
			->set_placeholder('00000000');

		add_action( 'wp_head', array( $this, 'wp_head' ), 991 );
	}
	public function wp_head(){
		if(
			$this->s['activate']->run_type()->get_data() &&
			strlen($this->s['pixel_ID']->run_type()->get_data()) > 0
		) {
			echo '
			<script data-id="' . $this->get_name() . '">
				/* ' . $this->get_name() . ' */
				if (sv_tracking_manager_modules_shapepress_dsgvo_userPermissions("linkedin")) {
				_linkedin_partner_id = "' . $this->s['pixel_ID']->run_type()->get_data() . '";window._linkedin_data_partner_ids = window._linkedin_data_partner_ids || [];window._linkedin_data_partner_ids.push(_linkedin_partner_id);(function(){var s = document.getElementsByTagName("script")[0];var b = document.createElement("script");b.type = "text/javascript";b.async = true;b.src = "https://snap.licdn.com/li.lms-analytics/insight.min.js";s.parentNode.insertBefore(b, s);})();
				}
			</script>
		';
		}
	}
}