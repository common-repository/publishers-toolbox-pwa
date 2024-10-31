<?php
    
    namespace PT\ptx;
    
    if (!defined('ABSPATH')) {
        exit();
    }
    
    /**
     * Handle requests.
     *
     * A class to handle all the api requests.
     *
     * This is used to to handle all requests to API servers.
     *
     * @link       https://www.publisherstoolbox.com/websuite/
     *
     * @package    PublishersToolboxPwa
     * @subpackage PublishersToolboxPwa/ptx
     * @author     Publishers Toolbox <support@afrozaar.com>
     *
     * @since      2.1.0
     */
    class PublishersToolboxPwaRequests {
        
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
         * PublishersToolboxPwaRequests constructor.
         *
         * @param $pluginName
         * @param $pluginVersion
         *
         * @since 2.0.0
         */
        public function __construct($pluginName, $pluginVersion) {
            $this->pluginName    = $pluginName;
            $this->pluginVersion = $pluginVersion;
        }
        
        /**
         * Request handler.
         *
         * @param string $url
         * @param $action
         * @param array|mixed $body
         * @param string $return Accepts array|json|code
         * @param string $method
         * @param array $headers
         *
         * @return array|mixed|object|string|null
         *
         * @since 2.1.0
         */
        public function request($url, $action, $body = [], $return = 'array', $method = 'POST', $headers = []) {
            global $wp_version;
            
            $url .= ($action ?? '');
            
            $data = [
                'method'      => $method,
                'timeout'     => 45,
                'redirection' => 5,
                'httpversion' => '1.0',
                'user-agent'  => 'WordPress/' . $wp_version . '; ' . get_site_url(get_current_blog_id()),
                'blocking'    => true,
                'sslverify'   => true,
                'headers'     => $headers,
            ];
            
            if (!empty($body)) {
                $data['body'] = $body;
            }
            
            $response = NULL;
            
            /**
             * Do the request.
             */
            if ($method === 'POST') {
                $response = wp_safe_remote_post($url, $data);
            } elseif ($method === 'GET') {
                $response = wp_safe_remote_get($url, $data);
            } else {
                $response = wp_safe_remote_post($url, $data);
            }
            
            $responseCode = wp_remote_retrieve_response_code($response);
            
            /**
             * Log error from lambda server.
             */
            $responseMessage = wp_remote_retrieve_response_message($response);
            $responseHeader  = wp_remote_retrieve_header($response, 'x-cache');
            $this->logErrors($responseCode, $responseHeader, $responseMessage, $url, get_site_url(get_current_blog_id()), $action, $response);
            
            /**
             * Respond with error code and log if there is an error.
             */
            if (200 !== $responseCode || !isset($response) || $response['body'] === '{}' || is_wp_error($response)) {
                /**
                 * Log error from lambda server.
                 */
                $this->logErrors($responseCode, $responseHeader, $responseMessage, $url, get_site_url(get_current_blog_id()));
                
                /**
                 * Return error code.
                 */
                return $responseCode;
            }
            
            /**
             * Return if 200.
             */
            if (wp_remote_retrieve_response_code($response) === 200 && $response['body'] !== '{}') {
                
                if ($return === 'json') {
                    return wp_remote_retrieve_body($response);
                }
                
                if ($return === 'array') {
                    return json_decode(wp_remote_retrieve_body($response), true);
                }
                
                if ($return === 'code') {
                    return wp_remote_retrieve_response_code($response);
                }
                
                return json_decode(wp_remote_retrieve_body($response), true);
            }
            
            return NULL;
        }
        
        /**
         * Log errors.
         *
         * Used in debug mode.
         *
         * @param int $code
         * @param string $message
         * @param string $wpError
         * @param string $endPoint
         * @param string $currentUrl
         * @param string $action
         * @param array $fullResponse Full body response from request class.
         *
         * @since 2.0.6
         */
        public function logErrors($code = 200, $message = '', $wpError = '', $endPoint = '', $currentUrl = '', $action = '', $fullResponse = []) {
            $pluginGlobal  = new PublishersToolboxPwaGlobal($this->pluginName, $this->pluginVersion);
            $pathDebugFile = plugin_dir_path(__DIR__) . PUBLISHERS_TOOLBOX_PLUGIN_NAME . '.log';
            $response      = $fullResponse['body'] ?? 'No Response';
            
            $divider      = '--------------------------------------------';
            $date         = '[Date: ' . date('Y-m-d H:i:s') . '] ';
            $error        = $date . 'PWA Error: ' . $code . ' - ' . $message;
            $trace        = $date . 'PWA Stack trace: ' . $action;
            $wp           = $date . 'PWA WP Error: ' . $wpError . ' ';
            $lambda       = $date . 'PWA Remote: ' . $endPoint . ' ';
            $url          = $date . 'PWA Url: ' . $currentUrl . ' ';
            $fullResponse = print_r($response, true);
            
            $errorMessage  = "$divider\n" . "$trace\n" . "$divider\n" . "$error\n" . "$wp\n" . "$lambda\n" . "$url\n" . "$divider\n" . $fullResponse . "\n$divider\n";
            $debugSettings = $pluginGlobal->setPluginDebug('info');
            
            if (PW_PWA_MODE === 'debug' || $debugSettings) {
                error_log($errorMessage, 3, $pathDebugFile);
            } else if (PW_PWA_MODE !== 'debug' && file_exists($pathDebugFile)) {
                unlink($pathDebugFile);
                $pluginGlobal->setPluginDebug(false);
            }
        }
    }
