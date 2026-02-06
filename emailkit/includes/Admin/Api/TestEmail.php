<?php 

namespace EmailKit\Admin\Api;

defined( 'ABSPATH' ) || exit;

class TestEmail {
	
	public $prefix = '';
    public $param = '';
    public $request = null;
	public function __construct() {
		
		add_action('rest_api_init', function () {
            register_rest_route('emailkit/v1', 'send-test-email', array(
                'methods'  => \WP_REST_Server::ALLMETHODS,
                'callback' => [$this, 'sendEmail'],
                'permission_callback' => '__return_true',
            ));
        });
	}

	public function sendEmail($request)
    {
		if (!wp_verify_nonce($request->get_header('X-WP-Nonce'), 'wp_rest')) {
            return [
                'status'    => 'fail',
                'message'   => [__('Nonce mismatch.', 'emailkit')]
            ];
        }

        if (!is_user_logged_in() || !current_user_can('manage_options')) {
            return [
                'status'    => 'fail',
                'message'   => [ __('Access denied.', 'emailkit')]
            ];
        }
        
        $post_id    = $request->get_param('post_id');
        $from       = get_option('admin_email');
        $to         = $request->get_param('email');

        $pre_header = get_post_meta($post_id, 'emailkit_email_preheader', true);
        $pre_header = !empty($pre_header) ? $pre_header : esc_html__('This is a test email.', 'emailkit');
        $subject    = get_post_meta($post_id, 'emailkit_email_subject', true);
        $subject    = !empty($subject) ? $subject . ' - ' . $pre_header : $request->get_param('subject') . ' - ' . $pre_header;
        
        $message    = $request->get_param('message');
		$headers = [
			'From: ' . $from . "\r\n",
			'Reply-To: ' . $from . "\r\n",
			'Content-Type: text/html; charset=UTF-8',
		];

        $sent = wp_mail($to, $subject, $message, $headers);
       
        if(!$sent){
            $error_message = error_get_last();
            $error_message = str_contains($error_message['message'] ?? '', 'Failed to connect to mailserver')? __( 'Failed to connect to mailserver', 'emailkit' ) : __( 'Failed to send the test email.', 'emailkit' );
       }
        

        if ($sent) {
            return [
                'status' => 'success',
                'message' => [ __( 'Test email sent successfully.', 'emailkit' ) ],
            ];
        } else {
            return [
                'status' => 'fail',
                'message' => [ esc_html( $error_message) ],
            ];
        }
    }
}