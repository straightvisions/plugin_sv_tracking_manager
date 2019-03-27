<?php
	namespace sv_tracking_manager;
	
	class mouseflow extends modules{
		public function __construct(){
		
		}
		public function init(){
			$this->set_section_title('Mouseflow');
			$this->set_section_desc('see <a href="https://mouseflow.com" target=_blank">mouseflow.com</a>');
			$this->set_section_type('settings');
			
			$this->get_root()->add_section($this);
			
			$this->s['activate']				= static::$settings->create($this)
																	 ->set_ID('activate')
																	 ->set_title(__('Activate Mouseflow Tracking', $this->get_module_name()))
																	 ->load_type('checkbox');
			
			$this->s['project_ID']				= static::$settings->create($this)
																	 ->set_ID('project_ID')
																	 ->set_title(__('Project ID', $this->get_module_name()))
																	 ->load_type('text')
																	 ->set_placeholder('00000000-0000-0000-0000-000000000000');
			
			add_action( 'wp_head', array( $this, 'wp_head' ), 991 );
		}
		public function wp_head(){
			if(
				$this->s['activate']->run_type()->get_data() &&
				strlen($this->s['project_ID']->run_type()->get_data()) > 0
			) {
				echo '
			<script data-id="' . $this->get_name() . '">
				/* ' . $this->get_name() . '_mouseflow */
window._mfq = window._mfq || [];
(function() {
var mf = document.createElement("script");
mf.type = "text/javascript"; mf.async = true;
mf.src = "//cdn.mouseflow.com/projects/'.$this->s['project_ID']->run_type()->get_data().'.js";
document.getElementsByTagName("head")[0].appendChild(mf);
})();
			</script>
		';
			}
		}
	}