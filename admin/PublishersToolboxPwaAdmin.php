<?php
    
    namespace PT\admin;
    
    use PT\ptx\PublishersToolboxPwaFields;
    use PT\ptx\PublishersToolboxPwaFileHandling;
    use PT\ptx\PublishersToolboxPwaGlobal;
    use PT\ptx\PublishersToolboxPwaNotices;
    
    if (!defined('ABSPATH')) {
        exit();
    }
    
    /**
     * The admin-specific functionality of the plugin.
     *
     * Defines the plugin name, version, and two examples hooks for how to
     * enqueue the admin-specific stylesheet and JavaScript.
     *
     * @package    PublishersToolboxPwa
     * @subpackage PublishersToolboxPwa/admin
     * @author     Publishers Toolbox <support@afrozaar.com>
     *
     * @since 2.0.0
     */
    class PublishersToolboxPwaAdmin {
        
        /**
         * The ID of this plugin.
         *
         * @access private
         * @var string $pluginName The ID of this plugin.
         *
         * @since 2.0.0
         */
        private $pluginName;
        
        /**
         * The version of this plugin.
         *
         * @access private
         * @var string $pluginVersion The current version of this plugin.
         *
         * @since 2.0.0
         */
        private $pluginVersion;
    
    
        /**
         * PublishersToolboxPwaAdmin constructor.
         *
         * Initialize the class and set its properties.
         *
         * @param string $pluginName
         * @param string $pluginVersion
         *
         * @since 2.0.0
         */
        public function __construct($pluginName, $pluginVersion) {
            $this->pluginName    = $pluginName;
            $this->pluginVersion = $pluginVersion;
            
            /**
             * Add fallback images.
             */
            $this->addImageSizes();
        }
        
        /**
         * Register the stylesheets for the admin area.
         *
         * @since 2.0.0
         */
        public function enqueueStyles() {
            global $pagenow;
            
            if ((isset($_GET['page']) && ($_GET['page'] === $this->pluginName)) || $pagenow === 'post.php') {
                wp_enqueue_style($this->pluginName, plugin_dir_url(__FILE__) . 'css/publishers-toolbox-pwa-admin.min.css', [], $this->pluginVersion, 'all');
            }
            
            if (isset($_GET['page']) && ($_GET['page'] === $this->pluginName)) {
                wp_enqueue_style($this->pluginName . '-iziToast', plugin_dir_url(__FILE__) . 'assets/lib/iziToast.min.css', [], $this->pluginVersion, 'all');
                wp_enqueue_style($this->pluginName . '-minicolors', plugin_dir_url(__FILE__) . 'assets/lib/minicolors.min.css', [], $this->pluginVersion, 'all');
                wp_enqueue_style($this->pluginName . '-tooltip', plugin_dir_url(__FILE__) . 'assets/lib/tooltip.min.css', [], $this->pluginVersion, 'all');
                wp_enqueue_style($this->pluginName . '-select2', plugin_dir_url(__FILE__) . 'assets/lib/select2.min.css', [], $this->pluginVersion, 'all');
            }
        }
        
        /**
         * Register the JavaScript for the admin area.
         *
         * @since 2.0.0
         */
        public function enqueueScripts() {
            global $pagenow;
            
            if ((isset($_GET['page']) && ($_GET['page'] === $this->pluginName)) || $pagenow === 'post.php') {
                wp_enqueue_script($this->pluginName, plugins_url('js/publishers-toolbox-pwa-admin.min.js', __FILE__), [
                    'jquery',
                    'jquery-ui-slider',
                    'wp-plugins',
                    'wp-edit-post',
                    'wp-element',
                    'wp-components',
                ], $this->pluginVersion, true);
            }
            
            /**
             * Ajax Libraries.
             */
            wp_localize_script($this->pluginName, 'pwaOptionsObject', [
                'ajax_url' => admin_url('admin-ajax.php'),
                '_nonce'   => wp_create_nonce($this->pluginName),
            ]);
            
            /**
             * Libraries used for admin screen.
             */
            if (isset($_GET['page']) && ($_GET['page'] === $this->pluginName)) {
                wp_enqueue_media();
                wp_enqueue_script('editor');
                
                wp_enqueue_script($this->pluginName . '-iziToast', plugins_url('assets/lib/iziToast.min.js', __FILE__), [], $this->pluginVersion, true);
                wp_enqueue_script($this->pluginName . '-dialog', plugins_url('assets/lib/dialog.min.js', __FILE__), [], $this->pluginVersion, true);
                wp_enqueue_script($this->pluginName . '-minicolors', plugins_url('assets/lib/minicolors.min.js', __FILE__), [], $this->pluginVersion, true);
                wp_enqueue_script($this->pluginName . '-tooltip', plugins_url('assets/lib/tooltip.min.js', __FILE__), [], $this->pluginVersion, true);
                wp_enqueue_script($this->pluginName . '-select2', plugins_url('assets/lib/select2.full.min.js', __FILE__), [], $this->pluginVersion, true);
                wp_enqueue_script($this->pluginName . '-html5sortable', plugins_url('assets/lib/html5sortable.min.js', __FILE__), [], $this->pluginVersion, true);
                wp_enqueue_script($this->pluginName . '-select2sortable', plugins_url('assets/lib/select2.sortable.min.js', __FILE__), [], $this->pluginVersion, true);
            }
        }
        
        /**
         * Register the administration menu for this plugin into the WordPress Dashboard menu.
         *
         * @since 2.0.0
         */
        public function addPluginAdminMenu() {
            /**
             * Add a settings page for this plugin to the Settings menu.
             *
             * Alternative menu locations are available via WordPress administration menu functions.
             *
             * Administration Menus: http://codex.wordpress.org/Administration_Menus
             */
            add_menu_page(__('WebSuite PWA', $this->pluginName), __('PWA', $this->pluginName), 'manage_options', $this->pluginName, // Menu slug
                [$this, 'displayPluginSetupPage'], // Function call
                plugin_dir_url(__FILE__) . 'assets/img/menu-icon.png');
        }
        
        /**
         * Add settings action link to the plugins page.
         *
         * @param $links
         *
         * @return array
         *
         * @since 2.0.0
         */
        public function addActionLinks($links) {
            $pluginGlobal = new PublishersToolboxPwaGlobal($this->pluginName, $this->pluginVersion);
            /**
             *  Documentation : https://codex.wordpress.org/Plugin_API/Filter_Reference/plugin_action_links_(plugin_file_name)
             */
            $settingsLink[] = $pluginGlobal->getSettingsLink();
            
            return array_merge($settingsLink, $links);
        }
        
        /**
         * Render the settings page for this plugin.
         *
         * @since 2.0.0
         */
        public function displayPluginSetupPage() {
            /**
             * Get setup options.
             */
            $pluginGlobal = new PublishersToolboxPwaGlobal($this->pluginName, $this->pluginVersion);
            $themeOptions = $pluginGlobal->getPluginOptions();
            
            /**
             * If premium get some details.
             */
            $subscriptionOptions = $pluginGlobal->getPluginSubscription();
            
            /**
             * Reset arn and subscription.
             */
            if (isset($_GET['reset']) && $_GET['reset'] === '1') {
                delete_option($this->pluginName . '-subscription');
            }
            
            /**
             * Set debug options.
             */
            if (isset($_GET['debug']) && $_GET['debug'] === '1') {
                $pluginGlobal->setPluginDebug(true);
            } else {
                $pluginGlobal->setPluginDebug(false);
            }
            
            /**
             * Load the fields object.
             */
            $formFields = new PublishersToolboxPwaFields($this->pluginName, $this->pluginVersion);
            
            /**
             * Get the api endpoint.
             */
            $endPoint = !empty($themeOptions['performance']['application_endpoint']) ? $themeOptions['performance']['application_endpoint'] : (new PublishersToolboxPwaFileHandling($this->pluginName, $this->pluginVersion))::PWA_ENDPOINT;
            
            /**
             * Get the media endpoint.
             */
            $mediaPoint = !empty($themeOptions['performance']['media_endpoint']) ? $themeOptions['performance']['media_endpoint'] : (new PublishersToolboxPwaFileHandling($this->pluginName, $this->pluginVersion))::PWA_MEDIA;
            
            /**
             * Setup category selection and selected.
             */
            $categoriesAll    = $this->setCategories($themeOptions, true);
            $categoriesActive = $this->setCategories($themeOptions, false);
            
            /**
             * Setup pages selection and selected.
             */
            $pagesAll    = $this->setPages($themeOptions, true);
            $pagesActive = $this->setPages($themeOptions, false);
            
            /**
             * Do a date check and adjust as needed.
             */
            $checkActiveStatus = $pluginGlobal->checkDateStatus() ? false : true;
            
            /**
             * Is preview active.
             */
            $previewActive = isset($themeOptions['preview']) ? true : false;
            $previewLink   = $this->getPreviewLink();
            
            /**
             * Set pretty logo.
             */
            $imageChange = $this->returnPluginLogo($subscriptionOptions);
            
            /**
             * Include the admin page.
             */
            include_once 'partials/publishers-toolbox-pwa-admin-display.php';
        }
        
        
        /**
         * Set pretty logo.
         *
         * @param $subscriptionOptions
         *
         * @return string
         *
         * @since 2.3.0
         */
        public function returnPluginLogo($subscriptionOptions) {
            if (isset($subscriptionOptions['status']) && $subscriptionOptions['status'] === 'active') {
                return 'assets/img/Premium-PWA.png';
            }
            
            if (isset($subscriptionOptions['status']) && $subscriptionOptions['status'] === 'enterprise') {
                return 'assets/img/Enterprise-PWA.png';
            }
            
            return 'assets/img/Standard-PWA.png';
        }
        
        /**
         * Saves the settings for the plugin.
         *
         * @since 2.0.0
         */
        public function savePluginOptions() {
            /**
             * Do security check first.
             */
            if (wp_verify_nonce($_REQUEST['security'], $this->pluginName) === false) {
                wp_send_json_error();
                wp_die('Invalid Request!');
            }
            
            /**
             * Parse the ajax string with data.
             */
            parse_str($_REQUEST['data']['content'], $outputOptions);
            
            /**
             * Double check endpoints.
             */
            $outputOptions['performance']['api_endpoint'] = !empty($outputOptions['performance']['api_endpoint']) ? $outputOptions['performance']['api_endpoint'] : $this->getSiteLink();
            
            $outputOptions['performance']['application_endpoint'] = !empty($outputOptions['performance']['application_endpoint']) ? $outputOptions['performance']['application_endpoint'] : (new PublishersToolboxPwaFileHandling($this->pluginName, $this->pluginVersion))::PWA_ENDPOINT;
            
            /**
             * Modify categories and pages to string for active check.
             */
            if (isset($outputOptions['categories']) && !empty($outputOptions['categories'])) {
                $outputOptions['categories'] = implode(',', $outputOptions['categories']);
            }
            
            if (isset($outputOptions['pages']) && !empty($outputOptions['pages'])) {
                $outputOptions['pages'] = implode(',', $outputOptions['pages']);
            }
            
            switch ($_REQUEST['data']['action']) {
                case 'preview':
                    
                    /**
                     * Build the template file.
                     */
                    $themeData = (new PublishersToolboxPwaFileHandling($this->pluginName, $this->pluginVersion))->buildThemeArray($outputOptions);
                    wp_send_json_success(['preview' => $themeData]);
                    
                    break;
                case 'save':
                    
                    /**
                     * Update the options.
                     */
                    if ($this->updatePluginOptions($outputOptions)) {
                        
                        /**
                         * Get previously saved data.
                         */
                        $pluginGlobal = new PublishersToolboxPwaGlobal($this->pluginName, $this->pluginVersion);
                        $themeOptions = $pluginGlobal->getPluginOptions();
                        
                        /**
                         * Build the template file.
                         */
                        $themeData = (new PublishersToolboxPwaFileHandling($this->pluginName, $this->pluginVersion))->buildThemeArray($themeOptions);
                        
                        /**
                         * Build the template file with legacy data.
                         */
                        (new PublishersToolboxPwaFileHandling($this->pluginName, $this->pluginVersion))->createThemeFile($themeData, true);
                        
                        /**
                         * Build the manifest file.
                         */
                        $manifestData = (new PublishersToolboxPwaFileHandling($this->pluginName, $this->pluginVersion))->buildManifestArray($themeOptions);
                        (new PublishersToolboxPwaFileHandling($this->pluginName, $this->pluginVersion))->createManifestFile($manifestData, true);
                        
                        /**
                         * Set the changed option to true. Rewrite rules will now be flushed.
                         */
                        update_option($this->pluginName . '-changed', ['changed' => true]);
                        
                        /**
                         * Return json response.
                         */
                        wp_send_json_success(['active' => array_key_exists('active', $outputOptions)]);
                    } else {
                        wp_send_json_error();
                    }
                    break;
                default:
                    wp_send_json_error();
                    wp_die();
            }
            
            wp_die();
        }
        
        /**
         * Update the options.
         *
         * @param $inputOption array
         *
         * @return bool
         *
         * @since 2.0.0
         */
        private function updatePluginOptions($inputOption = []) {
            return update_option($this->pluginName, $inputOption);
        }
        
        /**
         * Returns the latest post for the blog.
         *
         * @return int|string
         *
         * @since 2.0.0
         */
        public function getSiteLink() {
            return get_site_url(get_current_blog_id());
        }
        
        /**
         * Returns preview link.
         *
         * @return string
         *
         * @since 2.1.0
         */
        public function getPreviewLink() {
            return plugin_dir_url(__DIR__) . 'frontend/theme/preview.php';
        }
        
        /**
         * Ajax subscription check.
         *
         * @since 2.1.0
         */
        public function checkSubscription() {
            /**
             * Do security check first.
             */
            if (wp_verify_nonce($_REQUEST['security'], $this->pluginName) === false) {
                wp_send_json_error();
                wp_die('Invalid Request!');
            }
            
            /**
             * Run different tasks on request data.
             *
             * Use as automated global service.
             */
            $pluginGlobal        = new PublishersToolboxPwaGlobal($this->pluginName, $this->pluginVersion);
            $subscriptionOptions = $pluginGlobal->getPluginSubscription();
            $themeOptions        = $pluginGlobal->getPluginOptions();
            
            switch ($_REQUEST['data']) {
                case 'subscription':
                    $checkSubscription = (new PublishersToolboxPwaNotices($this->pluginName, $this->pluginVersion))->startPremiumVersion();
                    if ($checkSubscription) {
                        wp_send_json_success($checkSubscription);
                    } else {
                        wp_send_json_error($checkSubscription);
                    }
                    break;
                case 'heartbeat':
                    if (!empty($subscriptionOptions) && $subscriptionOptions['status'] !== 'free') {
                        $heartBeat = (new PublishersToolboxPwaNotices($this->pluginName, $this->pluginVersion))->adjustDate($subscriptionOptions, $themeOptions);
                        wp_send_json_success($heartBeat);
                    } else {
                        $checkSubscription = (new PublishersToolboxPwaNotices($this->pluginName, $this->pluginVersion))->startPremiumVersion();
                        wp_send_json_success($checkSubscription);
                    }
                    break;
                default:
                    wp_send_json_error();
            }
            
            wp_die();
        }
        
        /**
         * Add svg mime type support.
         *
         * @param $mimes
         *
         * @return array
         *
         * @since 2.0.0
         */
        public function svgMimeTypes($mimes) {
            $mimes['svg'] = 'image/svg+xml';
            
            return $mimes;
        }
        
        /**
         * Add fallback image sizes.
         *
         * We dont check for gd_library here, might as well create fallback images since the size is tiny.
         *
         * @since 2.0.0
         */
        public function addImageSizes() {
            add_image_size('pwa-logo', 9999, 50);
            add_image_size('pwa-push', 512, 256);
            
            /**
             * Manifest icons.
             */
            add_image_size('pwa-icon-72', 72, 72);
            add_image_size('pwa-icon-96', 96, 96);
            add_image_size('pwa-icon-128', 128, 128);
            add_image_size('pwa-icon-144', 144, 144);
            add_image_size('pwa-icon-152', 152, 152);
            add_image_size('pwa-icon-192', 192, 192);
            add_image_size('pwa-icon-384', 384, 384);
            add_image_size('pwa-icon-512', 512, 512);
            
            /**
             * Add service worker files.
             */
            if (isset($_GET['page']) && ($_GET['page'] === $this->pluginName)) {
                (new PublishersToolboxPwaFileHandling($this->pluginName, $this->pluginVersion))->createServiceWorkerFile();
                (new PublishersToolboxPwaFileHandling($this->pluginName, $this->pluginVersion))->createMessageServiceWorkerFile();
            }
        }
        
        /**
         * Return categories.
         *
         * @param $themeOptions
         * @param bool $returnAll
         *
         * @return mixed|string
         *
         * @since 2.0.0
         */
        public function setCategories($themeOptions, $returnAll = true) {
            /**
             * Setup category selection and selected.
             */
            $activeCategories = get_categories([
                'orderby' => 'name',
                'order'   => 'ASC',
            ]);
            $allCategories    = [];
            
            foreach ($activeCategories as $category) {
                $allCategories[] = $category->term_id;
            }
            
            $categoriesActive = '';
            
            /**
             * Do some legacy checks and conversions.
             */
            if (is_array($themeOptions['categories']) && array_key_exists('inactive', $themeOptions['categories'])) {
                $categoriesActive = $themeOptions['categories']['active'];
            }
            
            /**
             * No legacy data, move on with new code.
             */
            if (!is_array($themeOptions['categories']) && !empty($themeOptions['categories'])) {
                $categoriesActive = $themeOptions['categories'];
            }
            
            if ($returnAll) {
                $categoriesActive = implode(',', $allCategories);
            }
            
            return $categoriesActive;
        }
        
        /**
         * Return pages.
         *
         * @param $themeOptions
         * @param bool $returnAll
         *
         * @return mixed|string
         *
         * @since 2.0.0
         */
        public function setPages($themeOptions, $returnAll = true) {
            /**
             * Setup page selection and selected.
             */
            $activePages = get_pages();
            $allPages    = [];
            foreach ($activePages as $page) {
                $allPages[] = $page->ID;
            }
            
            $pagesActive = '';
            
            /**
             * Do some legacy checks and conversions.
             */
            if (is_array($themeOptions['pages']) && array_key_exists('inactive', $themeOptions['pages'])) {
                $pagesActive = $themeOptions['pages']['active'];
            }
            
            /**
             * No legacy data, move on with new code.
             */
            if (!is_array($themeOptions['pages']) && !empty($themeOptions['pages'])) {
                $pagesActive = $themeOptions['pages'];
            }
            
            if ($returnAll) {
                $pagesActive = implode(',', $allPages);
            }
            
            return $pagesActive;
        }
        
        /**
         * Check if version match, if not save files again.
         *
         * @since 2.1.6
         */
        public function getPluginUpdate() {
            $pluginGlobal = new PublishersToolboxPwaGlobal($this->pluginName, $this->pluginVersion);
            $themeOptions = $pluginGlobal->getPluginOptions();
            if (!isset($themeOptions['version']) || $themeOptions['version'] !== $this->pluginVersion) {
                $themeOptions['version'] = $this->pluginVersion;
                $this->updatePluginOptions($themeOptions);
                (new PublishersToolboxPwaFileHandling($this->pluginName, $this->pluginVersion))->registerAdmin();
            }
        }
    }
