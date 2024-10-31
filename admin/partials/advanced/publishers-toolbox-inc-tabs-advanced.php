<div id="advanced-tab-1" class="tab-content">
    <h3>Advanced Settings</h3>
    <div class="card">
        <div class="card-header">AMP</div>
        <div class="card-body">
            <?php if (!file_exists(WP_PLUGIN_DIR . '/amp/amp.php')) { ?>
                <div class="notice notice-error inline has-margin-bottom">
                    <p>Please install the AMP plugin:
                        <a href="plugin-install.php?s=AMP&tab=search&type=term" rel="noopener noreferrer">WordPress
                            Amp Plugin</a>.
                    </p>
                </div>
            <?php } elseif (!is_plugin_active('amp/amp.php')) { ?>
                <div class="notice notice-error inline has-margin-bottom">
                    <p>Please activate the AMP plugin:
                        <a href="plugins.php?s=amp&plugin_status=all" target="_blank" rel="noopener noreferrer">WordPress Amp Plugin</a>.</p>
                </div>
            <?php } ?>
            <div class="grid">
                <div class="form-item col-1-2 single"><?php echo $formFields->checkboxField('AMP', 'amp', 'advanced', 1, [
                        'description' => 'Switch on AMP.',
                        'status'      => !is_plugin_active('amp/amp.php'),
                        'message'     => 'Please Install or Activate AMP plugin.',
                    ]); ?>
                    <p>This will switch on AMP for your site. The PWA style will be used for the AMP template.</p>
                </div>
            </div>
        </div>
    </div>
    <div class="card">
        <div class="card-header">
            Display Options
        </div>
        <div class="card-body">
            <div class="grid">
                <div class="form-item col-1-1 single"><?php echo $formFields->checkboxField('Classic Website Switch', 'classic_switch', 'advanced', 1, ['description' => 'Switch on classic application switching.']); ?>
                    <p>This will add a button to your mobile site footer to switch between PWA and your Responsive
                        mobile site.</p>
                </div>
                <div class="cf">
                    <div class="form-item col-1-2">
                        <?php echo $formFields->textField('Search Parameter', 'search_parameter', 'advanced', '', [
                            'description' => 'The sites search parameter, if its using a custom action.',
                            'placeholder' => 's',
                        ]); ?>
                        <p><?php echo get_site_url(get_current_blog_id()) . '/?<span class="highlight-me">s</span>=mysearchterm'; ?>
                        </p>
                    </div>
                    <div class="form-item col-1-2">
                        <?php echo $formFields->textField('Search Action', 'search_action', 'advanced', '', [
                            'description' => 'The sites search action, if its using a custom action.',
                            'placeholder' => 'search',
                        ]); ?>
                        <p><?php echo get_site_url(get_current_blog_id()) . '/<span class="highlight-me">search</span>/?s=mysearchterm'; ?>
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="card">
        <div class="card-header">
            DNS Settings
        </div>
        <div class="card-body">
            <div class="grid">
                <div class="form-item col-1-2"><?php echo $formFields->textField('API Endpoint', 'api_endpoint', 'performance', get_site_url(get_current_blog_id()), ['description' => 'If the applications API is not available locally.']); ?></div>
                <div class="form-item col-1-2"><?php echo $formFields->textField('Application Endpoint', 'application_endpoint', 'performance', $endPoint, ['description' => 'Application CDN endpoint.']); ?></div>
                <div class="form-item col-1-2"><?php echo $formFields->textField('Media Service', 'media_endpoint', 'performance', $mediaPoint, ['description' => 'The application media cache endpoint.']); ?></div>
                <div class="form-item col-1-2"><?php echo $formFields->textField('CDN Host Overwrite', 'cdn_endpoint', 'performance', '', ['description' => 'The application CDN host url overwrite.']); ?></div>
                <div class="cf"></div>
                <div class="form-item col-1-1"><?php echo $formFields->textBoxField('DNS Prefetch List', 'dns_prefetch', 'performance', '', ['description' => 'List of DNS prefetch. (Comma-separated)']); ?></div>
            </div>
        </div>
    </div>
</div>
