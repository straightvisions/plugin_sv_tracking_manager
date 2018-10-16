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
			<div class="sv_content_descripion">
				<h2><?php echo get_admin_page_title(); ?></h2>
				<p>by <a href="https://straightvisions.com"><img src="<?php echo $this->get_url_lib_core('assets/logo.png'); ?>" /></a></p>
			</div>
			<div></div>
			<?php } ?>
		</div>
    </div>
<?php
	foreach($this->get_root()->get_sections() as $section_name => $section_path) {
		require_once($section_path);
	}
?>
