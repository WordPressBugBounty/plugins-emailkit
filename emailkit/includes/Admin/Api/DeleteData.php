<?php 

namespace EmailKit\Admin\Api;

defined('ABSPATH') || exit;

class DeleteData
{
	public $prefix = '';
	public $param = '';
	public $request = null;

	public function __construct()
	{
		add_action('rest_api_init', function () {
			register_rest_route('emailkit/v1/delete-data', '(?P<post_id>\d+)', array(
				'methods'  => \WP_REST_Server::ALLMETHODS,
				'callback' => [$this, 'delete_action'],
				'permission_callback' => '__return_true',
			));
		});
	}

	public function delete_action($request)
	{
		if (!wp_verify_nonce($request->get_header('X-WP-Nonce'), 'wp_rest')) {
			return [
				'status'    => 'fail',
				'message'   => [  __( 'Nonce mismatch.', 'emailkit')]
			];
		}

		if (!is_user_logged_in() || !current_user_can('publish_posts')) {
			return [
				'status'    => 'fail',
				'message'   => [  __( 'Access denied.', 'emailkit') ]
			];
		}

		$post_id = $request->get_param('post_id');

		$deleted = wp_delete_post($post_id, true);

		if ($deleted === false) {
			return [
				"status"  => "failed",
				"message" => [
					__( "Post Data not deleted ", "emailkit" ),
				],
			];
		} else {
			return [
				"status"    => "success",
				"result"    => $post_id,
				"message"   => [
					__( "Post data deleted successfully.", "emailkit" ),
				],
			];
		}
	}
}