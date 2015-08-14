<?php

class TMCECF_DummyController extends TMCECF_BaseController {

    public function __construct() {
        add_action("admin_menu", array(&$this, "add_dummy_menu"));
    }

    public function add_dummy_menu() {

        if (!$this->isTitanEnabled()):
            add_menu_page("TinyMCE Comment Field", "TinyMCE Comment Field", "manage_options", "tinymce-comment-field", array(&$this, "dummy"), "dashicons-edit");
        endif;
    }

    public function dummy() {
        require_once(TMCECF_PLUGIN_DIR . "views/dummy.php");
    }

}
