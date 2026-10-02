<?php
/**
 * Plugin Name: Vault PDF Embedder
 * Description: Secure, object-oriented document viewer.
 * Version: 1.0.0
 * Author: Mervan Agency
 * Author URI: https://mervanagency.io/
 * Text Domain: vault-pdf-embedder
 * Licence: GPL-2.0+
 */

if (! defined('ABSPATH')){
    exit;
}

// Global Plugin Constants
define('VPE_VERSION', '1.0.0');
define('VPE_PLUGIN_DIR', plugin_dir_path( __FILE__ ));
define('VPE_PLUGIN_URL', plugin_dir_url( __FILE__ ));
define('VPE_PLUGIN_FILE', __FILE__);

require_once VPE_PLUGIN_DIR . 'includes/class-vpe-loader.php';
require_once VPE_PLUGIN_DIR . 'includes/class-vpe-storage.php';
require_once VPE_PLUGIN_DIR . 'includes/class-vpe-access.php';
require_once VPE_PLUGIN_DIR . 'includes/class-vpe-shortcode.php';

function run_vault_pdf_embedder(){
    $plugin = VPE_Loader::get_instance();
    $plugin->init();
}

run_vault_pdf_embedder();