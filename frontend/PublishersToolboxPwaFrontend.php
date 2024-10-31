<?php
    
    namespace PT\frontend;
    
    use AMP_Content_Sanitizer;
    use AMP_DOM_Utils;
    use AMP_Theme_Support;
    use PT\libraries\MobileDetect;
    use PT\ptx\PublishersToolboxPwaFileHandling;
    use PT\ptx\PublishersToolboxPwaGlobal;
    use PT\ptx\PublishersToolboxPwaRequests;
    
    if (!defined('ABSPATH')) {
        exit();
    }
    
    /**
     * The public-facing functionality of the plugin.
     *
     * Defines the plugin name, version, and two examples hooks for how to enqueue the public-facing stylesheet and JavaScript.
     *
     * @package    PublishersToolboxPwa
     * @subpackage PublishersToolboxPwa/public
     * @author     Publishers Toolbox <support@afrozaar.com>
     *
     * @since 2.0.0
     */
    class PublishersToolboxPwaFrontend {
        
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
         * @var string $version The current version of this plugin.
         *
         * @since 2.0.0
         */
        private $pluginVersion;
        
        /**
         * PublishersToolboxPwaFrontend constructor.
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
             * Add ajax for frontend here, since the loader is used for functions.
             */
            add_action('wp_ajax_frontend_pwa_app', [$this, 'frontendPwaApp']);
            add_action('wp_ajax_nopriv_frontend_pwa_app', [$this, 'frontendPwaApp']);
        }
        
        /**
         * Register the stylesheets for the public-facing side of the site.
         *
         * @since 2.0.0
         */
        public function enqueueStyles() {
            wp_enqueue_style($this->pluginName, plugin_dir_url(__FILE__) . 'css/publishers-toolbox-pwa-public.min.css', [], $this->pluginVersion);
        }
        
        /**
         * Register the JavaScript for the public-facing side of the site.
         *
         * @since 2.0.0
         */
        public function enqueueScripts() {
            wp_enqueue_script($this->pluginName, plugin_dir_url(__FILE__) . 'js/publishers-toolbox-pwa-public.min.js', ['jquery'], $this->pluginVersion);
            
            wp_localize_script($this->pluginName, 'pwaFrontendObject', [
                'ajax_url' => admin_url('admin-ajax.php'),
                '_nonce'   => wp_create_nonce($this->pluginName),
            ]);
        }
        
        /**
         * Get pwa application data to overwrite.
         *
         * @param string $status
         *
         * @return array|bool|mixed
         *
         * @since 2.0.0
         */
        public function previewQueryUri($status = 'live') {
            global $wp_version;
            
            $pluginGlobal     = new PublishersToolboxPwaGlobal($this->pluginName, $this->pluginVersion);
            $themeOptions     = $pluginGlobal->getPluginOptions();
            $endPoint         = $this->getApplicationEndpoint($themeOptions);
            $sanitizeEndpoint = $this->sanitizeRemoteUrl($endPoint . $this->getCurrentPostId());
            
            $response = wp_remote_get($sanitizeEndpoint, [
                'timeout'     => 30,
                'redirection' => 5,
                'httpversion' => $_SERVER['SERVER_PROTOCOL'],
                'user-agent'  => 'WordPress/' . $wp_version . '; ' . get_site_url(get_current_blog_id()),
                'blocking'    => true,
                'headers'     => ['Origin' => str_replace(' ', '', $_SERVER['HTTP_HOST'])],
                'cookies'     => [],
                'body'        => NULL,
                'compress'    => false,
                'decompress'  => true,
                'sslverify'   => true,
                'stream'      => false,
                'filename'    => NULL,
            ]);
            
            $responseCode    = wp_remote_retrieve_response_code($response);
            $responseMessage = wp_remote_retrieve_response_message($response);
            
            if ($status === 'preview') {
                return ['body' => wp_remote_retrieve_body($response)];
            }
            
            /**
             * Respond with error code and log if there is an error.
             */
            if (200 !== $responseCode || $responseCode >= 400 || $responseCode >= 500) {
                /**
                 * Log error from lambda server.
                 */
                $responseHeader = wp_remote_retrieve_header($response, 'x-cache');
                (new PublishersToolboxPwaRequests($this->pluginName, $this->pluginVersion))->logErrors($responseCode, $responseHeader, $responseMessage, $sanitizeEndpoint, get_site_url(get_current_blog_id()) . $_SERVER['REQUEST_URI'], 'Frontend Call', $response);
                
                /**
                 * Return error to see which filter to load.
                 */
                return ['error' => true];
            }
            
            /**
             * Return the PWA if everything has been loaded successfully.
             */
            if (is_array($response) && $response['body'] !== '{}') {
                return ['body' => wp_remote_retrieve_body($response)];
            }
            
            return ['error' => true];
        }
        
        /**
         * Clean url if needed.
         *
         * @param $endPoint
         *
         * @return mixed
         *
         * @since 2.1.9
         */
        public function sanitizeRemoteUrl($endPoint) {
            return str_replace([' ', 'url(&quot', '/home', '/undefined'], '', $endPoint);
        }
        
        /**
         * Redirect template after all the checks are done.
         *
         * @since 2.3.3
         */
        public function redirectToPwa() {
            if (!$this->pwaDetectCustomPostTypes() && !array_key_exists('error', $this->previewQueryUri())) {
                add_filter('template_include', [$this, 'customTemplateOverwrite']);
            }
        }
        
        /**
         * Return Mobile PWA theme body.
         *
         * @return string
         * uses previewQueryUri();
         *
         * @since 2.0.0
         */
        public function customTemplateOverwrite() {
            return plugin_dir_path(__FILE__) . 'theme/template.php';
        }
        
        /**
         * Detect the device we are on.
         *
         * @return bool
         *
         * @since 2.0.0
         */
        public function pwaDetectDevice() {
            /**
             * Get device detection library.
             */
            return (new MobileDetect())->isMobile();
        }
        
        /**
         * Adds the classic switch to PWA button to frontend.
         *
         * @since 2.0.0
         */
        public function createSwitchButton() {
            /**
             * Create a simple switch to PWA button.
             */
            $pluginGlobal = new PublishersToolboxPwaGlobal($this->pluginName, $this->pluginVersion);
            $themeOptions = $pluginGlobal->getPluginOptions();
            
            if (isset($themeOptions['advanced']['classic_switch'])) {
                $currentLink = (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? 'https' : 'http') . "://$_SERVER[HTTP_HOST]";
                $themeConfig = $themeOptions['theme'];
                $activeTheme = $themeConfig['active'] === 'light';
                $themeLight  = $activeTheme ? $themeConfig['colour']['components'] : $themeConfig['colour']['background'];
                $themeDark   = $activeTheme ? $themeConfig['colour']['background'] : $themeConfig['colour']['components'];
                
                echo '<a href="' . get_site_url(get_current_blog_id()) . '" target="_self" style="background-color:' . ($themeLight === 'transparent' ? $themeConfig['colour']['accent'] : $themeLight) . ';color:' . ($themeDark === 'transparent' ? $themeConfig['colour']['accent'] : $themeDark) . ';border: solid 1px ' . ($themeDark === 'transparent' ? $themeConfig['colour']['accent'] : $themeDark) . '" class="pwa-button-switch" data-task="expire" data-url="' . $currentLink . '">Switch to PWA</a>';
            } else {
                unset($_COOKIE['classicCookie']);
            }
        }
        
        /**
         * Switch from application to classic site with Cookie.
         *
         * If this doesnt succeed, which happens with weird headers the javascript will switch off the cookie for us.
         *
         * @since 2.0.0
         */
        public function frontendPwaApp() {
            /**
             * Do security check first.
             */
            if (wp_verify_nonce($_REQUEST['security'], $this->pluginName) === false) {
                wp_send_json_error();
                wp_die('Invalid Request!');
            }
            
            if (isset($_COOKIE['classicCookie']) && $_REQUEST['data'] === 'expire') {
                unset($_COOKIE['classicCookie']);
                setcookie('classicCookie', 'false', 0, '/', get_site_url(get_current_blog_id()), true);
                wp_send_json_success($_REQUEST['data']);
            } else {
                wp_send_json_error($_REQUEST['data']);
            }
            wp_die();
        }
        
        /**
         * Set headers for PWA check for switch.
         *
         * @since 2.0.0
         */
        public function setHeaders() {
            remove_filter('rest_pre_serve_request', 'rest_send_cors_headers');
            add_filter('rest_pre_serve_request', [$this, 'setHeadersCors']);
            add_action('init', [$this, 'setHeadersCors']);
            $this->addFieldsToRest();
        }
        
        /**
         * Set headers for PWA check for switch.
         *
         * @param $value
         *
         * @return mixed
         *
         * @since 2.1.0
         */
        public function setHeadersCors($value) {
            header('Access-Control-Allow-Origin: *');
            header('Access-Control-Allow-Methods: GET');
            header('Access-Control-Allow-Credentials: true');
            header('Access-Control-Expose-Headers: Link', false);
            
            return $value;
        }
        
        /**
         * Adds rest support.
         *
         * Add User Avatar to WP POST API.
         * Add AMP Content to WP POST API.
         *
         * @since 2.1.0
         */
        public function addFieldsToRest() {
            register_rest_field(['post'], 'author_avatar', [
                'get_callback' => [$this, 'getUserAvatar'],
                'schema'       => 'JSON',
            ]);
            
            /**
             * Check if AMP plugin exists and is active.
             */
            if (file_exists(WP_PLUGIN_DIR . '/amp/amp.php')) {
                include_once ABSPATH . 'wp-admin/includes/plugin.php';
                if (is_plugin_active('amp/amp.php')) {
                    register_rest_field(['post', 'page'], 'pwa_amp', [
                        'get_callback' => [$this, 'getAmpContent'],
                        'schema'       => 'JSON',
                    ]);
                }
            }
        }
        
        /**
         * The rest return for user avatar.
         *
         * @param $object
         *
         * @return false|string
         *
         * @since 2.1.0
         */
        public function getUserAvatar($object) {
            $userId = $object['author'];
            
            return get_avatar_url($userId);
        }
        
        /**
         * Returns rendered AMP content to the rest api.
         *
         * @param $object
         *
         * @return array
         * @since 2.2.1
         */
        public function getAmpContent($object) {
            $sanitizers                                          = amp_get_content_sanitizers();
            $embedHandlers                                       = AMP_Theme_Support::register_content_embed_handlers();
            $sanitizers['AMP_Embed_Sanitizer']['embed_handlers'] = $embedHandlers;
            
            $returnAmp = static function() {
                return 'amp';
            };
            
            add_filter('wp_video_shortcode_library', $returnAmp);
            add_filter('wp_audio_shortcode_library', $returnAmp);
            
            /**
             * This filter is documented in wp-includes/post-template.php.
             */
            $content = apply_filters('the_content', $object['content']['rendered']);
            $results = AMP_Content_Sanitizer::sanitize($content, $sanitizers);
            
            remove_filter('wp_video_shortcode_library', $returnAmp);
            remove_filter('wp_audio_shortcode_library', $returnAmp);
            
            return ['rendered' => $results[0]];
        }
        
        /**
         * Get current id of where you are.
         *
         * @since 2.0.0
         */
        public function getCurrentPostId() {
            $queriedObject = get_queried_object();
            global $wp;
            
            if (is_category()) {
                $currentPostId = '/pt_wp_section/' . $queriedObject->term_id;
            } elseif (is_tax()) {
                $currentPostId = '/pt_wp_taxonomy/posts/' . $queriedObject->taxonomy . $queriedObject->term_id;
            } elseif (is_author()) {
                $authorId      = get_query_var('author');
                $currentPostId = '/pt_wp_taxonomy/posts/author/' . $authorId;
            } elseif (is_tag()) {
                $currentPostId = '/pt_wp_taxonomy/tags/' . $queriedObject->term_id;
            } elseif (is_singular() && !is_home() && !is_front_page()) {
                if (array_key_exists('pwa-amp', $_GET) || array_key_exists('amp', $wp->query_vars) || $wp->query_vars['name'] === 'pwa-amp') {
                    $currentPostId = '/pt_wp_post_amp/' . get_the_ID();
                } else {
                    $currentPostId = '/pt_wp_post/' . get_the_ID();
                }
            } elseif (is_home() || is_front_page()) {
                $currentPostId = '/';
            } else {
                $currentPostId = $_SERVER['REQUEST_URI'];
            }
            
            return $currentPostId;
        }
        
        /**
         * Add fallback script to load plugin interface.
         *
         * Full script gets loaded with frontend script, window vars gets loaded above frontend script.
         *
         * @since 2.0.0
         */
        public function insertFallbackScript() {
            $pluginGlobal = new PublishersToolboxPwaGlobal($this->pluginName, $this->pluginVersion);
            $themeOptions = $pluginGlobal->getPluginOptions();
            
            if (!$pluginGlobal->pwaDetectCustomPostTypes()) { ?>
                <script>
                    window.appEndPoint = '<?php echo $this->getApplicationEndpoint($themeOptions); ?>';
                    window.appColour = '<?php echo $themeOptions['theme']['colour']['components']; ?>';
                    window.postId = <?php echo get_the_ID(); ?>;
                    <?php include_once 'assets/lib/FallbackScript.php'; ?>
                </script>
                <?php
            }
        }
        
        /**
         * Add AMP script.
         *
         * @since 2.2.2
         */
        public function insertAmpScript() {
            if (is_tag() || is_category() || is_home() || is_front_page()) {
                return true;
            }
            if (!$this->pwaDetectCustomPostTypes('AMP')) {
                remove_action('wp_head', 'amp_add_amphtml_link');
                remove_action('template_redirect', 'amp_render');
                ?>
                <link rel="amphtml" href="<?php echo get_permalink(get_the_ID()); ?>?pwa-amp">
                <?php
            }
        }
        
        /**
         * Return the application endpoint.
         *
         * @param $themeOptions
         *
         * @return mixed
         *
         * @since 2.1.0
         */
        public function getApplicationEndpoint($themeOptions) {
            return !empty($themeOptions['performance']['application_endpoint']) ? $themeOptions['performance']['application_endpoint'] : (new PublishersToolboxPwaFileHandling($this->pluginName, $this->pluginVersion))::PWA_ENDPOINT;
        }
        
        /**
         * Detect if we are viewing a custom post type.
         *
         * @param null $post
         * @param string $product
         *
         * @return bool
         * @since 2.3.1
         */
        public function pwaDetectCustomPostTypes($product = 'PWA', $post = NULL) {
            $allCustomPostTypes = get_post_types(['_builtin' => false]);
            
            /**
             * Exclude some of the built in types as well.
             */
            if ($product !== 'PWA') {
                $allCustomPostTypes['page'] = 'page';
            }
            
            $allCustomPostTypes['attachment']    = 'attachment';
            $allCustomPostTypes['revision']      = 'revision';
            $allCustomPostTypes['nav_menu_item'] = 'nav_menu_item';
            $allCustomPostTypes['wp_block']      = 'wp_block';
            
            /**
             * There are no custom post types, go ahead and load everything.
             */
            if (empty($allCustomPostTypes)) {
                $postTypeStatus = false;
            } else {
                $customTypes     = array_keys($allCustomPostTypes);
                $currentPostType = get_post_type($post);
                
                /**
                 * Could not detect current type.
                 */
                if (!$currentPostType) {
                    $postTypeStatus = false;
                } else {
                    /**
                     * If our current type is in the custom post type then return false.
                     */
                    $postTypeStatus = in_array($currentPostType, $customTypes, true);
                }
            }

            return $postTypeStatus;
        }
        
        /**
         * Setup PWA rest endpoint for configs.
         *
         * Endpoint: /wp-json/pwa/v2/theme
         *
         * @since 2.3.5
         */
        public function themeRestPoint() {
            register_rest_route('pwa/v2', 'theme', [
                'methods'  => 'GET',
                'callback' => [$this, 'pwaThemeSettings'],
            ]);
        }
        
        /**
         * Setup return data for PWA rest api endpoint.
         *
         * @return array|false|mixed|string|void
         *
         * @since 2.3.5
         */
        public function pwaThemeSettings() {
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
             * Check if the dates align.
             */
            return (new PublishersToolboxPwaFileHandling($this->pluginName, $this->pluginVersion))->setupThemeFile($themeData, false, false);
        }
    }
