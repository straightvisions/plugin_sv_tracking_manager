<?php
namespace sv_tracking_manager;

class shapepress_dsgvo extends modules{
	public function __construct(){

	}
	public function init(){
		add_action('wp_head',array($this,'wp_head_first'), 900);
		add_action('wp_head',array($this,'wp_head_last'), 1100);
		require($this->get_path_lib_modules('shapepress_dsgvo_function_overloading.php'));
	}
	public function wp_head_first(){
	    if(class_exists('\SPDSGVOSettings') && \SPDSGVOSettings::get('ga_enable_analytics') === '1') {
			$ga_code = \SPDSGVOSettings::get('ga_code', '');
			if($ga_code == '' || \SPDSGVOSettings::get('own_code') !== '1') {
				?>
                <script async src='https://www.google-analytics.com/analytics.js'></script>
                <script data-id="<?php echo $this->get_name(); ?>">
					window.ga = window.ga || function () {
						(ga.q = ga.q || []).push(arguments)
					};
					ga.l = +new Date;
					ga('create', '<?php echo \SPDSGVOSettings::get('ga_tag_number'); ?>', 'auto');
					ga('set', 'anonymizeIp', true);
                </script>
				<?php
			}
		}
	}
	public function wp_head_last(){
        ?>
        <script data-id="<?php echo $this->get_name(); ?>">
			if (window.ga) {
				ga('send', 'pageview');
			}
        </script>
        <?php
	}
}