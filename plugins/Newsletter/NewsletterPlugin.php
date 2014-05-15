<?php
/**
 * @version $Id$
 * @copyright Libis
 * @license http://www.gnu.org/licenses/gpl-3.0.txt
 * @package Newsletter
 */

// Define Constants.
define('NEWSLETTER_PAGE_PATH', 'registreer/');
define('NEWSLETTER_CONTACT_PAGE_TITLE', 'Registreer');
define('NEWSLETTER_CONTACT_PAGE_INSTRUCTIONS', 'Please send us your comments and suggestions.');
define('NEWSLETTER_THANKYOU_PAGE_TITLE', 'Thank You For Your Feedback');
define('NEWSLETTER_THANKYOU_PAGE_MESSAGE', 'We appreciate your comments and suggestions.');
define('NEWSLETTER_ADMIN_NOTIFICATION_EMAIL_SUBJECT', 'A User Has Contacted You');
define('NEWSLETTER_ADMIN_NOTIFICATION_EMAIL_MESSAGE_HEADER', 'A user has sent you the following message:');
define('NEWSLETTER_USER_NOTIFICATION_EMAIL_SUBJECT', 'Thank You');
define('NEWSLETTER_USER_NOTIFICATION_EMAIL_MESSAGE_HEADER', 'Thank you for sending us the following message:');
define('NEWSLETTER_ADD_TO_MAIN_NAVIGATION', 1);
define('NEWSLETTER_DIR', dirname(__FILE__));

//HELPERS
require_once NEWSLETTER_DIR.'/helpers/NewsletterPluginFunctions.php';
//composer dependencies
require NEWSLETTER_DIR.'/vendor/autoload.php';

class NewsletterPlugin extends Omeka_Plugin_AbstractPlugin
{
     //'admin_items_show_sidebar',
    protected $_hooks = array(
        'install',
        'uninstall',
        'define_routes',
        'config_form',
        'config',
        'admin_head',
        'after_save_item'
    );

    protected $_filters = array(
        'public_navigation_main',
        'admin_navigation_main'
    );
    
    public function hookInstall()
    {
            //make new item type
            $itemType = new ItemType;
            // set the name and description of the item type
            $itemType->name = "Newsletter-contact";
            $itemType->description = "Contacts belonging to the Newsletter plugin.
                Always make sure that the e-mail field is present, as well as Newsletter-contact-Newsletter and ewsletter-contact-Activiteiten.";

            //add elements
            $contactemail = get_db()->getTable('Element')->findByElementSetNameAndElementName('Item Type Metadata', 'E-mail');
            $contactnieuwsbrief = new Element;
            $contactactiviteiten = new Element;

            $contactnieuwsbrief->element_set_id = 3;
            $contactnieuwsbrief->name = 'Nieuwsbrief';
            try{
                $contactnieuwsbrief->save();
                $elements[]= $contactnieuwsbrief;
            }catch(Exception $e){
                $problem .= $e->getMessage();
            }

            $contactactiviteiten->element_set_id = 3;
            $contactactiviteiten->name = 'Activiteiten';            
            try{
                $contactactiviteiten->save();
                $elements[]= $contactactiviteiten;
            }catch(Exception $e){
                $problem .= $e->getMessage();
            }

            $itemType->addElements($elements);

            //save the item type
            $itemType->save();   
            
            //salt
            $characterList = "abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789!@#$%&*?";
            $i = 0;
            $salt = "";
            while ($i < $max) {
                $salt .= $characterList{mt_rand(0, (strlen($characterList) - 1))};
                $i++;
            }
            
            set_option('newsletter_salt', $salt);
            
            set_option('newsletter_reply_from_email', get_option('administrator_email'));
            set_option('newsletter_forward_to_email', get_option('administrator_email'));
            set_option('newsletter_forward_to_email_admin_notification_email_subject', NEWSLETTER_ADMIN_NOTIFICATION_EMAIL_SUBJECT);
            set_option('newsletter_admin_notification_email_message_header', NEWSLETTER_ADMIN_NOTIFICATION_EMAIL_MESSAGE_HEADER);
            set_option('newsletter_user_notification_email_subject', NEWSLETTER_USER_NOTIFICATION_EMAIL_SUBJECT);
            set_option('newsletter_user_notification_email_message_header', NEWSLETTER_USER_NOTIFICATION_EMAIL_MESSAGE_HEADER);
            set_option('newsletter_contact_page_title', NEWSLETTER_CONTACT_PAGE_TITLE);
            set_option('newsletter_contact_page_instructions', NEWSLETTER_CONTACT_PAGE_INSTRUCTIONS);
            set_option('newsletter_thankyou_page_title', NEWSLETTER_THANKYOU_PAGE_TITLE);
            set_option('newsletter_thankyou_page_message', NEWSLETTER_THANKYOU_PAGE_MESSAGE);
            set_option('newsletter_add_to_main_navigation', NEWSLETTER_ADD_TO_MAIN_NAVIGATION);
    }

    public function hookUninstall()
    {
        $itemType = get_db()->getTable('ItemType')->findByName('Newsletter-contact');
        /*$elements = get_db()->getTable('Element')->findByItemType($itemType->id);
        foreach($elements as $element){
            $element->delete();
        }*/
        $itemType->delete();    

        delete_option('newsletter_reply_from_email');
        delete_option('newsletter_forward_to_email');
        delete_option('newsletter_admin_notification_email_subject');
        delete_option('newsletter_admin_notification_email_message_header');
        delete_option('newsletter_user_notification_email_subject');
        delete_option('newsletter_user_notification_email_message_header');
        delete_option('newsletter_contact_page_title');
        delete_option('newsletter_contact_page_instructions');
        delete_option('newsletter_thankyou_page_title');
        delete_option('newsletter_add_to_main_navigation');
    }

    /**
     * Adds 2 routes for the form and the thank you page.
     **/
    public function hookDefineRoutes($args)
    {
            $router = $args['router'];
            $router->addRoute(
                'newsletter_form',
                new Zend_Controller_Router_Route(
                    NEWSLETTER_PAGE_PATH,
                    array('module'       => 'newsletter')
                )
            );           

            $router->addRoute(
                'newsletter_thankyou',
                new Zend_Controller_Router_Route(
                    NEWSLETTER_PAGE_PATH . 'thankyou',
                    array(
                        'module'       => 'newsletter',
                        'controller'   => 'index',
                        'action'       => 'thankyou',
                    )
                )
            );

    }

    public function hookConfigForm()
    {
            include 'config_form.php';
    }

    public function hookConfig()
    {
            set_option('newsletter_reply_from_email', $_POST['reply_from_email']);
            set_option('newsletter_forward_to_email', $_POST['forward_to_email']);
            set_option('newsletter_admin_notification_email_subject', $_POST['admin_notification_email_subject']);
            
            set_option('newsletter_user_notification_email_subject', $_POST['user_notification_email_subject']);
            
            set_option('newsletter_contact_page_title', $_POST['contact_page_title']);
            set_option('newsletter_contact_page_instructions',$_POST['contact_page_instructions']);
            set_option('newsletter_thankyou_page_title', $_POST['thankyou_page_title']);
            set_option('newsletter_thankyou_page_message', $_POST['thankyou_page_message']);            
    }

    public function filterPublicNavigationMain($nav)
    {
        $contact_title = get_option('newsletter_contact_page_title');
        $contact_add_to_navigation = get_option('newsletter_add_to_main_navigation');
        if ($contact_add_to_navigation) {
            //$nav[$contact_title] = url(array(), 'newsletter_form');
            $navLinks[] = array(
                        'label' => $contact_title,
                        'uri' => url(array(), 'newsletter_form')
            );
            $nav = array_merge($nav, $navLinks);
        }
        return $nav;

    }
    
    public function hookAdminHead($args)
    {
        queue_css_file('jquery.handsontable.full');
        queue_js_file('jquery.handsontable.full');       
    }
    
    public function hookAfterSaveItem($item){
        
    }
    
    /**
     * Add the Newsletter link to the admin main navigation.
     * 
     * @param array Navigation array.
     * @return array Filtered navigation array.
     */
    public function filterAdminNavigationMain($nav)
    {
        $nav[] = array(
            'label' => __('Newsletter'),
            'uri' => url('newsletter')            
        );
        return $nav;
    } 
}    