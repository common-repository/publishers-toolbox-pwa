<div id="settings-tab-1" class="tab-content active">
    <h3>General</h3>
    <div class="card">
        <div class="card-header">
            Activate PWA
        </div>
        <div class="card-body">
            <div class="grid">
                <div class="form-item col-1-1 single"><?php echo $formFields->checkboxField('Switch on PWA', 'active', '', 1, [
                        'description' => 'Switch the PWA feature on or off.',
                        'eventClass'  => 'display_next',
                    ]); ?>
                    <div class="grid hidden-pwa cf has-margin-top grid-top">
                        <div class="form-item col-1-2 no-margin">
                            <?php echo $formFields->checkboxField('Push Notifications', 'push', '', 1, [
                                'description' => 'Use push notifications?',
                                'status'      => $checkActiveStatus,
                                'checked'     => 1,
                            ]); ?>
                        </div>
                        <div class="form-item col-1-2 no-margin">
                            <?php echo $formFields->checkboxField('Preview Window', 'preview', '', 1, [
                                'description' => 'Display preview window?',
                                'checked'     => 1,
                            ]); ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="card">
        <div class="card-header">
            Application Settings
        </div>
        <div class="card-body">
            <div class="grid">
                <div class="form-item col-1-2"><?php echo $formFields->textField('Application Name:', 'app_name', 'settings', get_bloginfo('name'), [
                        'description' => 'Your applications unique name.',
                        'validate'    => true,
                        'placeholder' => get_bloginfo('name'),
                        'api'         => 'appName',
                    ]); ?></div>
                <div class="form-item col-1-2"><?php echo $formFields->textField('Application Description:', 'app_description', 'settings', get_bloginfo('description'), [
                        'description' => 'A brief description for your application.',
                        'placeholder' => get_bloginfo('description'),
                        'validate'    => true,
                        'api'         => 'metaDescription',
                    ]); ?></div>
            </div>
        </div>
    </div>
    <div class="card">
        <div class="card-header">
            Application Branding
        </div>
        <div class="card-body">
            <div class="grid">
                <div class="form-item col-1-2"><?php echo $formFields->imageUploadField('Application Icon:', 'app_icon', 'branding', [
                        'default'     => 'admin/assets/img/icons/icon-72x72.png',
                        'description' => 'Please make sure that the icon is a square image 512x512 or higher quality.',
                    ]); ?></div>
                <div class="form-item col-1-2"><?php echo $formFields->imageUploadField('Application Logo:', 'app_logo', 'branding', [
                        'default'     => 'admin/assets/img/logo.png',
                        'description' => 'High quality landscape logo will look best - 1024x512 minimum size is ideal.',
                    ]); ?></div>
            </div>
        </div>
    </div>
</div>
<!--Advertisement-->
<div id="settings-tab-2" class="tab-content">
    <h3>Advertisement</h3>
    <div class="card">
        <div class="card-header">
            Ad Options: <strong>Google Advertisement</strong>
        </div>
        <?php $adSizesMobile = [
            '[320, 50]',
            '[250, 250]',
            '[200, 200]',
            '[728, 90]',
            '[300, 250]',
            '[336, 280]',
            '[120, 600]',
            '[160, 600]',
            '[300, 600]',
            '[970, 90]',
            '[728, 90]',
        ]; ?>
        <div class="card-body">
            <div class="grid">
                <div class="form-item col-1-1 single"><?php echo $formFields->checkboxField('Show Adverts?', 'active', 'advertisement', 1, [
                        'description' => 'Show adverts on your pwa?',
                        'status'      => $checkActiveStatus,
                    ]); ?>
                    <div class="grid cf has-margin-top">
                        <div class="form-item col-1-1 has-margin-top">
                            <div class="form-item col-1-2"><?php echo $formFields->textField('Ad Network Id:', 'network_id', 'advertisement', '', [
                                    'description' => 'Your unique Advertising ID.',
                                    'placeholder' => 'Network Id',
                                    'sub'         => 'google',
                                    'status'      => $checkActiveStatus,
                                ]); ?></div>
                            <div class="form-item col-1-2"><?php echo $formFields->textField('Targeting Arguments:', 'targeting_arguments', 'advertisement', '', [
                                    'description' => 'The targeting arguments defined for your ad slots.',
                                    'placeholder' => '',
                                    'sub'         => 'google',
                                    'status'      => $checkActiveStatus,
                                ]); ?></div>
                            <div class="form-item col-1-2"><?php echo $formFields->textField('Ad Slot:', 'ad_unit_mobile', 'advertisement', '', [
                                    'description' => 'Your mobile advert slot.',
                                    'placeholder' => 'Ad Unit for mobile.',
                                    'sub'         => 'google',
                                    'status'      => $checkActiveStatus,
                                ]); ?></div>
                            <div class="form-item col-1-2">
                                <?php echo $formFields->selectField('Select Ad Size:', 'ad_size_mobile', 'advertisement', $adSizesMobile, [
                                    'description' => 'The ad size that needs to be displayed for mobile.',
                                    'default'     => '[320, 50]',
                                    'sub'         => 'google',
                                    'status'      => $checkActiveStatus,
                                ]); ?>
                            </div>
                        </div>
                        <div class="cf">
                            <div class="form-item col-1-2">
                                <?php echo $formFields->sliderField('List Ad Interval', 'ad_list_interval', 'advertisement', 0, [
                                    'description' => 'Margin to push bottom advert up, if it covers social share buttons.',
                                    'min'         => 0,
                                    'max'         => 20,
                                    'step'        => 1,
                                ]); ?>
                            </div>
                            <div class="form-item col-1-2">
                                <?php echo $formFields->sliderField('Advert Margin Bottom', 'margin', 'advertisement', 0, [
                                    'description' => 'Margin to push bottom advert up, if it covers social share buttons.',
                                    'min'         => 0,
                                    'max'         => 100,
                                    'step'        => 1,
                                    'append'      => ' px',
                                ]); ?>
                            </div>
                            <div class="form-item col-1-1"><?php echo $formFields->textBoxField('External Advert Scripts:', 'external_scripts', 'advertisement', '', [
                                    'description' => 'Place your external Ad scripts here. (Comma-separated)',
                                    'status'      => $checkActiveStatus,
                                ]); ?></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="card">
        <div class="card-header">
            AMP Ad Options: <strong>Google Advertisement</strong>
        </div>
        <div class="card-body">
            <div class="grid">
                <div class="form-item col-1-1 single">
                    <?php echo $formFields->checkboxField('Show Adverts?', 'active', 'advertisement', 1, [
                        'description' => 'Show AMP adverts on your pwa?',
                        'sub'         => 'amp',
                        'status'      => $checkActiveStatus,
                    ]); ?>
                    <div class="grid cf has-margin-top">
                        <div class="form-item col-1-1 has-margin-top">
                            <div class="form-item col-1-2"><?php echo $formFields->textField('Ad Type:', 'ad_type', 'advertisement', '', [
                                    'description' => 'Your Ad type.',
                                    'placeholder' => 'doubleclick',
                                    'sub'         => 'amp',
                                    'status'      => $checkActiveStatus,
                                ]); ?></div>
                            <div class="form-item col-1-2"><?php echo $formFields->textField('Ad Slot:', 'ad_slot_id', 'advertisement', '', [
                                    'description' => 'The assigned ad slot.',
                                    'placeholder' => '/123456789/Slot_name_',
                                    'sub'         => 'amp',
                                    'status'      => $checkActiveStatus,
                                ]); ?></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<!--Analytics-->
<div id="settings-tab-3" class="tab-content">
    <h3>Analytics</h3>
    <div class="card">
        <div class="card-header">
            Options
        </div>
        <div class="card-body">
            <div class="grid">
                <div class="form-item col-1-2"><?php echo $formFields->textField('Google Analytics Tracking Tag:', 'ga_id', 'analytics', '', [
                        'description' => 'Find this in your Google Analytics dashboard.',
                        'placeholder' => 'UA-XXXXX-X',
                    ]); ?></div>
                <div class="form-item col-1-2"><?php echo $formFields->textField('Google Tag Manager Code:', 'gtm_id', 'analytics', '', [
                        'description' => 'Find this in your Google Analytics dashboard.',
                        'placeholder' => 'UA-XXXXXX-XX',
                    ]); ?></div>
            </div>
        </div>
    </div>
</div>
<!--Social Media-->
<div id="settings-tab-4" class="tab-content">
    <h3>Social Media</h3>
    <div class="card">
        <div class="card-header">
            Custom Suffix for Share Buttons
        </div>
        <div class="card-body">
            <div class="grid">
                <div class="form-item col-1-1 single">
                    <?php echo $formFields->textField('Share Message Suffix:', 'suffix', 'social', '', [
                        'description' => 'This will be appended onto the share description (@somehandle).',
                        'placeholder' => '@somehandle',
                    ]); ?>
                </div>
            </div>
        </div>
    </div>
    <div class="card">
        <div class="card-header">
            Share Buttons
        </div>
        <div class="card-body">
            <h6 class="card-title">Switch on share buttons on posts?</h6>
            <div class="grid">
                <div class="form-item col-1-4 single">
                    <?php echo $formFields->checkboxField('Facebook:', 'facebook', 'social', 1, [
                        'sub'         => 'ssk',
                        'description' => 'Switch Facebook share link on.',
                    ]); ?>
                </div>
                <div class="form-item col-1-4 single">
                    <?php echo $formFields->checkboxField('Twitter:', 'twitter', 'social', 1, [
                        'sub'         => 'ssk',
                        'description' => 'Switch Twitter share link on.',
                    ]); ?>
                </div>
                <div class="form-item col-1-4 single">
                    <?php echo $formFields->checkboxField('LinkedIn:', 'linkedin', 'social', 1, [
                        'sub'         => 'ssk',
                        'description' => 'Switch LinkedIn share link on.',
                    ]); ?>
                </div>
                <div class="form-item col-1-4 single">
                    <?php echo $formFields->checkboxField('Instagram:', 'instagram', 'social', 1, [
                        'sub'         => 'ssk',
                        'description' => 'Switch Instagram share link on.',
                    ]); ?>
                </div>
                <div class="form-item col-1-4 single">
                    <?php echo $formFields->checkboxField('YouTube:', 'youtube', 'social', 1, [
                        'sub'         => 'ssk',
                        'description' => 'Switch YouTube share link on.',
                    ]); ?>
                </div>
                <div class="form-item col-1-4 single">
                    <?php echo $formFields->checkboxField('WhatsApp:', 'whatsapp', 'social', 1, [
                        'sub'         => 'ssk',
                        'description' => 'Switch WhatsApp share link on.',
                    ]); ?>
                </div>
                <div class="form-item col-1-4 single">
                    <?php echo $formFields->checkboxField('Email:', 'email', 'social', 1, [
                        'sub'         => 'ssk',
                        'description' => 'Switch Email share link on.',
                    ]); ?>
                </div>
            </div>
        </div>
    </div>
    <div class="card">
        <div class="card-header">
            Share Links
        </div>
        <div class="card-body">
            <h6 class="card-title">Social share links to appear in menu.</h6>
            <div class="grid">
                <div class="form-item col-1-2 single">
                    <?php echo $formFields->textField('Facebook:', 'facebook_url', 'social', '', [
                        'description' => 'The url to your Facebook page.',
                        'placeholder' => 'https://facebook.com/' . get_bloginfo('name') . '',
                        'sub'         => 'url',
                        'type'        => 'url',
                    ]); ?>
                </div>
                <div class="form-item col-1-2 single">
                    <?php echo $formFields->textField('Twitter:', 'twitter_url', 'social', '', [
                        'description' => 'The url to your Twitter profile.',
                        'placeholder' => 'https://twitter.com/@' . get_bloginfo('name') . '',
                        'sub'         => 'url',
                        'type'        => 'url',
                    ]); ?>
                </div>
                <div class="form-item col-1-2 single">
                    <?php echo $formFields->textField('LinkedIn:', 'linkedin_url', 'social', '', [
                        'description' => 'The url to your LinkedIn page.',
                        'placeholder' => 'https://linkedin.com/' . get_bloginfo('name') . '',
                        'sub'         => 'url',
                        'type'        => 'url',
                    ]); ?>
                </div>
                <div class="form-item col-1-2 single">
                    <?php echo $formFields->textField('Instagram:', 'instagram_url', 'social', '', [
                        'description' => 'The url to your Instagram profile.',
                        'placeholder' => 'https://instagram.com/' . get_bloginfo('name') . '',
                        'sub'         => 'url',
                        'type'        => 'url',
                    ]); ?>
                </div>
                <div class="form-item col-1-2 single">
                    <?php echo $formFields->textField('YouTube:', 'youtube_url', 'social', '', [
                        'description' => 'The url to your YouTube channel.',
                        'placeholder' => 'https://youtube.com/' . get_bloginfo('name') . '',
                        'sub'         => 'url',
                        'type'        => 'url',
                    ]); ?>
                </div>
            </div>
        </div>
    </div>
</div>
