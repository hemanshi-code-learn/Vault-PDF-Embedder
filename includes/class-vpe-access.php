<?php

if (! defined('ABSPATH')){
    exit;
}

class VPE_Access{

    public function deliver_secure_payload() {
        if ( ! is_user_logged_in() ){
            wp_die( 'Access Denied: Unauthenticated user session.', 'Forbidden', array('response' => 403) );
        }

        $attachment_id = isset( $_GET['file_id'] ) ? absint( $_GET['file_id'] ) : 0;
        if (! $attachment_id){
            wp_die('Invalid Document Identifier.', 'Bad Request', array('response' => 400));
        }

        $file_path = get_attached_file( $attachment_id );

        if ( $file_path && file_exists ($file_path) && 'application/pdf' === mime_content_type( $file_path ) ){
            clean_term_cache( $attachment_id );

            header('Content-Type: application/pdf');
            header('Content-Disposition: inline; filename="' . esc_attr( basename($file_path ) ) . '"');
            header('Content-Transfer-Encoding: binary');
            header('Accept-Ranges: bytes');

            readfile( $file_path );
            exit;
        }

        wp_die('Document payload unavailable or deleted.', 'Not Fopund', array('response'=>404));
    }
}