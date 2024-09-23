<li data-mail-type="<?php echo esc_attr($template['mail_type']); ?>" data-template-type="<?php echo esc_attr(trim($template['title'])); ?>" class="emailkit-template-item emailkit-template-item<?php echo isset($template['package']) ? ' emailkit-template-item--' . esc_attr($template['package']) : ''; ?>

<?php echo (isset($template['package']) && $template['file'] === '') ? ' emailkit-template-item--go_pro' : ''; ?>">
    <label>
        <input class="emailkit-template-radio" name="emailkit-editor-template" type="radio" value="<?php echo esc_attr ($template['file'] );?>"<?php echo esc_attr($template['file'] === '' ? 'disabled="disabled"' : ''); ?>>
        <div class="emailkit-template-radio-data">
            <img src="<?php echo esc_url($template['preview-thumb']); ?>" alt="<?php echo esc_attr($template['template_title']) ?>">

            <?php if(isset($template['package']) && $template['package'] === 'pro') : ?>
                <div class="emailkit-template-radio-data--tag">
                    <span class="emailkit-template-radio-data--pro_tag"><?php echo esc_html(ucfirst($template['package'])); ?></span>
                </div>
            <?php endif; ?>

            <div class="emailkit-template-footer-content">
                <?php if(isset($template['template_title']) && $template['template_title'] != '') : ?>
                    <div class="emailkit-template-footer-title">
                        <h2><?php echo esc_html($template['template_title']); ?></h2>
                    </div>
                <?php endif; ?>
                
                <div class="emailkit-template-footer-links">
                    <?php if(isset($template['package']) && $template['package'] === 'pro' && isset($template['file']) && $template['file'] == '') : ?>
                        <a target="_blank" href="https://wpmet.com/emailkit-pricing/" class="emailkit-template-footer-links--pro_tag"><i class="emailkit-template-footer-links--icon fas fa-external-link-square-alt"></i><?php echo esc_html__('Buy Pro', 'emailkit'); ?></a>
                    <?php endif; ?>

                    <?php if(isset($template['demo-url']) && $template['demo-url'] != '') : ?>
                        <a target="_blank" class="emailkit-template-footer-links--demo_link" href="<?php echo esc_attr(ucfirst($template['demo-url'])); ?>"><i class="emailkit-template-footer-links--icon far fa-eye"></i><?php echo esc_html__('Demo', 'emailkit'); ?></a>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </label>
</li>