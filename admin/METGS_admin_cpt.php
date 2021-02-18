<?php
class METGS_admin_cpt {

    function __construct()
    {

    }

    function getQueryBasicArgs(){
        $args = array(
            'post_type' => array($this->cpt),
            'post_status' => array('publish'),
            'posts_per_page' => '-1',
        );
        return $args;
    }

    function archiveURL(){
        echo site_url() . '/' . $this->rewrite . '/';
    }

    // For saving functions
    function verifyOnSave($post_id, $post) {
        return $this->isCPTOK($post) && !$this->isAutoSave() && $this->hasPermissions($post_id);
    }

    function isAutoSave() {
        if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) {
            return true;
        }
        return false;
    }

    function hasPermissions($post_id) {
        if (current_user_can('edit_post', $post_id)) {
            return true;
        }
        return false;
    }

    function isCPTOK($post) {
        if ($post->post_type == $this->cpt) {
            return true;
        }
        return false;
    }

    function timeNow(){
        return (int)time();
    }

}
