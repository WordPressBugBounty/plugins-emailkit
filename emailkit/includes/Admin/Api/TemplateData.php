<?php

namespace EmailKit\Admin\Api;

defined('ABSPATH') || exit;
class TemplateData
{

    public $prefix = '';
    public $param = '';
    public $request = null;

    public function __construct()
    {
        add_action('rest_api_init', function () {
            register_rest_route('emailkit/v1', 'template-data', array(
                'methods'  => \WP_REST_Server::ALLMETHODS,
                'callback' => [$this, 'action'],
                'permission_callback' => '__return_true',
            ));
        });
    }

    public function action($request)
    {
        
        if (!wp_verify_nonce($request->get_header( 'X-WP-Nonce' ), 'wp_rest')) {
            return [
                'status'    => 'fail',
                'message'   => ['Nonce mismatch.']
            ];
        }

        if (!is_user_logged_in() || !current_user_can( 'publish_posts' )) {
            return [
                'status'    => 'fail',
                'message'   => ['Access denied.']
            ];
        }
        $template = '';
        $html = '';
        $body = $request->get_body();
        $req = json_decode($body);
        $message = '';
    

       
        if(!empty($request->get_param( 'emailkit-editor-template' ) && trim($request->get_param( 'emailkit-editor-template' )) !== '')){
            $template = file_get_contents($request->get_param( 'emailkit-editor-template' ))??'';
            $html = file_get_contents(str_replace( "content.json", "content.html", $request->get_param( 'emailkit-editor-template' )))??'';
        }

        $subject = !empty($request->get_param( 'emailkit_template_title' ))? trim($request->get_param( 'emailkit_template_title' )) : null;

        $post_id = empty($req->postIdField) ? '' : $req->postIdField;

        if (empty($post_id)) {
            $subject = ($request->get_param('emailkit_template_title') !== null) ? trim($request->get_param('emailkit_template_title')) : '';
            $data = array(
                'post_type'   => 'emailkit',
                'post_status' => 'publish',
                'post_author' => get_current_user_id(),
                'post_title' =>  $subject !== '' ?  $subject : "New Template ".uniqid(),
                'meta_input'  => array(
                    'emailkit_template_content_html'    => $html,
                    'emailkit_template_content_object'  => $template,
                    'emailkit_email_type'               => $request->get_param('emailkit_email_type'),
                    'emailkit_template_type'            => $request->get_param('emailkit_template_type'),
                    'emailkit_template_status'          => $request->get_param('emailkit_template_status') ?? 'inactive',
                    'emailkit_email_subject'            => $req->subject??'',
                    'emailkit_email_preheader'          => $req->preheader??'',
                    'emailkit_template_initial_content_object' => $template,
                )
            );
            
            if(!empty($request->get_param('emailkit_template_status')) && trim($request->get_param('emailkit_template_status')) == 'active'){
                $this->deactivateTemplateTypes(get_post_meta($post_id,'emailkit_template_type', true));
            }
            $post_id = wp_insert_post($data);

            $message = __( 'Post created successfully.', 'emailkit' );
        }

        return [
            "status"    => "success",
            "data"      => [
                "templateId" => $post_id,
                'builder_url' => (admin_url("post.php?post={$post_id}&action=emailkit-builder")),
            ],
            "message"   => [
                $message,
            ],
        ];
    }
    public function deactivateTemplateTypes($type)
    {

        $query = array(
            'post_type' => 'emailkit',
            'relation' => 'AND',
            'meta_query' => array(
                array(
                    'key' => 'emailkit_template_type',
                    'value' => $type,
                    'compare' => '==',
                ),
                array(
                    'key' => 'emailkit_template_status',
                    'value' => 'active',
                    'compare' => '==',
                ),
                'fields' => 'ids'
            )
        );

        $data = new \WP_Query($query);
        if (isset($data)) {
            
            $postsIds = wp_list_pluck($data->posts, 'ID');
            foreach ($postsIds as $id) {
                update_post_meta($id, 'emailkit_template_status', 'inactive');
            }
        }
    }
}