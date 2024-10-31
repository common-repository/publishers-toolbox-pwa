<?php
    
    namespace PT\ptx;
    
    if (!defined('ABSPATH')) {
        exit();
    }
    
    /**
     * Fired during plugin deactivation
     *
     * This class defines all code necessary to run during the plugin's deactivation.
     *
     * @link       https://www.publisherstoolbox.com/websuite/
     *
     * @package    PublishersToolboxPwa
     * @subpackage PublishersToolboxPwa/ptx
     * @author     Publishers Toolbox <support@afrozaar.com>
     *
     * @since 2.0.0
     */
    class PublishersToolboxPwaDeactivate {
        /**
         * Run on plugin deactivation.
         *
         * @since 2.0.0
         */
        public static function deactivate() {
            delete_option(PUBLISHERS_TOOLBOX_PLUGIN_NAME . '-subscription');
            flush_rewrite_rules(false);
        }
    }
