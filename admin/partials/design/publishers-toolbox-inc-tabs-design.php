<div id="design-tab-1" class="tab-content">
    <h3><?php printf(esc_attr__('Application Theme', $this->pluginName)); ?></h3>
    <div class="card">
        <div class="card-header">
            <?php printf(esc_attr__('Select Theme', $this->pluginName)); ?>
        </div>
        <div class="card-body">
            <?php if (isset($themeOptions['colours']['active'])) { ?>
                <div class="notice notice-warning inline has-margin-bottom">
                    <p>
                        <?php printf(esc_attr__('Overwrite Colours is switched on and will overwrite the selected Theme styles, go to Theme Colours to switch it off.', $this->pluginName)); ?>
                    </p>
                </div>
            <?php } ?>
            <div class="grid">
                <div class="form-item col-1-2 single has-margin-top">
                    <?php echo $formFields->radioBoxField('Light Theme', 'active', 'theme', 'light', [
                        'description' => 'Light theme with dark text.',
                        'checked'     => 1,
                    ]); ?>
                </div>
                <div class="form-item col-1-2 single has-margin-top">
                    <?php echo $formFields->radioBoxField('Dark Theme', 'active', 'theme', 'dark', ['description' => 'Dark theme with light text.']); ?>
                </div>
            </div>
        </div>
    </div>
    <div class="card">
        <div class="card-header">
            <?php printf(esc_attr__('Theme Colours', $this->pluginName)); ?>
        </div>
        <div class="card-body">
            <div class="grid">
                <div class="form-item col-1-2"><?php echo $formFields->colorPickerField('Theme Colour', 'components', 'theme', '#000000', [
                        'description' => 'The applications primary colour. Ex (Menu, Header)',
                        'sub'         => 'colour',
                    ]); ?></div>
                <div class="form-item col-1-2"><?php echo $formFields->colorPickerField('Background Colour', 'background', 'theme', '#FFFFFF', [
                        'description' => 'The background colour for the content area.',
                        'sub'         => 'colour',
                    ]); ?></div>
                <div class="form-item col-1-2"><?php echo $formFields->colorPickerField('Accent Colour', 'accent', 'theme', '#6ac9d4', [
                        'description' => 'The colour for highlighting categories, links, hover and active states.',
                        'sub'         => 'colour',
                    ]); ?></div>
                <div class="form-item col-1-2 has-margin-top single"><?php echo $formFields->checkboxField('Apply Accent to Menu', 'accent_menu', 'theme', 1, [
                        'description' => 'Activate the accent colours on the menu and header component area.',
                        'sub'         => 'colour',
                    ]); ?></div>
            </div>
        </div>
    </div>
    <div class="card">
        <div class="card-header">
            <?php printf(esc_attr__('Theme Layout', $this->pluginName)); ?>
        </div>
        <div class="card-body">
            <div class="grid">
                <div class="form-item col-1-2 is-center-align">
                    <img src="<?php echo plugins_url(PUBLISHERS_TOOLBOX_PLUGIN_NAME . '/admin/assets/img/mg.png'); ?>" alt="<?php echo get_admin_page_title(); ?>" class="has-margin-bottom pwa-layout">
                    <div class="is-center col-1-3 is-no-float">
                        <?php echo $formFields->radioBoxField('Layout 1', 'layout', 'theme', 'layout1', ['checked' => 1]); ?>
                    </div>
                </div>
                <div class="form-item col-1-2 is-center-align">
                    <img src="<?php echo plugins_url(PUBLISHERS_TOOLBOX_PLUGIN_NAME . '/admin/assets/img/np.png'); ?>" alt="<?php echo get_admin_page_title(); ?>" class="has-margin-bottom pwa-layout">
                    <div class="is-center col-1-3 is-no-float">
                        <?php echo $formFields->radioBoxField('Layout 2', 'layout', 'theme', 'layout2', []); ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<div id="design-tab-2" class="tab-content">
    <h3><?php printf(esc_attr__('Application Colours', $this->pluginName)); ?></h3>
    <div class="card">
        <div class="card-header">
            Individual Colour Settings (Will overwrite theme colours.)
        </div>
        <div class="card-body">
            <?php if (isset($themeOptions['colours']['active'])) { ?>
                <div class="notice notice-warning inline has-margin-bottom">
                    <p>
                        <?php printf(esc_attr__('Overwrite Colours is switched on and will overwrite the selected Theme defaults.', $this->pluginName)); ?>
                    </p>
                </div>
            <?php } ?>
            <div class="grid">
                <div class="form-item col-1-1 single">
                    <?php echo $formFields->checkboxField('Overwrite Colours', 'active', 'colours', 1, [
                        'description' => 'Overwrite the default Light/Dark theme colours.',
                        'eventClass'  => 'display_next',
                    ]); ?>
                    <div class="grid hidden-pwa cf has-margin-top">
                        <div class="form-item col-1-2"><?php echo $formFields->colorPickerField('Menu Icon', 'hamburger', 'colours', '#000000', [
                                'description' => 'Hamburger Menu Colour.',
                                'sub'         => 'menu',
                            ]); ?></div>
                        <div class="form-item col-1-2"><?php echo $formFields->colorPickerField('Menu Background', 'background', 'colours', '#ffffff', [
                                'description' => 'The slide out menu background colour.',
                                'sub'         => 'menu',
                            ]); ?></div>
                        <div class="form-item col-1-2"><?php echo $formFields->colorPickerField('Menu Block Background', 'block', 'colours', '#ffffff', [
                                'description' => 'The slide out menu background colour.',
                                'sub'         => 'menu',
                            ]); ?></div>
                        <div class="form-item col-1-2"><?php echo $formFields->colorPickerField('Menu Selected Background', 'selected', 'colours', '#ffffff', [
                                'description' => 'The background colour for a selected menu item. (Active)',
                                'sub'         => 'menu',
                            ]); ?></div>
                        <div class="form-item col-1-2"><?php echo $formFields->colorPickerField('Menu Text', 'text', 'colours', '#000000', [
                                'description' => 'The menu text colour.',
                                'sub'         => 'menu',
                            ]); ?></div>
                        <div class="form-item col-1-2"><?php echo $formFields->colorPickerField('Menu Selected Text', 'select', 'colours', '#6ac9d4', [
                                'description' => 'The menu text colour when selected. (Active)',
                                'sub'         => 'menu',
                            ]); ?></div>
                        <div class="form-item col-1-2"><?php echo $formFields->colorPickerField('Menu Items', 'items', 'colours', '#ffffff', [
                                'description' => 'Background colour of header and menu elements.',
                                'sub'         => 'menu',
                            ]); ?></div>
                        <div class="form-item col-1-2"><?php echo $formFields->colorPickerField('Menu Close Button', 'close', 'colours', '#ffffff', [
                                'description' => 'The close (x) button colour.',
                                'sub'         => 'menu',
                            ]); ?></div>
                        <div class="form-item col-1-2"><?php echo $formFields->colorPickerField('Header Background', 'header_background', 'colours', '#ffffff', [
                                'description' => 'The top header background colour.',
                                'sub'         => 'content',
                            ]); ?></div>
                        <div class="form-item col-1-2"><?php echo $formFields->colorPickerField('Theme Background', 'theme_background', 'colours', '#ffffff', [
                                'description' => 'The applications background colour.',
                                'sub'         => 'content',
                            ]); ?></div>
                        <div class="form-item col-1-2"><?php echo $formFields->colorPickerField('Text Colour', 'text', 'colours', '#000000', [
                                'description' => 'The colour of the content text for the application.',
                                'sub'         => 'content',
                            ]); ?></div>
                        <div class="form-item col-1-2"><?php echo $formFields->colorPickerField('Link Highlight', 'highlights', 'colours', '#087e84', [
                                'description' => 'Date blocks, underlines, links in content colours.',
                                'sub'         => 'content',
                            ]); ?></div>
                        <div class="form-item col-1-2"><?php echo $formFields->colorPickerField('Horizontal Menu Text', 'slider_text', 'colours', '#000000', [
                                'description' => 'Horizontal navigation slider menu text colour.',
                                'sub'         => 'content',
                            ]); ?></div>
                        <div class="form-item col-1-2"><?php echo $formFields->colorPickerField('Horizontal Menu Background', 'slider_background', 'colours', '#ffffff', [
                                'description' => 'Horizontal navigation slider menu background colour.',
                                'sub'         => 'content',
                            ]); ?></div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<div id="design-tab-3" class="tab-content">
    <h3><?php printf(esc_attr__('Display Options', $this->pluginName)); ?></h3>
    <div class="card">
        <div class="card-header">
            <?php printf(esc_attr__('Fonts', $this->pluginName)); ?>
        </div>
        <div class="card-body">
            <div class="grid">
                <?php $fontsArray = [
                    'Default',
                    'Open Sans',
                    'Montserrat',
                    'PT Sans',
                    'PT Sans Narrow',
                    'Maven Pro',
                    'PT Serif',
                    'Source Serif Pro',
                    'Cardo',
                    'Martel',
                    'Noto Serif TC',
                    'Roboto',
                ]; ?>
                <div class="form-item col-1-2 single"><?php echo $formFields->selectField('Primary Font Family', 'headers', 'theme', $fontsArray, [
                        'description' => 'Select the primary header font to use.',
                        'default'     => 'Default',
                        'sub'         => 'font',
                    ]); ?></div>
                <div class="form-item col-1-2 single"><?php echo $formFields->selectField('Secondary Font Family', 'content', 'theme', $fontsArray, [
                        'description' => 'Select secondary content font to use.',
                        'default'     => 'Default',
                        'sub'         => 'font',
                    ]); ?></div>
            </div>
        </div>
    </div>
    <div class="card">
        <div class="card-header">
            <?php printf(esc_attr__('Content Display Options', $this->pluginName)); ?>
        </div>
        <div class="card-body">
            <div class="grid">
                <div class="form-item col-1-2 has-margin-top"><?php echo $formFields->checkboxField('Show Latest Posts on Home?', 'latest_home', 'content', 1, [
                        'description' => 'Displays the latest posts on the home page.',
                        'sub'         => 'options',
                    ]); ?></div>
                <div class="form-item col-1-2 has-margin-top"><?php echo $formFields->checkboxField('Show Menu Search Box?', 'search_box', 'content', 1, [
                        'description' => 'Displays a search box in the menu.',
                        'sub'         => 'options',
                    ]); ?></div>
                <div class="form-item col-1-2 single"><?php echo $formFields->checkboxField('Infinite Vertical Scroll?', 'vertical', 'content', 1, [
                        'description' => 'Posts will have infinite vertical scroll loading.',
                        'sub'         => 'scroll',
                    ]); ?></div>
                <div class="form-item col-1-2 single"><?php echo $formFields->checkboxField('Infinite Horizontal Scroll?', 'horizontal', 'content', 1, [
                        'description' => 'Posts will have infinite horizontal scroll loading.',
                        'sub'         => 'scroll',
                    ]); ?></div>
            </div>
        </div>
    </div>
    <div class="card">
        <div class="card-header">
            <?php printf(esc_attr__('Category Options', $this->pluginName)); ?>
        </div>
        <div class="card-body">
            <div class="grid">
                <div class="form-item col-1-2 single has-margin-top"><?php echo $formFields->checkboxField('Enable Category Download', 'download', 'content', 1, [
                        'description' => 'This allows the latest content for the section to be available offline.',
                        'sub'         => 'category',
                    ]); ?></div>
                <div class="form-item col-1-2 single has-margin-top"><?php echo $formFields->checkboxField('Show Child Categories', 'child', 'content', 1, [
                        'description' => 'Display the child categories and the parent categories.',
                        'sub'         => 'category',
                    ]); ?></div>
                <div class="form-item col-1-2 single"> <?php echo $formFields->textField('Category Prefix', 'prefix', 'content', get_option('category_base'), [
                        'description' => 'The keyword before the category in url.',
                        'placeholder' => strtolower(get_bloginfo('name')),
                        'sub'         => 'category',
                    ]); ?></div>
            </div>
        </div>
    </div>
    <div class="card">
        <div class="card-header">
            <?php printf(esc_attr__('Post Options', $this->pluginName)); ?>
        </div>
        <div class="card-body">
            <div class="grid">
                <div class="form-item col-1-3 has-margin-top"><?php echo $formFields->checkboxField('Show Date on Posts?', 'date_post', 'content', 1, [
                        'description' => 'Displays the post publish date on feed items.',
                        'sub'         => 'options',
                    ]); ?>
                </div>
                <div class="form-item col-1-3 has-margin-top"><?php echo $formFields->checkboxField('Show Date on Thumbnail?', 'date_thumb', 'content', 1, [
                        'description' => 'Displays the date on the Thumbnail.',
                        'sub'         => 'options',
                    ]); ?></div>
                <div class="form-item col-1-3 has-margin-top"><?php echo $formFields->checkboxField('Show Author on Posts?', 'author_post', 'content', 1, [
                        'description' => 'Displays the author on the Post.',
                        'sub'         => 'options',
                    ]); ?></div>
            </div>
            <div class="grid">
                <div class="form-item col-1-2">
                    <?php echo $formFields->sliderField('Hero Posts', 'featured', 'content', 2, [
                        'description' => 'The number of featured/hero posts to display.',
                        'min'         => 1,
                        'max'         => 5,
                        'step'        => 1,
                        'append'      => ' posts',
                        'sub'         => 'post',
                    ]); ?>
                </div>
                <div class="form-item col-1-2">
                    <?php echo $formFields->sliderField('Posts to show', 'count', 'content', 2, [
                        'description' => 'The amount of posts to load on a feed.',
                        'min'         => 1,
                        'max'         => 50,
                        'step'        => 1,
                        'append'      => ' posts',
                        'sub'         => 'post',
                    ]); ?>
                </div>
            </div>
        </div>
    </div>
</div>
