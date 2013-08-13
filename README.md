# Wordpress plugin for Whateverweb
---------
Whateverweb services provides a various amount of optimisations for the web applications, like Image optimisations (reducing the size according to the device, you're looking site at, etc.), stylesheets optimisations (removing the styles, that would not work on the current device, like removing mobile-specific CSS rules from desktop site stylesheet) and device detection API to check the capabilities for specific device, based on huge WURFL database.

This plugin provides a basic integration to the Image Optimisation and CSS optimisation services. All you need is to install the plugin and provide the Whateverweb application name (set up in Wordpress settings).

## Installation

1. Upload `whateverweb` folder to the `/wp-content/plugins/` directory of your Wordpress site
2. Activate the plugin through the 'Plugins' menu in WordPress admin panel
3. If you don't have an application in Whateverweb, create one at http://whateverweb.com (registration required)
4. Copy application name from the Whateverweb Dashboard and put it into 'Whateverweb Settings' page in Wordpress admin

## Changelog

### 1.0
* Basic integration to Image and CSS optimisation services

## Frequently Asked Questions

### What if I didn't specify the Whateverweb application name in settings?

In that case, no optimisations are provided.

### I've put a correct application name into settings, and my images broke. What should I do?

Double-check the application name in the Whateverweb Dashboard (on the project settings page) and check that your application is enabled (can be done through the Whateverweb Dashboard). If the problem persists, contact Whateverweb support at team@whateverweb.com