<?php
	namespace sv_tracking_manager;
	
	class freemius extends init {
		public function __construct() {
		
		}
		
		public function init() {
			$this->load_sdk();
		}
		
		public function load_sdk() {
			global $sv_tracking_manager_freemius;
			
			if ( ! isset( $sv100_companion_freemius ) ) {
				$sv100_companion_freemius = fs_dynamic_init( array(
					'id'                  => '4991',
					'slug'                => 'sv-tracking-manager',
					'type'                => 'plugin',
					'public_key'          => 'pk_6f7dcd83527779b3bc94893c86411',
					'is_premium'          => false,
					'has_paid_plans'      => false,
					/*'parent'              => array(
						'id'         => '4082',
						'slug'       => 'sv100-companion',
						'public_key' => 'pk_bb203616096bc726f69ca51a0bbe3',
						'name'       => 'SV100 Companion',
					),*/
					'menu'                => array(
						'slug'           => 'sv_tracking_manager',
						'account'        => false,
						'parent'         => array(
							'slug' => 'straightvisions',
						),
					),
				) );
			}

			do_action( 'sv100_companion_freemius_loaded' );
			
			return $sv100_companion_freemius;
		}
	}