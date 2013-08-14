<?php
/* Plugin Name: WhateverWeb for Wordpress
Plugin URI: http://whateverweb.com/
Description: Integration to Whateverweb services
Version: 1.0
Author: Sergey Galchenko
Author URI: http://whateverweb.com/
License: GPLv2 or later
*/

    define('MT_WEW_PLUGIN_URL', plugin_dir_url( __FILE__ ));

    function mt_get_wew_image_scaler_prefix() {
        $wew_application_name = get_option("mt_wew_application");
        if($wew_application_name != "")
            return "http://img." . $wew_application_name . ".wew.io/";
        else
            return null;
    }

    function mt_get_wew_css_processor_prefix() {
        $wew_application_name = get_option("mt_wew_application");
        if($wew_application_name != "")
            return "http://css." . $wew_application_name . ".wew.io/";
        else
            return null;
    }

    function mt_is_already_wewified($url) {
        return substr_count($url, '://') > 1;
    }

    function mt_is_google_font_stylesheet($url) {
        return substr_count($url, 'fonts.google') > 0;
    }

    /*
     * Image Optimiser integration
     */

    function mt_add_wew_prefix_to_images($content) {
        $wew_image_prefix = mt_get_wew_image_scaler_prefix();
        if(isset($wew_image_prefix)) {
            $dom = new DOMDocument();
            @$dom->loadHTML(mb_convert_encoding($content, "HTML-ENTITIES", "UTF-8"));

            foreach ($dom->getElementsByTagName('img') as $node) {
                $oldsrc = $node->getAttribute('src');
                $newsrc = $oldsrc;
                if(!mt_is_already_wewified($oldsrc)) {
                    $newsrc = '' . mt_get_wew_image_scaler_prefix() . $oldsrc;
                }
                $node->setAttribute("src", $newsrc);
            }
            $newHtml = $dom->saveHtml();
            return $newHtml;
        }
        return $content;
    }

    add_filter('the_content', 'mt_add_wew_prefix_to_images');

    /*
     * CSS processor integration
     */

    function mt_add_wew_prefix_to_styles($tag){
        $wew_prefix = mt_get_wew_css_processor_prefix();
        if(isset($wew_prefix)) {
            $customXML = new SimpleXMLElement($tag);
            $href = (string)$customXML->attributes()->href;
            $rel = (string)$customXML->attributes()->rel;
            if(mt_is_google_font_stylesheet($href) || !isset($rel) || $rel != "stylesheet") {
                return $tag;
            }
            $customXML->attributes()->href = $wew_prefix . $customXML->attributes()->href;
            $dom = dom_import_simplexml($customXML);

            return $dom->ownerDocument->saveXML($dom->ownerDocument->documentElement);
        }
        return $tag;
    }

    add_filter('style_loader_tag', 'mt_add_wew_prefix_to_styles');

    /*
     * Options menu
     */

    add_action('admin_init', 'mt_wew_admin_init');
    add_action('admin_menu', 'mt_wew_plugin_settings');

    function mt_wew_admin_init() {
        wp_enqueue_style("mt-wew-style", MT_WEW_PLUGIN_URL . "css/mt_wew.css", false, "1.0", "all");
    }

    function mt_wew_plugin_settings() {
        add_menu_page('Whateverweb Settings', 'Whateverweb Settings', 'administrator', 'mt_wew_settings', 'mt_wew_display_settings');
    }

    function mt_wew_display_settings() {

        $wew_application_name = get_option('mt_wew_application');

        $html = '<div class="wrap mt-wew-wrap">
                <form method="post" name="options" action="options.php">
                <h2>Set up your Whateverweb integration</h2>' . wp_nonce_field('update-options') . '
                <table width="100%" cellpadding="10" class="form-table">
                    <tr>
                        <td align="left" scope="row">
                        <label>Whateverweb application name </label><input type="text" name="mt_wew_application" 
                        value="' . $wew_application_name . '" placeholder="e.g. AwesomeApplicationName"/>
                        </td> 
                    </tr>
                </table>
                <p class="submit">
                    <input type="hidden" name="action" value="update" />  
                    <input type="hidden" name="page_options" value="mt_wew_application" /> 
                    <input type="submit" name="Submit" value="Update" />
                </p>
                </form>
            </div>';
        echo $html;
    }
?>