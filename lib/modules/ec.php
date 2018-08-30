<?php
namespace sv_google_analytics_manager;

class ec extends modules{
	private static $product								= false;

	public function __construct(){

	}
	public function init(){
	
	}
	public static function set_product($product){
		if(!static::$product){
			if(is_array($product)){
				static::$product							= $product;
			}else{
				$notice = static::$notices->create();
				$notice->set_title(__('Invalid Produkt for GA Manager', static::get_name()));
				$notice->set_desc_admin(__('Product should be instance of WC_Product - ', static::get_name()) . var_export($product,true));
				$notice->set_state(3);
				die($product);
			}
		}else{
			$notice = static::$notices->create();
			$notice->set_title(__('Product for GA Manager already set', static::get_name()));
			$notice->set_desc_admin(__FUNCTION__ . __(' in GA Manager was called but product was already set', static::get_name()));
			$notice->set_state(3);
		}
	}
	public static function get_product(){
		return static::$product;
	}
	public function product_impression($product=false){
		if(static::get_product()){
			$product									= static::get_product();
		}

		if($product){
			echo '
			ga("ec:addImpression", {						// Provide product details in an impressionFieldObject.
				"id": "'.$product['id'].'",					// Product ID (string).
				"name": "'.$product['name'].'",				// Product name (string).
				"category": "'.$product['category'].'",		// Product category (string).
			});
			';
		}
	}
}