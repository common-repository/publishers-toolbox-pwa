<?php
    
    namespace PT\ptx;
    
    if (!defined('ABSPATH')) {
        exit();
    }
    
    /**
     * Define the internationalization functionality.
     *
     * Loads and defines the internationalization files for this plugin so that it is ready for translation.
     *
     * @package    PublishersToolboxPwa
     * @subpackage PublishersToolboxPwa/ptx
     * @author     Publishers Toolbox <support@afrozaar.com>
     *
     * @since 2.0.0
     */
    class PublishersToolboxPwaI18n {
        
        /**
         * The ID of this plugin.
         *
         * @access public
         * @var string $pluginName The ID of this plugin.
         *
         * @since 2.0.0
         */
        public $pluginName;
        
        /**
         * PublishersToolboxPwaI18n constructor.
         *
         * @param string $pluginName
         *
         * @since 2.0.0
         */
        public function __construct($pluginName) {
            $this->pluginName = $pluginName;
        }
        
        /**
         * Load the plugin text domain for translation.
         *
         * @param string $pluginName The name of this plugin.
         *
         * @since 2.0.0
         */
        public function loadPluginTextDomain($pluginName) {
            $this->pluginName = $pluginName;
            load_plugin_textdomain($this->pluginName, false, dirname(dirname(plugin_basename(__FILE__))) . '/languages/');
        }
    }
