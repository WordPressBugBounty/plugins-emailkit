<?php 
namespace EmailKit\Promotional;
defined('ABSPATH') || exit;
class ProConsent{
  public function get_consent_form(){
    ?>
                
        <style>
        .emailkit-user-consent-for-banner{
            margin: 0 0 15px 0!important;
            width: 842px;
            max-width: 1350px;
        }
        .emailkit-success-notice {
            position: fixed;
            top: 50px;
            right: 20px;
            background-color: #14c87c;
            color: white;
            padding: 10px;
            border-radius: 5px;
            display: none;
        }

        </style>
        <script>
        jQuery(document).ready(function ($) {
            "use strict";
            $('#emailkit-admin-switch__emailkit-user-consent-for-banner').on('change', function(){
                let val = ($(this).prop("checked") ? $(this).val() : 'no');
                let data = {
                    'settings' : {
                        'emailkit_user_consent_for_banner' : val, 
                    }, 
                    'nonce': "<?php echo esc_html(wp_create_nonce( 'ajax-nonce' )); ?>"
                };

                $.post( ajaxurl + '?action=emailkit_admin_action', data, function( data ) {
                    $('#success-notice').fadeIn().delay(1000).slideUp(); 
                });
            });
        }); // end ready function
        </script>


        <div id="success-notice" class="emailkit-success-notice"><?php esc_html_e( 'Success! Your action was completed.', 'emailkit' ); ?></div>
        <div class="emailkit-user-consent-for-banner notice notice-error">
        <p>
            <input type="checkbox" <?php echo esc_attr( \EmailKit\Promotional\Util::get_settings( 'emailkit_user_consent_for_banner', 'yes' ) == 'yes' ? 'checked' : '' ); ?>  value="yes" class="emailkit-admin-control-input" name="emailkit-user-consent-for-banner" id="emailkit-admin-switch__emailkit-user-consent-for-banner">
            <label for="emailkit-admin-switch__emailkit-user-consent-for-banner"><?php esc_html_e( 'Show update & fix related important messages, essential tutorials and promotional images on WP Dashboard', 'emailkit' ); ?></label>

        </p>
        </div>
    <?php
    }
}