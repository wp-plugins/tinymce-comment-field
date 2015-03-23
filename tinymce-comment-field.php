<?php
/*
  Plugin Name: TinyMCE Comment Field - WYSIWYG
  Plugin URI: http://wordpress.org
  Description: This plugin turns the comment field from a primitive into a WYSIWYG editor, using the internal TinyMCE library bundled with WordPress.
  Version: 0.5.1
  Author: Stefan Helmer
  Author URI: http://www.eracer.de
 */

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

new TMCECF_PluginController();