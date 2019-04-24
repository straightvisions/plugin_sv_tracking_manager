<?php
namespace sv_tracking_manager;

class shapepress_dsgvo extends modules{
	private static $cookie = array(
		'name' => 'sp_dsgvo_cn_accepted',
		'value' => 'TRUE'
	);
	
	private static $cookiePopup = array(
		'name' => 'sp_dsgvo_popup',
		'value' => '1'
	);
	
	public function __construct(){

	}
	public function init(){
		add_action('wp_head',array($this,'wp_head_first'), 900);
		add_action('wp_head',array($this,'wp_head_last'), 1100);
		require($this->get_path('lib/modules/shapepress_dsgvo_function_overloading.php'));
	}
	/* Imported Helper Methods */
	private static function cookies_accepted() {
		$noticeAccepted = isset($_COOKIE[self::$cookie['name']]) && strtoupper($_COOKIE[self::$cookie['name']]) === self::$cookie['value'];
		$popupAccepted  = isset($_COOKIE[self::$cookiePopup['name']]) && strtoupper($_COOKIE[self::$cookiePopup['name']]) === self::$cookiePopup['value'];
		
		return apply_filters('cn_is_cookie_accepted', $noticeAccepted || $popupAccepted);
	}
	private function writeGoogleAnalyticsOptOut() {
		// google analytics
		if ( $this->cookies_accepted() || \hasUserGivenPermissionFor( 'google-analytics' ) ) {
			?>
            <script>
				window['ga-disable-<?= \SPDSGVOSettings::get( 'ga_tag_number' ) ?>'] = false;
            </script>
			<?php
		}else{
			?>
            <script>
				window['ga-disable-<?= \SPDSGVOSettings::get( 'ga_tag_number' ) ?>'] = true;
            </script>
			<?php
		}
	}
	/* Helper Methods */
	public function tracking_allowed(){
	    if(class_exists('\SPDSGVOSettings') && \SPDSGVOSettings::get('ga_enable_analytics') === '1'){
			if(\SPDSGVOSettings::get('cn_tracker_init') === 'on_load'){
				if (\hasUserGivenPermissionFor('google-analytics')) {
					return true;
				}
			}
			if (\SPDSGVOSettings::get('cn_tracker_init') === 'after_confirm'
				&& ($this->cookies_accepted() || \hasUserGivenPermissionFor('cookies'))
				&& \hasUserGivenPermissionFor('google-analytics')) {
				return true;
			}
        }
		
		return false;
    }
	public function tracking_allowed_yahoo(){
		if(class_exists('\SPDSGVOSettings')){
			if(\SPDSGVOSettings::get('cn_tracker_init') === 'on_load'){
				if (\hasUserGivenPermissionFor('yahoo')) {
					return true;
				}
			}
			if (\SPDSGVOSettings::get('cn_tracker_init') === 'after_confirm'
				&& ($this->cookies_accepted() || \hasUserGivenPermissionFor('cookies'))
				&& \hasUserGivenPermissionFor('yahoo')) {
				return true;
			}
		}
		
		return false;
	}
	public function tracking_allowed_mouseflow(){
		if(class_exists('\SPDSGVOSettings')){
			if(\SPDSGVOSettings::get('cn_tracker_init') === 'on_load'){
				if (\hasUserGivenPermissionFor('mouseflow')) {
					return true;
				}
			}
			if (\SPDSGVOSettings::get('cn_tracker_init') === 'after_confirm'
				&& ($this->cookies_accepted() || \hasUserGivenPermissionFor('cookies'))
				&& \hasUserGivenPermissionFor('mouseflow')) {
				return true;
			}
		}
		
		return false;
	}
    /* Tracking Codes */
	public function wp_head_first(){
	    //var_dump($this->tracking_allowed()); die('end');
	    if($this->tracking_allowed()) {
			$ga_code = \SPDSGVOSettings::get('ga_code', '');
			if($ga_code == '' || \SPDSGVOSettings::get('own_code') !== '1' && strlen($this->s['tracking_id']->run_type()->get_data()) === 0) {
				$this->writeGoogleAnalyticsOptOut();
				?>
                <script async src='https://www.google-analytics.com/analytics.js'></script>
                <script data-id="<?php echo $this->get_name(); ?>">
					/* <?php echo $this->get_name(); ?> */
					window.ga = window.ga || function () {
						(ga.q = ga.q || []).push(arguments)
					};
					ga.l = +new Date;
					ga('create', '<?php echo \SPDSGVOSettings::get('ga_tag_number'); ?>', 'auto');
					ga('set', 'anonymizeIp', true);
					<?php echo $this->get_parent()->get_user_identification(); ?>
                </script>
				<?php
			}
		}
	}
	public function wp_head_last(){
		if($this->tracking_allowed()) {
			$ga_code = \SPDSGVOSettings::get('ga_code', '');
			if($ga_code == '' || \SPDSGVOSettings::get('own_code') !== '1') {
        ?>
        <script data-id="<?php echo $this->get_name(); ?>">
		/* <?php echo $this->get_name(); ?> */
			if (window.ga) {
				ga('send', 'pageview');
			}
        </script>
        <?php
			}
		}
	}
}