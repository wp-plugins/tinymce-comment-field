<?php

class TMCECF_EditorController {

    public function __construct() {
        add_filter('comment_form_field_comment', array(&$this, 'comment_editor'));
        add_filter("teeny_mce_buttons", array(&$this, "comment_editor_buttons"));
        add_filter("comment_form_defaults", array(&$this, "comment_editor_form_options"));
        add_action('wp_enqueue_scripts', array(&$this, 'comment_editor_scripts'));
        add_filter("comment_reply_link", array(&$this, 'comment_reply_link_fix'));
        add_action("template_redirect", array(&$this, "comment_editor_content_css"));
        add_filter('comments_template', array(&$this, "shortcodes_whitelist"), 10);
        add_filter('dynamic_sidebar', array(&$this, "reset_shortcodes"));
        add_filter('widget_execphp', 'do_shortcode');
    }

    public function testo($x) {
        return $x;
    }

    public function comment_editor($default) {

        if (!self::display_mce_comment()):
            return $default;
        endif;

        $titan = TitanFramework::getInstance('tinymce-comment-field');
        $text_direction = $titan->getOption('text-direction');

        global $wp_styles;

        $content_css = get_option("tinymce-comment-field_css-url");
        $height = $titan->getOption('height');

        foreach ($wp_styles->registered as $wp_style):
            if ("tf-google-webfont" === substr($wp_style->handle, 0, 17)):
                $google_web_font_src = $wp_style->src;
                $google_web_font_url_object = parse_url($google_web_font_src);
                $google_web_font_query = array();
                parse_str($google_web_font_url_object["query"], $google_web_font_query);
                $google_web_font_url = "//" . $google_web_font_url_object["host"] . $google_web_font_url_object["path"] . "?" . http_build_query($google_web_font_query);
                $content_css .= ", {$google_web_font_url}";
            endif;
        endforeach;

        ob_start();
        wp_editor('', 'comment', array('textarea_rows' => 15, 'teeny' => true, 'quicktags' => false, 'media_buttons' => false, 'tinymce' => array('height' => $height, 'directionality' => $text_direction, 'content_css' => $content_css)));
        $comment_editor = ob_get_contents();
        ob_end_clean();
        $comment_editor = str_replace('post_id=0', 'post_id=' . get_the_ID(), $comment_editor);

        return $comment_editor;
    }

    public function comment_editor_buttons($default_buttons) {

        if (is_admin()):
            return $default_buttons;
        endif;

        if (!self::display_mce_comment()):
            return $default_buttons;
        endif;

        $titan = TitanFramework::getInstance('tinymce-comment-field');
        $buttons = $titan->getOption('buttons');
        return $buttons;
    }

    public function comment_editor_form_options($defaults) {

        if (!self::display_mce_comment()):
            return $defaults;
        endif;

        $titan = TitanFramework::getInstance('tinymce-comment-field');
        $comments_notes_after = $titan->getOption("text-below-commentfield");


        $defaults["comment_notes_after"] = $comments_notes_after;
        return $defaults;
    }

    public function comment_editor_scripts() {

        if (!self::display_mce_comment()):
            return;
        endif;

        wp_enqueue_script('jquery');
        wp_enqueue_script("tinymce-comment-field", TMCECF_PLUGIN_URL . "js/tinymce-comment-field.js", "jquery", "1.1", true);
        wp_enqueue_style("mce-comments-no-status-bar", TMCECF_PLUGIN_URL . "css/editor-no-statusbar.css");
    }

    public function comment_reply_link_fix($link) {

        if (!self::display_mce_comment()):
            return $link;
        endif;

        return str_replace('onclick=', 'data-onclick=', $link);
    }

    private static function display_mce_comment() {
        global $display_mce_comment;

        if (!isset($display_mce_comment)):

            $display_mce_comment = false;

            if (!class_exists('TitanFramework')):
                $display_mce_comment = false;
                return $display_mce_comment;
            endif;

            $titan = TitanFramework::getInstance('tinymce-comment-field');
            $enabled = $titan->getOption('enabled');
            $mobile_browser_support = $titan->getOption('mobile-browser-support');


            if (!$enabled):
                $display_mce_comment = false;
                return $display_mce_comment;
            endif;

            if (!is_singular()):
                $display_mce_comment = false;
                return $display_mce_comment;
            endif;

            global $post;

            if (!comments_open($post->ID)):
                $display_mce_comment = false;
                return $display_mce_comment;
            endif;

            $enabled_on_object = get_post_meta($post->ID, 'tinymce-comment-field_enabled', true);

            if ($enabled_on_object === "0"):
                $display_mce_comment = false;
                return $display_mce_comment;
            endif;

            if (!$mobile_browser_support && wp_is_mobile()):
                $display_mce_comment = false;
                return $display_mce_comment;
            endif;

            $display_mce_comment = user_can_richedit();

        endif;


        return $display_mce_comment;
    }

    public function comment_editor_content_css() {

        $action = filter_input(INPUT_GET, "mcec_action", FILTER_SANITIZE_STRIPPED);

        if (isset($action) && $action === "comment_editor_content_css"):

            if (class_exists('TitanFramework')):

                $titan = TitanFramework::getInstance('tinymce-comment-field');
                $editor_font = $titan->getOption('editor-font');
                $background_color = $titan->getOption('background-color');

                header('Content-type: text/css')
                ?>
                body {
                <?php
                foreach ($editor_font as $key => $css):
                    echo $key . " : " . $css . ";" . chr(13);
                endforeach;
                ?>
                background-color: <?php echo $background_color; ?>;
                }
            <?php
            endif;

            exit();
        endif;
    }

    public function shortcodes_whitelist() {

        if (!class_exists('TitanFramework')):
            return;
        endif;

        global $shortcode_tags;
        global $shortcode_tags_saved;
        $shortcode_tags_saved = $shortcode_tags;

        $titan = TitanFramework::getInstance('tinymce-comment-field');
        $allowed_shortcodes = $titan->getOption('allowed-shortcodes');

        foreach ($shortcode_tags as $shortcode_tag => $value):
            $shortcode_found = false;
            foreach ($allowed_shortcodes as $allowed_shortcode):
                if ($allowed_shortcode === $shortcode_tag):
                    $shortcode_found = true;
                endif;
            endforeach;

            if ($shortcode_found === false):
                remove_shortcode($shortcode_tag);
            endif;
        endforeach;

        add_filter('comment_text', 'do_shortcode');
    }

    public function reset_shortcodes($text) {
        global $shortcode_tags;
        global $shortcode_tags_saved;
        $shortcode_tags = $shortcode_tags_saved;
        return $text;
    }

}
