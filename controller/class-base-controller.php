<?php
class TMCECF_BaseController {

    public function __construct() {

    }

    protected function isTitanEnabled() {
        global $tmcecf_titan_enabled;

        if (!isset($tmcecf_titan_enabled)) {
            $tmcecf_titan_enabled = false;

            if (class_exists('TitanFramework')) {
                $tmcecf_titan_enabled = true;
            } else {
                $tmcecf_titan_enabled = false;
            }
        }

        return $tmcecf_titan_enabled;
    }

    protected function isTMCECFEnabled() {
        global $tmcecf_enabled;

        if ($this->isTitanEnabled() && !isset($tmcecf_enabled)) {
            $titan = TitanFramework::getInstance('tinymce-comment-field');
            $tmcecf_enabled = $titan->getOption('enabled');
        } elseif (!$this->isTitanEnabled()) {
            $tmcecf_enabled = false;
        }

        return $tmcecf_enabled;
    }


    protected function displayEditor() {
        global $tmcecf_display_editor;

        if (!isset($tmcecf_display_editor)):

            $tmcecf_display_editor = false;

            if (!$this->isTitanEnabled()):
                $tmcecf_display_editor = false;
                return $tmcecf_display_editor;
            endif;

            if (!$this->isTMCECFEnabled()):
                $tmcecf_display_editor = false;
                return $tmcecf_display_editor;
            endif;

            $titan = TitanFramework::getInstance('tinymce-comment-field');
            $mobile_browser_support = $titan->getOption('mobile-browser-support');

            if (!is_singular()):
                $tmcecf_display_editor = false;
                return $tmcecf_display_editor;
            endif;

            global $post;

            if (!comments_open($post->ID)):
                $tmcecf_display_editor = false;
                return $tmcecf_display_editor;
            endif;

            $enabled_on_object = get_post_meta($post->ID, 'tinymce-comment-field_enabled', true);

            if ($enabled_on_object === "0"):
                $tmcecf_display_editor = false;
                return $tmcecf_display_editor;
            endif;

            if (!$mobile_browser_support && wp_is_mobile()):
                $tmcecf_display_editor = false;
                return $tmcecf_display_editor;
            endif;

            $tmcecf_display_editor = user_can_richedit();

        endif;


        return $tmcecf_display_editor;
    }
}