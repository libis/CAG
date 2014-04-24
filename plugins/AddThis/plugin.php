<?php
/* OMEKA PLUGIN ADDTHIS: 
 * This plug-in will add the addthis button to Omeka,
 * You will be able to configure where to show it (items or collections)
 * The button script can also be adjusted in the configuration menu
 */

    class AddThisPlugin extends Omeka_Plugin_AbstractPlugin
    {

         // Define Hooks
        protected $_hooks = array(
            'install',
            'uninstall',
            'config_form',
            'config'
        );

        //Add filters
        protected $_filters = array(
            'public_navigation_main'
        );


    //link to config_form.php
    public function hookConfigForm() {
            include('config_form.php');
    }

    //process the config_form
    public function hookConfig($args) {
            $post = $args['post'];
            //set script
            set_option('addThis_script',$post['addThis_script']);
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

    //the plug-in's output
    function addThis_add() {

        echo "<div id='add_this_block'>".(get_option('addThis_script'))."</div>";

    }

}