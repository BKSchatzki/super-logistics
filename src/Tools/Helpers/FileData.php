<?php
/**
 * Created by PhpStorm.
 * User: wedevs-macbook-2
 * Date: 30/1/19
 * Time: 11:29 AM
 */

// namespace SL\Tools\Helpers;

// use Exception;

// class FileData
// {
//     public $fs = null;

//     public function __construct()
//     {
//         $this->define_constants();
//         $this->set_fs();
//         $this->make_dir();
//     }

//     protected function define_constants() {
//         if(! defined('SL_IMPORTER_CONTENTS_STORE')) {
//             define('SL_IMPORTER_CONTENTS_STORE', WP_CONTENT_DIR . '/pm-import-contents');
//             define('SL_IMPORTER_CONTENTS_DIR_PERMISSION', 0755);
//             define('SL_IMPORTER_CONTENTS_FILE_PERMISSION', 0644);
//         }
//     }


//     protected function set_fs() {
//         require_once( ABSPATH . 'wp-admin/includes/file.php' );
//         require_once( ABSPATH . 'wp-admin/includes/class-wp-filesystem-base.php' );

//         if ( $this->fs instanceof \WP_Filesystem_Base ) {
//             return $this->fs;
//         }

//         WP_Filesystem();

//         global $wp_filesystem;

//         $this->fs = $wp_filesystem;
//     }

//     public function remove_root_dir() {
//         if ( $this->fs->is_dir( SL_IMPORTER_CONTENTS_STORE ) ) {
//             return $this->fs->rmdir( SL_IMPORTER_CONTENTS_STORE, true );
//         }

//         return false;
//     }

//     public function make_dir() {
//         $dirs = [
//             SL_IMPORTER_CONTENTS_STORE,
//             SL_IMPORTER_CONTENTS_STORE . '/data/',
//             SL_IMPORTER_CONTENTS_STORE . '/data/trello',
//             SL_IMPORTER_CONTENTS_STORE . '/data/asana',
//             SL_IMPORTER_CONTENTS_STORE . '/data/acl'
//         ];

//         foreach ( $dirs as $dir ) {
//             if(!$this->fs->exists($dir)){
//                 $created = $this->fs->mkdir( $dir, SL_IMPORTER_CONTENTS_DIR_PERMISSION );
//                 if ( ! $created ) {
//                     throw new Exception(
//                         sprintf( __( 'File permission error. Cannot create "%s" directory', 'super-logistics' ), $dir )
//                     );
//                 }
//             }

//         }

//         return $this;
//     }

//     public function make_file( $file) {

//         if ( $this->fs->is_file( $file ) ) {
//             $this->fs->delete( $file );
//         }
//         return $this->fs->touch( $file );
//     }

//     public function save_contents($filename, $contents ) {
//         $file = SL_IMPORTER_CONTENTS_STORE . '/data/'.$filename;
//         $this->make_file( $file );
//         $json = json_encode($contents, JSON_PRETTY_PRINT);
//         return $this->fs->put_contents($file, $json, SL_IMPORTER_CONTENTS_FILE_PERMISSION );
//     }

//     public function copy_file( $from, $to = '' ) {
//         $to = SL_IMPORTER_CONTENTS_STORE . $to;

//         return $this->fs->copy( $from, $to, true, SL_IMPORTER_CONTENTS_FILE_PERMISSION );
//     }

//     public function get_content( $filename ) {
//         $file = SL_IMPORTER_CONTENTS_STORE . '/data/'.$filename;
//         $json = $this->fs->get_contents( $file );

//         return $json ? json_decode( $json, true ) : [];
//     }

// }
