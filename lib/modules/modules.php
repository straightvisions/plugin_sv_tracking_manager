<?php
	namespace sv_tracking_manager;

	class modules extends init{
		/**
		 * @desc			Loads other classes of package
		 * @author			Matthias Reuter
		 * @since			1.0
		 * @ignore
		 */
		public function __construct(){
			$this->set_section_title('Common Settings');
			$this->set_section_desc('General Basic Settings');
		}
		/**
		 * @desc			initialize actions and filters
		 * @return	void
		 * @author			Matthias Reuter
		 * @since			1.0
		 */
		public function init(){
			$this->ec->init();
			$this->shapepress_dsgvo->init();
			$this->custom_events->init();

			add_action('admin_init', array($this, 'admin_init'));
			add_action('init', array($this, 'wp_init'));
		}
		private function load_settings(){
			$this->get_root()->add_section($this, 'settings');

			// check for tracking code by child available
			$tracking_code						= 'UA-XXXXXX-XXX';
			if(class_exists('\SPDSGVOSettings') && \SPDSGVOSettings::get('ga_enable_analytics') === '1') {
				$ga_code = \SPDSGVOSettings::get('ga_code', '');
				if($ga_code == '' || \SPDSGVOSettings::get('own_code') !== '1') {
					$tracking_code = __('Code retrieved by SPDSGVO-Plugin:',$this->get_root()->get_prefix()).' '.\SPDSGVOSettings::get('ga_tag_number');
				}
			}

			// Uploaded Fonts
			$this->s['tracking_id']					= static::$settings->create($this)
				->set_ID('tracking_id')
				->set_title(__('Tracking ID', $this->get_module_name()))
				->load_type('text')
				->set_placeholder($tracking_code);

			if(strlen($this->s['tracking_id']->run_type()->get_data()) > 0){
				add_action('wp_head',array($this,'wp_head_first'), 900);
				add_action('wp_head',array($this,'wp_head_last'), 1100);
			}
		}
		public function admin_init(){
			$this->load_settings();
		}
		public function wp_init(){
			if(!is_admin()){
				$this->load_settings();
				add_action('wp_head',array($this,'wp_head'), 1000);
			}
		}
		public function wp_head(){

		}
		public function wp_head_first(){
			if(strlen($this->s['tracking_id']->run_type()->get_data()) > 0){
					?>
					<script async src='https://www.google-analytics.com/analytics.js'></script>
					<script data-id="<?php echo $this->get_name(); ?>">
						window.ga = window.ga || function () {
							(ga.q = ga.q || []).push(arguments)
						};
						ga.l = +new Date;
						ga('create', '<?php echo $this->s['tracking_id']->run_type()->get_data(); ?>', 'auto');
						ga('set', 'anonymizeIp', true);
					</script>
					<?php
			}
		}
		public function wp_head_last(){
			if(strlen($this->s['tracking_id']->run_type()->get_data()) > 0) {
				?>
				<script data-id="<?php echo $this->get_name(); ?>">
					if (window.ga) {
						ga('send', 'pageview');
					}
				</script>
				<?php
			}
		}
	}
?>