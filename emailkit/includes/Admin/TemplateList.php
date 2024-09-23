<?php
namespace EmailKit\Admin;

use EmailKit\Admin\Emails\EmailLists;
defined( 'ABSPATH' ) || exit;

Class TemplateList{
    const  EMAILKIT_URL_TEMAPLTE_DIR = EMAILKIT_DIR. "includes/";
    const EMAILKIT_URL_TEMAPLTE_URL = EMAILKIT_URL. "includes/";

    public static function get_templates(){
    
        
        $template_list = [
            'template-1' => [
                'id' => 1,
                'package' => 'free',
                'mail_type' => 'woocommerce',
                'title' => EmailLists::NEW_ORDER,
                'template_title' => EmailLists::woocommerce_email(EmailLists::NEW_ORDER), 
                'preview-thumb' => self::EMAILKIT_URL_TEMAPLTE_URL . 'templates/new-order/1/preview-thumb.svg',
                'demo-url'  => 'https://wpmet.com/',
                'file' => self::EMAILKIT_URL_TEMAPLTE_DIR . 'templates/new-order/1/content.json',
            ],
            'template-2' => [
                'id' => 2,
                'package' => 'free',
                'mail_type' => 'woocommerce',
                'title' => EmailLists::CANCELLED_ORDER,
                'template_title' => EmailLists::woocommerce_email(EmailLists::CANCELLED_ORDER),
                'preview-thumb' => self::EMAILKIT_URL_TEMAPLTE_URL . 'templates/cancelled-order/1/preview-thumb.svg',
                'demo-url'  => 'https://wpmet.com/',
                'file' => self::EMAILKIT_URL_TEMAPLTE_DIR . 'templates/cancelled-order/1/content.json',
                
            ],
            'template-3' => [
                'id' => 3,
                'package' => 'free',
                'mail_type' => 'woocommerce',
                'title' => EmailLists::FAILED_ORDER,
                'template_title' => EmailLists::woocommerce_email(EmailLists::FAILED_ORDER),
                'preview-thumb' => self::EMAILKIT_URL_TEMAPLTE_URL . 'templates/failed-order/1/preview-thumb.svg',
                'demo-url'  => 'https://wpmet.com/',
                'file' => self::EMAILKIT_URL_TEMAPLTE_DIR . 'templates/failed-order/1/content.json',
            ],
            'template-4' => [
                'id' => 4,
                'package' => 'free',
                'mail_type' => 'woocommerce',
                'title' => EmailLists::ORDER_ON_HOLD,
                'template_title' => EmailLists::woocommerce_email(EmailLists::ORDER_ON_HOLD),
                'preview-thumb' => self::EMAILKIT_URL_TEMAPLTE_URL . 'templates/order-on-hold/1/preview-thumb.svg',
                'demo-url'  => 'https://wpmet.com/',
                'file' => self::EMAILKIT_URL_TEMAPLTE_DIR . 'templates/order-on-hold/1/content.json',
            ],
            'template-5' => [
                'id' => 5,
                'package' => 'free',
                'mail_type' => 'woocommerce',
                'title' => EmailLists::PROCESSING_ORDER,
                'template_title' => EmailLists::woocommerce_email(EmailLists::PROCESSING_ORDER),
                'preview-thumb' => self::EMAILKIT_URL_TEMAPLTE_URL . 'templates/processing-order/1/preview-thumb.svg',
                'demo-url'  => 'https://wpmet.com/',
                'file' => self::EMAILKIT_URL_TEMAPLTE_DIR . 'templates/processing-order/1/content.json',
            ],
            'template-6' => [
                'id' => 6,
                'package' => 'free',
                'mail_type' => 'woocommerce',
                'title' => EmailLists::COMPLETED_ORDER,
                'template_title' => EmailLists::woocommerce_email(EmailLists::COMPLETED_ORDER),
                'preview-thumb' => self::EMAILKIT_URL_TEMAPLTE_URL . 'templates/completed-order/1/preview-thumb.svg',
                'demo-url'  => 'https://wpmet.com/',
                'file' => self::EMAILKIT_URL_TEMAPLTE_DIR . 'templates/completed-order/1/content.json'
            ],
            'template-7' => [
                'id' => 7,
                'package' => 'free',
                'mail_type' => 'woocommerce',
                'title' => EmailLists::REFUNDED_ORDER,
                'template_title' => EmailLists::woocommerce_email(EmailLists::REFUNDED_ORDER),
                'preview-thumb' => self::EMAILKIT_URL_TEMAPLTE_URL . 'templates/refunded-order/1/preview-thumb.svg',
                'demo-url'  => 'https://wpmet.com/',
                'file' => self::EMAILKIT_URL_TEMAPLTE_DIR . 'templates/refunded-order/1/content.json'
            ],
            'template-8' => [
                'id' => 8,
                'package' => 'free',
                'mail_type' => 'woocommerce',
                'title' => EmailLists::CUSTOMER_INVOICE_OR_ORDER_DETAILS,
                'template_title' => EmailLists::woocommerce_email(EmailLists::CUSTOMER_INVOICE_OR_ORDER_DETAILS),
                'preview-thumb' => self::EMAILKIT_URL_TEMAPLTE_URL . 'templates/customer-invoice/1/preview-thumb.svg',
                'demo-url'  => '',
                'file' => self::EMAILKIT_URL_TEMAPLTE_DIR . 'templates/customer-invoice/1/content.json'
            ],
            'template-9' => [
                'id' => 9,
                'package' => 'free',
                'mail_type' => 'woocommerce',
                'title' =>  EmailLists::CUSTOMER_NOTE,
                'template_title' => EmailLists::woocommerce_email(EmailLists::CUSTOMER_NOTE),
                'preview-thumb' => self::EMAILKIT_URL_TEMAPLTE_URL . 'templates/customer-note/1/preview-thumb.svg',
                'demo-url'  => 'https://wpmet.com/',
                'file' => self::EMAILKIT_URL_TEMAPLTE_DIR . 'templates/customer-note/1/content.json'
            ],
            'template-10' => [
                'id' => 10,
                'package' => 'free',
                'mail_type' => 'woocommerce',
                'title' =>  EmailLists::NEW_ACCOUNT,
                'template_title' => EmailLists::woocommerce_email(EmailLists::NEW_ACCOUNT),
                'preview-thumb' => self::EMAILKIT_URL_TEMAPLTE_URL . 'templates/wc-new-account/1/preview-thumb.svg',
                'demo-url'  => 'https://wpmet.com/',
                'file' => self::EMAILKIT_URL_TEMAPLTE_DIR . 'templates/wc-new-account/1/content.json'
            ],
           
            'template-11' => [
                'id' => 11,
                'package' => 'free',
                'mail_type' => 'woocommerce',
                'title' =>  EmailLists::RESET_PASSWORD,
                'template_title' => EmailLists::woocommerce_email(EmailLists::RESET_PASSWORD),
                'preview-thumb' => self::EMAILKIT_URL_TEMAPLTE_URL . 'templates/wc-reset-password/1/preview-thumb.svg',
                'demo-url'  => 'https://wpmet.com/',
                'file' => self::EMAILKIT_URL_TEMAPLTE_DIR . 'templates/wc-reset-password/1/content.json'
            ],
            'template-12' => [
                'id' => 12,
                'package' => 'free',
                'mail_type' => 'woocommerce',
                'title' =>  EmailLists::LOW_STOCK,
                'template_title' => EmailLists::woocommerce_email(EmailLists::LOW_STOCK),
                'preview-thumb' => self::EMAILKIT_URL_TEMAPLTE_URL . 'templates/low-stock/1/preview-thumb.svg',
                'demo-url'  => 'https://wpmet.com/',
                'file' => self::EMAILKIT_URL_TEMAPLTE_DIR . 'templates/low-stock/1/content.json'
            ],
            'template-13' => [
                'id' => 13,
                'package' => 'free',
                'mail_type' => 'woocommerce',
                'title' =>  EmailLists::NO_STOCK,
                'template_title' => EmailLists::woocommerce_email(EmailLists::NO_STOCK),
                'preview-thumb' => self::EMAILKIT_URL_TEMAPLTE_URL . 'templates/no-stock/1/preview-thumb.svg',
                'demo-url'  => 'https://wpmet.com/',
                'file' => self::EMAILKIT_URL_TEMAPLTE_DIR . 'templates/no-stock/1/content.json'
            ],
            'template-14' => [
                'id' => 14,
                'package' => 'free',
                'mail_type' => 'woocommerce',
                'title' =>  EmailLists::BACK_ORDER,
                'template_title' => EmailLists::woocommerce_email(EmailLists::BACK_ORDER),
                'preview-thumb' => self::EMAILKIT_URL_TEMAPLTE_URL . 'templates/back-order/1/preview-thumb.svg',
                'demo-url'  => 'https://wpmet.com/',
                'file' => self::EMAILKIT_URL_TEMAPLTE_DIR . 'templates/back-order/1/content.json'
            ],
            
            'template-15' => [
                'id' => 16,
                'package' => 'free',
                'mail_type' => 'wordpress',
                'title' =>  EmailLists::WP_NEW_REGISTER,
                'template_title' => EmailLists::wordpress_email(EmailLists::WP_NEW_REGISTER),
                'preview-thumb' => self::EMAILKIT_URL_TEMAPLTE_URL . 'templates/wp-new-register/1/preview-thumb.svg',
                'demo-url'  => 'https://wpmet.com/',
                'file' =>  self::EMAILKIT_URL_TEMAPLTE_DIR . 'templates/wp-new-register/1/content.json' ,
            ],
            'template-17' => [
                'id' => 102,
                'package' => 'free',
                'mail_type' => 'wordpress',
                'title' =>  EmailLists::WP_RESET_PASSWORD,
                'template_title' => EmailLists::wordpress_email(EmailLists::WP_RESET_PASSWORD),
                'preview-thumb' => self::EMAILKIT_URL_TEMAPLTE_URL . 'templates/wp-reset-password/1/preview-thumb.svg',
                'demo-url'  => 'https://wpmet.com/',
                'file' => self::EMAILKIT_URL_TEMAPLTE_DIR . 'templates/wp-reset-password/1/content.json'
            ],

            'template-18' => [
                'id' => 17,
                'package' => 'free',
                'mail_type' => 'woocommerce',
                'title' =>  EmailLists::PARTIAL_REFUND,
                'template_title' => EmailLists::woocommerce_email(EmailLists::PARTIAL_REFUND),
                'preview-thumb' => self::EMAILKIT_URL_TEMAPLTE_URL . 'templates/partial-refund/1/preview-thumb.svg',
                'demo-url'  => 'https://wpmet.com/',
                'file' => self::EMAILKIT_URL_TEMAPLTE_DIR . 'templates/partial-refund/1/content.json'
            ],

           
            
        ];

        return apply_filters( 'emailkit/editor/templates', $template_list );
    }


     public static function get(){

            $email_types = [
                
                'wordpress' => __('WordPress Email', 'emailkit'),
            ];

        // Include necessary WordPress files
        //   require_once ABSPATH . 'wp-admin/includes/plugin.php';
        //   require_once ABSPATH . 'wp-includes/class-wp.php';  // Adjust the path if necessary

          
            if(is_plugin_active(WP_PLUGIN_DIR . '/woocommerce/woocommerce.php')){
                $email_types['woocommerce'] = __('Woocommerce email', 'emailkit');
            }

            return apply_filters('emailkit_email_types',$email_types);
    }


    /**
     * Get template list by form type
     *
     * @param string $mail_type
     * @return array
     */
    public function get_templates_by_mail_type( $mail_type ) {
        $templates_list = [];

        // array filter
        $templates_list = array_filter( $this->get_templates(), function( $template ) use ( $mail_type ) {
            if(isset($template['mail_type'])){
                return $template['mail_type'] === $mail_type;
            }
            
            return true;
        } );

        return $templates_list;
    }

    public function get_template_contents($id){

        if(!array_key_exists($id, $this->get_templates()) || !file_exists($this->get_templates()[$id]['file'])){
            return null;
        }

        $template_file_url =  self::abs_path_to_url($this->get_templates()[$id]['file']);
        $content = wp_remote_get($template_file_url, ['sslverify' => false]);
        $content = json_decode(wp_remote_retrieve_body($content));

        return (!isset($content->content)) ? null : $content->content;
    }

    public static function abs_path_to_url( $path = '' ) {
		$url = str_replace(
			wp_normalize_path( untrailingslashit( ABSPATH ) ),
			site_url(),
			wp_normalize_path( $path )
		);
		return esc_url_raw( $url );
	}

}