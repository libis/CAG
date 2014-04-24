<?php
/* OMEKA PLUGIN ADDTHIS: 
 * This plug-in will add the addthis button to Omeka,
 * You will be able to configure where to show it (items or collections)
 * The button script can also be adjusted in the configuration menu
 */
//HELPERS
require_once dirname(__FILE__) .'/helpers/AddThisPluginFunctions.php';

class AddThisPlugin extends Omeka_Plugin_AbstractPlugin
{

    // Define Hooks
    protected $_hooks = array(
        'install',
        'uninstall',
        'config_form',
        'config'
    );      

    //link to config_form.php
    public function hookConfigForm() {
        require dirname(__FILE__) .'/config_form.php';
    }

    //process the config_form
    public function hookConfig() {
            //set script
            set_option('addThis_script',$_POST['addThis_script']);
    }

    //handle the installation
    public function hookInstall() {
            //set the default plugin options (items is set as default)
            set_option('addThis_script','default');
    }

    //handle the uninstallation
    public function hookUninstall() {
        // Delete the plugin options
       delete_option('addThis_script');       
    }
  
}