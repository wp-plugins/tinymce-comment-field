<?php
class TMCECF_MetaBoxController {

    public function __construct() {
        add_action('add_meta_boxes', array(&$this, "add_meta_boxes"));
        add_action('save_post', array(&$this, "meta_box_enable_disable_tmcecf_save"));
    }

    public function add_meta_boxes() {
        if (class_exists('TitanFramework')):
            $titan = TitanFramework::getInstance('tinymce-comment-field');
            $post_types = $titan->getOption('post-types');

            foreach ($post_types as $post_type):
                add_meta_box("tinymce-comment-field", __("TinyMCE Comment Field", "tinymce-comment-field"), array(&$this, "meta_box_enable_disable_mce_comments_content"), $post_type, "side");
            endforeach;
        endif;
    }

    public function meta_box_enable_disable_mce_comments_content($post) {
        wp_nonce_field('tmcecf_metabox', 'tinymce-comment-field_wpnonce');

        $value = get_post_meta($post->ID, 'tinymce-comment-field_enabled', true);

        if ($value !== "0" || $value === "1"):
            $value = true;
        elseif ($value === "0"):
            $value = false;
        endif;
        ?>
        <p>
            <label for="tmcecf_enabled"><?php _e('Enabled', 'tinymce-comment-field'); ?></label>
            <input type="checkbox" id="tmcecf_enabled" name="tmcecf_enabled" value="1" <?php checked($value); ?> />
        </p>
        <?php
    }

    public function meta_box_enable_disable_tmcecf_save($post_id) {

        $nonce = filter_input(INPUT_POST, "tinymce-comment-field_wpnonce", FILTER_SANITIZE_STRING);

        if (!isset($nonce)):
            return;
        endif;

        if (!wp_verify_nonce($nonce, 'tmcecf_metabox')):
            return;
        endif;

        if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE):
            return;
        endif;

        $post_type = filter_input(INPUT_POST, "post_type", FILTER_SANITIZE_STRING);

        if (isset($post_type) && 'page' == $post_type):
            if (!current_user_can('edit_page', $post_id)):
                return;
            endif;
        else:
            if (!current_user_can('edit_post', $post_id)):
                return;
            endif;
        endif;

        $enabled = filter_input(INPUT_POST, "tmcecf_enabled", FILTER_SANITIZE_NUMBER_INT);

        if (isset($enabled)):
            $enabled_value = "1";
        else:
            $enabled_value = "0";
        endif;

        update_post_meta($post_id, 'tinymce-comment-field_enabled', $enabled_value);
    }

}
