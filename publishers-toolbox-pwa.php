<?php
    
    /**
     * Publishers Toolbox PWA Plugin
     *
     * This file is read by WordPress to generate the plugin information in the plugin
     * admin area. This file also includes all of the dependencies used by the plugin,
     * registers the activation and deactivation functions, and defines a function
     * that starts the plugin.
     *
     * @link              https://www.publisherstoolbox.com/websuite/
     * @since             2.0.0
     * @package           PublishersToolboxPwa
     *
     * @wordpress-plugin
     * Plugin Name:       WebSuite PWA
     * Plugin URI:        https://wordpress.org/plugins/publishers-toolbox-pwa/
     * Description:       WebSuite PWA is a fully customisable plugin that transform your site into a progressive web application.
     *
     * Version:           2.3.8
     * Author:            Publishers Toolbox
     * Author URI:        https://www.publisherstoolbox.com/websuite/
     * License:           GPL-2.0+
     * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt!
     * Text Domain:       publishers-toolbox-pwa
     * Domain Path:       /languages
     */
    
    use PT\ptx\PublishersToolboxPwa;
    use PT\ptx\PublishersToolboxPwaActivate;
    use PT\ptx\PublishersToolboxPwaDeactivate;
    
    if (!defined('WPINC')) {
        die;
    }
    
    /**
     * Setup plugin version.
     */
    define('PUBLISHERS_TOOLBOX_PLUGIN_VERSION', '2.3.8');
    
    /**
     * Setup the plugin name.
     */
    define('PUBLISHERS_TOOLBOX_PLUGIN_NAME', 'publishers-toolbox-pwa');
    
    /**
     * The current mode. (debug/live)
     */
    define('PW_PWA_MODE', (isset($_GET['debug']) && $_GET['debug'] === '1') ? 'debug' : 'live');
    
    /**
     * SPL auto loader for Publishers Toolbox
     *
     * This function loads all of the methods for the plugin automatically with unique namespace.
     *
     * @param $className
     */
    function ClassPwaAutoLoader(string $className) {
        /**
         * If the class being requested does not start with our prefix, we know it's not one in our project
         */
        if (0 !== strpos($className, 'PT')) {
            return;
        }
        
        $className = ltrim($className, '\\');
        $fileName  = '';
        if ($lastNsPos = strrpos($className, '\\')) {
            $namespace = plugin_dir_path(__FILE__) . '/' . substr($className, 0, $lastNsPos);
            $className = substr($className, $lastNsPos + 1);
            $fileName  = str_replace('\\', DIRECTORY_SEPARATOR, $namespace) . DIRECTORY_SEPARATOR;
            $fileName  = str_replace('//PT', '', $fileName);
        }
        $fileName .= str_replace('_', DIRECTORY_SEPARATOR, $className) . '.php';
        
        if (file_exists($fileName)) {
            require $fileName;
        }
    }
    
    try {
        spl_autoload_register('ClassPwaAutoLoader');
    } catch (Exception $e) {
        throw new RuntimeException('Could not register SPL ClassPwaAutoLoader');
    }
    
    /**
     * The code that runs during plugin activation.
     * This action is documented in ptx/PublishersToolboxPwaActivate.php
     *
     * @PublishersToolboxPwaActivate
     */
    function activatePublishersToolboxPwa() {
        PublishersToolboxPwaActivate::activate();
    }
    
    register_activation_hook(__FILE__, 'activatePublishersToolboxPwa');
    
    /**
     * The code that runs during plugin deactivation.
     * This action is documented in  documented in ptx/PublishersToolboxPwaDeactivate.php
     *
     * @PublishersToolboxPwaDeactivate
     */
    function deactivatePublishersToolboxPwa() {
        PublishersToolboxPwaDeactivate::deactivate();
    }
    
    register_deactivation_hook(__FILE__, 'deactivatePublishersToolboxPwa');
    
    /**
     * Begins execution of the plugin.
     *
     * @since 2.0.0
     */
    function runPublishersToolboxPwa() {
        $plugin = new PublishersToolboxPwa();
        $plugin->run();
    }
    
    runPublishersToolboxPwa();
