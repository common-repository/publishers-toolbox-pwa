<?php
    
    namespace PT\ptx;
    
    use WP_Error;
    
    if (!defined('ABSPATH')) {
        exit();
    }
    
    /**
     * Define and build the PWA URI properties and rewrites.
     *
     * Url rewrite to emulate root access to pwa theme files.
     *
     * @package    PublishersToolboxPwa
     * @subpackage PublishersToolboxPwa/includes
     * @author     Publishers Toolbox <support@afrozaar.com>
     *
     * @since 2.0.0
     */
    class PublishersToolboxPwaServeFile {
        
        /**
         * The ID of this plugin.
         *
         * @since 1.0.0
         * @access private
         * @var string $pluginName The ID of this plugin.
         */
        private $pluginName;
        
        /**
         * The version of this plugin.
         *
         * @since 1.0.0
         * @access private
         * @var string $pluginVersion The current version of this plugin.
         */
        private $pluginVersion;
        
        /**
         * The query parameter for the json api endpoint.
         *
         * @access constant
         * @var string ENDPOINT_QUERY_PARAM The uri query parameter.
         *
         * @since 2.0.0
         */
        public const ENDPOINT_QUERY_PARAM = 'pt_pwa_json';
        
        /**
         * The query parameter for the frontend preview endpoint.
         *
         * @access constant
         * @var string ENDPOINT_PREVIEW_PARAM The uri query parameter for frontend preview.
         *
         * @since 2.0.0
         */
        public const ENDPOINT_PREVIEW_PARAM = 'pt_pwa_preview';
        
        /**
         * The uri match that wordpress redirect will look for, pretty url.
         *
         * @access constant
         *
         * @var string WORDPRESS_PREVIEW_PARAM The uri that wordpress uri match.
         *
         * @since 2.0.0
         */
        public const WORDPRESS_PREVIEW_PARAM = 'ptpwa';
        
        /**
         * The plugin dir path.
         *
         * @access private
         * @var string $directoryPath The plugin directory path.
         *
         * @since 2.0.0
         */
        private $directoryPath;
    
        /**
         * PublishersToolboxPwaServeFile constructor.
         *
         * @param $pluginName
         * @param $pluginVersion
         *
         * @since 2.0.0
         */
        public function __construct($pluginName, $pluginVersion) {
            $this->pluginName    = $pluginName;
            $this->pluginVersion = $pluginVersion;
            $this->directoryPath = plugin_dir_path(__DIR__) . 'frontend/theme/sites/';
        }
        
        /**
         * Register the hooks and filters.
         *
         * @since 2.0.0
         */
        public function registerPublicQuerySetup() {
            add_filter('query_vars', [$this, 'addQueryVars'], 0);
            add_action('parse_request', [$this, 'sniffRequests'], 0);
            add_action('init', [$this, 'addEndpoint'], 0);
        }
        
        /**
         * Add public query vars.
         *
         * @param $vars
         *
         * @return array
         *
         * @since 2.0.0
         */
        public function addQueryVars($vars) {
            $vars[] = self::ENDPOINT_QUERY_PARAM; // API
            $vars[] = self::ENDPOINT_PREVIEW_PARAM; // Template
            
            return $vars;
        }
        
        /**
         * Add API and Preview Endpoints.
         *
         * @since 2.0.0
         */
        public function addEndpoint() {
            /**
             * Add plugin tag.
             */
            add_rewrite_tag('%' . self::WORDPRESS_PREVIEW_PARAM . '%', '([a-zA-Z\d\-_+]+)');
            
            /**
             * Get requested files.
             */
            add_rewrite_rule('([^\/"]+\.json)$', 'index.php?' . self::ENDPOINT_QUERY_PARAM . '=$matches[1]', 'top');
            
            /**
             * Flush rules.
             */
            $this->settingsFlushRewrite();
        }
        
        /**
         * Sniff Requests.
         *
         * @since 2.0.0
         */
        public function sniffRequests() {
            global $wp;
            if ($wp->request === PublishersToolboxPwaFileHandling::SW_FILE_NAME || isset($wp->query_vars[self::ENDPOINT_QUERY_PARAM])) {
                $this->handleFileRequest();
            }
        }
        
        /**
         * Handle File Requests.
         *
         * @since 2.0.0
         */
        protected function handleFileRequest() {
            global $wp;
            
            /**
             * Check if query param matches and include the file.
             */
            if (isset($wp->query_vars[self::ENDPOINT_QUERY_PARAM])) {
                $directoryPathSet = $this->directoryPath . get_current_blog_id() . '/' . $wp->request;
            } elseif ($wp->request === PublishersToolboxPwaFileHandling::SW_FILE_NAME) {
                $directoryPathSet = $this->directoryPath . get_current_blog_id() . '/' . PublishersToolboxPwaFileHandling::SW_FILE_NAME;
            } else {
                $directoryPathSet = '';
            }
            
            if (!empty($directoryPathSet)) {
                /**
                 * If we can't read it throw an Error.
                 */
                if (!is_readable($directoryPathSet)) {
                    $error = new WP_Error('Forbidden', 'Access is not allowed for this request. Or the file doesnt exist.', 403);
                    wp_die($error->get_error_message(), $error->get_error_code());
                }
                
                /**
                 * We can read it, so let's render it.
                 */
                $this->serveFile($directoryPathSet);
            }
            
            /**
             * Nothing happened, just give some feedback.
             */
            $error = new WP_Error('Bad Request', 'Invalid Request.', 400);
            wp_die($error->get_error_message(), $error->get_error_code());
        }
        
        /**
         * Output the file.
         *
         * @param $directoryPathSet
         * @param bool $headers
         *
         * @return bool
         *
         * @since 2.0.0
         */
        protected function serveFile($directoryPathSet, $headers = true) {
            if (!empty($directoryPathSet)) {
                if ($headers) {
                    /**
                     * Write headers for json and js file.
                     */
                    $pathCheck = explode('.', $directoryPathSet);
                    $extension = end($pathCheck);
                    if ($extension === 'js') {
                        header('Content-Type: application/javascript');
                    } elseif ($extension === 'json') {
                        header('Content-Type: application/json');
                    }
                    header('Cache-control: private');
                    header('Content-transfer-encoding: binary\n');
                    header('Content-Length: ' . filesize($directoryPathSet));
                }
                
                /**
                 * Render the contents of the file.
                 */
                readfile($directoryPathSet);
                
                /**
                 * Kill the request. Nothing else to do now.
                 */
                exit();
            }
            
            return false;
        }
        
        /**
         * Check if there has been a flush_rewrite_rules.
         *
         * @since 2.0.0
         */
        public function settingsFlushRewrite() {
            $pluginGlobal = new PublishersToolboxPwaGlobal($this->pluginName, $this->pluginVersion);
            
            flush_rewrite_rules(false);
            $changedOptions = $pluginGlobal->getChangedOptions();
            if (isset($changedOptions['changed']) && $changedOptions['changed'] === true) {
                flush_rewrite_rules(false);
                update_option($this->pluginName . '-changed', ['changed' => false]);
            }
        }
    }
