=== WebSuite PWA ===
Contributors: publisherstoolbox, johan101
Tags: pwa, progressive web app, amp, mobile web application, wordpress pwa, push notifications
Requires at least: 5.0
Tested up to: 5.8.1
Requires PHP: 7.1
Stable tag: trunk
Version: 2.3.8
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html

WebSuite PWA transform your website(s) into progressive web applications, with integrated AMP support.

== Description ==

WebSuite PWA transform your website(s) into progressive web applications, with integrated AMP support.

It comes with multiple App themes and display configurations to choose from and supported on all smart phones and tablets.

Compatible browsers: Safari, Google Chrome, Android Native Browser.

== WebSuite Standard PWA ==

= App themes. = You can offer your users an exceptional reading experience by giving them a mobile web application with a native app-like look & feel. The default theme comes with six abstract covers that are randomly displayed on the loading screen to give the app a magazine flavour.

= Customize appearance. = Once a favourite theme has been selected, you can customize the colours and fonts, add your logo and graphic elements that can relate to your website’s identity.

= Preview display settings. = Change UI values in the plugin dashboard and instantly see the changes reflected in the preview window. Safely experiment with the look and feel from the safety of the plugin dashboard. Once all display settings are saved, they are instantly published to the PWA.

= Posts sync. = The articles/posts inside the mobile web application are organised into their corresponding categories, thus readers can simply swipe through articles and jump from category to category in a seamless way.

= Pages Sync. = Choose what pages you want to display on your mobile web application. You can edit, show/hide different pages and order them according to your needs.

= Analytics. = WebSuite PWA easily integrates with Google Analytics.

= Add to home screen. = Readers can add the mobile web application to their home screen and run it in full-screen mode.

== WebSuite Premium PWA ==

= Monetization options. =
Support for Google Ad Manager built in.

= Push notifications. =
Allows website owners to increase engagement by sending web alerts about new content, even when the browser is closed.

= Support. =
Technical support within two working days.

== WebSuite Enterprise PWA ==

= Scalability. =
Personalised CDN (content distribution network) configuration for high-traffic websites.

= Support. =

Technical support within one working day.

Please contact us for more information on our Enterprise solution: [Publishers Toolbox](https://www.publisherstoolbox.com/websuite/contact-us/ "Publishers Toolbox")

We enjoy writing and maintaining this plugin. If you like it too, please rate us. But if you don’t, let us know how we can improve it. Have fun on your mobile adventures.

== 3rd party services and applications ==

This plugin uses the WebSuite PWA Script to fetch the required HTML to display your website’s PWA. The content is loaded via the WordPress Rest API, no user data is exposed or transmitted at this point. The service simply fetches the required markup for your content to populate. No account is required. More about our PWA solution can be found here: https://www.publisherstoolbox.com/websuite/

== Installation ==

= Simple installation for WordPress v4.6 and later =

1. Go to the Plugins, Add new.
1. Search WordPress for WebSuite PWA then press Install now.
1. Activate plugin.
1. Enable PWA and Set plugin settings.
1. Enjoy.

= Comprehensive setup =

A more comprehensive setup process and guide to configuration is as follows.

1. Locate your WordPress install on the file system
1. Extract the contents of `publishers-toolbox-pwa.zip` into `wp-content/plugins`
1. In `wp-content/plugins` you should now see a directory named `publishers-toolbox-pwa`
1. Login to the WordPress admin panel at `http://yoursite.com/wp-admin`
1. Go to the Plugins menu.
1. Click Activate for the plugin.
1. Go to the PWA settings page.
1. You are all done!

== Frequently Asked Questions ==

= I have enabled WebSuite PWA, but I still see the desktop theme on my smartphone =
If you are using a caching plugin, please ensure that it is disabled or configured correctly. Some additional settings on the cache plugin might be required to correctly enable the mobile detection from WebSuite PWA.

= What devices and operating systems are supported by my mobile web application?
WebSuite PWA is supported on iOS and Android smart phones and tablets. Compatible browsers: Safari, Google Chrome, Android - Native Browser.

= How can my readers switch back to the desktop theme from my mobile web application? =
The side menu of the mobile web application contains a Switch to website button that will take readers back to the desktop theme. Their option will be remembered the next time they visit your blog.

= How can my readers switch back to the mobile web application from the desktop theme? =
A link called Switch to mobile, will be displayed in the footer of your desktop theme, only for readers that are viewing the site from a supported device and browser. Their option will be remembered the next time they visit your blog.

= I want to temporarily deactivate my PWA application. What steps must I follow? =
The PWA application can be deactivated from the Settings page on the admin panel. This option will not delete any settings that you have done so far, like customizing the look & feel of your application, but mobile readers will no longer be able to see it on their devices.

= What is the difference between my PWA application and a responsive theme? =
A responsive theme is all about screen-size: it loads the same styling as the desktop view, adjusting it to fit a smaller screen. On the other hand a PWA application combines the versatility of the web with the functionality of touch-enabled devices and can support native app-like features such as:

1. Apps load nearly instantly and is reliable, no matter what kind of network connection your user is on.
1. PWA install banners and gives users the ability to quickly and seamlessly add your mobile app to their home screen, making it easy to launch and return to your app.
1. Web push notifications makes it easy to re-engage with users by showing relevant, timely, and contextual notifications, even when the browser is closed.
1. Smooth animations, scrolling, and navigation keeps the experience silky Smooth.
1. Secured via HTTPS.

== Changelog ==

= 2.3.8 =
* Performance upgrades.
* Homepage loading upgrades.
* Readme updates.
* Removed all legacy code.

= 2.3.7 =
* Update amp html link.

= 2.3.6 =
* Remove amp home html link.
* Remove amp category html link.

= 2.3.5 =
* Fixed AMPHTML tag link on archive pages.
* Added rest API link for PWA configs.

= 2.3.4 =
* Updated tags redirection.
* Updated response code detection.
* Updated banner asset.
* Fixed homepage amphtml tag.
* Tested with latest WordPress version.

= 2.3.3 =
* Fixed Push notification messages.
* Added search fields customisation to plugin admin.
* General code cleanup and bug fixes.

= 2.3.2 =
* Added homepage check for ?noapp.

= 2.3.1 =
* Added interim ?noapp to custom post types.
* Made fields more descriptive.
* Added additional descriptions to fields.
* Cleaned up legacy fields.
* Minor code improvements and checks.

= 2.3.0 =
* Improved verification checks for multi sites.
* Improved error handling.
* Improved multi site options handling.
* General code improvements.

= 2.2.9 =
* Fixed script conflict.

= 2.2.8 =
* Improved Pages selection and ordering.
* Improved Category selection and ordering.
* Added GDPR fields for EU users.
* General code improvements.
* Tested WordPress version 5.4
* Upgrade version to 2.2.8

= 2.2.7 =
* Added post ad list option.
* Added post author display.
* Fixed Theme swap.

= 2.2.6 =
* Fixed version.
* Added host CDN overwrite field for caching plugins images.
* Fixed OG Meta tag script inclusion in Facebook share.

= 2.2.5 =
* Added host CDN overwrite for caching plugins.
* Fixed OG Meta tag issue.

= 2.2.4 =
* Fixed AMP trailing slash.

= 2.2.3 =
* Fixed Ad slots.

= 2.2.2 =
* Fixed multisite header origin.
* Added AMP support.
* Fixed legacy issues.
* Minor bug fixes on info messages.

= 2.2.1 =
* CloudFront mobile detect update.

= 2.2.0 =
* Fixed html characters in menu.
* Fixed fallback script.

= 2.1.9 =
* Fixed push notifications excerpt escape characters.
* Updated fallback script.
* Updated App routes.

= 2.1.8 =
* Fixed legacy manifest generator.

= 2.1.7 =
* Implemented better version checking.

= 2.1.6 =
* Fixed plugin update file reset.
* Fixed minor display bugs.
* Removed debug log in live mode.
* Minor bug fixes and improvements.

= 2.1.5 =
* Fixed push notifications.

= 2.1.4 =
* Switched preview to live mode.

= 2.1.3 =
* Added instant preview for display settings.
* Added premium licence detection.
* Added push notification sending for premium plugin owners.
* Simplified display settings for light and dark themed apps.
* Bug fixes for sortable fields.
* Bug fixes for input fields.
* Performance updates.

= 2.1.2 =
* Fixed URL issue on ajax calls.

= 2.1.1 =
* Assets update.

= 2.1.0 =
* Wordpress version update.

= 2.0.9 =
* Update CDN endpoint field.

= 2.0.8 =
* Load fallback script in header.

= 2.0.7 =
* Bug fixes.

= 2.0.6 =
* Fix admin link.

= 2.0.5 =
* Add jquery fallback script for mobile detection and bypass weird caching issues on some servers.
* Updated Application Endpoint overwrite to use CDN if needed.
* Bug fixes.

= 2.0.4 =
* Rewrite router for better loading on legacy and standardization.

= 2.0.4 =
* Rewrite router for better loading on legacy and standardization.

= 2.0.3 =
* Legacy loader routing fix.

= 2.0.2 =
* Legacy CDN loader added for old options.
* Legacy check updated.
* Minor bug fixes.

= 2.0.1 =
* Bug fixes.

= 2.0.0 =
* Upgraded UI/UX.
* Upgraded codebase to PHP 7.1 compatibility.
* Upgraded multisite loading.
* Improved memory handling.
* Security patches for libraries.
* Updated documentation.
* Fixed general bugs and layout issues on admin area.
* Added ajax loading and fallback options.

== Upgrade Notice ==

= 2.3.8 =
* Performance upgrades.
