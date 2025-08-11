<?php

namespace EmailKit\Admin\Api;

use WP_Error;
use WP_REST_Response;

defined('ABSPATH') || exit;

class CheckForm
{
    public function __construct()
    {
        add_action('rest_api_init', function () {
            // Check if template exists
            register_rest_route('emailkit/v1', 'check-template', [
                'methods' => 'GET',
                'callback' => [$this, 'check_template'],
                'permission_callback' => function () {
                    return current_user_can('edit_posts');
                },
            ]);
            
            // Create new template
            register_rest_route('emailkit/v1', 'create-template', [
                'methods' => 'POST',
                'callback' => [$this, 'create_template'],
                'permission_callback' => function () {
                    return current_user_can('edit_posts');
                },
            ]);
        });
    }

    public function check_template($request) {
        if (!wp_verify_nonce($request->get_header('X-WP-Nonce'), 'wp_rest')) {
            return [
                'status' => 'fail',
                'message' => [__('Nonce mismatch.', 'emailkit')]
            ];
        }

        if (!is_user_logged_in() || !current_user_can('publish_posts')) {
            return [
                'status' => 'fail',
                'message' => [__('Access denied.', 'emailkit')]
            ];
        }

        $form_id = $request->get_param('form_id');
        $template_type = 'metform_form_' . $form_id;
        
        $args = [
            'post_type' => 'emailkit',
            'meta_query' => [
                'relation' => 'OR',
                [
                    'relation' => 'AND',
                    [
                        'key'     => 'emailkit_template_type',
                        'value'   => $template_type,
                        'compare' => '='
                    ],
                    [
                        'key'     => 'emailkit_template_status',
                        'value'   => 'active',
                        'compare' => '='
                    ]
                ]
            ],
            'posts_per_page' => 1,
            'fields' => 'ids'
        ];

        $existing_templates = get_posts($args);

        if (!empty($existing_templates)) {
            $template_id = $existing_templates[0];
            return new WP_REST_Response([
                'success' => true,
                'data' => [
                    'exists' => true,
                    'builder_url' => admin_url("post.php?post={$template_id}&action=emailkit-builder")
                ]
            ], 200);
        } else {
            $args = [
                'post_type' => 'emailkit',
                'meta_query' => [
                    'relation' => 'OR',
                    [
                        'relation' => 'AND',
                        [
                            'key'     => 'emailkit_template_type',
                            'value'   => $template_type,
                            'compare' => '='
                        ]
                    ]
                ],
                'posts_per_page' => 1,
                'fields' => 'ids'
            ];

            $existing_templates = get_posts($args);

            if (!empty($existing_templates)) {
                $template_id = $existing_templates[0];
                return new WP_REST_Response([
                    'success' => true,
                    'data' => [
                        'exists' => true,
                        'builder_url' => admin_url("post.php?post={$template_id}&action=emailkit-builder")
                    ]
                ], 200);
            }
        }

        return new WP_REST_Response([
            'success' => true,
            'data' => [
                'exists' => false,
            ]
        ], 200);
    }

    public function create_template($request) {
        // Verify nonce and permissions
        if (!wp_verify_nonce($request->get_header('X-WP-Nonce'), 'wp_rest')) {
            return [
                'status' => 'fail',
                'message' => [__('Nonce mismatch.', 'emailkit')]
            ];
        }

        if (!is_user_logged_in() || !current_user_can('publish_posts')) {
            return [
                'status' => 'fail',
                'message' => [__('Access denied.', 'emailkit')]
            ];
        }

        $form_id = $request->get_param('form_id');
        $form_title = $request->get_param('form_title') ?? __('MetForm', 'emailkit');
        $template_type = 'metform_form_' . $form_id;
        $template_title =  $request->get_param('template_title');

        // First check for existing templates (both old and new format)
        $existing_template = $this->get_existing_template($form_id);
        if ($existing_template) {
            return new WP_REST_Response([
                'success' => false,
                'message' => __('A template already exists for this form.', 'emailkit'),
                'data' => [
                    'builder_url' => admin_url("post.php?post={$existing_template}&action=emailkit-builder")
                ]
            ], 400);
        }

        // Get template content
        $template = '';
        $html = '';
        if (!empty($request->get_param('emailkit-editor-template')) && trim($request->get_param('emailkit-editor-template')) !== '') {
            $template_path = $request->get_param('emailkit-editor-template');
            $template = file_exists($template_path) ? file_get_contents($template_path) : '';
            $html_path = str_replace("content.json", "content.html", $template_path);
            $html = file_exists($html_path) ? file_get_contents($html_path) : '';
        }

        // Create new emailkit post
        $post_id = wp_insert_post([
            'post_title' => $template_title,
            'post_type' => 'emailkit',
            'post_status' => 'publish',
            'meta_input' => [
                'emailkit_template_type' => $template_type,
                'emailkit_form_id' => $form_id,
                'emailkit_template_status' => 'active',
                'emailkit_template_content_html' => $html,
                'emailkit_template_content_object' => $template,
                'emailkit_email_type' => $request->get_param('emailkit_email_type'),
            ]
        ]);

        if (is_wp_error($post_id)) {
            return new WP_REST_Response([
                'success' => false,
                'message' => __('Failed to create template', 'emailkit')
            ], 400);
        }

        return new WP_REST_Response([
            'success' => true,
            'data' => [
                'builder_url' => admin_url("post.php?post={$post_id}&action=emailkit-builder"),
                'post_id' => $post_id
            ]
        ], 200);
    }

    private function get_existing_template($form_id) {
        $template_type = 'metform_form_' . $form_id;
        
        $args = [
            'post_type' => 'emailkit',
            'meta_query' => [
                'relation' => 'OR',
                [
                    'relation' => 'AND',
                    [
                        'key'     => 'emailkit_template_type',
                        'value'   => $template_type,
                        'compare' => '='
                    ],
                    [
                        'key'     => 'emailkit_template_status',
                        'value'   => 'active',
                        'compare' => '='
                    ]
                ]
            ],
            'posts_per_page' => 1,
            'fields' => 'ids'
        ];

        $existing = get_posts($args);
        return !empty($existing) ? $existing[0] : false;
    }
}