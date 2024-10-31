<?php
    
    namespace PT\ptx;
    
    if (!defined('ABSPATH')) {
        exit();
    }
    
    /**
     * Build the routes for the application.
     *
     * Used for building the routes which the application will use to navigate.
     *
     * @package    PublishersToolboxPwa
     * @subpackage PublishersToolboxPwa/ptx
     * @author     Publishers Toolbox <support@afrozaar.com>
     *
     * @since 2.0.0
     */
    class PublishersToolboxPwaRouteMap {
        
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
         * PublishersToolboxPwaRouteMap constructor.
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
         * Translate permalink structure from WP structure to PWA structure.
         *
         * @return string $articlePattern
         *
         * @since 2.0.0
         */
        public static function translatePermalinkStructure() {
            $articlePattern = '/';
            $permalinkStructure = get_option('permalink_structure');
            
            if (!$permalinkStructure) {
                $articlePattern = '/?p=:id';
            } else {
                $parameterKeys = explode('/', $permalinkStructure);
                
                foreach ($parameterKeys as $param) {
                    switch ($param) {
                        case '%category%':
                            $articlePattern .= ':sectionSlug/';
                            break;
                        case '%postname%':
                            $articlePattern .= ':title/';
                            break;
                        case '%postname%-%bcf_baobab_content_id%':
                            $articlePattern .= ':articleSlug/';
                            break;
                        case '%post_id%':
                            $articlePattern .= ':id/';
                            break;
                        case '%author%':
                            $articlePattern .= ':author/';
                            break;
                        case '%year%':
                            $articlePattern .= ':year/';
                            break;
                        case '%monthnum%':
                            $articlePattern .= ':month/';
                            break;
                        case '%day%':
                            $articlePattern .= ':day/';
                            break;
                        case '%tag%':
                            $articlePattern .= ':tag/';
                            break;
                        default:
                            break;
                    }
                }
            }
            
            return $articlePattern;
        }
        
        /**
         * Identifies if permalink has a trailing slash at the end.
         *
         * @return bool $includeTrailingSlashes
         *
         * @since 2.0.0
         */
        public static function includeTrailingSlashes() {
            $permalinkStructure = get_option('permalink_structure');
            return substr($permalinkStructure, -1) === '/';
        }
        
        /**
         * Creates an array of routes mapped to PWA structure.
         *
         * @return array $routes
         *
         * @since 2.0.0
         */
        public function mapRoutes() {
            $pluginGlobal = new PublishersToolboxPwaGlobal($this->pluginName, $this->pluginVersion);
            $themeOptions = $pluginGlobal->getPluginOptions();

            $categoryPrefix = !empty($themeOptions['content']['category']['prefix']) ? $themeOptions['content']['category']['prefix'] : get_option('category_base');
            $articlePattern = self::translatePermalinkStructure();
            $includeTrailingSlashes = self::includeTrailingSlashes();
            $trailingSlash = $includeTrailingSlashes ? '/' : '';
            
            if (!empty($categoryPrefix)) {
                $categoryPrefix = '/' . $categoryPrefix;
            }
            
            $routes = [
                [
                    'name'    => 'home',
                    'pattern' => '/',
                    'page'    => 'index',
                ],
                [
                    'name'    => 'taxonomy',
                    'pattern' => '/pt_wp_taxonomy/:dataUrl/:taxonomy/:id',
                    'page'    => 'index',
                ],
                [
                    'name'    => 'category',
                    'pattern' => '/pt_wp_section/:sectionId',
                    'page'    => 'index',
                ],
                [
                    'name'    => 'post',
                    'pattern' => '/pt_wp_post/:articleId',
                    'page'    => 'article',
                ],
                [
                    'name'    => 'post-amp',
                    'pattern' => '/pt_wp_post_amp/:articleId',
                    'page'    => 'article-amp',
                ],
                [
                    'name'    => 'list',
                    'pattern' => $categoryPrefix . '/:sectionSlug' . $trailingSlash,
                    'page'    => 'index',
                ],
            ];
            
            if ($articlePattern !== '/:sectionSlug/:articleSlug/') {
                $routes[] = [
                    'name'    => 'list 2',
                    'pattern' => $categoryPrefix . '/:sectionSlug/:secondSectionSlug' . $trailingSlash,
                    'page'    => 'index',
                ];
            }
            
            if (!empty($articlePattern) && !$includeTrailingSlashes) {
                $articlePattern = rtrim($articlePattern, '/');
            }
            
            array_push($routes, [
                'name'    => 'article',
                'pattern' => ':articleSlug' . $trailingSlash,
                'page'    => 'article',
            ], [
                'name'    => NULL,
                'pattern' => $articlePattern,
                'page'    => 'article',
            ]);
            
            return $routes;
        }
    }
