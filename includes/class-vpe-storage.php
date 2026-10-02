<?php

if (! defined('ABSPATH')){
    exit;
}

class VPE_Storage{

    public function filter_pdf_upload($file){
        if( isset( $file['type'] ) && 'application/pdf' === $file['type']) {
            add_filter( 'upload_dir', array($this, 'set_vault_directory'));
        }
        return $file;
    }

    public function set_vault_directory($param) {

        $folder = '/securepdfs';

        $param['subdir'] = $folder;
        $param['path'] = $param['basedir'] . $folder;
        $param['url'] = $param['baseurl'] . $folder;

        if ( ! file_exists($param['path']) ){
            wp_mkdir_p( $param['path'] );

            $htaccess = $param['path'] . '/.htaccess';
            if ( ! file_exists( $htaccess )){
                $rules = "<FilesMatch \"\.(pdf)$\">\nRequire all denied\n</FileMatch>";
                file_put_contents($htaccess, $rules);
            }
        }

        remove_filter( 'upload_dir', array( $this, 'set_vault_directory') );
        return $param;
    }
}