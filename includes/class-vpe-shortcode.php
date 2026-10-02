<?php

if (! defined('ABSPATH')){
    exit;
}

class VPE_Shortcode{

    public function render_shortcode($atts){
        $atts = shortcode_atts( array('id' => 0), $atts, 'vault-pdf' );
        $doc_id = absint($atts['id']);

        if ( ! $doc_id ){
            return '<p class="vpe-error">Error: Invalid Document ID specified.</p>';
        }

        $this->enqueue_assets();
        $endpoint_url = admin_url( 'admin-ajax.php?action=vpe_fetch_document&file_id' . $doc_id );

        ob_start();
        ?>
        <div class="vault-pdf-wrapper" oncontextmenu="return false;">
            <div class="vpe-toolbar">
                <div class="toolbar-group">
                    <button id="prev-page" type="button" title="Previous">&#x21e1;</button>
                    <button id="next-page" type="button" title="Next">&#x21e3;</button>
                    <span class="page-info">Page <span id="page-num">1</span> / <span id="page-count">-</span></span>
                </div>
                <div class="toolbar-group">
                    <button id="zoom-out" type="button" title="Zoom Out">-</button>
                    <button id="zoom-in" type="button" title="Zoom In">+</button>
                    <span class="zoom-info">Zoom <span id="zoom-percent">100%</span></span>
                </div>
                <div class="toolbar-group">
                    <span class="secure-badge">Secure</span>
                    <button id="fullscreen-btn" type="button" title="Toggle Fullscreen">&#x26f6;</button>
                </div>
            </div>
            <div class="vpe-viewer-container">
                <div id="vpe-loading-msg"><p>Please wait a few moments for the pdf to load.</p></div>
                <canvas id="vault-pdf-canvas" data-src="<?php echo esc_url($endpoint_url); ?>"></canvas>
            </div>
        </div>
        <?php
        return ob_get_clean();
    }

    private function enqueue_assets(){
        wp_enqueue_script( 'pdfjs-dist', 'https://cdnjs.cloudflare.com/ajax/libs/pdf.js/2.16.105/pdf.min.js', array(), '2.16.105', true );
        wp_enqueue_script( 'vpe-viewer-script', VPE_PLUGIN_URL . '/assets/js/viewer.js', array('jquery', 'pdfjs-dist'), VPE_VERSION, true );
        wp_enqueue_style( 'vpe-viewer-style', VPE_PLUGIN_URL . '/assets/css/viewer.css', array(), VPE_VERSION );
    }
}