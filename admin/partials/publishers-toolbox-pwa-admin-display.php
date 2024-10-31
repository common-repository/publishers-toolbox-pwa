<?php
    /**
     * Provide a admin area view for the plugin
     *
     * This file is used to markup the admin-facing aspects of the plugin.
     *
     * @package    PublishersToolboxPWA
     * @subpackage PublishersToolboxPWA/admin/partials
     *
     * @since 2.0.0
     */
?>
<hr class="wp-header-end">
<section class="<?php echo $this->pluginName; ?>">
    <div class="header">
        <div class="grid">
            <div class="col-1-2">
                <img src="<?php echo plugin_dir_url(__DIR__) . $imageChange; ?>" alt="<?php echo esc_html(get_admin_page_title()); ?>" class="logo">
            </div>
            <div class="col-1-2">
                <div class="is-right">
                    <p class="is-text-right">
                        Version: <?php echo PUBLISHERS_TOOLBOX_PLUGIN_VERSION; ?>
                        <br>Subscription:
                        <?php if (isset($subscriptionOptions['status']) && $subscriptionOptions['status'] !== 'free') { ?>
                            <?php echo $subscriptionOptions['status'] === 'trialing' ? '15 Day Trial' : 'Active Subscription'; ?>
                        <?php } else { ?>Free<?php } ?>
                    </p>
                </div>
            </div>
        </div>
    </div>
    <div class="wrap">
        <section class="start-page">
            <div class="grid">
                <div class="col-2-12">
                    <div class="col-1-1 menu-block">
                        <ul>
                            <?php require_once 'settings/publishers-toolbox-inc-menu-settings.php' ?>
                            <?php require_once 'design/publishers-toolbox-inc-menu-design.php' ?>
                            <?php require_once 'content/publishers-toolbox-inc-menu-content.php' ?>
                            <?php require_once 'advanced/publishers-toolbox-inc-menu-advanced.php' ?>
                            <?php require_once 'premium/publishers-toolbox-inc-menu-premium.php' ?>
                        </ul>
                    </div>
                </div>
                <div class="col-10-12">
                    <div class="grid">
                        <div class="col-2-3 content-block">
                            <form class="plugin-options-form" id="plugin-options-form">
                                <input type="hidden" name="version" value="<?php echo $this->pluginVersion; ?>">
                                <?php echo $formFields->textField('', '@type', '', 'PtPwaTheme', ['type' => 'hidden']); ?>
                                <?php echo $formFields->textField('', 'application', '', 'pt-pwa', ['type' => 'hidden']); ?>
                                <input type="hidden" name="premium" value="<?php echo !$checkActiveStatus ? true : false; ?>">
                                <?php echo $formFields->textField('', 'host_url', '', get_site_url(get_current_blog_id()), ['type' => 'hidden']); ?>
                                <?php echo $formFields->textField('', 'last_save', '', date('Y-m-d H:i:s'), ['type' => 'hidden']); ?>
                                <button class="btn is-secondary is-right" id="options-admin-save" form="plugin-options-form" type="submit"><?php _e('Save All', $this->pluginName); ?></button>
                                <?php require_once 'settings/publishers-toolbox-inc-tabs-settings.php' ?>
                                <?php require_once 'design/publishers-toolbox-inc-tabs-design.php' ?>
                                <?php require_once 'content/publishers-toolbox-inc-tabs-content.php' ?>
                                <?php require_once 'advanced/publishers-toolbox-inc-tabs-advanced.php' ?>
                                <?php require_once 'premium/publishers-toolbox-inc-tabs-premium.php' ?>
                            </form>
                            <div class="grid">
                                <div class="col-1-1">
                                    <button class="btn is-secondary is-right" id="options-admin-save" form="plugin-options-form" type="submit"><?php _e('Save All', $this->pluginName); ?></button>
                                </div>
                            </div>
                        </div>
                        <?php if (array_key_exists('active', $themeOptions) && $previewActive) { ?>
                            <div class="col-1-3 content-block">
                                <?php require_once 'preview/publishers-toolbox-inc-preview.php' ?>
                            </div>
                        <?php } ?>
                    </div>
                </div>
            </div>
        </section>
    </div>
</section>
