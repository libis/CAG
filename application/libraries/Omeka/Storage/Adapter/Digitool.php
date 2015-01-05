<?php
/**
 * Omeka
 * 
 * @copyright Copyright 2007-2012 Roy Rosenzweig Center for History and New Media
 * @license http://www.gnu.org/licenses/gpl-3.0.txt GNU GPLv3
 */

/**
 * Digitool storage adapter.
 *
 * The default adapter; this stores files in the Omeka files directory by 
 * default, but can be set to point to a different path.
 * 
 * @package Omeka\Storage\Adapter
 */
class Omeka_Storage_Adapter_Digitool implements Omeka_Storage_Adapter_AdapterInterface
{
    private $_options;

     /**
     * Set options for the storage adapter.
     *
     * @param array $options
     */
    public function __construct(array $options = array())
    {
         $this->_options = $options;
    }

    public function setUp()
    {
        // Required by interface but does nothing, for the time being.
    }

    /**
     * Check whether the adapter is set up correctly to be able to store
     * files.
     *
     * Specifically, this checks to see if the local storage directory
     * is writable.
     *
     * @return boolean
     */
    public function canStore()
    {
        return false;
    }

    /**
     * Move a local file to "storage."
     *
     * @param string $source Local filesystem path to file.
     * @param string $dest Destination path.
     */
    public function store($source, $dest)
    {
        //cannot store
    }

    /**
     * Move a file between two "storage" locations.
     *
     * @param string $source Original stored path.
     * @param string $dest Destination stored path.
     */
    public function move($source, $dest)
    {
        //cannot store so cannot move
    }

    /**
     * Remove a "stored" file.
     *
     * @param string $path
     */
    public function delete($path)
    {
        //cannot delete files
    }

    /**
     * Get a URI for a "stored" file.
     *
     * @param string $path
     * @return string URI
     */
    public function getUri($path)
    {
        list( $size, $filename ) = explode('/', $path, 2);

        $mapping = array(
        'square_thumbnails' => 'THUMBNAIL', // 200
        'thumbnails' => 'THUMBNAIL', // 200
        'fullsize' => 'VIEW_MAIN,VIEW', // 980
        'files' => 'VIEW_MAIN,VIEW',
        'archive' => 'VIEW_MAIN,VIEW' );

        //GET original_filename which is the pid
        //$db = get_db();
        //$res = $db->fetchRow("SELECT original_filename FROM {$db->prefix}files WHERE archive_filename = '{$filename}'");
        echo $filename;
        //construct url
        $url = "http://resolver.lias.be/get_pid?redirect&usagetype=".$mapping[$size]."&pid=".$filename;//$res['original_filename'];
               
        return $url;
        
    }   
}
