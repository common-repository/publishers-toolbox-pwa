<?php
    
    namespace PT\ptx;
    
    use WP_Widget;
    
    if (!defined('ABSPATH')) {
        exit();
    }
    
    /**
     * Setup the push notifications options.
     *
     * Create push widget for posts, create/edit.
     *
     * @package    PublishersToolboxPwa
     * @subpackage PublishersToolboxPwa/ptx
     * @author     Publishers Toolbox <support@afrozaar.com>
     *
     * @since 2.0.0
     */
    class PublishersToolboxPwaPush extends WP_Widget {
        
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
         * The saved options for the plugin.
         *
         * @access public
         * @var array $pluginOptions The plugin options array.
         *
         * @since 2.0.0
         */
        private $pluginOptions;
        
        /**
         * The saved options for the plugin subscription.
         *
         * @access public
         * @var array $pluginOptions The plugin options array.
         *
         * @since 2.0.0
         */
        private $pluginSubscription;
        
        /**
         * Initialize the class and set its properties.
         *
         * @param $pluginOptions
         * @param $pluginSubscription
         * @param $pluginName
         * @param $pluginVersion
         *
         * @since 2.1.0
         */
        public function __construct($pluginOptions, $pluginSubscription, $pluginName, $pluginVersion) {
            $this->pluginName         = $pluginName;
            $this->pluginVersion      = $pluginVersion;
            $this->pluginOptions      = $pluginOptions;
            $this->pluginSubscription = $pluginSubscription;
            
            if ($this->registerActions()) {
                parent::__construct($this->pluginName, __('PWA Push Notifications Widget', $this->pluginName), [
                    'classname'   => $this->pluginName,
                    'description' => __('Pwa Push Notifications', $this->pluginName),
                ]);
            }
        }
        
        /**
         * Register actions.
         *
         * @return bool
         *
         * @since 2.1.0
         */
        public function registerActions() {
            if (isset($this->pluginOptions['push'])) {
                if (isset($this->pluginSubscription['days']) && $this->pluginSubscription['days'] !== 0) {
                    add_action('admin_init', [$this, 'addCustomWidgetBoxPost'], 1);
                    add_action('save_post', [$this, 'savePushData']);
                    
                    return true;
                }
            }
            
            return false;
        }
        
        /**
         * Set the post list new column header.
         *
         * @param $columns
         *
         * @return string
         *
         * @since 2.1.0
         */
        public function pushColumnsHead($columns) {
            $columns['push_notifications_date_sent'] = __('Sent Push Notification');
            
            return $columns;
        }
        
        /**
         * Set the post list new column content.
         *
         * @param $column
         * @param $post_id
         *
         * @return void
         *
         * @since 2.1.0
         */
        public function pushColumnsContent($column, $post_id) {
            if ($column === 'push_notifications_date_sent') {
                $metaData = get_post_meta($post_id, $this->pluginName, true);
                if (isset($metaData) && !empty($metaData)) {
                    echo $metaData;
                }
            }
        }
        
        /**
         * Setup Post meta box.
         *
         * @since 2.1.0
         */
        public function addCustomWidgetBoxPost() {
            add_meta_box('pt_pwa_post_widget_setting', __('PWA Push Notification', $this->pluginName), [
                $this,
                'customWidgetBoxForm',
            ], 'post', 'side');
        }
        
        /**
         * Display the box content.
         *
         * @since 2.1.0
         */
        public function customWidgetBoxForm() {
            /**
             * Only published posts can send push notifications.
             */
            global $post;
            $postMeta   = get_post_meta($post->ID, $this->pluginName, true);
            $formFields = new PublishersToolboxPwaFields($this->pluginName, $this->pluginVersion);
            
            /**
             * Build fields.
             */
            if (get_post_status($post->ID) === 'publish') { ?>
                <section class="pwa-sidebar-widget">
                    <?php if (isset($postMeta) && !empty($postMeta)) { ?>
                        <div class="notice notice-warning inline pwa-widget-notice">
                            <p><?php _e('You have already sent a Push Notification for this post.', $this->pluginName); ?></p>
                        </div>
                    <?php } ?>
                    <div class="notice notice-success inline pwa-widget-notice pwa-hidden">
                        <p><?php _e('Push Notification Sent!', $this->pluginName); ?></p>
                    </div>
                    <div class="notice notice-error inline pwa-widget-notice pwa-hidden">
                        <p><?php _e('Error sending Push Notification! (Please check the Title and Message field.)', $this->pluginName); ?></p>
                    </div>
                    <div class="description">
                        <p>To send a push notification check the fields below and click on the "Send Push Notification"
                            to send a notification to your PWA users.</p>
                        <p>** Only insert data into the below fields if you would like to customise the message data.
                            **</p>
                    </div>
                    <div class="pwa-custom-fields">
                        <div class="widget-field">
                            <?php echo $formFields->textField('Custom Message Title:', 'title', '', '', [
                                'description' => 'Leave blank for default.',
                                'placeholder' => get_the_title($post->ID),
                            ]); ?></div>
                        <div class="widget-field">
                            <?php echo $formFields->textBoxField('Custom Message:', 'message', '', '', ['description' => 'Leave blank for default.']); ?></div>
                        <div class="widget-field">
                            <?php echo $formFields->checkboxField('Send Featured Image?', 'image', '', 1, [
                                'description' => 'Include featured image in push message?',
                                'checked'     => 1,
                            ]); ?></div>
                    </div>
                    <p>
                        <button type="button" class="btn is-primary has-margin-top pwa-widget-button pwa-send-push-notification" data-action="push" data-post="<?php echo $post->ID; ?>"><?php echo isset($postMeta) && !empty($postMeta) ? 'Resend Push Notification' : 'Send Push Notification'; ?></button>
                    </p>
                </section>
            <?php } else { ?>
                <div class="notice notice-warning inline pwa-widget-notice">
                    <p><?php _e('Only <b>Published</b> posts can send Push Notifications. Please publish this post and refresh the page to send a Push Notification.', $this->pluginName); ?></p>
                </div>
                <?php
            }
        }
        
        /**
         * When the post is saved, saves our custom data.
         *
         * @param $postId
         *
         * @since 2.1.0
         */
        public function savePushData($postId) {
            /**
             * Verify if this is an auto save routine.
             * Our form has not been submitted, do nothing.
             */
            if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) {
                return;
            }
            
            /**
             * Check permissions.
             */
            if ($_POST['post_type'] === 'page') {
                return;
            }
            
            if (!current_user_can('edit_post', $postId)) {
                return;
            }
        }
        
        /**
         * Ajax push notification.
         *
         * @since 2.1.0
         */
        public function sendPushNotification() {
            /**
             * Do security check first.
             */
            if (wp_verify_nonce($_REQUEST['security'], $this->pluginName) === false) {
                wp_send_json_error();
                wp_die('Invalid Request!');
            }
            
            $postData = $_REQUEST['data'];
            
            if (isset($postData['post'])) {
                /**
                 * Update sent date.
                 */
                update_post_meta($postData['post'], $this->pluginName, date('Y-m-d H:i:s'));
                
                /**
                 * Send to service for processing.
                 */
                if ($sendNotification = $this->postPushNotification($postData)) {
                    wp_send_json_success($sendNotification);
                } else {
                    wp_send_json_error($sendNotification);
                }
            }
            wp_send_json_error();
            
            /**
             * We are done.
             */
            wp_die();
        }
        
        /**
         * Check if ARN was registered.
         *
         * @param $postData
         * @return bool|string
         *
         * @since 2.1.0
         * @throws \JsonException
         */
        private function postPushNotification($postData) {
            $postDataDefault = [
                'messageTitle' => isset($postData['title']) && !empty($postData['title']) ? $postData['title'] : $this->cleanTextForPush(get_the_title($postData['post'])),
                'messageBody'  => isset($postData['message']) && !empty($postData['message']) ? $postData['message'] : $this->cleanTextForPush(get_the_excerpt($postData['post'])),
                'messageUrl'   => get_the_permalink($postData['post']),
                'domain'       => (new PublishersToolboxPwaNotices($this->pluginName, $this->pluginVersion))->sanitizeDomain(get_site_url(get_current_blog_id())),
                'application'  => PublishersToolboxPwaNotices::APP_ENV,
            ];
            
            if (isset($postData['image']) && !empty($postData['image'])) {
                $messageIcon = PublishersToolboxPwaImageResize::imageResize($postData, 512, 256, false, ['size' => 'pwa-push']);
                if (!$messageIcon) {
                    $messageIcon = get_the_post_thumbnail_url($postData['post'], 'post-thumbnail');
                }
                $postDataDefault['messageIcon'] = $messageIcon;
            }
            
            $body        = json_encode($postDataDefault, JSON_THROW_ON_ERROR, 512);
            $sendMessage = (new PublishersToolboxPwaRequests($this->pluginName, $this->pluginVersion))->request(PublishersToolboxPwaNotices::ARN_API_URL, 'publishmessage', $body, 'code');
            
            return $sendMessage === 200;
        }
        
        /**
         * Clean up the excerpt.
         *
         * @param $text
         * @return bool|string|string[]|null
         *
         * @since 2.1.9
         */
        public function cleanTextForPush($text) {
            $excerpt = rtrim(str_replace('[&hellip', '', $text), '[...]');
            $excerpt = preg_replace('/ ([.*?])/', '', $excerpt);
            $excerpt = strip_shortcodes($excerpt);
            $excerpt = strip_tags($excerpt);
            $excerpt = substr($excerpt, 0, 120);
            $excerpt = substr($excerpt, 0, strrpos($excerpt, ' '));
            
            return trim(preg_replace('/\s+/', ' ', $excerpt));
        }
    }
