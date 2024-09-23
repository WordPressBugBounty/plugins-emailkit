<?php 

namespace EmailKit\Admin\Api;

defined('ABSPATH') || exit;

class UploadImage
{
    public $prefix  = '';
    public $param   = '';
    public $request = null;

    public function __construct()
    {
        add_action('rest_api_init', function () {
            register_rest_route('emailkit/v1', 'upload-image', array(
                'methods'  => \WP_REST_Server::ALLMETHODS,
                'callback' => [$this, 'action'],
                'permission_callback' => '__return_true',
            ));
        });
    }

    public function action($request)
    {
        if (!wp_verify_nonce($request->get_header('X-WP-Nonce'), 'wp_rest')) {
            return [
                'status'  => 'fail',
                'message' => [ __('Nonce mismatch.', 'emailkit') ],
            ];
        }

        if (!is_user_logged_in() || !current_user_can('publish_posts')) {
            return [
                'status'  => 'fail',
                'message' => [ __('Access denied.', 'emailkit') ],
            ];
        }

        if (isset($_FILES['file'])) {
            // Handle the uploaded file
            require_once(ABSPATH . 'wp-admin/includes/image.php');
            require_once(ABSPATH . 'wp-admin/includes/file.php');
            require_once(ABSPATH . 'wp-admin/includes/media.php');

            // Upload the file
            $attachment_id = media_handle_upload('file', 0);

            if (is_wp_error($attachment_id)) {
                return array(
                    'status'  => 'fail',
                    'message' => $attachment_id->get_error_message(),
                );
            }

            // Get the file URL
            $file_url = wp_get_attachment_url($attachment_id);

            return array(
                'status'  => 'success',
                'data'    => array(
                    'attachmentId' => $attachment_id,
                    'fileUrl'      => esc_url($file_url),
                ),
                'message' => __('Image uploaded successfully.', 'emailkit'),
            );
        } else {
            return array(
                'status'  => 'fail',
                'message' => __('No file uploaded.', 'emailkit'),
            );
        }
    }
}