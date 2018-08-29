<?php
	if(current_user_can('activate_plugins')){
?>
<div id="sv_settings">
	<div id="sv_header">
		<div id="sv_logo"><img src="<?php echo $this->core->url; ?>lib/assets/img/logo.png" /></div>
	</div>
	<div id="sv_thankyou">
		<h2><?php echo $this->core->title; ?></h2>
		<p><?php _e('Manage Google Analytics on your Site', 'sv_ga_manager'); ?></p>
	</div>
	<form action="#" method="post" id="sv_global_settings">
		<h2><?php _e('Common', 'sv_ga_manager'); ?></h2>
		<div class="sv_setting sv_setting_<?php echo $this->settings_default['common']['TRACKING_ID']['type']; ?>">
			<div class="sv_setting_name"><?php echo $this->settings_default['common']['TRACKING_ID']['name']; ?></div>
			<div class="sv_setting_desc"><?php
				if(!defined('SV_GA_MANAGER_ANALYTICS_ID')){
					echo $this->settings_default['common']['TRACKING_ID']['desc'];
				}else{
					echo 'Constant <strong>SV_GA_MANAGER_ANALYTICS_ID</strong> is currently defined in wp-config.';
				}
			?></div>
			<div class="sv_setting_value"><input type="text" name="common[TRACKING_ID][value]" value="<?php echo (defined('SV_GA_MANAGER_ANALYTICS_ID') ? SV_GA_MANAGER_ANALYTICS_ID : $this->settings['common']['TRACKING_ID']['value']); ?>"<?php if(defined('SV_GA_MANAGER_ANALYTICS_ID')){ echo ' disabled="disabled"'; } ?> /></div>
		</div>
		<div class="sv_setting sv_setting_<?php echo $this->settings_default['common']['SCRIPT_URL']['type']; ?>">
			<div class="sv_setting_name"><?php echo $this->settings_default['common']['SCRIPT_URL']['name']; ?></div>
			<div class="sv_setting_desc"><?php echo $this->settings_default['common']['SCRIPT_URL']['desc']; ?></div>
			<div class="sv_setting_value"><input type="text" name="common[SCRIPT_URL][value]" value="<?php echo $this->settings['common']['SCRIPT_URL']['value']; ?>" placeholder="<?php echo $this->settings_default['common']['SCRIPT_URL']['value']; ?>" /></div>
		</div>
		<h2><?php _e('Events', 'sv_ga_manager'); ?></h2>
		<div class="sv_setting sv_setting_<?php echo $this->settings_default['events']['CLICK']['type']; ?>">
			<div class="sv_setting_name"><?php echo $this->settings_default['events']['CLICK']['name']; ?></div>
			<div class="sv_setting_desc"><?php echo $this->settings_default['events']['CLICK']['desc']; ?></div>
			<div class="sv_setting_value"><textarea name="events[CLICK][value]"><?php echo $this->settings['events']['CLICK']['value']; ?></textarea></div>
		</div>
		<input type="hidden" name="<?php echo $this->name; ?>" value="1" />
		<div style="clear:both;"><input type="submit" value="<?php echo _e('Save Settings', 'sv_ga_manager'); ?>" /></div>
	</form>
</div>
<?php
	}
?>