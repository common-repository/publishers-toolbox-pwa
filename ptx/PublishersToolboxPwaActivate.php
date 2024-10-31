<?php
    
    namespace PT\ptx;
    
    if (!defined('ABSPATH')) {
        exit();
    }
    
    /**
     * Fired during plugin activation.
     *
     * This class defines all code necessary to run during the plugin's activation.
     *
     * @link       https://www.publisherstoolbox.com/websuite/
     *
     * @package    PublishersToolboxPwa
     * @subpackage PublishersToolboxPwa/ptx
     * @author     Publishers Toolbox <support@afrozaar.com>
     *
     * @since 2.0.0
     */
    class PublishersToolboxPwaActivate {
        /**
         * Activation function on activate.
         *
         * Runs teh activate function when the plugin activates.
         *
         * @since 2.0.0
         */
        public static function activate() {
            (new PublishersToolboxPwaFileHandling(PUBLISHERS_TOOLBOX_PLUGIN_NAME, PUBLISHERS_TOOLBOX_PLUGIN_VERSION))->registerAdmin();
            delete_option(PUBLISHERS_TOOLBOX_PLUGIN_NAME . '-subscription');
            flush_rewrite_rules(false);
        }
    }
