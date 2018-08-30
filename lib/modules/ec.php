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
	public function product_list_single($product=false){
		/*if(static::get_product()){
			$product									= static::get_product();
		}
*/
		if($product){
			// @todo: Add price support (make prices to settings)
			echo '
			<script data-id="'.static::get_name().'">
			ga("ec:addImpression", {
				"id": "'.$product['id'].'",
				"name": "'.$product['name'].'",
				"category": "'.$product['category'].'",
				"price": "1290"
			});
			</script>
			';
		}
	}
	public function product_detail_view($product=false){
		if(static::get_product()){
			$product									= static::get_product();
		}

		if($product){
			// @todo: Add price support (make prices to settings)
			echo '
			<script data-id="'.static::get_name().'">
			ga("ec:addProduct", {
				"id": "'.$product['id'].'",
				"name": "'.$product['name'].'",
				"category": "'.$product['category'].'",
				"price": "1290"
			});
			</script>
			';
		}
	}
	public function set_action_detail(){
		echo '
		<script data-id="'.static::get_name().'">
		ga("ec:setAction", "detail");
		</script>
		';
	}
	public function add_to_cart_form(){
		echo '
		<script data-id="'.static::get_name().'">
			$( document ).ready()
			function addToCart(product) {
			  ga("ec:addProduct", {
				"id": product.id,
				"name": product.name,
				"category": product.category,
				"brand": product.brand,
				"variant": product.variant,
				"price": product.price,
				"quantity": product.qty
			  });
			  ga("ec:setAction", "add");
			  ga("send", "event", "UX", "click", "add to cart");     // Send data using an event.
			}
		
		ga("ec:addProduct", {
			"id": "'.$product['id'].'",
			"name": "'.$product['name'].'",
			"category": "'.$product['category'].'",
			"price": "1290"
		});
		</script>
		';
	}
}