<?php 

namespace EmailKit\Admin\Api;

defined( 'ABSPATH' ) || exit;

class OrderItem {
	
	public $prefix = '';
	public $param = '';
	public $request = null;

	public function __construct() {
		
		add_action('rest_api_init', function () {
            register_rest_route('emailkit/v1', 'last-order-item', array(
                'methods' => \WP_REST_Server::ALLMETHODS,
                'callback' => [$this, 'get_last_order_item'],
                'permission_callback' => '__return_true',
            ));
        });
	}

	public function get_last_order_item($request)
    {

		if (!wp_verify_nonce($request->get_header('X-WP-Nonce'), 'wp_rest')) {
			return [
				'status'    => 'fail',
				'message'   => [ __( 'Nonce mismatch.', 'emailkit' )]
			];
		}

		if (!is_user_logged_in() || !current_user_can('publish_posts')) {
			return [
				'status'    => 'fail',
				'message'   => [ __( 'Access denied.', 'emailkit' ) ]
			];
		}

        if(!function_exists('is_plugin_active')){
           // Include necessary WordPress files
           require_once ABSPATH . 'wp-admin/includes/plugin.php';
        }
		 
        if(!is_plugin_active('woocommerce/woocommerce.php')){
            return [
                'status' => 'fail',
                'message' => __( 'No processing orders found or no items in the last order.', 'emailkit' ),
            ];  
        }

        $last_order = wc_get_orders(array(
            'limit' => 1,
            'orderby' => 'date',
            'order' => 'DESC',
            'status' => array_keys( wc_get_order_statuses()),
        ));
        
        if (!empty($last_order)) {
            $last_order_id = reset($last_order)->get_id();
            $order = wc_get_order($last_order_id);
            $items = $order->get_items();
        
            if (!empty($items)) {
                $last_item = reset($items);
                $product = wc_get_product($last_item->get_product_id());
        
                if ($product) {
                    $product_price = $product->get_price();
                }
        
                $billing_name = $order->get_formatted_billing_full_name();
                $status = $order->get_status();
                
                 
                $shipping_first_name = $order->get_shipping_first_name();
                $shipping_last_name = $order->get_shipping_last_name();
                $shipping_full_name = $shipping_first_name . ' ' . $shipping_last_name;


                $billing_country_code = $order->get_billing_country();
                $billing_country_full_name = WC()->countries->countries[ $billing_country_code ];
                $billing_state_code = $order->get_billing_state();
                $billing_state_full_name = WC()->countries->get_states( $billing_country_code )[ $billing_state_code ];
                $shipping_country_code = $order->get_shipping_country();
                $shipping_country_full_name = WC()->countries->countries[ $shipping_country_code ];
                $shipping_full_state_name = WC()->countries->get_states( $shipping_country_code )[ $order->get_shipping_state() ];

                $item_data = [
                    'order_id' => $last_order_id,
                    'product_name' => $last_item->get_name(),
                    'billing_name' => $billing_name,
                    'status' => $status,
                    'product_price' => wc_price($product_price),
                    'quantity' => $order->get_item_count(),
                    "order_number" => $order->get_order_number(),
                    "order_fully_refunded" => $order->get_total_refunded(),
                    "order_currency" => $order->get_currency(),
                    "billing_phone" => $order->get_billing_phone(),
                    "shipping_total" => wc_price($order->get_shipping_total()),
                    "order_subtotal" => wc_price($order->get_subtotal()),
                    "shipping_tax_total" => wc_format_decimal($order->get_shipping_tax(), 2),
                    "order_date" => gmdate('Y-m-d H:i:s', strtotime($order->get_date_created()->format('Y-m-d H:i:s'))),
                    "shipping_method" => $order->get_shipping_method(),
                    "payment_method" => $order->get_payment_method_title(),
                    "total" => wc_price($order->get_total(), 2),
                    "billing_first_name" => $order->get_billing_first_name(),
                    "billing_last_name" => $order->get_billing_last_name(),
                    "billing_company" => $order->get_billing_company(),
                    "billing_address_1" => $order->get_billing_address_1(),
                    "billing_address_2" => $order->get_billing_address_2(),
                    "billing_city" => $order->get_billing_city(),
                    "billing_state" => $billing_state_full_name,
                    "billing_postcode" => $order->get_billing_postcode(),
                    "billing_country" => $billing_country_full_name,
                    "billing_email" => $order->get_billing_email(),
                    "shipping_name" => $shipping_full_name,
                    "shipping_company" => $order->get_shipping_company(),
                    "shipping_address_1" => $order->get_shipping_address_1(),
                    "shipping_address_2" => $order->get_shipping_address_2(),
                    "shipping_city" => $order->get_shipping_city(),
                    "shipping_state" => $shipping_full_state_name,
                    "shipping_postcode" => $order->get_shipping_postcode(),
                    "shipping_country" => $shipping_country_full_name,
                    "shipping_phone" => $order->get_shipping_phone(),
                    "customer_note" => $order->get_customer_note(),
                ];
                
                return [
                    'status' => 'success',
                    'data' => $item_data,
                    'message' => __( 'Last order item retrieved successfully.', 'emailkit' ),
                ];
            }
        }

        return [
            'status' => 'fail',
            'message' => __( 'No processing orders found or no items in the last order.', 'emailkit' ),
        ];
    }
}