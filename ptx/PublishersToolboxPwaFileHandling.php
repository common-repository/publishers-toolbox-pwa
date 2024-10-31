<?php
    
    namespace PT\ptx;
    
    use WP_Error;
    
    if (!defined('ABSPATH')) {
        exit();
    }
    
    /**
     * Setup the files and paths for PWA files.
     *
     * Creates files for external access.
     *
     * @package    PublishersToolboxPwa
     * @subpackage PublishersToolboxPwa/includes
     * @author     Publishers Toolbox <support@afrozaar.com>
     *
     * @since 2.0.0
     */
    class PublishersToolboxPwaFileHandling {
        
        /**
         * The ID of this plugin.
         *
         * @access private
         * @var string $pluginName The ID of this plugin.
         *
         * @since 2.7.3
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
         * The theme file name to use.
         *
         * @access constant
         * @var string THEME_FILE_NAME The default theme file name.
         *
         * @since 2.0.0
         */
        const THEME_FILE_NAME = 'theme.json';
        
        /**
         * The manifest file name to use.
         *
         * @access constant
         * @var string MANIFEST_FILE_NAME The default manifest file name.
         *
         * @since 2.0.0
         */
        const MANIFEST_FILE_NAME = 'manifest.json';
        
        /**
         * The service worker file name to use.
         *
         * @access constant
         * @var string SW_FILE_NAME The default service worker file name.
         *
         * @since 2.0.0
         */
        const SW_FILE_NAME = 'service-worker.js';
        
        /**
         * The service worker file name to use.
         *
         * @access constant
         * @var string SW_FILE_NAME The default service worker file name.
         *
         * @since 2.0.0
         */
        const MSW_FILE_NAME = 'firebase-messaging-sw.js';
        
        /**
         * The service worker assets url.
         *
         * @access constant
         * @var string SW_ASSETS_FILE The default service worker assets uri.
         *
         * @since 2.0.0
         */
        const SW_ASSETS_FILE = 'https://pwa-assets.baobabsuite.com';
        
        /**
         * The service worker assets url.
         *
         * @access constant
         * @var string PWA_ENDPOINT The default endpoint uri.
         *
         * @since 2.0.0
         */
        const PWA_ENDPOINT = PW_PWA_MODE === 'debug' ? 'https://test-pwa-cdn.baobabsuite.com' : 'https://pwa-cdn.publisherstoolbox.com';
        
        /**
         * The theme verify url.
         *
         * @access constant
         * @var string PWA_VERIFY The default verify uri.
         *
         * @since 2.0.0
         */
        const PWA_VERIFY = 'https://5j1jwvqmdd.execute-api.eu-west-1.amazonaws.com/dev/web/pwa/sign';
        
        /**
         * The application media url.
         *
         * @access constant
         * @var string PWA_MEDIA The default media uri.
         *
         * @since 2.0.0
         */
        const PWA_MEDIA = 'https://image.afrozaar.com/image/1/process';
        
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
         * PublishersToolboxPwaFileHandling constructor.
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
         * Create the paths and files for all the sites
         *
         * @since 2.0.0
         */
        public function registerAdmin() {
            /**
             * Create the paths for all the sites files.
             */
            $this->createFilePaths();
            
            /**
             * Create the configuration files.
             */
            $this->createThemeFile();
            $this->createManifestFile();
            $this->createServiceWorkerFile();
            $this->createMessageServiceWorkerFile();
        }
        
        /**
         * Create directories for all sites present.
         *
         * @return bool
         *
         * @since 2.0.0
         */
        public function createFilePaths() {
            if (is_multisite()) {
                foreach (get_sites() as $sites) {
                    if (!file_exists($this->directoryPath . $sites->blog_id) && !mkdir($concurrentDirectory = $this->directoryPath . $sites->blog_id, 0755, true) && !is_dir($concurrentDirectory)) {
                        $error = new WP_Error('Directory Creation Failed.', sprintf('Directory "%s" was not created', $concurrentDirectory), 500);
                        wp_die($error->get_error_message(), $error->get_error_code());
                    }
                }
            } else if (!file_exists($this->directoryPath . get_current_blog_id()) && !mkdir($concurrentDirectory = $this->directoryPath . get_current_blog_id(), 0755, true) && !is_dir($concurrentDirectory)) {
                $error = new WP_Error('Directory Creation Failed.', sprintf('Directory "%s" was not created', $concurrentDirectory), 500);
                wp_die($error->get_error_message(), $error->get_error_code());
            }
            
            if (file_exists($this->directoryPath . get_current_blog_id())) {
                return true;
            }
            
            return false;
        }
        
        /**
         * Create default json theme file.
         *
         * @param array $configData Data to use for creating the file.
         * @param bool $createOnSave Create new file on save.
         *
         * @return bool
         *
         * @since 2.0.0
         */
        public function createThemeFile($configData = [], $createOnSave = false) {
            /**
             * This will always be the current site file location.
             */
            $currentSite = $this->directoryPath . get_current_blog_id() . '/' . self::THEME_FILE_NAME;
            
            /**
             * Check if the dates align.
             */
            $validateData = $this->setupThemeFile($configData);
            
            /**
             * Do multi site check.
             */
            if (is_multisite()) {
                if (!$createOnSave) {
                    /**
                     * We aren't saving, lets create the folders and files for later use.
                     */
                    foreach (get_sites() as $sites) {
                        if (!file_exists($currentSiteLoop = $this->directoryPath . $sites->blog_id . '/' . self::THEME_FILE_NAME)) {
                            if ($multiThemeFile = fopen($currentSiteLoop, 'wb')) {
                                fwrite($multiThemeFile, $validateData);
                                fclose($multiThemeFile);
                            }
                        }
                    }
                } else {
                    /**
                     * We are sending a save command, lets do all checks and write data.
                     */
                    if (!file_exists($currentSite)) {
                        $this->createFilePaths();
                    }
                    
                    if ($multiThemeFile = fopen($currentSite, 'wb')) {
                        fwrite($multiThemeFile, $validateData);
                        fclose($multiThemeFile);
                    }
                }
            } else {
                if (!file_exists($currentSite)) {
                    $this->createFilePaths();
                }
                
                $singleThemeFile = fopen($currentSite, 'wb');
                if ($singleThemeFile) {
                    fwrite($singleThemeFile, $validateData);
                    fclose($singleThemeFile);
                }
            }
            
            /**
             * Return true or false if the file exists.
             */
            if (file_exists($currentSite)) {
                return true;
            }
            
            return false;
        }
        
        /**
         * Build the array for options.
         *
         * @param array $configData
         *
         * @return array
         *
         * @since 2.0.0
         */
        public function buildThemeArray($configData = []) {
            /**
             * Build THEME_FILE_NAME file contents.
             */
            return [
                '@type'            => $configData['@type'],
                'appName'          => htmlspecialchars($configData['settings']['app_name']),
                'metaDescription'  => htmlspecialchars($configData['settings']['app_description']),
                'appActive'        => isset($configData) ? true : false,
                'appPremium'       => (bool)$configData['premium'],
                'appPreview'       => isset($configData['preview']) ? true : false,
                'hostUrl'          => $configData['host_url'],
                'appEndPoint'      => $configData['performance']['application_endpoint'],
                'apiEndPoint'      => $configData['performance']['api_endpoint'],
                'pluginVersion'    => PUBLISHERS_TOOLBOX_PLUGIN_VERSION,
                'contentProvider'  => 'WORDPRESS',
                'manifestUrl'      => $configData['host_url'] . '/' . self::MANIFEST_FILE_NAME,
                'serviceWorkerUrl' => $configData['host_url'] . '/' . self::SW_FILE_NAME,
                'isPushEnabled'    => isset($configData['push']) ? true : false,
                'media'            => $this->setApplicationMedia($configData),
                'design'           => $this->setApplicationTheme($configData),
                'social'           => $this->setApplicationSocial($configData),
                'advertisement'    => $this->setApplicationAdvertisement($configData),
                'analytics'        => $this->setApplicationAnalytics($configData),
                'settings'         => $this->setApplicationSettings($configData),
                'content'          => $this->setApplicationContent($configData),
            ];
        }
        
        /**
         * Build media for theme.
         *
         * @param array $configData
         *
         * @return array
         *
         * @since 2.0.0
         */
        public function setApplicationMedia($configData) {
            return [
                'headerImage'     => $this->checkMediaUrl($configData['branding']['app_logo']),
                'appIconUrl'      => $this->checkMediaUrl($configData['branding']['app_icon']),
                'hamburgerImage'  => $this->checkMediaUrl($configData['branding']['app_logo']),
                'loadingSpinner'  => plugin_dir_url(__DIR__) . 'admin/assets/img/ajax-loader.gif',
                'mediaServiceUrl' => $configData['performance']['media_endpoint'] ?: self::PWA_MEDIA,
                'mediaCdnUrl'     => $configData['performance']['cdn_endpoint'] ?: NULL,
            ];
        }
        
        /**
         * Setup images.
         *
         * @param $media
         *
         * @return string
         *
         * @since 2.0.0
         */
        public function checkMediaUrl($media) {
            $siteLogo = '';
            if (isset($media) && !empty($media)) {
                if (is_numeric($media)) {
                    /**
                     * Set logic for grouped and single field data.
                     */
                    $siteLogo = wp_get_attachment_url($media);
                } else if (!empty($media)) {
                    $siteLogo = $media;
                }
            } else {
                $siteLogo = plugin_dir_url(__DIR__) . 'admin/assets/img/logo.png';
            }
            
            return $siteLogo;
        }
        
        /**
         * Build Light and Dark theme options.
         *
         * @param array $configData
         *
         * @return array
         *
         * @since 2.0.0
         */
        public function setApplicationTheme($configData) {
            /**
             * Set the defaults and logic for colours.
             */
            $themeOverwrite        = isset($configData['colours']['active']);
            $colourOverWriteConfig = $configData['colours'];
            $themeConfig           = $configData['theme'];
            $activeTheme           = $themeConfig['active'] === 'light';
            
            $black = $activeTheme ? '#000000' : '#FFFFFF';
            $white = $activeTheme ? '#FFFFFF' : '#000000';
            $theme = $activeTheme ? $themeConfig['colour']['background'] : $themeConfig['colour']['components'];
            
            $layout = $themeConfig['layout'] === 'layout1' ? 1 : 2;
            
            /**
             * Font Select.
             */
            $primaryFont   = $themeConfig['font']['headers'] !== 'Default' ? str_replace(' ', '+', $themeConfig['font']['headers']) : NULL;
            $secondaryFont = $themeConfig['font']['content'] !== 'Default' ? str_replace(' ', '+', $themeConfig['font']['content']) : NULL;
            
            return [
                'selectedTheme'             => $themeConfig['active'],
                'themeOverwrite'            => $themeOverwrite,
                'bmBurgerBarsBackground'    => $themeOverwrite ? $colourOverWriteConfig['menu']['hamburger'] : $black,
                'bmCrossBackground'         => $themeOverwrite ? $colourOverWriteConfig['menu']['close'] : $black,
                'bmItemListColor'           => $themeOverwrite ? $colourOverWriteConfig['menu']['items'] : $white,
                'bmMenuBackground'          => $themeOverwrite ? $colourOverWriteConfig['menu']['background'] : $theme,
                'bmMenuBlockBackground'     => $themeOverwrite ? $colourOverWriteConfig['menu']['block'] : $theme,
                'bmMenuHeaderBackground'    => $themeOverwrite ? $colourOverWriteConfig['content']['header_background'] : $theme,
                'bmOverlayBackground'       => $themeOverwrite ? $colourOverWriteConfig['content']['theme_background'] : $black,
                'highlightsColour'          => $themeOverwrite ? $colourOverWriteConfig['content']['highlights'] : $themeConfig['colour']['accent'],
                'menuTextColour'            => $themeOverwrite ? $colourOverWriteConfig['menu']['text'] : $black,
                'sectionSliderBackground'   => $themeOverwrite ? $colourOverWriteConfig['content']['slider_background'] : $theme,
                'sectionSliderTextColor'    => $themeOverwrite ? $colourOverWriteConfig['content']['slider_text'] : $black,
                'selectedBackground'        => $themeOverwrite ? $colourOverWriteConfig['menu']['selected'] : $theme,
                'selectedText'              => $themeOverwrite ? $colourOverWriteConfig['menu']['select'] : $themeConfig['colour']['accent'],
                'textColour'                => $themeOverwrite ? $colourOverWriteConfig['content']['text'] : $white,
                'titleBlockBackgroundColor' => $themeOverwrite ? $colourOverWriteConfig['menu']['background'] : $theme,
                'themeColour'               => $theme,
                'backgroundColour'          => $themeOverwrite ? $colourOverWriteConfig['menu']['background'] : $theme,
                'borderColour'              => $themeOverwrite ? $colourOverWriteConfig['content']['highlights'] : $themeConfig['colour']['accent'],
                'menuSlideOutWidth'         => '75%',
                'searchLightTheme'          => $activeTheme ? true : false,
                'menuLightIcons'            => $activeTheme ? false : true,
                'layout'                    => !empty($themeConfig['layout']) && isset($themeConfig['layout']) ? $layout : 1,
                'primaryFont'               => $primaryFont,
                'secondaryFont'             => $secondaryFont,
                'mastHeadHeight'            => '75vh',
                'imageGalleryHeight'        => '60vh',
            ];
        }
        
        /**
         * Build Social options.
         *
         * @param array $configData
         *
         * @return array
         *
         * @since 2.0.0
         */
        public function setApplicationSocial($configData) {
            /**
             * Build ssk selectors.
             */
            $socialSsk = [];
            if (isset($configData['social']['ssk'])) {
                $count = 0;
                foreach ($configData['social']['ssk'] as $key => $val) {
                    $socialSsk[$count++] = 'ssk-' . $key;
                }
            }
            
            return [
                'twitterEmbedUrl'       => 'https://platform.twitter.com/widgets.js',
                'instagramEmbedUrl'     => 'https://www.instagram.com/embed.js',
                'shareTitlePrefix'      => $configData['social']['suffix'],
                'socialShareKitButtons' => $socialSsk,
                'linkedInSocialUrl'     => $configData['social']['url']['linkedin_url'],
                'instagramSocialUrl'    => $configData['social']['url']['instagram_url'],
                'twitterSocialUrl'      => $configData['social']['url']['twitter_url'],
                'facebookSocialUrl'     => $configData['social']['url']['facebook_url'],
                'youtubeSocialUrl'      => $configData['social']['url']['youtube_url'],
            ];
        }
        
        /**
         * Build Advertisement options.
         *
         * @param array $configData
         *
         * @return array
         *
         * @since 2.0.0
         */
        public function setApplicationAdvertisement($configData) {
            /**
             * Advertisers.
             *
             * We only support Google at the moment.
             */
            $argumentTargeting = [];
            $googleAds         = [];
            $advertiser        = 'GOOGLE';
            
            if (!$configData['premium'] || isset($configData['advertisement']['active'])) {
                /**
                 * Target Arguments for custom slots in google.
                 */
                $targetingArguments = explode(',', $configData['advertisement']['google']['targeting_arguments']);
                if ($targetingArguments) {
                    foreach ($targetingArguments as $target) {
                        $targetObject = explode(':', $target);
                        if ($targetObject) {
                            $argumentTargeting[$targetObject[0]] = $targetObject[1] ?? '';
                        }
                    }
                }
                
                if (!$configData['premium']) {
                    $googleAds = [
                        'networkId'          => '221010893',
                        'mobileAdUnit'       => 'ptpwa',
                        'mobileAdSize'       => '[[320, 50], [300, 250], [300, 600]]',
                        'targetingArguments' => '',
                    ];
                }
                
                if (isset($configData['advertisement']['google']['network_id']) && $configData['premium']) {
                    $googleAds = [
                        'networkId'          => $configData['advertisement']['google']['network_id'],
                        // 'DFTNetworkId'          => '',
                        'mobileAdUnit'       => $configData['advertisement']['google']['ad_unit_mobile'],
                        'mobileAdSize'       => $configData['advertisement']['google']['ad_size_mobile'],
                        'targetingArguments' => $argumentTargeting,
                    ];
                }
            }
            
            /**
             * Custom scripts array build.
             */
            $customScripts = '';
            if (isset($configData['advertisement']['external_scripts'])) {
                $customScripts = explode(',', $configData['advertisement']['external_scripts']);
            }
            
            $returnTheme = [
                'renderAds'             => (isset($configData['advertisement']['active']) || !$configData['premium']),
                'adProvider'            => $advertiser,
                'customScripts'         => isset($configData['advertisement']['active']) && !empty($customScripts[0]) ? $customScripts : [],
                'renderAdsServerSide'   => true,
                'socialMargin'          => isset($configData['advertisement']['margin']) ? $configData['advertisement']['margin'] . 'px' : '0px',
                'adUnitSectionExtended' => false,
                'firstImpressionsId'    => NULL,
                'listAdInterval'        => $configData['advertisement']['ad_list_interval'] ? (int)$configData['advertisement']['ad_list_interval'] : 0,
                'listAdMax'             => 0,//Legacy
                'adUnit'                => '',
                'adUnitConfig'          => [],
                'ampAdUnitConfig'       => [
                    'articleAds' => [
                        'type' => isset($configData['advertisement']['amp']['active']) || !$configData['premium'] ? $configData['advertisement']['amp']['ad_type'] : '',
                        'slot' => isset($configData['advertisement']['amp']['active']) || !$configData['premium'] ? $configData['advertisement']['amp']['ad_slot_id'] : '',
                    ],
                ],
            ];
            
            return array_merge($returnTheme, $googleAds);
        }
        
        /**
         * Build analytics options.
         *
         * @param array $configData
         *
         * @return array
         *
         * @since 2.0.0
         */
        public function setApplicationAnalytics($configData) {
            return [
                'GATrackingCode' => !empty($configData['analytics']['ga_id']) ? $configData['analytics']['ga_id'] : NULL,
                'GTMID'          => !empty($configData['analytics']['gtm_id']) ? $configData['analytics']['gtm_id'] : NULL,
            ];
        }
        
        /**
         * Build settings options.
         *
         * @param array $configData
         *
         * @return array
         *
         * @since 2.0.0
         */
        public function setApplicationSettings($configData) {
            /**
             * Setup the DNS prefetch array.
             */
            $dnsPrefetch = [];
            if (!empty($configData['performance']['dns_prefetch'])) {
                $dnsPrefetch = explode(',', str_replace('"', '', $configData['performance']['dns_prefetch']));
            }
            
            $themData = [
                'sectionDownloadEnabled'          => isset($configData['content']['category']['download']) ? true : false,
                'showDateBlockOnFeedListItem'     => isset($configData['content']['options']['date_post']) ? true : false,
                'showDatesOnList'                 => isset($configData['content']['options']['date_thumb']) ? true : false,
                'showAuthor'                      => isset($configData['content']['options']['author_post']) ? true : false,
                'showSearch'                      => isset($configData['content']['options']['search_box']) ? true : false,
                'showAllFeed'                     => isset($configData['content']['options']['latest_home']) ? true : false,
                'topHeros'                        => (int)$configData['content']['post']['featured'],
                'defaultFeedPageSize'             => (int)$configData['content']['post']['count'],
                'infiniteVerticalArticleScroll'   => isset($configData['content']['scroll']['vertical']) ? true : false,
                'infiniteHorizontalArticleScroll' => isset($configData['content']['scroll']['horizontal']) ? true : false,
                'showClassicSwitch'               => isset($configData['advanced']['classic_switch']) ? true : false,
                'amp'                             => isset($configData['advanced']['amp']) ? '?pwa-amp' : false,
                'dnsPrefetch'                     => !empty($dnsPrefetch[0]) ? $dnsPrefetch : [],
                'newsItemDateFormat'              => 'Do MMM YYYY',
                'newsItemTimeFormat'              => 'LT',
                'searchParam'                     => !empty($configData['advanced']['search_parameter']) ? $configData['advanced']['search_parameter'] : 's',
                'searchAction'                    => !empty($configData['advanced']['search_action']) ? $configData['advanced']['search_action'] : '/',
                'maxWidth'                        => 1024,
                'customStyles'                    => '',
                'adjustYoutubeEmbed'              => true,
                'customHtml'                      => '',
                'moreBlockTags'                   => [],
                'routes'                          => (new PublishersToolboxPwaRouteMap($this->pluginName, $this->pluginVersion))->mapRoutes(),
                'pages'                           => $this->setApplicationPages($configData),
                'extraLinks'                      => $this->setApplicationLinks($configData),
            ];
            
            return array_merge($themData, $this->setApplicationSections($configData));
        }
        
        /**
         * Build Application Sections.
         *
         * @param array $configData
         *
         * @return array
         *
         * @since 2.0.0
         */
        public function setApplicationSections($configData) {
            /**
             * Setup active categories.
             */
            $activeCategories = [];
            $categoriesActive = explode(',', $configData['categories']);
            foreach ($categoriesActive as $category) {
                $activeCategories[] = html_entity_decode(get_cat_name($category));
            }
            
            return [
                'sectionPrefix'       => $configData['content']['category']['prefix'],
                'multiSection'        => isset($configData['content']['category']['child']) ? true : false,
                'flattenSections'     => false,
                'whitelistedSections' => !empty($activeCategories[0]) ? $activeCategories : [],
            ];
        }
        
        /**
         * Build Application Pages.
         *
         * @param array $configData
         *
         * @return array
         *
         * @since 2.0.0
         */
        public function setApplicationPages($configData) {
            /**
             * Active Pages.
             */
            $activePages = [];
            $pagesActive = explode(',', $configData['pages']);
            foreach ($pagesActive as $pages) {
                if (get_the_title($pages) === 'Home' || get_post_field('post_name', $pages) === 'home') {
                    continue;
                }
                $activePages[] = [
                    'label' => html_entity_decode(get_the_title($pages)),
                    'link'  => '/' . get_post_field('post_name', $pages) . '?noapp=true',
                ];
            }
            
            return !empty($activePages[0]['label']) ? $activePages : [];
        }
        
        /**
         * Build application links.
         *
         * @param array $configData
         *
         * @return array
         *
         * @since 2.0.0
         */
        public function setApplicationLinks($configData) {
            $extraLinks = [];
            if (!empty($configData['links']['active'])) {
                $linksDecoded = json_decode($configData['links']['active'], OBJECT);
                if ($linksDecoded) {
                    foreach ($linksDecoded as $index => $link) {
                        $extraLinks[$index] = [
                            'label' => $link['label'],
                            'link'  => strpos($link['link'], 'noapp') !== false ? $link['link'] : $link['link'] . '?noapp=true',
                        ];
                    }
                    
                    return $extraLinks;
                }
            }
            
            return [];
        }
        
        /**
         * Build application additional content.
         *
         * @param array $configData
         *
         * @return array
         *
         * @since 2.2.8
         */
        public function setApplicationContent($configData) {
            if (isset($configData['gdpr']['active']) && $configData['gdpr']['active']) {
                $gdprMessage = '<p>We use cookies to personalise content and ads, to provide social media features, and to analyse our traffic. Please confirm if you accept our tracking cookies.</p>';
                
                return [
                    'gdprActive'     => isset($configData['gdpr']['active']) ? true : false,
                    'gdprButtonText' => isset($configData['gdpr']['button']) ? $configData['gdpr']['button'] : 'Accept',
                    'gdprText'       => isset($configData['gdpr']['gdpr_text']) ? $configData['gdpr']['gdpr_text'] : $gdprMessage,
                ];
            }
            
            return [];
        }
        
        /**
         * Create default json theme file.
         *
         * @param array $configData Data to use for creating the file.
         * @param bool $createOnSave Create new file on save.
         *
         * @return bool
         *
         * @since 2.0.0
         */
        public function createManifestFile($configData = [], $createOnSave = false) {
            /**
             * This will always be the current site file position.
             */
            $currentSite = $this->directoryPath . get_current_blog_id() . '/' . self::MANIFEST_FILE_NAME;
            
            /**
             * Rebuild legacy manifest file to new file.
             */
            if (empty($configData)) {
                $pluginGlobal = new PublishersToolboxPwaGlobal($this->pluginName, $this->pluginVersion);
                $themeOptions = $pluginGlobal->getPluginOptions();
                
                if (!empty($themeOptions)) {
                    $configData = $this->buildManifestArray($themeOptions);
                }
            }
            
            /**
             * Do multi site check.
             */
            if (is_multisite()) {
                if (!$createOnSave) {
                    foreach (get_sites() as $sites) {
                        if (!file_exists($currentSiteLoop = $this->directoryPath . $sites->blog_id . '/' . self::MANIFEST_FILE_NAME)) {
                            if ($multiManifestFile = fopen($currentSiteLoop, 'wb')) {
                                fwrite($multiManifestFile, json_encode($configData));
                                fclose($multiManifestFile);
                            }
                        }
                    }
                } else {
                    if (!file_exists($currentSite)) {
                        $this->createFilePaths();
                    }
                    
                    if ($multiManifestFile = fopen($currentSite, 'wb')) {
                        fwrite($multiManifestFile, json_encode($configData));
                        fclose($multiManifestFile);
                    }
                }
            } else {
                if (!file_exists($currentSite)) {
                    $this->createFilePaths();
                }
                
                $singleManifestFile = fopen($currentSite, 'wb');
                if ($singleManifestFile) {
                    fwrite($singleManifestFile, json_encode($configData));
                    fclose($singleManifestFile);
                }
            }
            
            /**
             * Return true or false if the file exists.
             */
            if (file_exists($currentSite)) {
                return true;
            }
            
            return false;
        }
        
        /**
         * Build the array for options.
         *
         * @param array $configData
         *
         * @return array
         *
         * @since 2.0.0
         */
        public function buildManifestArray($configData = []) {
            /**
             * Default values
             */
            $iconsDefault = [
                '72'  => plugin_dir_url(__DIR__) . 'admin/assets/img/icons/icon-72x72.png',
                '96'  => plugin_dir_url(__DIR__) . 'admin/assets/img/icons/icon-96x96.png',
                '128' => plugin_dir_url(__DIR__) . 'admin/assets/img/icons/icon-128x128.png',
                '144' => plugin_dir_url(__DIR__) . 'admin/assets/img/icons/icon-144x144.png',
                '152' => plugin_dir_url(__DIR__) . 'admin/assets/img/icons/icon-152x152.png',
                '192' => plugin_dir_url(__DIR__) . 'admin/assets/img/icons/icon-192x192.png',
                '384' => plugin_dir_url(__DIR__) . 'admin/assets/img/icons/icon-384x384.png',
                '512' => plugin_dir_url(__DIR__) . 'admin/assets/img/icons/icon-512x512.png',
            ];
            
            /**
             * Create icons array.
             */
            $mimeType      = isset($configData['branding']) ? get_post_mime_type($configData['branding']['app_icon']) : 'image/png';
            $manifestIcons = [];
            foreach ($iconsDefault as $size => $icon) {
                $resizeIcon      = PublishersToolboxPwaImageResize::imageResize($configData['branding']['app_icon'], $size, $size, false, ['size' => 'pwa-icon-' . $size]);
                $manifestIcons[] = [
                    'src'   => $resizeIcon ?: $icon,
                    'sizes' => $size . 'x' . $size,
                    'type'  => $mimeType,
                ];
            }
            
            /**
             * Build the manifest.
             */
            return [
                '@type'            => 'PtPwaManifest',
                'name'             => htmlspecialchars($configData['settings']['app_name']),
                'short_name'       => htmlspecialchars($configData['settings']['app_name']),
                'description'      => htmlspecialchars($configData['settings']['app_description']),
                'icons'            => $manifestIcons,
                'theme_color'      => $configData['theme']['colour']['components'],
                'background_color' => $configData['theme']['colour']['background'] === 'transparent' ? $configData['theme']['colour']['accent'] : $configData['theme']['colour']['background'],
                'start_url'        => $configData['host_url'],
                'display'          => 'standalone',
                'orientation'      => 'portrait',
                'gcm_sender_id'    => '103953800507',
            ];
        }
        
        /**
         * Create default js service-worker file.
         *
         * @return bool
         *
         * @since 2.0.0
         */
        public function createServiceWorkerFile() {
            /**
             * Write the service worker data from the start.
             */
            $swData = 'importScripts("' . self::SW_ASSETS_FILE . '/production/static/' . self::SW_FILE_NAME . '");';
            
            /**
             * This will be the current site sw file location.
             */
            $pathToSw = ABSPATH . '/' . self::SW_FILE_NAME;
            
            /**
             * This will be the fallback for single SW files.
             */
            $fallbackPathToSw = $this->directoryPath . get_current_blog_id() . '/' . self::SW_FILE_NAME;
            
            /**
             * Do multi site check.
             */
            if (is_multisite()) {
                foreach (get_sites() as $sites) {
                    if (!file_exists($currentSiteLoop = $this->directoryPath . $sites->blog_id . '/' . self::SW_FILE_NAME)) {
                        if ($multiSwFile = fopen($currentSiteLoop, 'wb')) {
                            fwrite($multiSwFile, $swData);
                            fclose($multiSwFile);
                        }
                    }
                }
            } else {
                if (!file_exists($pathToSw)) {
                    $this->createFilePaths();
                }
                
                /**
                 * Create root SW file.
                 */
                $singleSwFile = fopen($pathToSw, 'wb');
                if ($singleSwFile) {
                    fwrite($singleSwFile, $swData);
                    fclose($singleSwFile);
                }
                
                /**
                 * Create a fallback SW file.
                 */
                $singleSwFileFallback = fopen($fallbackPathToSw, 'wb');
                if ($singleSwFileFallback) {
                    fwrite($singleSwFileFallback, $swData);
                    fclose($singleSwFileFallback);
                }
            }
            
            /**
             * Return true or false if the file exists.
             */
            return file_exists($pathToSw) || file_exists($fallbackPathToSw);
        }
        
        /**
         * Create default js service-worker file.
         *
         * @return bool
         *
         * @since 2.0.0
         */
        public function createMessageServiceWorkerFile() {
            /**
             * Write the service worker data from the start.
             */
            $swData = 'importScripts("' . self::SW_ASSETS_FILE . '/production/static/messaging-sw.js");';
            
            /**
             * This will be the current site sw file location.
             */
            $pathToSw = ABSPATH . '/' . self::MSW_FILE_NAME;
            
            /**
             * This will be the fallback for single SW files.
             */
            $fallbackPathToSw = $this->directoryPath . get_current_blog_id() . '/' . self::MSW_FILE_NAME;
            
            /**
             * Do multi site check.
             */
            if (is_multisite()) {
                foreach (get_sites() as $sites) {
                    if (!file_exists($currentSiteLoop = $this->directoryPath . $sites->blog_id . '/' . self::MSW_FILE_NAME)) {
                        if ($multiSwFile = fopen($currentSiteLoop, 'wb')) {
                            fwrite($multiSwFile, $swData);
                            fclose($multiSwFile);
                        }
                    }
                }
            } else {
                if (!file_exists($pathToSw)) {
                    $this->createFilePaths();
                }
                
                /**
                 * Create root SW file.
                 */
                $singleSwFile = fopen($pathToSw, 'wb');
                if ($singleSwFile) {
                    fwrite($singleSwFile, $swData);
                    fclose($singleSwFile);
                }
                
                /**
                 * Create a fallback SW file.
                 */
                $singleSwFileFallback = fopen($fallbackPathToSw, 'wb');
                if ($singleSwFileFallback) {
                    fwrite($singleSwFileFallback, $swData);
                    fclose($singleSwFileFallback);
                }
            }
            
            /**
             * Return true or false if the file exists.
             */
            return file_exists($pathToSw) || file_exists($fallbackPathToSw);
        }
        
        /**
         * Validate the theme file.
         *
         * @param $themeOptions
         * @param bool $encryptTheme
         * @param bool $json
         *
         * @return array|false|mixed|string|void
         *
         * @since 2.1.0
         */
        public function setupThemeFile($themeOptions, $encryptTheme = false, $json = true) {
            $getThemeKey = (new PublishersToolboxPwaRequests($this->pluginName, $this->pluginVersion))->request(self::PWA_VERIFY, '', '{"json":"' . str_replace('"', '\"', json_encode($themeOptions)) . '"}');
            
            if ($encryptTheme && is_array($getThemeKey) && array_key_exists('key', $getThemeKey)) {
                return json_encode(['signature' => $getThemeKey['key']]);
            }
            
            return $json ? json_encode($themeOptions) : $themeOptions;
        }
    }
