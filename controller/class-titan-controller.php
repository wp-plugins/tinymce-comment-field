<?php

class TMCECF_TitanController {

    public function __construct() {
        add_action('tf_create_options', array(&$this, "create_options"));
        add_action("tf_admin_options_saved_tinymce-comment-field", array(&$this, "save_editor_content_css"));
    }

    public function create_options() {

        $titan = TitanFramework::getInstance('tinymce-comment-field');

        $panel = $titan->createAdminPanel(array(
            'name' => 'TinyMCE Comment Field',
            "icon" => "dashicons-edit"
        ));

        $general_tab = $panel->createTab(array(
            "name" => __("General", "tinymce-comment-field")
        ));

        $general_tab->createOption(array(
            'name' => 'Enabled',
            'id' => 'enabled',
            'type' => 'checkbox',
            'desc' => __("Enable or Disable TinyMCE Comment Field", "tinymce-comment-field"),
            'default' => true,
        ));

        $general_tab->createOption(array(
            'name' => 'Mobile Browser Support',
            'id' => 'mobile-browser-support',
            'type' => 'checkbox',
            'desc' => __("Enable or Disable TinyMCE Comment Field on Mobile Devices", "tinymce-comment-field"),
            'default' => true,
        ));

        $post_types = get_post_types(array("public" => true));
        $titan_post_types = array();

        foreach ($post_types as $post_type):
            $titan_post_type = get_post_type_object($post_type);
            $titan_post_types[$post_type] = $titan_post_type->labels->name;
        endforeach;

        $general_tab->createOption(array(
            'name' => __('Editor Font', 'tinymce-comment-field'),
            'id' => 'editor-font',
            'type' => 'font',
            'desc' => 'Enable or Disable TinyMCE Comment Field on certain Post Types ',
            'show_font_family' => true,
            "enqueue" => true,
            "show_text_shadow" => false,
            'default' => array('font-family' => "Georgia, serif")
        ));

        $general_tab->createOption(array(
            'name' => __("Background Color", "tinymce-comment-field"),
            'id' => 'background-color',
            'type' => 'color',
            'desc' => __('Pick a color', "tinymce-comment-field"),
            'default' => '#ffffff',
        ));

        $general_tab->createOption(array(
            'name' => _("Height"),
            'id' => 'height',
            'type' => 'number',
            'desc' => '',
            'default' => '200',
            'min' => '100',
            'max' => '1000',
            "unit" => "px"
        ));



        $general_tab->createOption(array(
            'name' => __('Post Types', 'tinymce-comment-field'),
            'id' => 'post-types',
            'type' => 'multicheck',
            'desc' => __('Enable or Disable TinyMCE Comment Field on certain Post Types', 'tinymce-comment-field'),
            'options' => $titan_post_types,
            'default' => array('post', 'page'),
        ));

        $general_tab->createOption(array(
            'name' => __('Text Direction', 'tinymce-comment-field'),
            'id' => 'text-direction',
            'options' => array(
                'ltr' => __('Left to Right', 'tinymce-comment-field'),
                'rtl' => __('Right to Left', 'tinymce-comment-field'),
            ),
            'type' => 'radio',
            'desc' => __('Set the Text Direction', 'tinymce-comment-field'),
            'default' => 'ltr',
        ));


        $general_tab->createOption(array(
            'name' => __("Text below Comment Field", "tinymce-comment-field"),
            'id' => "text-below-commentfield",
            'type' => 'editor',
            'desc' => __('Put your text or html here', "tinymce-comment-field"),
        ));


        $general_tab->createOption(array(
            'type' => 'save',
        ));

        $buttons_tab = $panel->createTab(array(
            "name" => "Buttons"
        ));

        $buttons_tab->createOption(array(
            'name' => __('Buttons', 'tinymce-comment-field'),
            'id' => 'buttons',
            'type' => 'multicheck',
            'desc' => __("Enable or Disable Buttons on the Toolbar", 'tinymce-comment-field'),
            'options' => TMCECF_Buttons::getTeeny(),
            'default' => array('bold', 'italic', "underline", "strikethrough", "cut", "copy", "paste", "blockquote", "link", "unlink"),
        ));

        $buttons_tab->createOption(array(
            'type' => 'save',
        ));

        $shortcode_tab = $panel->createTab(array("name" => "Shortcodes"));

        global $shortcode_tags;
        $options_allowed_shortcodes = array();
        foreach ($shortcode_tags as $shortcode_tag => $value):
            $options_allowed_shortcodes[$shortcode_tag] = $shortcode_tag;
        endforeach;

        $shortcode_tab->createOption(array(
            'name' => __('Allowed Shortcodes', 'tinymce-comment-field'),
            'id' => 'allowed-shortcodes',
            'type' => 'multicheck',
            'desc' => __("Enable or Disable Shortcodes for the Comment Field", 'tinymce-comment-field'),
            'options' => $options_allowed_shortcodes,
            'default' => array('caption', 'wp_caption'),
        ));

        $shortcode_tab->createOption(array(
            'type' => 'save',
        ));
    }

    public static function save_editor_content_css() {

        $css_url_dynamic = site_url() . "/?mcec_action=comment_editor_content_css";

        try {
            $wp_upload_dir = wp_upload_dir();
            $css_filename = "tinymce-comment-field-editor.css";
            $css_base_path = $wp_upload_dir["basedir"];
            $css_base_url = $wp_upload_dir["baseurl"];
            $css_url = $css_base_url . "/" . $css_filename;
            $css_path = $css_base_path . "/" . $css_filename;
            $css_content = file_get_contents($css_url_dynamic);
            $result = file_put_contents($css_path, $css_content);

            if ($result === false):
                update_option("tinymce-comment-field_css-url", $css_url_dynamic);
            else:
                update_option("tinymce-comment-field_css-url", $css_url);
            endif;
            update_option("tinymce-comment-field_css-path", $css_path);
        } catch (Exception $ex) {
            update_option("tinymce-comment-field_css-url", $css_url_dynamic);
        }
    }

}
