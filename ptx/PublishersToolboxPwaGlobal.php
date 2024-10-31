<?php
    
    namespace PT\ptx;
    
    if (!defined('ABSPATH')) {
        exit();
    }
    
    /**
     * Global usage class.
     *
     * This class is used to load functions that are global (Admin and Frontend) used.
     *
     * @package    PublishersToolboxPwa
     * @subpackage PublishersToolboxPwa/ptx
     * @author     Publishers Toolbox <support@afrozaar.com>
     *
     * @since      2.3.7
     */
    class PublishersToolboxPwaGlobal {
        
        /**
         * The ID of this plugin.
         *
         * @since 2.0.0
         * @access private
         * @var string $pluginName The ID of this plugin.
         */
        private $pluginName;
        
        /**
         * The version of this plugin.
         *
         * @since 2.0.0
         * @access private
         * @var string $pluginVersion The current version of this plugin.
         */
        private $pluginVersion;
        
        /**
         * Initialize the collections used to maintain backend and frontend functions.
         *
         * @param string $pluginName
         * @param string $pluginVersion
         *
         * @since 2.0.0
         */
        public function __construct($pluginName, $pluginVersion) {
            $this->pluginName    = $pluginName;
            $this->pluginVersion = $pluginVersion;
        }
        
        /**
         * Return the plugin options.
         *
         * @since 2.0.0
         */
        public function getPluginOptions() {
            return get_option($this->pluginName, []);
        }
        
        /**
         * Returns the saved subscription data as an array.
         *
         * @since 2.0.0
         */
        public function getPluginSubscription() {
            return get_option($this->pluginName . '-subscription', []);
        }
        
        /**
         * Returns the saved subscription data as an array.
         *
         * @since 2.0.0
         */
        public function getNoticeOptions() {
            return get_option($this->pluginName . '-notices', []);
        }
        
        /**
         * Returns the options to determine last rewrite flush.
         *
         * @since 2.0.0
         */
        public function getChangedOptions() {
            return get_option($this->pluginName . '-changed', []);
        }
        
        /**
         * Return the settings page link.
         *
         * @return string
         *
         * @since 2.0.0
         */
        public function getSettingsLink() {
            return '<a href="' . admin_url('admin.php?page=' . $this->pluginName) . '">' . __('Settings', $this->pluginName) . '</a>';
        }
        
        /**
         * Simplify check for no app.
         *
         * @return string
         *
         * @since 2.0.0
         */
        public function checkNoApp() {
            return isset($_GET['noapp']) && $_GET['noapp'] === 'true';
        }
        
        /**
         * Simplify check for cookies.
         *
         * @return string
         *
         * @since 2.0.0
         */
        public function checkCookie() {
            return isset($_COOKIE['classicCookie']) && $_COOKIE['classicCookie'] === 'true';
        }
        
        /**
         * Simplify check for switch.
         *
         * @return string
         *
         * @since 2.0.0
         */
        public function checkClassicSwitch() {
            $pluginGlobal = new PublishersToolboxPwaGlobal($this->pluginName, $this->pluginVersion);
            $themeOptions = $pluginGlobal->getPluginOptions();
            if (isset($themeOptions['advanced']['classic_switch'])) {
                return true;
            }
            
            return false;
        }
        
        /**
         * Check plugin status for messages.
         *
         * @return bool
         *
         * @since 2.0.6
         */
        public function checkDateStatus() {
            /**
             * Get setup options.
             */
            $pluginGlobal = new PublishersToolboxPwaGlobal($this->pluginName, $this->pluginVersion);
            
            /**
             * If premium get some details.
             */
            return (new PublishersToolboxPwaNotices($this->pluginName, $this->pluginVersion))->subscriptionBoolCheck($pluginGlobal->getPluginSubscription());
        }
        
        /**
         * Set the plugin domain data as an array.
         *
         * @param $inputOption
         *
         * @return mixed|void
         *
         * @since 2.3.0
         */
        public function setPluginDomain($inputOption) {
            if ($inputOption === false) {
                delete_option($this->pluginName . '-domain');
            } elseif ($inputOption === true) {
                update_option($this->pluginName . '-domain', $inputOption);
            } elseif ($inputOption === 'info') {
                return get_option($this->pluginName . '-domain', []);
            }
        }
        
        /**
         * Set the plugin debug data as an array.
         *
         * @param $inputOption
         *
         * @return mixed|void
         *
         * @since 2.3.0
         */
        public function setPluginDebug($inputOption) {
            if ($inputOption === false) {
                delete_option($this->pluginName . '-debug');
                $pathDebugFile = plugin_dir_path(__DIR__) . PUBLISHERS_TOOLBOX_PLUGIN_NAME . '.log';
                if (file_exists($pathDebugFile)) {
                    unlink($pathDebugFile);
                }
            } elseif ($inputOption === true) {
                update_option($this->pluginName . '-debug', $inputOption);
                $pathDebugFile = plugin_dir_url(PUBLISHERS_TOOLBOX_PLUGIN_NAME . '.log') . PUBLISHERS_TOOLBOX_PLUGIN_NAME . '/' . PUBLISHERS_TOOLBOX_PLUGIN_NAME . '.log';
                echo '<div class="notice notice-error inline"><p>PWA - Debug Mode Enabled: <a href="' . $pathDebugFile . '" target="_blank">Debug Log File</a>	</p></div>';
            } elseif ($inputOption === 'info') {
                return get_option($this->pluginName . '-debug', []);
            }
        }
        
        /**
         * Detect if we are viewing a custom post type.
         *
         * @param null $post
         *
         * @return bool
         *
         * @since 2.3.1
         */
        public function pwaDetectCustomPostTypes($post = NULL) {
            $allCustomPostTypes = get_post_types(['_builtin' => false]);
            
            /**
             * Exclude some of the built in plugins as well.
             */
            $allCustomPostTypes['page']          = 'page';
            $allCustomPostTypes['attachment']    = 'attachment';
            $allCustomPostTypes['revision']      = 'revision';
            $allCustomPostTypes['nav_menu_item'] = 'nav_menu_item';
            $allCustomPostTypes['wp_block']      = 'wp_block';
            
            /**
             * There are no custom post types
             */
            if (empty($allCustomPostTypes)) {
                return false;
            }
            
            $customTypes     = array_keys($allCustomPostTypes);
            $currentPostType = get_post_type($post);
            
            /**
             * Could not detect current type.
             */
            if (!$currentPostType) {
                return false;
            }
            
            return in_array($currentPostType, $customTypes, true);
        }
        
        /**
         * Check for AMP.
         *
         * @return string
         *
         * @since 2.0.0
         */
        public function checkAmpSwitch() {
            if (isset($this->getPluginOptions()['advanced']['amp'])) {
                return true;
            }
            
            return false;
        }
        
        /**
         * Adds rest support for custom Post Types.
         *
         * @since 2.1.0
         */
        public function customPostTypeRestSupport() {
            $output   = 'names';
            $operator = 'and';
            
            $postTypes = get_post_types(['public' => true, '_builtin' => false], $output, $operator);
            global $wp_post_types;
            
            foreach ($postTypes as $postType) {
                $wp_post_types[$postType]->show_in_rest          = true;
                $wp_post_types[$postType]->rest_base             = $postType;
                $wp_post_types[$postType]->rest_controller_class = 'WP_REST_Posts_Controller';
            }
        }
        
        /**
         * Adds rest support for custom Taxonomies.
         *
         * @since 2.1.0
         */
        public function customTaxonomiesRestSupport() {
            $output     = 'object'; // or objects
            $operator   = 'and'; // 'and' or 'or'
            $taxonomies = get_taxonomies(['public' => true, '_builtin' => false], $output, $operator);
            if ($taxonomies) {
                foreach ($taxonomies as $taxonomy) {
                    $taxonomy->show_in_rest = true;
                }
            }
        }
    }
