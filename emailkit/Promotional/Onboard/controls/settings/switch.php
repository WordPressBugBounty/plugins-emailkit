<div class="attr-input attr-input-switch <?php echo esc_attr($class); ?>">
<?php
    // note:
    // $options['large_img'] $options['icon'] $options['small_img'] self::strify($name) $label $value
    // $options['checked'] true / false
    $no_demo = [
        'sticky-content',
        'nav-menu',
        'header-info',
        'elementskit-icon-pack',
        'back-to-top',
        'image-swap',
        'facebook-messenger',
        'advanced-tooltip',
        'fluent-forms',
        'zoom',
        'trustpilot'
    ];
?>
    <div class="emailkit-admin-input-switch emailkit-admin-card-shadow attr-card-body">
        <input <?php echo esc_attr($options['checked'] === true ? 'checked' : ''); ?> 
            type="checkbox" value="<?php echo esc_attr($value); ?>" 
            class="emailkit-admin-control-input" 
            name="<?php echo esc_attr($name); ?>" 
            id="emailkit-admin-switch__<?php echo esc_attr(self::strify($name) . $value); ?>"

            <?php 
            if(isset($attr)){
                foreach($attr as $k => $v){
                    echo esc_attr("$k='$v'");
                }
            }
            ?>
        >

        <label class="emailkit-admin-control-label"  for="emailkit-admin-switch__<?php echo esc_attr(self::strify($name) . $value); ?>">
            <span class="emailkit-admin-control-label-text"><?php echo esc_html($label); ?></span>
            <span class="emailkit-admin-control-label-switch" data-active="ON" data-inactive="OFF"></span>
        </label>

       
    </div>
    <?php 
    $slug = 'elementskit/';
    if( !in_array($value, $no_demo) ) :
        if($value === 'parallax'){
            $value = 'effects';
        }
    ?>
        <a target="_blank" href="https://wpmet.com/plugin/<?php echo esc_attr($slug) . esc_attr($value); ?>/" class="emailkit-admin-demo-tooltip"><i class="fa fa-laptop"></i><?php esc_html_e('View Demo', 'emailkit'); ?></a>
    <?php endif; ?>
</div>