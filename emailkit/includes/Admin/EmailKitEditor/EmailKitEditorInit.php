<?php
namespace EmailKit\Admin\EmailKitEditor;

use EmailKit\Admin\MetaField\StyleLoad;

defined('ABSPATH') || exit('No direct script access allowed!');

/**
 * EmailKitEditorInit
 *
 * @since 1.0.0
 */

class EmailKitEditorInit
{

    public function __construct()
    {
        if(!current_user_can( 'manage_options' )){
            return;
        }

        $post_id = isset($_GET['post']) ? sanitize_text_field(wp_unslash($_GET['post'])) : ''; //phpcs:ignore WordPress.Security.NonceVerification -- Nonce can't be added in CPT edit page URL
        $action  = isset($_GET['action']) ? sanitize_text_field(wp_unslash($_GET['action'])) : ''; //phpcs:ignore WordPress.Security.NonceVerification -- Nonce can't be added in CPT edit page URL
        $post_type = get_post_type($post_id);

        if (empty($post_id) || $action != 'emailkit-builder' || $post_type != 'emailkit') {
            return;
        }
        add_action('init', function () use($post_id) {
            $dep = \EmailKit\Admin\Dependency::check(get_post_meta($post_id,'emailkit_email_type', true));
          
            if(true !== $dep){
                wp_die("Need to " . esc_html($dep['label']??'') . "<a href='" . esc_url($dep['url']??'') . "'>  Check here </a>", 'Need to activate plugin');
            }
        });

        new StyleLoad();
        add_action('wp_loaded', [$this, 'add_editor_template']);
    }

    
    public function add_editor_template()
    {
        ?>
        <!DOCTYPE html>
        <html>
        <head>
        <?php wp_head(); ?>
        <meta charSet="utf-8" />
        <meta name="viewport" content="width=device-width" />
        <meta name="next-head-count" content="2" />
        </head>

        <body class="<?php body_class(['post-'.get_the_ID()]); ?>">

        <?php 
            require_once EMAILKIT_PATH . '/dist/editor.php'; 
            wp_footer();
            ?>

        </body>
        </html>
        <?php
        exit();
    }
}
