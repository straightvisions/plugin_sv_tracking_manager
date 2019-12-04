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

		if ( ! isset( $sv_tracking_manager_freemius ) ) {
			$sv_tracking_manager_freemius = fs_dynamic_init( array(
				'id'                  => '4993',
				'slug'                => 'sv-tracking-manager',
				'type'                => 'plugin',
				'public_key'          => 'pk_20c9b91b701dbbd82fc28dcb2c576',
				'is_premium'          => false,
				'has_addons'          => false,
				'has_paid_plans'      => false,
				'menu'                => array(
					'slug'           => 'sv_tracking_manager',
					'parent'         => array(
						'slug' => 'straightvisions',
					),
				),
			) );
		}

		do_action( $this->get_root()->get_name().'_freemius_loaded' );

		return $sv_tracking_manager_freemius;
	}
}