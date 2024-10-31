<div id="content-tab-1" class="tab-content">
    <h3><?php printf(esc_attr__('Menu Settings', $this->pluginName)); ?></h3>
    <div class="card">
        <div class="card-header">
            <?php printf(esc_attr__('Select menu Items', $this->pluginName)); ?>
        </div>
        <div class="card-body">
            <div class="grid">
                <div class="form-item col-1-2">
                    <h5><?php printf(esc_attr__('Select Pages', $this->pluginName)); ?></h5>
                    <div class="desc"><?php printf(esc_attr__('Will show in Application menu.', $this->pluginName)); ?></div>
                    <div class="sorting-box has-margin-top">
                        <select name="pages[]" multiple="" class="select2 sortable" data-order="<?php echo $pagesActive; ?>" title="Select pages">
                            <?php $selectedActivePages = array_map('intval', explode(',', $pagesActive)); ?>
                            <?php if (!empty($pagesAll)) { ?>
                                <?php foreach (explode(',', $pagesAll) as $pageId) { ?>
                                    <?php $page = get_post($pageId); ?>
                                    <?php if (!is_wp_error($page)) { ?>
                                        <?php if (isset($page->ID)) { ?>
                                            <option value="<?php echo $page->ID; ?>" <?php echo in_array($page->ID, $selectedActivePages, true) ? 'selected' : '' ?>><?php echo $page->post_title; ?></option>
                                        <?php } ?>
                                    <?php } ?>
                                <?php } ?>
                            <?php } ?>
                        </select>
                    </div>
                </div>
                <div class="form-item col-1-2">
                    <h5><?php printf(esc_attr__('Select Categories', $this->pluginName)); ?></h5>
                    <div class="desc"><?php printf(esc_attr__('Will show in Application menu.', $this->pluginName)); ?></div>
                    <?php $selectedActiveCategories = array_map('intval', explode(',', $categoriesActive)); ?>
                    <div class="sorting-box has-margin-top">
                        <select name="categories[]" multiple="" class="select2 sortable" data-order="<?php echo $categoriesActive; ?>" title="Select categories">
                            <?php if (!empty($categoriesAll)) { ?>
                                <?php foreach (explode(',', $categoriesAll) as $catId) { ?>
                                    <?php $category = get_category($catId); ?>
                                    <?php if (!is_wp_error($category)) { ?>
                                        <?php if (isset($category->term_id)) { ?>
                                            <option value="<?php echo $category->term_id; ?>" <?php echo in_array($category->term_id, $selectedActiveCategories, true) ? 'selected' : '' ?>><?php echo $category->name; ?></option>
                                        <?php } ?>
                                    <?php } ?>
                                <?php } ?>
                            <?php } ?>
                        </select>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div id="content-tab-2" class="tab-content">
    <h3><?php printf(esc_attr__('Extra Links', $this->pluginName)); ?></h3>
    <div class="card">
        <div class="card-header">
            <?php printf(esc_attr__('Links', $this->pluginName)); ?>
        </div>
        <div class="card-body">
            <div class="grid">
                <div class="form-item col-5-12"><?php echo $formFields->textField('Link Title', 'link_title', 'links', '', [
                        'description' => 'The link title.',
                        'placeholder' => get_bloginfo('name'),
                    ]); ?></div>
                <div class="form-item col-5-12"><?php echo $formFields->textField('Link Url', 'link_url', 'links', '', [
                        'description' => 'The url where the link needs to go.',
                        'placeholder' => get_bloginfo('url'),
                        'type'        => 'url',
                    ]); ?></div>
                <div class="form-item col-1-7 has-margin-top single">
                    <button type="button" class="btn is-primary has-margin-top add_link">Add Link</button>
                </div>
            </div>
            <hr>
            <div class="grid">
                <div class="form-item col-1-1">
                    <h5>Active Links</h5>
                    <div class="desc">Will show in Application.</div>
                    <ul class="sorting-list active sortable-pt-links-active list_sort list_links_active" data-type="links" data-status="active">
                        <?php if (!empty($themeOptions['links']['active'])) { ?>
                            <?php foreach (json_decode($themeOptions['links']['active'], OBJECT) as $link) { ?>
                                <li id="<?php echo $link['id']; ?>" data-id="<?php echo $link['id']; ?>" data-link="<?php echo $link['link']; ?>" data-label="<?php echo $link['label']; ?>"><?php echo $link['label']; ?>
                                    -
                                    <span class="link-in-list"><?php echo $link['link']; ?></span><span class="is-right remove_link dashicons dashicons-no-alt is-error"></span>
                                </li>
                            <?php } ?>
                        <?php } ?>
                    </ul>
                    <input type="hidden" name="links[active]" value='<?php echo isset($themeOptions['links']['active']) ? (string)$themeOptions['links']['active'] : ''; ?>'>
                </div>
            </div>
        </div>
    </div>
</div>

<div id="content-tab-3" class="tab-content">
    <h3><?php printf(esc_attr__('GDPR', $this->pluginName)); ?></h3>
    <div class="card">
        <div class="card-header">
            <?php printf(esc_attr__('Settings', $this->pluginName)); ?>
        </div>
        <div class="card-body">
            <div class="grid">
                <div class="form-item col-1-1 single"><?php echo $formFields->checkboxField('Show GDPR?', 'active', 'gdpr', 1, [
                        'description' => 'Show GDPR on your pwa?',
                    ]); ?>
                    <div class="grid cf has-margin-top">
                        <div class="form-item col-1-1 has-margin-top">
                            <div class="form-item col-1-2"><?php echo $formFields->textField('Button text', 'button', 'gdpr', '', [
                                    'description' => 'GDPR message button text.',
                                    'placeholder' => 'Accept',
                                ]); ?></div>
                            <div class="form-item col-1-1">
                                <label for="gdpr_text">GDPR Text</label>
                                <?php echo $formFields->wysiwygField('GDPR text to display.', 'gdpr_text', 'gdpr', ''); ?>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
