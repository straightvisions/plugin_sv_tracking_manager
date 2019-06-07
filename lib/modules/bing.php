<?php
namespace sv_tracking_manager;

class bing extends modules{
	public function __construct(){

	}
	public function init(){
		$this->set_section_title('Bing');
		$this->set_section_desc('see <a href="https://about.ads.microsoft.com/en-us/resources/training/universal-event-tracking">microsoft.com</a>');
		$this->set_section_type('settings');

		$this->get_root()->add_section($this);

		$this->s['activate']				= static::$settings->create($this)
			->set_ID('activate')
			->set_title(__('Activate Bing UET Tracking', $this->get_module_name()))
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
				if (sv_tracking_manager_modules_shapepress_dsgvo_userPermissions("bing")) {
					(function(w,d,t,r,u){var f,n,i;w[u]=w[u]||[],f=function(){var o={ti:"'.$this->s['pixel_ID']->run_type()->get_data().'"};o.q=w[u],w[u]=new UET(o),w[u].push("pageLoad")},n=d.createElement(t),n.src=r,n.async=1,n.onload=n.onreadystatechange=function(){var s=this.readyState;s&&s!=="loaded"&&s!=="complete"||(f(),n.onload=n.onreadystatechange=null)},i=d.getElementsByTagName(t)[0],i.parentNode.insertBefore(n,i)})(window,document,"script","//bat.bing.com/bat.js","uetq");
				}
			</script>
		';
		}
	}
}