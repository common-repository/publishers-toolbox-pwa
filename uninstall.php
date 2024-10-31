<?php

    /**
     * Fired when the plugin is uninstalled.
     *
     * When populating this file, consider the following flow
     * of control:
     *
     * - This method should be static
     * - Check if the $_REQUEST content actually is the plugin name
     * - Run an admin referrer check to make sure it goes through authentication
     * - Verify the output of $_GET makes sense
     * - Repeat with other user roles. Best directly by using the links/query string parameters.
     * - Repeat things for multisite. Once for a single site in the network, once sitewide.
     *
     * This file may be updated more in future version of the Boilerplate; however, this is the
     * general skeleton and outline for how the file should work.
     *
     * For more information, see the following discussion:
     * https://github.com/tommcfarlin/WordPress-Plugin-Boilerplate/pull/123#issuecomment-28541913
     *
     * @link       https://www.publisherstoolbox.com/websuite/
     * @since 2.0.0
     *
     * @package    PublishersToolboxPwa
     */

    // If uninstall not called from WordPress, then exit.
    if (!defined('WP_UNINSTALL_PLUGIN')) {
        exit;
    }

    /**
     * Delete the leftover options from WordPress.
     */
    if (is_multisite()) {
        foreach (get_sites() as $sites) {
            /**
             * Delete all options for multisite.
             */
            delete_blog_option($sites->blog_id, PUBLISHERS_TOOLBOX_PLUGIN_NAME);

            /**
             * Delete all Notices for multisite.
             */
            delete_blog_option($sites->blog_id, PUBLISHERS_TOOLBOX_PLUGIN_NAME . '-notices');

            /**
             * Delete changed status for multisite.
             */
            delete_blog_option($sites->blog_id, PUBLISHERS_TOOLBOX_PLUGIN_NAME . '-changed');

            /**
             * Delete subscription options for multisite.
             */
            delete_blog_option($sites->blog_id, PUBLISHERS_TOOLBOX_PLUGIN_NAME . '-subscription');
        }
    } else {
        /**
         * Delete all options.
         */
        delete_option(PUBLISHERS_TOOLBOX_PLUGIN_NAME);

        /**
         * Delete all Notices.
         */
        delete_option(PUBLISHERS_TOOLBOX_PLUGIN_NAME . '-notices');

        /**
         * Delete changed status.
         */
        delete_option(PUBLISHERS_TOOLBOX_PLUGIN_NAME . '-changed');

        /**
         * Delete subscription options.
         */
        delete_option(PUBLISHERS_TOOLBOX_PLUGIN_NAME . '-subscription');
    }

    /**
     * Remove global SW file.
     */
    unlink(ABSPATH . '/service-worker.js');

    /**
     * Remove global messaging SW file.
     */
    unlink(ABSPATH . '/firebase-messaging-sw.js');
