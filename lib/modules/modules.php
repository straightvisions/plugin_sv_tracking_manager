<?php
	namespace sv_tracking_manager;

	class modules extends init{
		/**
		 * @desc			Loads other classes of package
		 * @author			Matthias Reuter
		 * @since			1.0
		 * @ignore
		 */
		public function __construct(){

		}
		/**
		 * @desc			initialize actions and filters
		 * @return	void
		 * @author			Matthias Reuter
		 * @since			1.0
		 */
		public function init(){
			$this->ec->init();
			$this->shapepress_dsgvo->init();
			$this->custom_events->init();
		}
	}
?>