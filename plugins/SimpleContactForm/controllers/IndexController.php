<?php
/**
 * @copyright Roy Rosenzweig Center for History and New Media, 2007-2012
 * @license http://www.gnu.org/licenses/gpl-3.0.txt
 * @package SimpleContactForm
 */

/**
 * Controller for Contact form.
 *
 * @package SimpleContactForm
 */
class SimpleContactForm_IndexController extends Omeka_Controller_AbstractActionController
{    
	public function indexAction()
	{	
	    $name = isset($_POST['name']) ? $_POST['name'] : '';
            $email = isset($_POST['email']) ? $_POST['email'] : '';;
            $message = isset($_POST['message']) ? $_POST['message'] : '';;
            $motivatie = isset($_POST['motivatie']) ? $_POST['motivatie'] : '';
            
	    $captchaObj = $this->_setupCaptcha();
	    
	    if ($this->getRequest()->isPost()) {    		
    		// If the form submission is valid, then send out the email
    		if ($this->_validateFormSubmission($captchaObj)) {
                    if( $_POST['aanvraag']=="true"){
                        $redentxt ='<b>Motivatie:</b><ul> ';
                        foreach($motivatie as $reden){
                            if($reden == 'Anders'){
                                $redentxt .= "<li>Anders: ".html_escape($_POST["motivatie-other"])."</li>";
                            }else{
                                $redentxt .= "<li>".$reden."</li>";
                            }
                        }
                        $message .= "<br>".$redentxt;
                    }
                    
		    $this->sendEmailNotification($_POST['email'], $_POST['name'], $message);
	            $url = WEB_ROOT."/".SIMPLE_CONTACT_FORM_PAGE_PATH."thankyou";
                    $this->_helper->redirector->goToUrl($url);
                 
    		}
	    }	
	    
	    // Render the HTML for the captcha itself.
	    // Pass this a blank Zend_View b/c ZF forces it.
		if ($captchaObj) {
		    $captcha = $captchaObj->render(new Zend_View);
		} else {
		    $captcha = '';
		}
		
		$this->view->assign(compact('name','email','message', 'captcha'));
	}
        
        public function bestelAction(){
            $naam = isset($_POST['naam']) ? $_POST['naam'] : '';
            $voornaam = isset($_POST['voornaam']) ? $_POST['voornaam'] : '';
            $email = isset($_POST['email']) ? $_POST['email'] : '';
            $straat = isset($_POST['straat']) ? $_POST['straat'] : '';
            $postcode = isset($_POST['postcode']) ? $_POST['postcode'] : '';
            $gemeente = isset($_POST['gemeente']) ? $_POST['gemeente'] : '';
            $land = isset($_POST['land']) ? $_POST['land'] : '';
            
            $publicatie = isset($_POST['publicatie']) ? $_POST['publicatie'] : '';
                     
	    $captchaObj = $this->_setupCaptcha();
               
	    if ($this->getRequest()->isPost()) {    		
    		// If the form submission is valid, then send out the email
    		if ($this->_validateBestelFormSubmission($captchaObj)) {
		    $this->sendBestelEmailNotification($naam,$voornaam,$email,$straat,$gemeente,$postcode,$land,$publicatie);
	            $url = WEB_ROOT."/".SIMPLE_CONTACT_FORM_PAGE_PATH."thankyou";
                    $this->_helper->redirector->goToUrl($url);                 
    		}
	    }
	    
	    // Render the HTML for the captcha itself.
	    // Pass this a blank Zend_View b/c ZF forces it.
            if ($captchaObj) {
                $captcha = $captchaObj->render(new Zend_View);
            } else {
                $captcha = '';
            }

            $this->view->assign(compact('naam','voornaam','email','straat','postcode','gemeente','publicatie','land','captcha'));
        }
	
	public function thankyouAction()
	{
		
	}
	
	protected function _validateFormSubmission($captcha = null)
	{
	    $valid = true;
	    $msg = $this->getRequest()->getPost('message');
            $name = $this->getRequest()->getPost('name');
	    $email = $this->getRequest()->getPost('email');
            $motivatie = $this->getRequest()->getPost('motivatie');
            $aanvraag = $this->getRequest()->getPost('aanvraag');
            
	    // ZF ReCaptcha ignores the 1st arg.
	    if ($captcha and !$captcha->isValid('foo', $_POST)) {
            $this->_helper->flashMessenger(__('De CAPTCHA is niet juist.'));
            $valid = false;
	    } else if (empty($msg)) {
            $this->_helper->flashMessenger(__('Je bent het bericht vergeten.'));
            $valid = false;
	    } else if($aanvraag=="true"){
                if (empty($motivatie)) {
                    $this->_helper->flashMessenger(__('Je motivatie ontbreekt.'));
                    $valid = false;
                }
            } else if (empty($name)) {
            $this->_helper->flashMessenger(__('Je naam ontbreekt.'));
            $valid = false;
            } else if (!Zend_Validate::is($email, 'EmailAddress')) {
            $this->_helper->flashMessenger(__('Je e-mailadres is niet geldig.'));
            $valid = false;
	    } 
	    return $valid;
	}
        
        protected function _validateBestelFormSubmission($captcha = null)
	{
	    $valid = true;
	    $naam = $this->getRequest()->getPost('naam');
            $voornaam = $this->getRequest()->getPost('voornaam');
            $straat = $this->getRequest()->getPost('straat');
            $gemeente = $this->getRequest()->getPost('gemeente');
            $postcode = $this->getRequest()->getPost('postcode');
            $land = $this->getRequest()->getPost('land');
	    $email = $this->getRequest()->getPost('email');
            $publicatie = $this->getRequest()->getPost('publicatie');
            
	    // ZF ReCaptcha ignores the 1st arg.
	    if ($captcha and !$captcha->isValid('foo', $_POST)) {
            $this->_helper->flashMessenger(__('De CAPTCHA is niet juist.'));
            $valid = false;
	    } else if (!Zend_Validate::is($email, 'EmailAddress')) {
            $this->_helper->flashMessenger(__('Je e-mailadres is niet geldig.'));
            $valid = false;
	    } else if (empty($naam)) {
            $this->_helper->flashMessenger(__('Je naam ontbreekt.'));
            $valid = false;
	    } else if (empty($voornaam)) {
            $this->_helper->flashMessenger(__('Je voornaam ontbreekt.'));
            $valid = false;
	    } else if (empty($straat)) {
            $this->_helper->flashMessenger(__('Je straat en nummer ontbreken.'));
            $valid = false;
	    } else if (empty($gemeente)) {
            $this->_helper->flashMessenger(__('Je gemeente ontbreekt.'));
            $valid = false;
	    } else if (empty($postcode)) {
            $this->_helper->flashMessenger(__('Je postcode ontbreekt.'));
            $valid = false;
	    } else if (empty($land)) {
            $this->_helper->flashMessenger(__('Je land ontbreekt.'));
            $valid = false;
	    }
	    
	    return $valid;
	}

    protected function _setupCaptcha()
    {
        return Omeka_Captcha::getCaptcha();
    }
	
    protected function sendEmailNotification($formEmail, $formName, $formMessage) {
		
		//notify the admin
		//use the admin email specified in the plugin configuration.
        $forwardToEmail = get_option('simple_contact_form_forward_to_email');
        if (!empty($forwardToEmail)) {
            $mail = new Zend_Mail();
            $mail->setBodyText(get_option('simple_contact_form_admin_notification_email_message_header') . "\n\n" . $formMessage);
            $mail->setFrom($formEmail, $formName);
            $mail->addTo($forwardToEmail);
            $mail->setSubject(get_option('site_title') . ' - ' . get_option('simple_contact_form_admin_notification_email_subject'));
            $mail->send();		
        }

        //notify the user who sent the message
        $replyToEmail = get_option('simple_contact_form_reply_from_email');
        if (!empty($replyToEmail)) {
            $mail = new Zend_Mail();
            $mail->setBodyText(get_option('simple_contact_form_user_notification_email_message_header') . "\n\n" . $formMessage);
            $mail->setFrom($replyToEmail);
            $mail->addTo($formEmail, $formName);
            $mail->setSubject(get_option('site_title') . ' - ' . get_option('simple_contact_form_user_notification_email_subject'));
            $mail->send();
        }
    }
    
     protected function sendBestelEmailNotification($naam,$voornaam,$email,$straat,$gemeente,$postcode,$land,$publicatie) {
	$message= "<h3>Bestelling publicaties</h3>";
        $message .= $voornaam." ".$naam."<br><br>";
        $message .= "Adres:<br>";
        $message .= $straat."<br>";
        $message .= $postcode." ".$gemeente."<br>";
        $message .= $land."<br><br>";
        $message .= "<h5>Publicaties</h5><table> <tr><th>Titel</th><th>Aantal</th><th>Bedrag</th></tr>";
        foreach( $publicatie as $key => $value) {
                $message .= "<tr><td>".$key."</td><td>".$value['aantal']."</td><td>".$value['bedrag']." &euro</td></tr>";
                echo $message;
        }
        $message .= "</table>";
        
        //notify the admin
	//use the admin email specified in the plugin configuration.
        $forwardToEmail = get_option('simple_contact_form_forward_to_email');
        if (!empty($forwardToEmail)) {
            $mail = new Zend_Mail();
            $mail->setBodyText(get_option('simple_contact_form_admin_notification_email_message_header') . "\n\n" . $message);
            $mail->setFrom($email, $voornaam." ".$naam);
            $mail->addTo($forwardToEmail);
            $mail->setSubject(get_option('site_title') . ' - ' . get_option('simple_contact_form_admin_notification_email_subject'));
            $mail->send();		
        }

        //notify the user who sent the message
        $replyToEmail = get_option('simple_contact_form_reply_from_email');
        if (!empty($replyToEmail)) {
            $mail = new Zend_Mail();
            $mail->setBodyText(get_option('simple_contact_form_user_notification_email_message_header') . "\n\n" . $message);
            $mail->setFrom($replyToEmail);
            $mail->addTo($email, $voornaam." ".$naam);
            $mail->setSubject(get_option('site_title') . ' - ' . get_option('simple_contact_form_user_notification_email_subject'));
            $mail->send();
        }
    }
}
