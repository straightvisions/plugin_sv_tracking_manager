<?php
namespace sv_tracking_manager;

class optimize extends modules{
	public function __construct(){

	}
	public function init(){
		$this->set_section_title('Optimize');
		$this->set_section_desc('see <a href="https://support.google.com/optimize/answer/6211921?hl=en" target=_blank">Set up Optimize</a>');
		$this->set_section_type('settings');

		$this->get_root()->add_section($this);

		$this->s['activate']						= static::$settings->create($this)
			->set_ID('activate')
			->set_title(__('Activate Optimize-Tests', $this->get_module_name()))
			->load_type('checkbox');

		$this->s['ID']				= static::$settings->create($this)
			->set_ID('ID')
			->set_title(__('Optimize Container ID', $this->get_module_name()))
			->set_description(__('see <a href="https://support.google.com/optimize/answer/6211921?hl=en" target="_blank">Set up Optimize</a>', $this->get_module_name()))
			->load_type('text')
			->set_placeholder('GTM-XXXXXX');

		$this->s['activate_anti_flicker']						= static::$settings->create($this)
			->set_ID('activate_anti_flicker')
			->set_title(__('Activate Anti Flicker Script', $this->get_module_name()))
			->load_type('checkbox');

		add_action('wp_head',array($this,'wp_head'), 991);
	}
	public function wp_head(){
		if(
		$this->s['activate']->run_type()->get_data() &&
		strlen($this->s['ID']->run_type()->get_data()) > 0
		) {
			echo '
			<script data-id="' . $this->get_name() . '">
			if (window.ga) {
				ga("require", "' . $this->s['ID']->run_type()->get_data() . '");
			}
			</script>
		';
			$this->anti_flicker();
		}
	}
	public function anti_flicker(){
		if(
			$this->s['activate']->run_type()->get_data() &&
			strlen($this->s['ID']->run_type()->get_data()) > 0 &&
			$this->s['activate_anti_flicker']->run_type()->get_data()
		) {
			echo '
			<style data-id="' . $this->get_name() . '">
				.async-hide { opacity: 0 !important}
			</style>
			<script data-id="' . $this->get_name() . '">
			if (window.ga) {
				(function(a,s,y,n,c,h,i,d,e){s.className+=" "+y;h.start=1*new Date;
				h.end=i=function(){s.className=s.className.replace(RegExp(" ?"+y),"")};
				(a[n]=a[n]||[]).hide=h;setTimeout(function(){i();h.end=null},c);h.timeout=c;
				})(window,document.documentElement,"async-hide","dataLayer",4000,
				{"'.$this->s['ID']->run_type()->get_data().'":true});
			}
			</script>
		';
		}
	}
}