<?php
namespace sv_tracking_manager;

class hotjar extends modules{
	public function __construct(){

	}
	public function init(){
		$this->set_section_title('Hotjar');
		$this->set_section_desc('see <a href="https://help.hotjar.com/hc/en-us/articles/115009336727-How-to-Install-your-Hotjar-Tracking-Code" target=_blank">hotjar.com</a>');
		$this->set_section_type('settings');

		$this->get_root()->add_section($this);

		$this->s['activate']				= static::$settings->create($this)
			->set_ID('activate')
			->set_title(__('Activate Hotjar Tracking', $this->get_module_name()))
			->load_type('checkbox');

		$this->s['hotjar_ID']				= static::$settings->create($this)
			->set_ID('hotjar_ID')
			->set_title(__('Hotjar ID', $this->get_module_name()))
			->load_type('number')
			->set_placeholder('000000');

		add_action( 'wp_head', array( $this, 'wp_head' ), 991 );
	}
	public function wp_head(){
		if(
			$this->s['activate']->run_type()->get_data() &&
			strlen($this->s['hotjar_ID']->run_type()->get_data()) > 0
		) {
			echo '
			<script data-id="' . $this->get_name() . '">
				/* ' . $this->get_name() . ' */
				    (function(h,o,t,j,a,r){
        h.hj=h.hj||function(){(h.hj.q=h.hj.q||[]).push(arguments)};
        h._hjSettings={hjid:' . $this->s['hotjar_ID']->run_type()->get_data() . ',hjsv:5};
        a=o.getElementsByTagName(\'head\')[0];
        r=o.createElement(\'script\');r.async=1;
        r.src=t+h._hjSettings.hjid+j+h._hjSettings.hjsv;
        a.appendChild(r);
    })(window,document,\'//static.hotjar.com/c/hotjar-\',\'.js?sv=\');
			</script>
		';
		}
	}
}