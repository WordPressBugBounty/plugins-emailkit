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
                        <strong>Thank you for using EmailKit .</strong> To get more amazing 
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
            ->set_message('<strong>Loving the EmailKit email-building experience? 😀 </strong> </br> 
            Share your feedback, like a 5-star review, and motivate us to take EmailKit to the next level! 💯')
            ->set_plugin_logo('https://ps.w.org/emailkit/assets/icon-128x128.png')       # @plugin_logo_url
            ->set_plugin('EmailKit', 'https://wpmet.com/wordpress.org/rating/emailkit')   # @plugin_name  @plugin_url
            ->set_rating_url('https://wordpress.org/support/plugin/emailkit/reviews/#new-post')
            ->set_support_url('https://help.wpmet.com/')
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
        ->set_submenu_name(esc_html__('Our Plugins', 'emailkit'))
        ->set_section_title( esc_html__('Get More out of Your WordPress Website!', 'emailkit'))
        ->set_section_description(esc_html__('Revamp your website with other top plugins from us. And guess what, they\'re absolutely free!', 'emailkit'))                         # @section_description (optional)
        ->set_items_per_row(4)                                                      # @items_per_row (optional- default: 6)
        ->set_plugins(                                                              # @plugins
        [
            'elementskit-lite/elementskit-lite.php' => [
                'name' => esc_html__('ElementsKit Elementor addons', 'emailkit'),
                'url'  => 'https://wordpress.org/plugins/elementskit-lite/',
                'icon' => 'https://ps.w.org/elementskit-lite/assets/icon-256x256.gif?rev=2518175',
                'desc' => esc_html__('All-in-one Elementor addon trusted by 1 Million+ users, makes your website builder process easier with ultimate freedom.', 'emailkit'),
                'docs' => 'https://wpmet.com/docs/elementskit/',
            ],
            'getgenie/getgenie.php' => [
                'name' => esc_html__('GetGenie', 'emailkit'),
                'url'  => 'https://wordpress.org/plugins/getgenie/',
                'icon' => 'https://ps.w.org/getgenie/assets/icon-256x256.gif?rev=2798355',
                'desc' => esc_html__('Your personal AI assistant for content and SEO. Write content that ranks on Google with NLP keywords and SERP analysis data.', 'emailkit'),
                'docs' => 'https://getgenie.ai/docs/',
            ],
            'gutenkit-blocks-addon/gutenkit-blocks-addon.php' => [
                'name' => esc_html__('GutenKit', 'emailkit'),
                'url'  => 'https://wordpress.org/plugins/gutenkit-blocks-addon/',
                'icon' => 'https://ps.w.org/gutenkit-blocks-addon/assets/icon-128x128.png?rev=3044956',
                'desc' => esc_html__('Gutenberg blocks, patterns, and templates that extend the page-building experience using the WordPress block editor.', 'emailkit'),
                'docs' => 'https://wpmet.com/doc/gutenkit/',
            ],
            'shopengine/shopengine.php' => [
                'name' => esc_html__('Shopengine', 'emailkit'),
                'url'  => 'https://wordpress.org/plugins/shopengine/',
                'icon' => 'https://ps.w.org/shopengine/assets/icon-256x256.gif?rev=2505061',
                'desc' => esc_html__('Complete WooCommerce solution for Elementor to fully customize any pages including cart, checkout, shop page, and so on.', 'emailkit'),
                'docs' => 'https://wpmet.com/doc/shopengine/',
            ],
            'metform/metform.php' => [
                'name' => esc_html__('MetForm', 'emailkit'),
                'url'  => 'https://wordpress.org/plugins/metform/',
                'icon' => 'https://ps.w.org/metform/assets/icon-256x256.png',
                'desc' => esc_html__('Drag & drop form builder for Elementor to create contact forms, multi-step forms, and more — smoother, faster, and better!', 'emailkit'),
                'docs' => 'https://wpmet.com/doc/metform/',
            ],

            'wp-social/wp-social.php' => [
                'name' => esc_html__('WP Social', 'emailkit'),
                'url'  => 'https://wordpress.org/plugins/wp-social/',
                'icon' => 'https://ps.w.org/wp-social/assets/icon-256x256.png?rev=2544214',
                'desc' => esc_html__('Add social share, login, and engagement counter — unified solution for all social media with tons of different styles for your website.', 'emailkit'),
                'docs' => 'https://wpmet.com/doc/wp-social/',
            ],
            
            'wp-ultimate-review/wp-ultimate-review.php' => [
                'name' => esc_html__('WP Ultimate Review', 'emailkit'),
                'url'  => 'https://wordpress.org/plugins/wp-ultimate-review/',
                'icon' => 'https://ps.w.org/wp-ultimate-review/assets/icon-256x256.png?rev=2544187',
                'desc' => esc_html__('Collect and showcase reviews on your website to build brand credibility and social proof with the easiest solution.', 'emailkit'),
                'docs' => 'https://wpmet.com/doc/wp-ultimate-review/',
            ],

            'wp-fundraising-donation/wp-fundraising.php' => [
                'name' => esc_html__('FundEngine', 'emailkit'),
                'url'  => 'https://wordpress.org/plugins/wp-fundraising-donation/',
                'icon' => 'https://ps.w.org/wp-fundraising-donation/assets/icon-256x256.png?rev=2544150',
                'desc' => esc_html__('Create fundraising, crowdfunding, and donation websites with PayPal and Stripe payment gateway integration.', 'emailkit'),
                
                'docs' => 'https://wpmet.com/doc/fundengine/',
            ],
            'blocks-for-shopengine/shopengine-gutenberg-addon.php' => [
                'name' => esc_html__('Blocks for ShopEngine', 'emailkit'),
                'url'  => 'https://wordpress.org/plugins/blocks-for-shopengine/',
                'icon' => 'https://ps.w.org/blocks-for-shopengine/assets/icon-256x256.gif?rev=2702483',
                'desc' => esc_html__('All in one WooCommerce solution for Gutenberg! Build your WooCommerce pages in a block editor with full customization.', 'emailkit'),
                'docs' => 'https://wpmet.com/doc/shopengine/shopengine-gutenberg/',
            ],
            'genie-image-ai/genie-image-ai.php' => [
                'name' => esc_html__('Genie Image', 'emailkit'),
                'url'  => 'https://wordpress.org/plugins/genie-image-ai/',
                'icon' => 'https://ps.w.org/genie-image-ai/assets/icon-256x256.png?rev=2977297',
                'desc' => esc_html__('AI-powered text-to-image generator for WordPress with OpenAI’s DALL-E 2 technology to generate high-quality images in one click.', 'emailkit'),
                'docs' => 'https://getgenie.ai/docs/',
            ],
            
        ]
        )
        ->call();
       
        $is_pro_active = '';

        if (!in_array('emailkit-pro/emailkit-pro.php', apply_filters('active_plugins', get_option('active_plugins')))) {
            $is_pro_active = 'Go Premium';
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
                'title' => esc_html__('Join the Community', 'emailkit'),
                'thumbnail' => EMAILKIT_URL . '/Promotional/ProAwareness/assets/images/community.png',
                'description' => esc_html__('Join our Facebook group to get 20% discount coupon on premium products. Follow us to get more exciting offers.', 'emailkit')

            ])
            ->set_page_grid([
                'url' => 'https://www.youtube.com/watch?v=Fz_1M-s_Faw&list=PL3t2OjZ6gY8O0ul4d9KROcQMyoSaTad6N',
                'title' => esc_html__('Video Tutorials', 'emailkit'),
                'thumbnail' => EMAILKIT_URL . '/Promotional/ProAwareness/assets/images/videos.png',
                'description' => esc_html__('Learn the step by step process for developing your site easily from video tutorials.', 'emailkit')
            ])
            ->set_page_grid([
                'url' => 'https://wpmet.com/plugin/emailkit/roadmaps#ideas',
                'title' => esc_html__('Request a feature', 'emailkit'),
                'thumbnail' => EMAILKIT_URL . '/Promotional/ProAwareness/assets/images/request.png',
                'description' => esc_html__('Have any special feature in mind? Let us know through the feature request.', 'emailkit')
            ])
            ->set_page_grid([
                    'url'       => 'https://wpmet.com/doc/emailkit/',
                    'title'     => esc_html__('Documentation', 'emailkit'),
                    'thumbnail' => EMAILKIT_URL . '/Promotional/ProAwareness/assets/images/documentation.png',
                    'description' => esc_html__('Detailed documentation to help you understand the functionality of each feature.', 'emailkit')
            ])
            ->set_page_grid([
                    'url'       => 'https://wpmet.com/plugin/emailkit/roadmaps/',
                    'title'     => esc_html__('Public Roadmap', 'emailkit'),
                    'thumbnail' => EMAILKIT_URL . '/Promotional/ProAwareness/assets/images/roadmaps.png',
                    'description' => esc_html__( 'Check our upcoming new features, detailed development stories and tasks', 'emailkit')
            ])

            ->set_plugin_row_meta('Documentation', 'https://help.wpmet.com/docs-cat/emailkit/', ['target' => '_blank'])
            ->set_plugin_row_meta('Facebook Community', 'https://wpmet.com/fb-group', ['target' => '_blank'])
            ->set_plugin_row_meta('Rate the plugin ★★★★★', 'https://wordpress.org/support/plugin/emailkit/reviews/#new-post', ['target' => '_blank'])
            ->set_plugin_action_link('Settings', admin_url() . 'admin.php?page=emailkit-menu-settings')
            ->set_plugin_action_link($is_pro_active, 'https://wpmet.com/plugin/emailkit', ['target' => '_blank', 'style' => 'color: #FCB214; font-weight: bold;'])
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
                    }
                }

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