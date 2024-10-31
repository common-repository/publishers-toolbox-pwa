<?php
    
    namespace PT\ptx;
    
    if (!defined('ABSPATH')) {
        exit();
    }
    
    /**
     * The file that defines all notices.
     *
     * This is used to create notifications for our plugin.
     *
     * @link       https://www.publisherstoolbox.com/websuite/
     *
     * @package    PublishersToolboxPwa
     * @subpackage PublishersToolboxPwa/ptx
     * @author     Publishers Toolbox <support@afrozaar.com>
     *
     * @since 2.0.0
     */
    class PublishersToolboxPwaNotices {
        
        /**
         * The ID of this plugin.
         *
         * @access private
         * @var string $pluginName The ID of this plugin.
         *
         * @since 2.0.0
         */
        public $pluginName;
        
        /**
         * The version of this plugin.
         *
         * @access private
         * @var string $pluginVersion The current version of this plugin.
         *
         * @since 2.7.3
         */
        private $pluginVersion;
        
        /**
         * The nice name for notifications.
         *
         * @since 2.0.0
         */
        const PUBLISHERS_TOOLBOX_PLUGIN_NICE_NAME = 'WebSuite PWA';
        
        /**
         * Application Env
         *
         * @since 2.1.0
         */
        const APP_ENV = 'pt-pwa';
        
        /**
         * The default url to verify key.
         *
         * @since 2.1.0
         */
        const PWA_API_URL = PW_PWA_MODE === 'debug' ? 'https://zco4fscfwi.execute-api.eu-west-1.amazonaws.com/test/v1/stripe/domain/' . self::APP_ENV . '/' : 'https://zco4fscfwi.execute-api.eu-west-1.amazonaws.com/prod/v1/stripe/domain/' . self::APP_ENV . '/';
        
        /**
         * The default url to verify domain.
         *
         * @since 2.1.0
         */
        const PWA_REGISTER_URL = 'https://api.websuite.org/pwa/generate?domain=';
        
        /**
         * The default key to verify domain.
         *
         * @since 2.1.0
         */
        const PWA_REGISTER_KEY = 'U6Qf31DTwm9JHvRdntdAmJdRme6HELZ9phOB4UGh';
        
        /**
         * Set ARN for application.
         *
         * @since 2.1.0
         */
        const ARN_API_URL = PW_PWA_MODE === 'debug' ? 'https://test-sns.publisherstoolbox.com/web/sns/' : 'https://sns.publisherstoolbox.com/web/sns/';
        
        /**
         * PublishersToolboxPwaNotices constructor.
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
         * Returns the notices for the plugin.
         *
         * @since 2.0.0
         */
        public function displayNotices() {
            $premiumOptions = $this->getOptions('subscription');
            $pluginOptions  = $this->getOptions('options');
            /**
             * Check the date.
             */
            $this->adjustDate($premiumOptions, $pluginOptions);
            
            /**
             * Trial version.
             */
            if (isset($premiumOptions['status']) && $premiumOptions['status'] === 'trialing') {
                $this->noticeMessage('You have started your PWA Premium trial. You have ' . $premiumOptions['days'] . ' days left before your subscription renewal date.', 'info', 'trial');
            }
            
            /**
             * Check period of subscription.
             */
            if (isset($premiumOptions['days']) && $premiumOptions['days'] <= 30 && $premiumOptions['days'] > 0 && $premiumOptions['days'] !== 0 && $premiumOptions['status'] !== 'trialing') {
                $this->noticeMessage('You have less than ' . $premiumOptions['days'] . ' days left on your PWA license. Go to the ' . $this->getOptions('link') . ' page to upgrade.', 'info', 'subscribed');
            }
            
            /**
             * Expired license.
             */
            if (isset($premiumOptions['days']) && $premiumOptions['days'] === 0) {
                $this->noticeMessage('Your PWA subscription has expired, you are now using the free version. Go to the ' . $this->getOptions('link') . ' page to upgrade.', 'error', 'expired');
            }
            
            /**
             * Free version.
             */
            if (isset($premiumOptions['days']) && $premiumOptions['days'] < 0) {
                $this->noticeMessage('Your are using PWA free version. Go to the ' . $this->getOptions('link') . ' page to subscribe and get premium features and support.', 'info', 'free');
            }
            
            /**
             * PWA On or Off.
             */
            if (empty($pluginOptions) || !isset($pluginOptions['active']) || $pluginOptions['active'] === false) {
                $this->noticeMessage('PWA has not been activated yet. Go to the ' . $this->getOptions('link') . ' page to switch it on.', 'warning', 'disabled');
            }
        }
        
        /**
         * The notice message layout.
         *
         * @param string $message
         * @param string $type | success, error, warning, info
         * @param string $action The action to run on dismiss.
         *
         * @since 2.0.0
         */
        public function noticeMessage($message = '', $type = 'success', $action = 'notify') {
            /**
             * Check the status before displaying notification.
             */
            if ($this->checkNoticeStatus($action)) { ?>
                <div class="notice notice-<?php echo $type; ?> is-dismissible <?php echo $this->getOptions('name'); ?>" data-action="<?php echo $action; ?>">
                    <p>
                        <strong><?php echo self::PUBLISHERS_TOOLBOX_PLUGIN_NICE_NAME; ?></strong> - <?php echo $message; ?>
                    </p>
                    <button type="button" class="notice-dismiss">
                        <span class="screen-reader-text">Dismiss this notice.</span>
                    </button>
                </div>
                <?php
            }
        }
        
        /**
         * Setup the subscription details.
         *
         * @return bool
         *
         * @since 2.0.0
         */
        public function startPremiumVersion() {
            /**
             * Validate the domain.
             */
            $verifyDate = $this->getVerificationKey();
            
            /**
             * Check arn registration.
             */
            $verifyArn = $this->checkVerificationArn();
            
            /**
             * Save the data.
             */
            if (is_array($verifyDate)) {
                $checkSubscription = date_diff(date_create(date('Ymd')), date_create(date('Ymd', $verifyDate['currentPeriodEnd'])));
                
                /**
                 * Pretty names for products.
                 */
                if (stripos($verifyDate['plan']['nickname'], 'enterprise') !== false) {
                    $status = 'enterprise';
                } else {
                    $status = $verifyDate['status'];
                }
                
                return $this->updatePremiumOptions([
                    'date'         => date('Ymd', $verifyDate['currentPeriodEnd']),
                    'days'         => $checkSubscription->days,
                    'subscription' => $verifyDate['plan']['nickname'],
                    'last_save'    => date('Y-m-d H:i:s'),
                    'arn'          => $verifyArn,
                    'status'       => $status,
                ]);
            }
            
            /**
             * False means free.
             */
            $this->updatePremiumOptions([
                'last_save' => date('Y-m-d H:i:s'),
                'arn'       => $verifyArn,
                'status'    => 'free',
            ]);
            
            return false;
        }
        
        /**
         * Check domain registration.
         *
         * @return array
         *
         * @since 2.1.0
         */
        public function getVerificationKey() {
            return (new PublishersToolboxPwaRequests($this->pluginName, $this->pluginVersion))->request(self::PWA_API_URL, $this->sanitizeDomain(get_site_url(get_current_blog_id())), '', '', 'GET');
        }
        
        /**
         * Check if ARN was registered.
         *
         * @return bool|string
         *
         * @since 2.1.0
         */
        public function checkVerificationArn() {
            /**
             * Validate the arn registration.
             */
            $body     = '{"domain":"' . $this->sanitizeDomain(get_site_url(get_current_blog_id())) . '", "application":"' . self::APP_ENV . '"}';
            $checkArn = (new PublishersToolboxPwaRequests($this->pluginName, $this->pluginVersion))->request(self::ARN_API_URL, 'checkapplication', $body, 'code');
            if ($checkArn === 200) {
                return true;
            }
            
            /**
             * Create the arn registration.
             */
            $createArn = (new PublishersToolboxPwaRequests($this->pluginName, $this->pluginVersion))->request(self::ARN_API_URL, 'createapplication', $body, 'code');
            
            return $createArn === 200;
        }
        
        /**
         * Sanitize the domain.
         *
         * @param $domain
         * @return mixed
         *
         * @since 2.1.0
         */
        public function sanitizeDomain($domain) {
            $sanitizeDomain = sanitize_text_field($domain);
            if (strpos($sanitizeDomain, 'http') !== false) {
                $parseUrl = parse_url($sanitizeDomain, PHP_URL_HOST);
                
                return preg_replace('#^www\.(.+\.)#i', '$1', $parseUrl);
            }
            
            return preg_replace('#^www\.(.+\.)#i', '$1', $sanitizeDomain);
        }
        
        /**
         * Dismiss notice ajax function.
         *
         * @since 2.0.0
         */
        public function dismissNotice() {
            /**
             * Do security check first.
             */
            if (wp_verify_nonce($_REQUEST['security'], $this->getOptions('name')) === false) {
                wp_send_json_error();
                wp_die('Invalid Request!');
            }
            
            /**
             * Build multiple options array.
             */
            $noticeOptions                            = $this->getOptions('notice');
            $requestAction                            = $_REQUEST['data']['action'];
            $noticeOptions['notices'][$requestAction] = date('Y-m-d H:i');
            
            /**
             * There are different notifications, we can access them via the @var $action .
             */
            switch ($_REQUEST['data']['action']) {
                case 'disabled':
                case 'subscribed':
                    $this->updateNoticeOptions($noticeOptions);
                    break;
                default:
                    wp_die();
            }
            /**
             * We are done.
             */
            wp_die();
        }
        
        /**
         * Check current active notice status.
         *
         * @param string $notification The notification identifier name.
         * @return bool
         *
         * @since 2.0.0
         */
        public function checkNoticeStatus($notification = '') {
            /**
             * Get the current set of options.
             */
            $noticeOptions = $this->getOptions('notice');
            if (isset($noticeOptions['notices'][$notification])) {
                $notifyAgain    = date('Y-m-d H:i', strtotime($noticeOptions['notices'][$notification] . '+2 days'));
                $dateDifference = date_diff(date_create(date('Ymd')), date_create($notifyAgain));
                if ($dateDifference->days >= 2) {
                    return false;
                }
            }
            
            return true;
        }
        
        /**
         * Update the options.
         *
         * @param $inputOption array
         * @return bool
         *
         * @since 2.0.0
         */
        public function updatePremiumOptions($inputOption = []) {
            return update_option($this->getOptions('name') . '-subscription', $inputOption);
        }
        
        /**
         * Update notice options.
         *
         * @param $inputOption array
         * @return bool
         *
         * @since 2.0.0
         */
        private function updateNoticeOptions($inputOption = []) {
            return update_option($this->getOptions('name') . '-notices', $inputOption);
        }
        
        /**
         * Do some date checking.
         *
         * @param array $premiumOptions
         * @param array $themeOptions
         * @return bool
         *
         * @since 2.0.0
         */
        public function adjustDate($premiumOptions, $themeOptions) {
            /**
             * Do quick check for premium and verify its been checked only once.
             */
            $pluginGlobal = new PublishersToolboxPwaGlobal($this->pluginName, $this->pluginVersion);
            $checkDomain  = $pluginGlobal->setPluginDomain('info');
            if (!$checkDomain) {
                $testDomain = (new PublishersToolboxPwaRequests($this->pluginName, $this->pluginVersion))->request(self::PWA_REGISTER_URL, $this->sanitizeDomain(get_site_url(get_current_blog_id())), '', 'code', 'GET', ['x-api-key' => self::PWA_REGISTER_KEY]);
                if ($testDomain === 200) {
                    $pluginGlobal->setPluginDomain(true);
                } else {
                    $pluginGlobal->setPluginDomain(false);
                }
            }
            
            /**
             * Do date update if needed.
             */
            if (isset($premiumOptions['date'])) {
                $currentDate = date_diff(date_create(date('Ymd')), date_create($premiumOptions['date']));
                if ($currentDate->days < $premiumOptions['days']) {
                    $premiumOptions['days'] = $currentDate->days;
                    $this->updatePremiumOptions($premiumOptions);
                    (new PublishersToolboxPwaFileHandling($this->pluginName, $this->pluginVersion))->setupThemeFile($themeOptions);
                }
                
                return $premiumOptions['days'] <= 0;
            }
            
            return false;
        }
        
        /**
         * Check if plugin is valid.
         *
         * This is only to display upgrade messages, keeping it simple.
         *
         * @param $premiumOptions
         * @return bool
         *
         * @since 2.1.0
         */
        public function subscriptionBoolCheck($premiumOptions) {
            if (isset($premiumOptions['date'], $premiumOptions['days'], $premiumOptions['status'], $premiumOptions['days']) && $premiumOptions['status'] !== 'free' && $premiumOptions['days'] !== 0) {
                return true;
            }
            
            if (empty($premiumOptions)) {
                return false;
            }
            
            return false;
        }
        
        /**
         * Simple helper function.
         *
         * @param $type
         * @return mixed|string|void
         *
         * @since 2.0.0
         */
        public function getOptions($type) {
            $pluginGlobal = new PublishersToolboxPwaGlobal($this->pluginName, $this->pluginVersion);
            
            switch ($type) {
                case 'options':
                    return $pluginGlobal->getPluginOptions();
                    break;
                case 'notice':
                    return $pluginGlobal->getNoticeOptions();
                    break;
                case 'name':
                    return $this->pluginName;
                    break;
                case 'link':
                    return $pluginGlobal->getSettingsLink();
                    break;
                case 'subscription':
                    return $pluginGlobal->getPluginSubscription();
                    break;
                default:
                    return $pluginGlobal->getPluginOptions();
            }
        }
    }
