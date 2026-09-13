<?php 
namespace Emailkit\Admin\Emails;

defined( "ABSPATH") || exit;

class EmailLists {
    const NEW_ORDER = "new_order";
    const CANCELLED_ORDER = "cancelled_order";
    const FAILED_ORDER = "failed_order";
    const FAILED_ORDER_CUSTOMER = "customer_failed_order";
    const ORDER_ON_HOLD = "customer_on_hold_order";
    const PROCESSING_ORDER = "customer_processing_order";
    const COMPLETED_ORDER = "customer_completed_order";
    const REFUNDED_ORDER = "customer_refunded_order";
    const LOW_STOCK = "low_stock";
    const NO_STOCK = "no_stock";
    const BACK_ORDER = "back_order";
    const CUSTOMER_INVOICE_OR_ORDER_DETAILS = "customer_invoice";
    // const ORDER_DETAILS = "Order Details";
    const CUSTOMER_NOTE = "customer_note";
    const RESET_PASSWORD = "customer_reset_password";
    const NEW_ACCOUNT = "customer_new_account";
    const WP_NEW_REGISTER = "new_register";
    const WP_RESET_PASSWORD = "reset_account";
    const PARTIAL_REFUND = "partial_refund";
    const PAYMENT_GATEWAY_ENABLED = "admin_payment_gateway_enabled";
    const METFORM = "metform";
    const POPUPKIT = "popupkit";
    
    
    /**
     * Get woocommerce template names
     * @return array
     */
    public static function woocommerce_email($template_type = '') {
        $list = [
            'Select Template'                         =>  esc_html__('Select Template', 'emailkit'),
            self::NEW_ORDER                           =>  esc_html__('New Order', 'emailkit'),
            self::CANCELLED_ORDER                     =>  esc_html__('Cancelled order', 'emailkit'),
            self::FAILED_ORDER                        =>  esc_html__('Failed Order - Admin', 'emailkit'),
            self::FAILED_ORDER_CUSTOMER               =>  esc_html__('Failed Order - Customer', 'emailkit'),
            self::ORDER_ON_HOLD                       =>  esc_html__('Order On Hold', 'emailkit'),
            self::PROCESSING_ORDER                    =>  esc_html__('Processing Order', 'emailkit'),
            self::COMPLETED_ORDER                     =>  esc_html__('Completed Order', 'emailkit'),
            self::LOW_STOCK                           =>  esc_html__('Low Stock', 'emailkit'),
            self::NO_STOCK                            =>  esc_html__('No Stock', 'emailkit'),
            self::BACK_ORDER                          =>  esc_html__('Back Order', 'emailkit'),
            self::REFUNDED_ORDER                      =>  esc_html__('Refunded Order', 'emailkit'),
            self::PARTIAL_REFUND                      =>  esc_html__('Partial Refund', 'emailkit'),
            self::CUSTOMER_INVOICE_OR_ORDER_DETAILS   =>  esc_html__('Customer Invoice ', 'emailkit'),
            self::CUSTOMER_NOTE                       =>  esc_html__('Customer Note', 'emailkit'),
            self::NEW_ACCOUNT                         =>  esc_html__('New Account', 'emailkit'),
            self::RESET_PASSWORD                      =>  esc_html__('Reset Password', 'emailkit'),
            self::PAYMENT_GATEWAY_ENABLED             =>  esc_html__('Payment Gateway Enabled', 'emailkit'),
        ];
        
        if($template_type){
            return isset($list[$template_type]) ? $list[$template_type] : '';
        }
        
        return $list;
    }

     /**
     * Get wordpress template names
     * @return array
     */
    public static function wordpress_email($template_type = '') {
        $list = [
            'Select Template'      => esc_html__('Select Template', 'emailkit'),
            self::WP_NEW_REGISTER  => esc_html__('New Account', 'emailkit'),
            self::WP_RESET_PASSWORD => esc_html__('Reset Password', 'emailkit'),
        ];
        
        if ($template_type) {
            return isset($list[$template_type]) ? $list[$template_type] : '';
        }
        
        return $list;
    }

    public static function metform_email($template_type = '') {
        $list = ['Select Template' => esc_html__('Select Form', 'emailkit')];

        $metform_forms = get_posts([
            'post_type'      => 'metform-form',
            'post_status'    => 'publish',
            'numberposts'    => -1,
            'orderby'        => 'title',
            'order'          => 'ASC',
        ]);
        
        // Free: Check for templates
        $templates = get_posts([
            'post_type'      => 'emailkit',
            'meta_query'     => [['key' => ' ', 'value' => 'metform_form_', 'compare' => 'LIKE']],
            'posts_per_page' => 1,
            'fields'         => 'ids',
            'orderby'        => 'ID',
            'order'          => 'ASC'
        ]);

        if (defined('EMAILKITPRO_VERSION') || class_exists('EmailKitPro')) {
            // Pro: Show all forms
            foreach ($metform_forms as $form) {
                $list['metform_form_' . $form->ID] = esc_html__('Form: ', 'emailkit') . $form->post_title;
            }
        } else {
            if (empty($templates)) {
                // No templates: Show all forms
                foreach ($metform_forms as $form) {
                    $list['metform_form_' . $form->ID] = esc_html__('Form: ', 'emailkit') . $form->post_title;
                }
            } else {
                // Templates exist: Show only forms with templates
                $form_ids = [];
                foreach ($templates as $template_id) {
                    $form_id = get_post_meta($template_id, 'emailkit_form_id', true) ?: 
                            (preg_match('/metform_form_(\d+)/', get_post_meta($template_id, 'emailkit_template_type', true), $matches) ? $matches[1] : 0);
                    if ($form_id) $form_ids[] = (int) $form_id;
                }
                foreach ($metform_forms as $form) {
                    if (in_array($form->ID, $form_ids)) {
                        $list['metform_form_' . $form->ID] = esc_html__('Form: ', 'emailkit') . $form->post_title;
                    }
                }
            }
        }

        if( !class_exists( 'EmailKitPro' ) && is_array($metform_forms) && count($metform_forms) > 1 && !empty($templates)) {

            $list['more-forms'] = esc_html__( 'More Forms...', 'emailkit' );
        }

        return $template_type ? ($list[$template_type] ?? '') : $list;
    }

    public static function popupkit_email($template_type = '') {
        $list = ['Select Template' => esc_html__('Select Popup', 'emailkit')];

        $popupkit_popups = get_posts([
            'post_type'      => 'popupkit-campaigns',
            'post_status'    => 'publish',
            'numberposts'    => -1,
            'orderby'        => 'title',
            'order'          => 'ASC',
        ]);

        // Free: Check for existing templates linked to popupkit
        $templates = get_posts([
            'post_type'      => 'emailkit',
            'meta_query'     => [['key' => 'emailkit_template_type', 'value' => 'popupkit_popup_', 'compare' => 'LIKE']],
            'posts_per_page' => 1,
            'fields'         => 'ids',
            'orderby'        => 'ID',
            'order'          => 'ASC'
        ]);

        if (defined('EMAILKITPRO_VERSION') || class_exists('EmailKitPro')) {
            // Pro: Show all popups
            foreach ($popupkit_popups as $popup) {
                $list['popupkit_popup_' . $popup->ID] = esc_html__('Popup: ', 'emailkit') . $popup->post_title;
            }
        } else {
            if (empty($templates)) {
                // No templates: Show all popups
                foreach ($popupkit_popups as $popup) {
                    $list['popupkit_popup_' . $popup->ID] = esc_html__('Popup: ', 'emailkit') . $popup->post_title;
                }
            } else {
                // Templates exist: Show only popups that already have a template
                $popup_ids = [];
                foreach ($templates as $template_id) {
                    $popup_id = get_post_meta($template_id, 'emailkit_popup_id', true) ?:
                        (preg_match('/popupkit_popup_(\d+)/', get_post_meta($template_id, 'emailkit_template_type', true), $matches) ? $matches[1] : 0);
                    if ($popup_id) $popup_ids[] = (int) $popup_id;
                }
                foreach ($popupkit_popups as $popup) {
                    if (in_array($popup->ID, $popup_ids)) {
                        $list['popupkit_popup_' . $popup->ID] = esc_html__('Popup: ', 'emailkit') . $popup->post_title;
                    }
                }
            }
        }

        if (!class_exists('EmailKitPro') && is_array($popupkit_popups) && count($popupkit_popups) > 1 && !empty($templates)) {
            $list['more-popups'] = esc_html__('More Popups...', 'emailkit');
        }

        return $template_type ? ($list[$template_type] ?? '') : $list;
    }


    /**
     * Get saved templates from database
     * @return array
     */
    public static function saved_templates() : array {
       
        $emailkit_saved_templates = [];

        $templates = get_posts([
            'post_type'    => 'emailkit-template',
            'numberposts'  => -1,
        ]);
    
    
        foreach ( $templates as $template ) {

            setup_postdata( $template );
    
            $template_id = get_post_meta( $template->ID, 'emailkit_template_id', true ); //get the id of main template to fetch types and etc
    
            $emailkit_saved_templates[] = array(
                'title'             =>   $template->post_title,
                'id'                =>   $template->ID,
                'template_id'       =>   $template_id,
                'email_type'        =>   get_post_meta( $template_id, 'emailkit_email_type', true ),
                'template_type'     =>   get_post_meta( $template_id, 'emailkit_template_type', true ),
                'template_status'   =>   get_post_meta( $template_id, 'emailkit_template_status', true ),
            );

        }
    
        wp_reset_postdata();
    

        return $emailkit_saved_templates;
    }

    
}