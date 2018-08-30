<?php
namespace sv_google_analytics_manager;

class woocommerce extends modules{
	private static $product								= false;
	
	public function __construct(){

	}
	public function init(){
		add_action('wp_footer',array($this,'product_impression'));
	}
	public static function set_product($product){
		if(!static::$product){
			if($product instanceof \WC_Product){
				static::$product							= $product;
			}else{
				$notice = static::$notices->create();
				$notice->set_title(__('Invalid Produkt for GA Manager', static::get_name()));
				$notice->set_desc_admin(__('Product should be instance of WC_Product - ', static::get_name()) . var_export($product,true));
				$notice->set_state(3);
			}
		}else{
			$notice = static::$notices->create();
			$notice->set_title(__('Product for GA Manager already set', static::get_name()));
			$notice->set_desc_admin(__FUNCTION__.__(' in GA Manager was called but product was already set', static::get_name()));
			$notice->set_state(3);
		}
	}
	public static function get_product(){
		return static::$product;
	}
	public function product_impression($product=false){
		if(is_product()){
			// @todo: get product object
		}elseif(static::get_product()){
			$product									= static::get_product();
		}
		if($product){
			echo '
			ga("ec:addImpression", {					// Provide product details in an impressionFieldObject.
				"id": "P12345",							// Product ID (string).
				"name": "Android Warhol T-Shirt",		// Product name (string).
				"category": "Apparel/T-Shirts",			// Product category (string).
				"brand": "Google",						// Product brand (string).
				"variant": "Black",						// Product variant (string).
				"list": "Search Results",				// Product list (string).
				"position": 1,							// Product position (number).
				"dimension1": "Member"					// Custom dimension (string).
			});
			';
		}
	}
}
?>