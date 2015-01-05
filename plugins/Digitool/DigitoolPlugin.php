<?php

/**
* @package omeka
* @subpackage digitool plugin
* @copyright 2014 Libis.be
*/
define('DIGITOOL_DIR', dirname(__FILE__));

//HELPERS
require_once DIGITOOL_DIR.'/helpers/CurlHelper.php';
require_once DIGITOOL_DIR.'/helpers/DigitoolPluginFunctions.php';

class DigitoolPlugin extends Omeka_Plugin_AbstractPlugin
{
    //'admin_items_show_sidebar',
    protected $_hooks = array(
        'install',
        'uninstall',
        'define_routes',
        'config_form',
        'config',        
        'after_save_item',
        'define_acl'
    );

    protected $_filters = array(
        'admin_items_form_tabs',
        'api_resources',
        'api_import_omeka_adapters',
        'api_extend_items'
    );


    function hookInstall()
    {
        $db = get_db();
        $sql = "
        CREATE TABLE IF NOT EXISTS $db->DigitoolUrl (
        `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY ,
        `item_id` BIGINT UNSIGNED NOT NULL ,
        `pid` VARCHAR(100) NOT NULL ,
        `label` VARCHAR(25)
        INDEX (`item_id`)) ENGINE = MYISAM";
        $db->query($sql);

        set_option('digitool_proxy','');
        set_option('digitool_cgi','');
        set_option('digitool_thumb','');
        set_option('digitool_view','');
    }

    /**
     * Uninstall the plugin.
     */
    public function hookUninstall(){
        // Drop the url table.
        $db = get_db();
        $db->query("DROP TABLE $db->DigitoolUrl");

        delete_option('digitool_proxy');
        delete_option('digitool_cgi');
        delete_option('digitool_thumb');
        delete_option('digitool_view');
    }
    //link to config_form.php
    public function hookConfigForm() {        
        require dirname(__FILE__) .'/config_form.php';
    }
    //process the config_form
    public function hookConfig() {
        //get the POST variables from config_form and set them in the DB
        set_option('digitool_proxy',$_POST['proxy']);

        set_option('digitool_cgi',$_POST['cgi']);

        set_option('digitool_thumb',$_POST['thumb']);

        set_option('digitool_view',$_POST['view']);
    }

    /**
    * digitool define_routes hook
    */
    public function hookDefineRoutes($args){

        $router = $args['router'];
        $router->addRoute(
            'digitoolActionRoute',
            new Zend_Controller_Router_Route(
                'digitool/index/:action/:id',
                array(
                    'module'        => 'digitool',
                    'controller'    => 'index'
                    ),
                array('id'          => '\d+')
             )
         );
         $router->addRoute(
            'digitoolIndexRoute',
            new Zend_Controller_Router_Route(
                'digitool/index/:id',
                array(
                    'module'        => 'digitool'
                    ),
                array('id'          => '\d+')
             )
         );
    }

    public function hookAfterSaveItem($item){

        if(!isset($_POST['pid'])){
            return false;
        }

        $post = $_POST;

        //save to db
        $url = new DigitoolUrl;
        $url->item_id = $item->id;

        $url->saveForm($post);
    }
    
    public function hookDefineAcl($args)
    {
        $acl = $args['acl'];
        $acl->addResource('Digitool_DigitoolUrls');
        
        $acl->allow(null, 'Digitool_DigitoolUrls',
        array('show', 'summary', 'showitem', 'browse', 'tags'));

        // Allow contributors everything but editAll and deleteAll.
        $acl->allow('contributor', 'Digitool_DigitoolUrls',
        array('add', 'add-page', 'delete-page', 'delete-confirm', 'edit-page-content',
            'edit-page-metadata', 'item-container', 'theme-config',
            'editSelf', 'deleteSelf', 'showSelfNotPublic'));

        $acl->allow(null, 'Digitool_DigitoolUrls', array('edit', 'delete'),
        new Omeka_Acl_Assert_Ownership);
    }

    /**
    * Shows the digitool urls on the admin show page in the secondary column
    * @param Item $item
    * @return void
    
    public function hookAdminItemsShowSidebar($item,$view){
            $html = '<div class="panel"><h2>Digitool</h2>'
            . '<img src="'.digitool_get_thumb_url($item).'">'
            . '<br><br></div>';
            return $html;
    }**/

    /**
    * Add a tab to the edit item page
    * @return array
    **/
    public function filterAdminItemsFormTabs($tabs,$args){
        $item = $args['item'];
        $tabs['Digitool'] = digitool_admin_form($item);       
        return $tabs;
    }


    public function filterApiResources($apiResources)
    {
        $apiResources['digitool_urls'] = array(
            'record_type' => 'DigitoolUrl', 
            'actions' => array('get','index','put','post','delete')
        );       

        return $apiResources;    
    }
    
    
    /**
    * Add digitool urls to item API representations.
    *
    * @param array $extend
    * @param array $args
    * @return array
    */
    public function filterApiExtendItems($extend, $args)
    {
        $item = $args['record'];
        $urls = $this->_db->getTable('DigitoolUrl')->findBy(array('item_id' => $item->id));
        if (!$urls) {
            return $extend;
        }
        $url = $urls[0];
        $i=1;
        foreach($urls as $url):
        $extend['digitool_urls'] = array(
        'count'=>$i,        
        'url' => Omeka_Record_Api_AbstractRecordAdapter::getResourceUrl("/digitool_urls/{$url->id}"),
        'resource' => 'digitool_urls',
        'pid' => $url->id,
        'item_id' => $item->id
        );
        $i++;
        endforeach;
        return $extend;
    }
    
    public function filterApiImportOmekaAdapters($adapters, $args)
    {
        $digiAdapter = new ApiImport_ResponseAdapter_Omeka_GenericAdapter(null, $args['endpointUri'], 'DigitoolUrl');
        $digiAdapter->setResourceProperties(array('item' => 'Item'));
        $adapters['digitool_urls'] = $digiAdapter;
        return $adapters;
    }
}
?>