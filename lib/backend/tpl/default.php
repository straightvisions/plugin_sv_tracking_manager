<?php if(current_user_can('activate_plugins')){ ?>
    <div class="sv_side_menu">
        <a href="#section_about" class="sv_side_menu_item active">About</a>
		<?php
			$i = 0;
			foreach($this->get_root()->get_sections() as $section_name => $section_path) {
				echo '<a href="#section_' . $section_name . '" class="sv_side_menu_item">' . $section_name . '</a>';
			}
		?>
    </div>
    <div id="section_about" class="sv_content_wrapper">
    <div class="sv_content">
    <h1 class="sv_content_title"><?php _e('About', $this->get_module_name()); ?></h1>
    
        <div class="wrap" id="sv_settings">
            <div id="sv_header">
                <div id="sv_logo"><img src="<?php echo $this->get_url_lib_core('assets/logo.png'); ?>" /></div>
            </div>
            <h1><?php echo get_admin_page_title(); ?></h1>
			<?php
				//echo static::$settings->get_module_settings_form($this);
			?>
        </div>
		<?php } ?>
    </div>
    </div>
<?php
	foreach($this->get_root()->get_sections() as $section_name => $section_path) {
		require_once($section_path);
	}
?>
