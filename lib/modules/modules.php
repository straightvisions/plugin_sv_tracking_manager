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
			$this->google_analytics->init();
			$this->google_optimize->init();
			$this->bing->init();
			$this->facebook->init();
			$this->hotjar->init();
			$this->linkedin->init();
			$this->mouseflow->init();
			$this->yahoo->init();

			$this->freemius->init();

			/*$this->ec->init();
			$this->shapepress_dsgvo->init(); */
		}
	}
?>