<?php
	namespace sv_tracking_manager;
	
	class gemini extends modules{
		public function __construct(){
		
		}
		public function init(){
			$this->set_section_title('Gemini');
			$this->set_section_desc('see <a href="https://developer.yahoo.com/nativeandsearch/guide/audience-management/dottags/" target=_blank">Yahoo Dot Tags</a>');
			$this->set_section_type('settings');
			
			$this->get_root()->add_section($this);
			
			$this->s['activate']						= static::$settings->create($this)
																		   ->set_ID('activate')
																		   ->set_title(__('Activate Yahoo Gemini Pixel', $this->get_module_name()))
																		   ->load_type('checkbox');
			
			$this->s['pixel_ID']				= static::$settings->create($this)
															 ->set_ID('pixel_ID')
															 ->set_title(__('Pixel ID', $this->get_module_name()))
															 ->set_description(__('see <a href="https://developer.yahoo.com/nativeandsearch/guide/audience-management/dottags/" target="_blank">Yahoo Dot Tags</a>', $this->get_module_name()))
															 ->load_type('text')
															 ->set_placeholder('99999999');
			
			$this->s['project_ID']				= static::$settings->create($this)
															 ->set_ID('project_ID')
															 ->set_title(__('Project ID', $this->get_module_name()))
															 ->set_description(__('see <a href="https://developer.yahoo.com/nativeandsearch/guide/audience-management/dottags/" target="_blank">Yahoo Dot Tags</a>', $this->get_module_name()))
															 ->load_type('text')
															 ->set_placeholder('10000');
			
			add_action( 'wp_head', array( $this, 'wp_head' ), 991 );
		}
		public function wp_head(){
			if(
				$this->s['activate']->run_type()->get_data() &&
				strlen($this->s['pixel_ID']->run_type()->get_data()) > 0 &&
				strlen($this->s['project_ID']->run_type()->get_data()) > 0
			) {
				echo '
			<script data-id="' . $this->get_name() . '">
				/* ' . $this->get_name() . '_gemini */
				(function(w,d,t,r,u){w[u]=w[u]||[];w[u].push({"projectId":"'.$this->s['project_ID']->run_type()->get_data().'","properties":{"pixelId":"'.$this->s['pixel_ID']->run_type()->get_data().'"}});var s=d.createElement(t);s.src=r;s.async=true;s.onload=s.onreadystatechange=function(){var y,rs=this.readyState,c=w[u];if(rs&&rs!="complete"&&rs!="loaded")
				
				{return}
				try{y=YAHOO.ywa.I13N.fireBeacon;w[u]=[];w[u].push=function(p)
				
				{y([p])}
				;y(c)}catch(e){}};var scr=d.getElementsByTagName(t)[0],par=scr.parentNode;par.insertBefore(s,scr)})(window,document,"script","https://s.yimg.com/wi/ytc.js","dotq");
			</script>
		';
			}
		}
	}