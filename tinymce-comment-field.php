<?php
/*
  Plugin Name: TinyMCE Comment Field - WYSIWYG
  Plugin URI: https://wordpress.org/plugins/tinymce-comment-field/
  Description: This plugin turns the comment field from a primitive into a WYSIWYG editor, using the internal TinyMCE library bundled with WordPress.
  Version: 0.8
  Author: Stefan Helmer
  Author URI: http://www.eracer.de
 */
! defined( 'ABSPATH' ) and exit;

define("TMCECF_PLUGIN", plugin_basename(__FILE__));
define("TMCECF_PLUGIN_DIR", plugin_dir_path(__FILE__));
define("TMCECF_PLUGIN_URL", plugin_dir_url(__FILE__));
define("TMCECF_PLUGIN_RELATIVE_DIR", dirname(plugin_basename(__FILE__)));
define("TMCECF_PLUGIN_FILE", __FILE__);

require_once(TMCECF_PLUGIN_DIR . "classes/class-buttons.php");
require_once(TMCECF_PLUGIN_DIR . "classes/class-tgm.php");
require_once(TMCECF_PLUGIN_DIR . "controller/class-plugin-controller.php");
require_once(TMCECF_PLUGIN_DIR . "controller/class-titan-controller.php");

require_once(TMCECF_PLUGIN_DIR . "controller/class-metabox-controller.php");
require_once(TMCECF_PLUGIN_DIR . "controller/class-editor-controller.php");
require_once(TMCECF_PLUGIN_DIR . "controller/class-dummy-controller.php");

new TMCECF_PluginController();