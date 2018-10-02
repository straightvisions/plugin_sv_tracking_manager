<?php
namespace sv_tracking_manager;

class ec_woocommerce extends ec{
	private static $product								= false;
	private static $order_id							= false;
	
	public function __construct(){

	}
	public function init(){
		add_action('wp_head',array($this,'wp_footer'), 991);
		add_action('wp_head',array($this,'checkout_cart'), 999);
		add_action('wp_head',array($this,'checkout_review'), 999);
		add_action('wp_head',array($this,'checkout_thankyou'), 999);
	}
	public function wp_footer(){
		echo '
			<script data-id="'.$this->get_name().'">
			if (window.ga) {
				ga("set", "currencyCode", "' . get_woocommerce_currency() . '");
			}
			</script>
		';
	}
	public function set_product($product){
		if(!static::$product){
			if($product instanceof \WC_Product){
				static::$product							= $product;
			}else{
				$notice = static::$notices->create();
				$notice->set_title(__('Invalid Produkt for GA Manager', $this->get_name()));
				$notice->set_desc_admin(__('Product should be instance of WC_Product - ', $this->get_name()) . var_export($product,true));
				$notice->set_state(3);
			}
		}else{
			$notice = static::$notices->create();
			$notice->set_title(__('Product for GA Manager already set', $this->get_name()));
			$notice->set_desc_admin(__FUNCTION__.__(' in GA Manager was called but product was already set', $this->get_name()));
			$notice->set_state(3);
		}
	}
	public function get_product_cat($product_id){
		$terms = get_the_terms( $product_id, 'product_cat' );
		foreach ($terms as $term) {
			$product_cat = $term->name;
		}
		return $product_cat;
	}
	public function map_wc_item_to_ec_product($item){
		return apply_filters('sv_tracking_manager_ec_woocommerce_map_wc_item_to_ec_product', array(
			'id'								=> $item['product_id'],
			'name'								=> get_the_title($item['product_id']),
			'category'							=> $this->get_product_cat($item['product_id']),
			'brand'								=> '', // no default support for brands in WooCommerce
			'variant'							=> isset($item['data']) ? strip_tags($item['data']->get_formatted_name()) : strip_tags($item->get_name()),
			'price'								=> $item['line_total'],
			'quantity'							=> $item['quantity']
		), $item);
	}
	public function checkout_cart(){
		if(is_cart() && WC()->cart->get_cart_contents_count() > 0) {
			foreach(WC()->cart->cart_contents as $item){
				$this->add_product($this->map_wc_item_to_ec_product($item));
			}
			echo '
			<script data-id="'.$this->get_name().'">
			if (window.ga) {
				ga("ec:setAction","checkout", {
					"step": 1,
					"option": "Cart"
				});
				ga("send", "event", "Checkout", "View Cart");
			}
			</script>
			';
		}
	}
	public function checkout_review(){
		if(is_checkout() && WC()->cart->get_cart_contents_count() > 0) {
			foreach(WC()->cart->cart_contents as $item){
				$this->add_product($this->map_wc_item_to_ec_product($item));
			}

			echo '
			<script data-id="'.$this->get_name().'">
			if (window.ga) {
				ga("ec:setAction","review", {
					"step": 2,
					"option": "Checkout"
				});
				ga("send", "event", "Checkout", "View Review");
			}
			</script>
			';
		}
	}
	public function checkout_thankyou(){
		if(is_wc_endpoint_url('order-received')) {
			global $wp;
			$order									= new \WC_Order($wp->query_vars['order-received']);
			if(!$order->meta_exists( $this->get_name().'_checkout_tracked') && $order->get_items()) {
				foreach ($order->get_items() as $item) {
					$this->add_product($this->map_wc_item_to_ec_product($item));
				}

				$order->update_meta_data($this->get_name() . '_checkout_tracked', '1');
				$order->save();

				echo '
				<script data-id="' . $this->get_name() . '">
				if (window.ga) {
					ga("ec:setAction", "purchase", {
						"id": "' . $order->get_id() . '",
						"affiliation": "' . get_bloginfo('name') . '",
						"revenue": ' . $order->get_total() . ',
						"tax": ' . $order->get_total_tax() . ',
						"shipping": ' . $order->get_shipping_total() . ',
						"coupon": "' . implode(',', $order->get_used_coupons()) . '"
					});
					ga("send", "event", "Checkout", "View Thankyou", "", ' . intval($order->get_total()) . ');
				}
				</script>
				';
			}
		}
	}
}
?>