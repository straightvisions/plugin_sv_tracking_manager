<?php
	namespace sv_tracking_manager;
	
	class optimize_metabox extends optimize{
		public function __construct(){
		
		}
		public function init(){
			$this->set_section_title('Optimize Metabox');
			
			$this->s['enable_on_page']						= static::$settings->create($this)
																		  ->set_ID('enable_on_page')
																		  ->set_title(__('Enable', $this->get_module_name()))
																		  ->set_description(__('Enable Optimize on this page.', $this->get_module_name()))
																		  ->load_type('checkbox');
			static::$metabox->create($this)
								->set_title('Optimize');
			
			add_action('wp', array($this, 'wp_init'));
		}
		public function wp_init(){
			if(is_front_page()){
				$post			= get_post(get_option('page_on_front'));
			}else{
				global $post;
			}
			
			if(get_post_meta($post->ID, $this->s['enable_on_page']->get_prefix($this->s['enable_on_page']->get_ID()), true)){
				add_action( 'wp_head', array( $this->get_parent(), 'wp_head' ), 991 );
				add_action( 'wp_head', array( $this->get_parent(), 'anti_flicker' ), 0 ); // as early as possible
			}
		}
	}