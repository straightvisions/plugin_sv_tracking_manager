<?php
namespace sv_tracking_manager;

class shapepress_dsgvo extends modules{
	public function __construct(){
		// WP DSGVO BUG FIX
		// if user has accepted default cookies again, we should set cn to acccepted again
		$user_permissions = json_decode($_COOKIE['sp_dsgvo_user_permissions'], true);
		if (isset($user_permissions['cookies']) && $user_permissions['cookies'] == 1){
			setcookie('sp_dsgvo_cn_accepted', 'true', time()+60*60*24*30);
		}
	}

	public function init(){
		add_action('wp_head',array($this,'wp_head_first'), 900);
		add_action('wp_head',array($this,'wp_head_last'), 1100);
		require($this->get_path('lib/modules/shapepress_dsgvo_function_overloading.php'));
	}
    /* Tracking Codes */
	public function wp_head_first(){
		?>
		<script data-id="<?php echo $this->get_name(); ?>_check_cookies_allowed">
		/* <?php echo $this->get_name(); ?>_check_cookies_allowed */
		function <?php echo $this->get_prefix('readCookie'); ?>(name) {
			var cookiename = name + "=";
			var ca = document.cookie.split(';');
			for(var i=0;i < ca.length;i++)
			{
				var c = ca[i];
				while (c.charAt(0)==' ') c = c.substring(1,c.length);
				if (c.indexOf(cookiename) == 0) return c.substring(cookiename.length,c.length);
			}
			return null;
		}

		//  check if cookies are allowed at all
		function <?php echo $this->get_prefix('cookiesAllowed'); ?>() {
			// get cookie
			var cookiesAccepted =
			sv_tracking_manager_modules_shapepress_dsgvo_readCookie('sp_dsgvo_cn_accepted');

			// if this does not exist, allow tracking, but look for user permissions, too
			if(cookiesAccepted === null){
				return true;
			}

			// if this exists and is false, do not track anything.
			// if this exists and is true, allow tracking, but look for user permissions, too
			if(cookiesAccepted == 'true'){
				return true;
			}else{
				return false;
			}
		}

		//  get user permissions for each service
		function <?php echo $this->get_prefix('userPermissions'); ?>(service) {
			if(!sv_tracking_manager_modules_shapepress_dsgvo_cookiesAllowed()){
				// we won't allow tracking if cookies are declined at all
				return false;
			}

			// get cookie
			var userPermissions = JSON.parse(unescape(sv_tracking_manager_modules_shapepress_dsgvo_readCookie('sp_dsgvo_user_permissions')));

			// if userPermissions does not exist, allow tracking
			if(userPermissions === null){
				return true;
			}

			// if service does not exist, allow tracking
			if(userPermissions[service] === null){
				return true;
			}

			// if this exists and is false, do not track anything, except user permissions allow this.
			// if this exists and is true, allow tracking, but look for user permissions, too
			return !!+userPermissions[service]; // convert "1"/"0" to boolean true/false
		}
		</script>
		<?php
			$ga_code = \SPDSGVOSettings::get('ga_code', '');
			if($ga_code == '' || \SPDSGVOSettings::get('own_code') !== '1' && strlen($this->get_parent()->s['tracking_id']->run_type()->get_data()) === 0) {
				//$this->writeGoogleAnalyticsOptOut();
				?>
                <script data-id="<?php echo $this->get_name(); ?>">
					/* <?php echo $this->get_name(); ?> */
					if(sv_tracking_manager_modules_shapepress_dsgvo_userPermissions('google-analytics')){
						document.write('<scr'+'ipt async src="https://www.google-analytics.com/analytics.js"></sc'+'ript>');

						window.ga = window.ga || function () {
							(ga.q = ga.q || []).push(arguments)
						};
						ga.l = +new Date;
						ga('create', '<?php echo \SPDSGVOSettings::get('ga_tag_number'); ?>', 'auto');
						ga('set', 'anonymizeIp', true);
						<?php echo $this->get_parent()->get_user_identification(); ?>
					}
                </script>
				<?php
		}
	}
	public function wp_head_last(){
			$ga_code = \SPDSGVOSettings::get('ga_code', '');
			if($ga_code == '' || \SPDSGVOSettings::get('own_code') !== '1') {
        ?>
        <script data-id="<?php echo $this->get_name(); ?>">
		/* <?php echo $this->get_name(); ?> */
		if(sv_tracking_manager_modules_shapepress_dsgvo_userPermissions('google-analytics') && window.ga) {
			ga('send', 'pageview');
		}
        </script>
        <?php
		}
	}
}