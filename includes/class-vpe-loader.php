<?php

if (! defined('ABSPATH')){
    exit;
}

class VPE_Loader{

    private static $instance = null;

    public static function get_instance() {
        if (null === self::$instance){
            self::$instance = new self();
        }
        return self::$instance;
    }


    private function __construct(){}

    public function init(){
        $storage = new VPE_Storage();
        $access = new VPE_Access();
        $shortcode = new VPE_Shortcode();
    }

    add_filter('wp_handle_upload_prefilter', array($storage, 'filter_pdf_upload'));
    add_action('wp_ajax_vpe_fetch_document', array($access, 'deliver_secure_payload'));
    add_action('wp_ajax_nopriv_vpe_fetch_document', array($access, 'deliver_secure_payload'));
    add_shortcode('vault-pdf', array($shortcode, 'render_shortcode'));
}