<?php
namespace EmailKit\Promotional;
defined( 'ABSPATH' ) || exit;
use \Wpmet\UtilityPackage\Stories\Stories;
use \Wpmet\UtilityPackage\Notice\Notice;
use \Wpmet\UtilityPackage\Banner\Banner;
use \Wpmet\UtilityPackage\Rating\Rating;
use \Wpmet\UtilityPackage\Plugins\Plugins;

use \EmailKit\Promotional\ProAwareness\ProAwareness;
use \EmailKit\Promotional\Onboard\Onboard;
use \EmailKit\Promotional\Onboard\Attr;
class Promotional{
    
    function init(){
        
        /**
         * Show Eamilkit Notice
         */
        Notice::init();

        if( !class_exists( 'EmailKitPro' ) ){
            Notice::instance( 'emailkit', 'go-pro-notice' )   # @plugin_slug @notice_name
            ->set_dismiss( 'global', ( 3600 * 24 * 300 ) )                                          # @global/user @time_period
            ->set_type( 'warning' )                                                                 # @notice_type
            ->set_html(
                    '
                    <div class="ekit-go-pro-notice">
                        <strong>Thank you for using EmailKit.</strong> To get more amazing
                        features and the outstanding pro ready-made templates, please get the
                        <a style="color: #FCB214;" target="_blank"
                        href="https://wpmet.com/emailkit-pricing">Premium Version</a>.
                    </div>
                '
                )                                                                                     # @notice_massage_html
            ->call();
        }

        if( \EmailKit\Promotional\Util::get_settings( 'emailkit_user_consent_for_banner', 'yes' ) == 'yes' ){


            /**
			 * MetForm get free templates promotional class initialization
			 *
			 */
			if( !did_action('metform/after_load') && did_action('elementor/loaded') && class_exists('\EmailKit\Promotional\MetformPromoBanner\MetformPromoBanner') ) {
	
                new \EmailKit\Promotional\MetformPromoBanner\MetformPromoBanner();
			}

            /**
             * Show WPMET stories widget in the dashboard
             */
            
            $filter_string = ''; // elementskit,metform-pro
            $filter_string .= ((!in_array('elementskit/elementskit.php', apply_filters('active_plugins', get_option('active_plugins')))) ? '' : ',elementskit');
            $filter_string .= (!class_exists('\MetForm\Plugin') ? '' : ',metform');
            $filter_string .= (!class_exists('\MetForm_Pro\Plugin') ? '' : ',metform-pro');

            Stories::instance( 'metform' )   # @plugin_slug
            // ->is_test(true)                                                      # @check_interval
            ->set_filter( $filter_string )                                          # @active_plugins
            ->set_plugin( 'EmailKit', 'https://wpmet.com/plugin/metform/' )  # @plugin_name  @plugin_url
            ->set_api_url( 'https://api.wpmet.com/public/stories/' )                # @api_url_for_stories
            ->call();
            
            /**
             * Show WPMET banner (codename: jhanda)
             */
            
            Banner::instance( 'emailkit' )     # @plugin_slug
            // ->is_test(true)                                                      # @check_interval
            ->set_filter( ltrim( $filter_string, ',' ) )                            # @active_plugins
            ->set_api_url( 'https://api.wpmet.com/public/jhanda' )                  # @api_url_for_banners
            ->set_plugin_screens( 'edit-elementskit_template' )                     # @set_allowed_screen
            ->set_plugin_screens( 'toplevel_page_elementskit' )                     # @set_allowed_screen
            ->call();
        
            /**
             * Ask for Ratings 
             */            
            Rating::instance('emailkit')                    # @plugin_slug
            ->set_message('Creating custom emails with a no-code solution - <strong>EmailKit?</strong> 📩 </br>
            We would love to hear your thoughts! Share a <strong>5-star</strong> review to keep us motivated. 🙌')
            ->set_plugin_logo('https://ps.w.org/emailkit/assets/icon-128x128.png')       # @plugin_logo_url
            ->set_plugin('EmailKit', 'https://wpmet.com/wordpress.org/rating/emailkit')   # @plugin_name  @plugin_url
            ->set_rating_url('https://wordpress.org/support/plugin/emailkit/reviews/#new-post')
            ->set_support_url('https://wpmet.com/support-ticket')
            ->set_allowed_screens('edit-emailkit')                                 # @set_allowed_screen
            ->set_allowed_screens('edit-emailkit')                                  # @set_allowed_screen
            ->set_allowed_screens('emailkit_page_emailkit_get_help')                      # @set_allowed_screen
            ->set_priority(30)                                                          # @priority
            ->set_first_appear_day(7)                                                   # @time_interval_days
            ->set_condition(true)                                                       # @check_conditions
            ->call();
        }
        
        /**
         * Show our plugins menu for others wpmet plugins
         */
        
        Plugins::instance()->init('emailkit')                # @text_domain
        ->set_parent_menu_slug('emailkit-menu')                                      # @plugin_slug
        ->set_submenu_name(__('Our Plugins', 'emailkit'))
        ->set_section_title(__('Get More out of Your WordPress Website!', 'emailkit'))
        ->set_section_description(__('Revamp your website with other top plugins from us. And guess what, they\'re absolutely free!', 'emailkit'))                         # @section_description (optional)
        ->set_items_per_row(4)                                                      # @items_per_row (optional- default: 6)
        ->set_plugins(                                                              # @plugins
        [
            'elementskit-lite/elementskit-lite.php' => [
                'name' => __('ElementsKit Elementor addons', 'emailkit'),
                'url'  => 'https://wordpress.org/plugins/elementskit-lite/',
                'icon' => 'https://ps.w.org/elementskit-lite/assets/icon-256x256.gif?rev=2518175',
                'desc' => __('All-in-one Elementor addon trusted by 1 Million+ users, makes your website builder process easier with ultimate freedom.', 'emailkit'),
                'docs' => 'https://wpmet.com/docs/elementskit/',
            ],
            'gutenkit-blocks-addon/gutenkit-blocks-addon.php' => [
                'name' => __('GutenKit', 'emailkit'),
                'url'  => 'https://wordpress.org/plugins/gutenkit-blocks-addon/',
                'icon' => 'https://ps.w.org/gutenkit-blocks-addon/assets/icon-128x128.gif?rev=3116270',
                'desc' => __('Gutenberg blocks, patterns, and templates that extend the page-building experience using the WordPress block editor.', 'emailkit'),
                'docs' => 'https://wpmet.com/doc/gutenkit/',
            ],
            'rox-dynamic-cpt-fields-engine/rox-dynamic-cpt-fields-engine.php' => [
                'name' => esc_html__('Rox Dynamic CPT Fields', 'emailkit'),
                'url'  => 'https://wordpress.org/plugins/rox-dynamic-cpt-fields-engine/',
                'icon' => 'https://ps.w.org/rox-dynamic-cpt-fields-engine/assets/icon-256x256.jpeg?rev=3538537',
                'desc' => esc_html__('Build custom post types, fields, taxonomies, and dynamic frontend layouts for WordPress, with zero coding and full AI-generated schema.', 'emailkit'),
                'docs' => 'https://wpmet.com/doc/rox-dynamic-cpt-fields-engine/',
            ],
            'rox-appointment-booking/rox-appointment-booking.php' => [
                'name' => esc_html__('Rox Appointment Booking', 'emailkit'),
                'url'  => 'https://wordpress.org/plugins/rox-appointment-booking/',
                'icon' => 'https://ps.w.org/rox-appointment-booking/assets/icon-256x256.png?rev=3575641',
                'desc' => esc_html__('Manage bookings, agents, payments, and calendars from one dashboard! A complete appointment and scheduling solution for WordPress.', 'emailkit'),
                'docs' => 'https://wpmet.com/doc/rox-appointment-booking/',
            ],
            'metform/metform.php' => [
                'name' => __('MetForm', 'emailkit'),
                'url'  => 'https://wordpress.org/plugins/metform/',
                'icon' => 'https://ps.w.org/metform/assets/icon-256x256.png',
                'desc' => __('Drag & drop form builder for Elementor to create contact forms, multi-step forms, and more — smoother, faster, and better!', 'emailkit'),
                'docs' => 'https://wpmet.com/doc/metform/',
            ],
            'shopengine/shopengine.php' => [
                'name' => __('Shopengine', 'emailkit'),
                'url'  => 'https://wordpress.org/plugins/shopengine/',
                'icon' => 'https://ps.w.org/shopengine/assets/icon-256x256.gif?rev=2505061',
                'desc' => __('Complete WooCommerce solution for Elementor to fully customize any pages including cart, checkout, shop page, and so on.', 'emailkit'),
                'docs' => 'https://wpmet.com/doc/shopengine/',
            ],
            'popup-builder-block/popup-builder-block.php' => [
				'name' => esc_html__('PopupKit', 'emailkit'),
				'url'  => 'https://wordpress.org/plugins/popup-builder-block/',
				'icon' => 'https://ps.w.org/popup-builder-block/assets/icon-256x256.png?rev=3316844',
				'desc' => esc_html__('Design popups that convert, right in your WordPress dashboard.', 'emailkit'),
				'docs' => 'https://wpmet.com/doc/popupkit/',
            ],
			'table-builder-block/table-builder-block.php' => [
				'name' => esc_html__('TableKit', 'emailkit'),
				'url'  => 'https://wordpress.org/plugins/table-builder-block/',
				'icon' => 'https://ps.w.org/table-builder-block/assets/icon-256x256.png?rev=3509972',
				'desc' => esc_html__('Fully Customizable. Multi-Media Integration. Synch Any Data Files. All Within Block Editor.', 'emailkit'),
				'docs' => 'https://wpmet.com/doc/tablekit/',
			],
            'getgenie/getgenie.php' => [
                'name' => __('GetGenie', 'emailkit'),
                'url'  => 'https://wordpress.org/plugins/getgenie/',
                'icon' => 'https://ps.w.org/getgenie/assets/icon-256x256.gif?rev=2798355',
                'desc' => __('Your personal AI assistant for content and SEO. Write content that ranks on Google with NLP keywords and SERP analysis data.', 'emailkit'),
                'docs' => 'https://getgenie.ai/docs/',
            ],
            'wp-social/wp-social.php' => [
                'name' => __('WP Social', 'emailkit'),
                'url'  => 'https://wordpress.org/plugins/wp-social/',
                'icon' => 'https://ps.w.org/wp-social/assets/icon-256x256.png?rev=2544214',
                'desc' => __('Add social share, login, and engagement counter — unified solution for all social media with tons of different styles for your website.', 'emailkit'),
                'docs' => 'https://wpmet.com/doc/wp-social/',
            ],
            'blocks-for-shopengine/shopengine-gutenberg-addon.php' => [
                'name' => __('Blocks for ShopEngine', 'emailkit'),
                'url'  => 'https://wordpress.org/plugins/blocks-for-shopengine/',
                'icon' => 'https://ps.w.org/blocks-for-shopengine/assets/icon-256x256.gif?rev=2702483',
                'desc' => __('All in one WooCommerce solution for Gutenberg! Build your WooCommerce pages in a block editor with full customization.', 'emailkit'),
                'docs' => 'https://wpmet.com/doc/shopengine/shopengine-gutenberg/',
            ],
            
        ]
        )
        ->call();
       
        $is_pro_active = '';

        if (!in_array('emailkit-pro/emailkit-pro.php', apply_filters('active_plugins', get_option('active_plugins')))) {
            $is_pro_active = __('Go Premium', 'emailkit');
        }

        $pro_awareness = ProAwareness::instance('emailkit');
        $pro_awareness
            ->set_parent_menu_slug('emailkit-menu')
            ->set_pro_link(
                (in_array('emailkit-pro/emailkit-pro.php', apply_filters('active_plugins', get_option('active_plugins')))) ? '' :
                    'https://wpmet.com/emailkit-pricing'
            )
            ->set_plugin_file('emailkit/EmailKit.php')
            ->set_default_grid_thumbnail(EMAILKIT_URL . '/Promotional/ProAwareness/assets/images/support.png')
            ->set_page_grid([
                'url' => 'https://wpmet.com/fb-group',
                'title' => __('Join the Community', 'emailkit'),
                'thumbnail' => EMAILKIT_URL . '/Promotional/ProAwareness/assets/images/community.png',
                'description' => __('Join our Facebook group to get 20% discount coupon on premium products. Follow us to get more exciting offers.', 'emailkit')

            ])
            ->set_page_grid([
                'url' => 'https://www.youtube.com/watch?v=Fz_1M-s_Faw&list=PL3t2OjZ6gY8O0ul4d9KROcQMyoSaTad6N',
                'title' => __('Video Tutorials', 'emailkit'),
                'thumbnail' => EMAILKIT_URL . '/Promotional/ProAwareness/assets/images/videos.png',
                'description' => __('Learn the step by step process for developing your site easily from video tutorials.', 'emailkit')
            ])
            ->set_page_grid([
                'url' => 'https://wpmet.com/plugin/emailkit/roadmaps#ideas',
                'title' => __('Request a feature', 'emailkit'),
                'thumbnail' => EMAILKIT_URL . '/Promotional/ProAwareness/assets/images/request.png',
                'description' => __('Have any special feature in mind? Let us know through the feature request.', 'emailkit')
            ])
            ->set_page_grid([
                    'url'       => 'https://wpmet.com/doc/emailkit/',
                    'title'     =>  __('Documentation', 'emailkit'),
                    'thumbnail' => EMAILKIT_URL . '/Promotional/ProAwareness/assets/images/documentation.png',
                    'description' => __('Detailed documentation to help you understand the functionality of each feature.', 'emailkit')
            ])
            ->set_page_grid([
                    'url'       => 'https://wpmet.com/plugin/emailkit/roadmaps/',
                    'title'     => __('Public Roadmap', 'emailkit'),
                    'thumbnail' => EMAILKIT_URL . '/Promotional/ProAwareness/assets/images/roadmaps.png',
                    'description' => __('Check our upcoming new features, detailed development stories and tasks', 'emailkit')
            ])

            ->set_plugin_row_meta(__('Documentation', 'emailkit'), 'https://help.wpmet.com/docs-cat/emailkit/', ['target' => '_blank'])
            ->set_plugin_row_meta(__('Facebook Community', 'emailkit'), 'https://wpmet.com/fb-group', ['target' => '_blank'])
            ->set_plugin_row_meta(__('Rate the plugin ★★★★★', 'emailkit'), 'https://wordpress.org/support/plugin/emailkit/reviews/#new-post', ['target' => '_blank'])
            ->set_plugin_action_link(__('Settings', 'emailkit'), admin_url() . 'admin.php?page=emailkit-menu-settings')
            ->set_plugin_action_link($is_pro_active, 'https://wpmet.com/plugin/emailkit/pricing/', ['target' => '_blank', 'style' => 'color: #FCB214; font-weight: bold;'])
            ->call();
            
            if( ! $this->already_onboarded_other() ){

                Onboard::instance()->init();

                if(isset($_GET['emailkit-onboard-steps']) && isset($_GET['emailkit-onboard-steps-nonce']) && wp_verify_nonce(sanitize_text_field(wp_unslash($_GET['emailkit-onboard-steps-nonce'])),'emailkit-onboard-steps-action')) {
                    Attr::instance();                    
                }
            }
            
            add_action('emailkit-settings', function(){

                if( ! $this->already_onboarded_other() ){
                
                    if(isset($_GET['emailkit-onboard-steps']) && $_GET['emailkit-onboard-steps'] == 'loaded' && isset($_GET['emailkit-onboard-steps-nonce'])  && wp_verify_nonce(sanitize_text_field(wp_unslash($_GET['emailkit-onboard-steps-nonce'])),'emailkit-onboard-steps-action')) {
                        wp_enqueue_style( 'emailkit-steps-css-steps' ); // needed when onboardning
                        Onboard::instance()->views();
                        return; // Exit early during onboarding to prevent showing ProConsent form
                    }
                }

                // Only show ProConsent form when not in onboarding mode
                (new \EmailKit\Promotional\ProConsent())->get_consent_form();
            }
        );
    }

    /**
     * Check if user already onboarded for any of the plugins (elements_kit, met_form or shopengine).
     * 
     * @return bool
     * 
     * @since 1.5.4
     */
    public function already_onboarded_other() {

        return get_option( 'elements_kit_onboard_status' ) == 'onboarded' || get_option( 'met_form_onboard_status' ) == 'onboarded' || get_option( 'shopengine_onboard_status' ) == '1';
    }

}