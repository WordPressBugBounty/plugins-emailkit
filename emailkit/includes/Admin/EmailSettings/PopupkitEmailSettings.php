<?php

namespace EmailKit\Admin\EmailSettings;

use WP_Query;

defined('ABSPATH') || exit;

/**
 * Handles the EmailKit ↔ PopupKit integration:
 *   – Enqueues the JS that powers the "Edit with EmailKit" button inside the
 *     PopupKit block editor (via wp_localize_script on the block-editor handle).
 *   – Intercepts the outgoing auto-responder email fired by popup-builder-block-pro
 *     and replaces the body with the saved EmailKit template when one exists.
 */
class PopupkitEmailSettings
{

    public function __construct()
    {
        // The PopupKit block editor already localises `popupBuilderBlock` in
        // popup-builder-block/includes/Admin/Admin.php; we only need to ensure
        // the EmailKit REST data is available (done there via our Admin.php patch).

        // Replace the auto-responder body when an EmailKit template is found.
        add_filter('popup_builder_block_autoresponder_email_body', [$this, 'use_emailkit_template'], 10, 2);
    }

    /**
     * Swap the default email body for the saved EmailKit template HTML.
     *
     * @param string $body        The outgoing email body (plain or simple HTML).
     * @param array  $request     The raw form-submission request array, including campaign_id.
     * @return string             Filtered email body.
     */
    public function use_emailkit_template($body, $request)
    {
        $campaign_id = absint($request['campaign_id'] ?? 0);

        if (!$campaign_id) {
            return $body;
        }

        $post_id = $this->get_emailkit_post_id('popupkit_popup_' . $campaign_id);

        if (!$post_id) {
            return $body;
        }

        $template_html = get_post_meta($post_id, 'emailkit_template_content_html', true);

        if (empty($template_html)) {
            return $body;
        }

        // Run through EmailKit's shortcode filter if hooked, then replace {tags}
        if (has_filter('emailkit_shortcode_filter')) {
            $template_html = apply_filters('emailkit_shortcode_filter', $template_html);
        }

        // Replace {field} placeholders with submitted values
        return $this->replace_placeholders($template_html, $request);
    }

    /**
     * Find the active EmailKit post ID for a given popupkit_popup_* template type.
     *
     * @param string $template_type  e.g. 'popupkit_popup_123'
     * @return int|null
     */
    private function get_emailkit_post_id($template_type)
    {
        // Deliberately not filtered on emailkit_template_status: the builder's save
        // (UpdateData) writes 'inactive' unless a status is explicitly posted, and
        // create_popup_template already guarantees one template per popup.
        $args = [
            'post_type'      => 'emailkit',
            'post_status'    => 'publish',
            'posts_per_page' => 1,
            'fields'         => 'ids',
            'meta_query'     => [
                [
                    'key'     => 'emailkit_template_type',
                    'value'   => $template_type,
                    'compare' => '=',
                ],
            ],
        ];

        $query = new WP_Query($args);
        return !empty($query->posts) ? $query->posts[0] : null;
    }

    /**
     * Replace {placeholder} tags in $text with values from $request.
     *
     * @param string $text
     * @param array  $request
     * @return string
     */
    private function replace_placeholders($text, $request)
    {
        if (strpos($text, '{') === false) {
            return $text;
        }

        $current_user = wp_get_current_user();

        $replacements = [
            '{current_user_name}'  => $current_user->exists() ? $current_user->display_name : '',
            '{current_user_email}' => $current_user->exists() ? $current_user->user_email   : '',
            '{campaign_name}'      => $request['campaign_title'] ?? '',
            '{campaign_id}'        => $request['campaign_id']    ?? '',
            '{name}'               => $request['name']           ?? '',
            '{email}'              => $request['email']          ?? '',
        ];

        // Expose the remaining top-level request fields as {key}. form_data and
        // user_data are serialised blobs handled separately below.
        $internal_keys = ['form_data', 'user_data', 'emailData'];

        foreach ($request as $key => $value) {
            $tag = '{' . $key . '}';
            if (!isset($replacements[$tag]) && is_scalar($value) && !in_array($key, $internal_keys, true)) {
                $replacements[$tag] = $value;
            }
        }

        // Expose the submitted custom fields ({phone}, {message}, …).
        foreach ($this->get_submitted_fields($request) as $key => $value) {
            $tag = '{' . $key . '}';
            if (!isset($replacements[$tag])) {
                $replacements[$tag] = $value;
            }
        }

        $replacements = array_map('esc_html', $replacements);

        return str_replace(array_keys($replacements), array_values($replacements), $text);
    }

    /**
     * Flatten the popup form's custom fields into a name => value map.
     *
     * PopupKit JSON-encodes form_data before firing
     * popup_builder_block_form_integration_submit, so it arrives as a string;
     * grouped inputs (checkboxes, multi-selects) arrive as a list of values.
     *
     * @param array $request
     * @return array<string, string>
     */
    private function get_submitted_fields($request)
    {
        $form_data = $request['form_data'] ?? [];

        if (is_string($form_data)) {
            $form_data = json_decode($form_data, true);
        }

        if (!is_array($form_data)) {
            return [];
        }

        $fields = [];

        foreach ($form_data as $key => $value) {
            if (is_scalar($value)) {
                $fields[$key] = (string) $value;
            } elseif (is_array($value)) {
                $fields[$key] = implode(', ', array_filter($value, 'is_scalar'));
            }
        }

        return $fields;
    }
}
