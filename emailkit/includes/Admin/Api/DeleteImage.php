<?php 

namespace EmailKit\Admin\Api;

defined('ABSPATH') || exit;

class DeleteImage
{

    public $prefix = '';
    public $param = '';
    public $request = null;


    public function __construct()
    {
        add_action('rest_api_init', function () {
            register_rest_route('emailkit/v1', 'delete-image/(?P<attachment_id>\d+)', array(
                'methods'  => \WP_REST_Server::ALLMETHODS,
                'callback' => [$this, 'delete_image'],
                'permission_callback' => '__return_true',
            ));
        });
    }


    public function delete_image($request)
    {
        if (!wp_verify_nonce($request->get_header('X-WP-Nonce'), 'wp_rest')) {
            return [
                'status'  => 'fail',
                'message' => [ __('Nonce mismatch.', 'emailkit')],
            ];
        }

        if (!is_user_logged_in() || !current_user_can('delete_posts')) {
            return [
                'status'  => 'fail',
                'message' => [  __('Access denied.', 'emailkit') ],
            ];
        }

        $attachment_id = $request->get_param('attachment_id');

        if (empty($attachment_id)) {
            return [
                'status'  => 'fail',
                'message' => [  __( 'Attachment ID is missing.', 'emailkit')],
            ];
        }

        $attachment = get_post($attachment_id);

        if (!$attachment || $attachment->post_type !== 'attachment') {
            return [
                'status'  => 'fail',
                'message' => [  __( 'Invalid attachment ID.', 'emailkit')],
            ];
        }

        $deleted = wp_delete_attachment($attachment_id, true);

        if ($deleted) {
            // Optionally, you can also delete the physical file from the server using the following line:
            // wp_delete_file(get_attached_file($attachment_id));

            return [
                'status'  => 'success',
                'message' =>  __( 'Image deleted successfully.', 'emailkit'),
            ];
        } else {
            return [
                'status'  => 'fail',
                'message' =>  __( 'Failed to delete image.', 'emailkit'),
            ];
        }
    }
}