<?php
namespace sv_tracking_manager;

class shapepress_dsgvo extends modules{
	public function __construct(){

	}
	public function init(){
		add_action('wp_head',array($this,'wp_head_first'), 900);
		add_action('wp_head',array($this,'wp_head_last'), 1100);
		require($this->get_root()->get_path_lib_modules('shapepress_dsgvo_function_overloading.php'));
	}
	public function wp_head_first(){
		?>
		<script async src='https://www.google-analytics.com/analytics.js'></script>
		<script data-id="<?php echo $this->get_name(); ?>">
			window.ga=window.ga||function(){(ga.q=ga.q||[]).push(arguments)};ga.l=+new Date;
			ga('create', '<?php echo \SPDSGVOSettings::get('ga_tag_number'); ?>', 'auto');
			ga('set', 'anonymizeIp', true);
		</script>
		<?php
	}
	public function wp_head_last(){
		?>
		<script data-id="<?php echo $this->get_name(); ?>">
			ga('send', 'pageview');
		</script>
		<?php
	}
}