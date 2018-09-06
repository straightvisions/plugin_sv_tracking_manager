<?php
namespace sv_tracking_manager;

class ec extends modules{
	private static $product								= false;

	public function __construct(){

	}
	public function init(){
		$this->ec_woocommerce->init();
		add_action('wp_head',array($this,'wp_head'), 990);
	}
	public function wp_head(){
		echo '
			<script data-id="'.$this->get_name().'">
			ga("require", "ec");
			function addToCart(product) {
				ga("send", "event", "Checkout", "Add To Cart", "", ' . (product . price * product . qty) . ');     // Send data using an event.
				
				ga("ec:setAction", "add");
				ga("ec:addProduct", {
					"id": product.id,
					"name": product.name,
					"category": product.category,
					"brand": product.brand,
					"variant": product.variant,
					"price": product.price,
					"quantity": product.qty
				});
			}
			</script>
		';
	}
	public function set_product($product){
		if(!static::$product){
			if(is_array($product)){
				static::$product							= $product;
			}else{
				$notice = static::$notices->create();
				$notice->set_title(__('Invalid Produkt for GA Manager', $this->get_name()));
				$notice->set_desc_admin(__('Product should be instance of WC_Product - ', $this->get_name()) . var_export($product,true));
				$notice->set_state(3);
				die($product);
			}
		}else{
			$notice = static::$notices->create();
			$notice->set_title(__('Product for GA Manager already set', $this->get_name()));
			$notice->set_desc_admin(__FUNCTION__ . __(' in GA Manager was called but product was already set', $this->get_name()));
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
			<script data-id="'.$this->get_name().'">
			ga("ec:addImpression", {
				"id": "'.$product['id'].'",
				"name": "'.$product['name'].'",
				"category": "'.$product['category'].'",
				"price": 1290
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
			<script data-id="'.$this->get_name().'">
			ga("ec:addProduct", {
				"id": "'.$product['id'].'",
				"name": "'.$product['name'].'",
				"category": "'.$product['category'].'",
				"price": 1290
			});
			</script>
			';
		}
	}
	public function set_action_detail(){
		echo '
		<script data-id="'.$this->get_name().'">
		ga("ec:setAction", "detail");
		</script>
		';
	}
	public function add_to_cart_form($product){
		echo '
		<script data-id="'.$this->get_name().'">
		ga("ec:addProduct", {
			"id": "'.$product['id'].'",
			"name": "'.$product['name'].'",
			"category": "'.$product['category'].'",
			"price": '.$product['price'].'
		});
		</script>
		';
	}
	public function ec_add_product(array $param){
		echo '
			<script data-id="'.$this->get_name().'">
			ga("ec:addProduct", {
				"id": "'.$param['id'].'",
				"name": "'.$param['name'].'",
				"category": "'.$param['category'].'",
				"brand": "'.$param['brand'].'",
				"variant": "'.$param['variant'].'",
				"price": '.$param['price'].',
				"quantity": '.$param['quantity'].'
			});
			</script>
			';
	}
}