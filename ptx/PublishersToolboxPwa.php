<?php
    
    namespace PT\ptx;
    
    use PT\admin\PublishersToolboxPwaAdmin;
    use PT\frontend\PublishersToolboxPwaFrontend;
    
    if (!defined('ABSPATH')) {
        exit();
    }
    
    /**
     * The file that defines the core plugin class
     *
     * A class definition that includes attributes and functions used across both the
     * public-facing side of the site and the admin area.
     *
     * This is used to define internationalization, admin-specific hooks, and
     * public-facing site hooks.
     *
     * Also maintains the unique identifier of this plugin as well as the current
     * version of the plugin.
     *
     * @link       https://www.publisherstoolbox.com/websuite/
     *
     * @since 2.0.0
     * @package    PublishersToolboxPwa
     * @subpackage PublishersToolboxPwa/ptx
     * @author     Publishers Toolbox <support@afrozaar.com>
     */
    class PublishersToolboxPwa {
        
        /**
         * The loader that's responsible for maintaining and registering all hooks that power
         * the plugin.
         *
         * @access protected
         * @var      PublishersToolboxPwaLoader $loader Maintains and registers all hooks for the plugin.
         *
         * @since 2.0.0
         */
        protected $loader;
        
        /**
         * The unique identifier of this plugin.
         *
         * @access protected
         * @var string $pluginName The string used to uniquely identify this plugin.
         *
         * @since 2.0.0
         */
        protected $pluginName;
        
        /**
         * The current version of the plugin.
         *
         * @access protected
         * @var string $version The current version of the plugin.
         *
         * @since 2.0.0
         */
        protected $pluginVersion;
        
        /**
         * Define the core functionality of the plugin.
         *
         * Set the plugin name and the plugin version that can be used throughout the plugin.
         * Load the dependencies, define the locale, and set the hooks for the admin area and
         * the public-facing side of the site.
         *
         * @since 2.0.0
         */
        public function __construct() {
            $this->pluginVersion = PUBLISHERS_TOOLBOX_PLUGIN_VERSION;
            $this->pluginName    = PUBLISHERS_TOOLBOX_PLUGIN_NAME;
            
            $this->loadDependencies();
            $this->setLocale();
            $this->defineGlobalHooks();
            
            if (is_admin()) {
                $this->defineAdminHooks();
            } else {
                $pluginGlobal = new PublishersToolboxPwaGlobal($this->getPluginName(), $this->getPluginVersion());
                $switchPlugin = $pluginGlobal->getPluginOptions();
                if (isset($switchPlugin['active'])) {
                    $this->definePublicHooks();
                }
            }
        }
        
        /**
         * Load the required dependencies for this plugin.
         *
         * Include the following files that make up the plugin:
         *
         * - PublishersToolboxPwaLoader. Orchestrates the hooks of the plugin.
         *
         * Create an instance of the loader which will be used to register the hooks
         * with WordPress.
         *
         * @access private
         *
         * @since 2.0.0
         */
        private function loadDependencies() {
            /**
             * The class is responsible for orchestrating the actions and filters of the core plugin.
             */
            $this->loader = new PublishersToolboxPwaLoader();
        }
        
        /**
         * Define the locale for this plugin for internationalization.
         *
         * Uses the PublishersToolboxPwaI18n class in order to set the domain and to register the hook with WordPress.
         *
         * @access private
         *
         * @since 2.0.0
         */
        private function setLocale() {
            /**
             * Translate Methods @PublishersToolboxPwaI18n
             */
            $pluginI18n = new PublishersToolboxPwaI18n($this->getPluginName());
            
            /**
             * Load the translations.
             */
            $this->loader->addAction('plugins_loaded', $pluginI18n, 'loadPluginTextDomain');
        }
        
        /**
         * Define the globals for this plugin.
         *
         * Used for functions, filters and hooks that are available globally.
         *
         * @access private
         *
         * @since 2.3.7
         */
        private function defineGlobalHooks() {
            $pluginGlobal = new PublishersToolboxPwaGlobal($this->getPluginName(), $this->getPluginVersion());
            
            /**
             * Add CPT and Taxonomies to rest.
             */
            $this->loader->addAction('init', $pluginGlobal, 'customPostTypeRestSupport', 25);
            $this->loader->addAction('init', $pluginGlobal, 'customTaxonomiesRestSupport', 25);
    
            /**
             * Admin Methods @PublishersToolboxPwaAdmin
             */
            $pluginAdmin = new PublishersToolboxPwaAdmin($this->getPluginName(), $this->getPluginVersion());
            $this->loader->addAction('init', $pluginAdmin, 'getPluginUpdate');
        }
        
        /**
         * Register all of the hooks related to the admin area functionality
         * of the plugin.
         *
         * @access private
         *
         * @since 2.0.0
         */
        private function defineAdminHooks() {
            
            /**
             * Admin Methods @PublishersToolboxPwaAdmin
             */
            $pluginAdmin = new PublishersToolboxPwaAdmin($this->getPluginName(), $this->getPluginVersion());
            
            /**
             * Plugin Options.
             */
            $pluginGlobal = new PublishersToolboxPwaGlobal($this->getPluginName(), $this->getPluginVersion());
            $themeOptions = $pluginGlobal->getPluginOptions();
            
            /**
             * Scripts to only load on this plugin page.
             */
            $this->loader->addAction('admin_enqueue_scripts', $pluginAdmin, 'enqueueStyles');
            $this->loader->addAction('admin_enqueue_scripts', $pluginAdmin, 'enqueueScripts');
            
            /**
             * need some functionality here.
             */
            $pluginServeFiles = new PublishersToolboxPwaServeFile($this->pluginName, $this->pluginVersion);
            
            /**
             * Do rewrite rules flush check.
             */
            $this->loader->addAction('admin_init', $pluginServeFiles, 'settingsFlushRewrite');
            
            /**
             * Add admin menu.
             */
            $this->loader->addAction('admin_menu', $pluginAdmin, 'addPluginAdminMenu');
            
            /**
             * Add svg mime type.
             */
            $this->loader->addAction('upload_mimes', $pluginAdmin, 'svgMimeTypes');
            
            /**
             * Add Settings link to the plugin.
             */
            $pluginBasename = plugin_basename(plugin_dir_path(__DIR__) . $this->pluginName . '.php');
            $this->loader->addFilter('plugin_action_links_' . $pluginBasename, $pluginAdmin, 'addActionLinks');
            
            /**
             * Save the settings data ajax.
             */
            $this->loader->addAction('wp_ajax_store_pwa_admin_options', $pluginAdmin, 'savePluginOptions');
            
            /**
             * Check the subscription details.
             */
            $this->loader->addAction('wp_ajax_pwa_subscription_check', $pluginAdmin, 'checkSubscription');
            
            /**
             * Load admin notices.
             */
            $pluginAdminNotices = new PublishersToolboxPwaNotices($this->pluginName, $this->pluginVersion);
            $this->loader->addAction('admin_notices', $pluginAdminNotices, 'displayNotices');
            
            /**
             * Check the notice options ajax.
             */
            $this->loader->addAction('wp_ajax_notice_pwa_options_check', $pluginAdminNotices, 'dismissNotice');
            
            /**
             * Setup the push notifications options.
             */
            $pushNotify = new PublishersToolboxPwaPush($themeOptions, $pluginGlobal->getPluginSubscription(), $this->getPluginName(), $this->getPluginVersion());
            if (isset($themeOptions['push'])) {
                $this->loader->addAction('manage_posts_custom_column', $pushNotify, 'pushColumnsContent', 10, 2);
                $this->loader->addFilter('manage_posts_columns', $pushNotify, 'pushColumnsHead');
            }
            
            /**
             * Send Push Notifications
             */
            $this->loader->addAction('wp_ajax_pwa_push_notification', $pushNotify, 'sendPushNotification');
        }
        
        /**
         * Register all of the hooks related to the public-facing functionality
         * of the plugin.
         *
         * @access private
         *
         * @since 2.0.0
         */
        private function definePublicHooks() {
            /**
             * Global settings.
             */
            $pluginGlobal = new PublishersToolboxPwaGlobal($this->pluginName, $this->pluginVersion);
            
            /**
             * Frontend Methods @PublishersToolboxPwaFrontend
             */
            $pluginPublic = new PublishersToolboxPwaFrontend($this->getPluginName(), $this->getPluginVersion());
            
            /**
             * Header scripts for fallback.
             *
             * Set priority to be included lower than OG tags.
             */
            $this->loader->addAction('wp_head', $pluginPublic, 'insertFallbackScript', 14);
            
            /**
             * Check if amp has been switched on.
             */
            if ($pluginGlobal->checkAmpSwitch()) {
                $this->loader->addAction('wp_head', $pluginPublic, 'insertAmpScript', 9);
            }
            
            /**
             * Scripts & Styles.
             */
            $this->loader->addAction('wp_enqueue_scripts', $pluginPublic, 'enqueueStyles');
            $this->loader->addAction('wp_enqueue_scripts', $pluginPublic, 'enqueueScripts');
            
            /**
             * Set Core headers.
             */
            $this->loader->addAction('rest_api_init', $pluginPublic, 'setHeaders', 15);
            
            /**
             * Rest api PWA config.
             */
            $this->loader->addAction('rest_api_init', $pluginPublic, 'themeRestPoint');
            
            /**
             * Define public theme file serve.
             */
            (new PublishersToolboxPwaServeFile($this->pluginName, $this->pluginVersion))->registerPublicQuerySetup();
            
            /**
             * Load the PWA template file instead of wordpress.
             *
             * Check if on mobile device : If true continue.
             * Check if cookie has been set and true : If set true load app.
             * Check if noapp parameter has been set : If set and false load app.
             */
            if ($pluginPublic->pwaDetectDevice() && !$pluginGlobal->checkNoApp() && !$pluginGlobal->checkCookie()) {
                $this->loader->addAction('template_redirect', $pluginPublic, 'redirectToPwa', 10);
            }
            
            /**
             * Switch on classic switch.
             */
            if ($pluginPublic->pwaDetectDevice() && $pluginGlobal->checkClassicSwitch() && ($pluginGlobal->checkNoApp() || $pluginGlobal->checkCookie())) {
                $this->loader->addAction('wp_footer', $pluginPublic, 'createSwitchButton');
            }
        }
        
        /**
         * Run the loader to execute all of the hooks with WordPress.
         *
         * @since 2.0.0
         */
        public function run() {
            $this->loader->run();
        }
        
        /**
         * The name of the plugin used to uniquely identify it within the context of
         * WordPress and to define internationalization functionality.
         *
         * @return string The name of the plugin.
         *
         * @since 2.0.0
         */
        public function getPluginName() {
            return $this->pluginName;
        }
        
        /**
         * Retrieve the version number of the plugin.
         *
         * @return string The version number of the plugin.
         *
         * @since 2.0.0
         */
        public function getPluginVersion() {
            return $this->pluginVersion;
        }
    }
