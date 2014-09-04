<?php
class Newsletter_IndexController extends Omeka_Controller_AbstractActionController
{
        public function indexAction(){
            $db = get_db();
            $itemType = $db->getTable('ItemType')->findByName('Newsletter-contact');
            $elements = $db->getTable('Element')->findByItemType($itemType->id);
            $contacts = get_records('Item',array('type'=>'Newsletter-contact'),9999999);
            $pages = $db->getTable('SimplePagesPage')->findAll();
            
            //create headers for handsontable
            $colheaders='["id",';
            $columns = '[{data: "id", readOnly:true},';
            $size_elements = count($elements);
            $counter =1;
            foreach($elements as $element):
                if($counter != $size_elements):
                    $colheaders .= '"'.$element->name.'",';
                    if($element->name == 'Nieuwsbrief' or $element->name == 'Activiteiten'):
                        $columns .= '{data: "'.$element->name.'",type:"checkbox"},';
                    else:
                        $columns .= '{data: "'.$element->name.'"},';
                    endif;
                    
                    $counter++;
                else:
                    $colheaders .= '"'.$element->name.'"';
                    if($element->name == 'Nieuwsbrief' or $element->name == 'Activiteiten'):
                        $columns .= '{data: "'.$element->name.'",type:"checkbox"}';
                    else:
                        $columns .= '{data: "'.$element->name.'"}';
                    endif;
                endif;    
            endforeach;    
            $colheaders .=']';
            $columns .= "]";
            
            //create data table for handsontable
            $data='[';      
            $counterContact =1;
            $size_contacts = count($contacts);
            foreach($contacts as $contact):
                
               
                $counter =1;
                $data .= "{id:".$contact->id." ,";
                foreach($elements as $element):
                    if($counter != $size_elements):
                        $data .= $element->name.':"'.str_replace('"',"'",metadata($contact,array('Item Type Metadata',$element->name))).'",'; 
                        $counter++;
                    else:
                        $data .= $element->name.':"'.str_replace('"',"'",metadata($contact,array('Item Type Metadata',$element->name))).'"';
                    endif;
                endforeach;
                
                if($counterContact != $size_contacts):
                    $data .='},';
                    $counterContact++;
                else:
                    $data .='}';
                endif;
                
            endforeach;
            $data .=']';
                       
            $this->view->assign(compact('contacts','elements','data','colheaders','columns','pages'));
        }
        
        public function saveAction(){
            $db = get_db();
            //var_dump($_POST);
            $changes = $_POST['changes'];
            $data = $_POST['table'];
            foreach($changes as $change){
                $row = $change[0];
                $row = $data[$row]['id'];
                $col = $change[1];
                $update = $change[3];
                
                //get record to save
                $item = get_record_by_id('Item',$row);
                //get element id(s) of the changes
                $element = $db->getTable('Element')->findByElementSetNameAndElementName('Item Type Metadata',$col);
                $elementtext = $db->getTable('ElementText')->findByRecord($item);
                
                $exists= false;
                foreach($elementtext as $text){
                    if($text->element_id == $element->id){
                        $text->text = $update;
                        $text->save();
                        $exists = true;
                    }
                }
                if(!$exists){
                    $text = new ElementText();
                    $text->record_id = $item->id;
                    $text->record_type = 'Item';
                    $text->element_id = $element->id;
                    $text->text = $update;
                    $text->save();
                }         
                
            }
            //echo "set update: ".$update.", op rij ".$row." en kolom ".$col;
        }
        
        public function deleteAction(){
            $db = get_db();
            //var_dump($_POST);
            $row = $_POST['index'];
            $data = $_POST['table'];
                        
            $id = $data[$row]['id'];
                          
            //get record to save
            $item = get_record_by_id('Item',$id);
            
            $item->delete();
        }
        
        public function sendAction(){
            $page = $_POST['page'];
            $nieuwsbrief= false;
            $activiteiten = false;
            $allen=false;
            
            if(isset($_POST['Nieuwsbrief'])){
                $nieuwsbrief=true;
            }
            if(isset($_POST['Activiteiten'])){
                $activiteiten=true;
            }
            if(isset($_POST['Activiteiten']) && isset($_POST['Nieuwsbrief'])){
                $allen=true;
            }
                   
            $contacts = get_records('Item',array('type'=>'Newsletter-contact'));
            $page = get_record_by_id('SimplePagesPage',$page);
            
            $message_head = '
                <div class="wrapper" width="100%" style="color: #006c68;font:14px/20px Calibri,Verdana,Arial,Helvetica,sans-serif;">
                <div class="frame" width="400" style="margin:0 auto;width:600px;">
		
		<p class="explanation" style="font:11px/15px Calibri, Verdana, Arial, Helvetica, sans-serif; color:#999999;" align="center" valign="top">
			Dit is de tweemaandelijkse nieuwsbrief van het Centrum Agrarische Geschiedenis vzw.</a>
		';

            $message_head_2 = '</p>
		
		<div class="content" class="top" style="border:2px solid #006c68;padding:10px;">
                    <h2 style="font-weight:bold; line-height:31px; color:#006c68;">
				Nieuwsbrief   
			</h2>
			
			
			<!-- facebook logo -->															  
			<p>
                             <a href="#"><img src="'.WEB_PUBLIC_THEME.'/CAG/images/cag_logo.png" alt="CAG_email" width="165" height="70" border="0" /></a>
                            <a href="https://www.facebook.com/pages/Centrum-Agrarische-Geschiedenis/127938257279959?fref=ts" target="_blank">
					<span style="color:#fff; text-decoration:none;">
						<img style="" src="'.WEB_PUBLIC_THEME.'/CAG/images/FB_Logo.png" border="0"; width="149" height="52" />
					</span>
				</a>
			</p>
			
			<h2 width="233" valign="top" style="line-height:20px; color:#fff;background:#006c68;padding:5px;">
				'.$page->title.'
			</h2>
            ';
            
            $message_foot = '                	
                </div>                
                <div class="footer">
		<a href="#"><img src="'.WEB_PUBLIC_THEME.'/CAG/images/bg-footer_.gif" alt="www.HetVirtueleLand.be" width="600" height="252" border="0" /></a>
                </div>
                </div>
                </div>
            ';
            
            $aantal = 0;            
            
            // Create the Mailer using any Transport
            $mailer = Swift_Mailer::newInstance(
              Swift_SmtpTransport::newInstance('smtp.kuleuven.be')
            );
           
            //100 mails/30 seconden
            $mailer->registerPlugin(new Swift_Plugins_AntiFloodPlugin(100, 30));
            
            //loop through contacts
            foreach ($contacts as $contact){
                //skip if needed
                if(!$allen){
                    if($nieuwsbrief && 
                           metadata($contact,array('Item Type Metadata','Nieuwsbrief'))=='false'){
                        continue;
                    }
                    if($activiteiten && 
                           metadata($contact,array('Item Type Metadata','Activiteiten'))=='false'){
                        continue;
                    }
                }    
                $aantal++;
                
                //continue to send if all is set correctly
                $name= metadata($contact,array('Item Type Metadata','Naam'));
                $to_email = metadata($contact,array('Item Type Metadata','Email'));
                $from_email = get_option('newsletter_reply_from_email');
                
                $unsub = newsletter_add_unsubscribe($to_email);
                              
                // Create the message
                $message = Swift_Message::newInstance()

                  // Give the message a subject
                  ->setSubject("Nieuwsbrief - Het Virtuele Land: ".$page->title)

                  // Set the From address with an associative array
                  ->setFrom(array($from_email))

                  // Set the To addresses with an associative array
                  ->setTo(array($to_email => $name))

                  // Give it a body                       
                  ->setBody($message_head.$unsub.$message_head_2.$page->text.$message_foot,'text/html')
                        
                  // And optionally an alternative body
                  //->addPart('<q>Here is the message itself</q>', 'text/html')

                  // Optionally add any attachments
                 // ->attach(Swift_Attachment::fromPath('my-document.pdf'))
                  ;
                  // Send the message
                 $result = $mailer->send($message);
            }
            $this->view->assign(compact('page','contacts','aantal'));
            
        }
        
	public function registerAction()
	{
            $db = get_db();
            $itemType = $db->getTable('ItemType')->findByName('Newsletter-contact');
            $elements = $db->getTable('Element')->findByItemType($itemType->id);
            
            $sticky = array();
            
            foreach($elements as $element):
                $sticky[$element->name] = isset($_POST[$element->name]) ? $_POST[$element->name] : '';
            endforeach;
	                
	    $captchaObj = $this->_setupCaptcha();

	    if ($this->getRequest()->isPost()) {
    		// If the form submission is valid, then send out the email               
    		if ($this->_validateFormSubmission($captchaObj,$elements)) {
                    
                    //CREATE & SAVE ITEM
                    $item = new Item();
                    $item->item_type_id = $itemType->id;
                    $item->public = 0;
                    $item->save();
                    foreach($elements as $element):
                        $text = new ElementText();
                        $text->record_id = $item->id;
                        $text->record_type = 'Item';
                        $text->element_id = $element->id;
                        if($this->getRequest()->getPost($element->name) == 'on'):                      
                            //checkbox checked
                            $text->text = 'true';
                        elseif($this->getRequest()->getPost($element->name) != null):
                            $text->text = $this->getRequest()->getPost($element->name);                        
                        else:
                            //checkbox unchechecked
                            $text->text = 'false';
                        endif;
                        $text->save();
                    endforeach;
                    $this->sendEmailNotification($this->getRequest()->getPost('Email'), $this->getRequest()->getPost('Naam'));
                    
                    $this->_helper->redirector('thankyou');
    		}
                
	    }

	    // Render the HTML for the captcha itself.
	    // Pass this a blank Zend_View b/c ZF forces it.
		if ($captchaObj) {
		    $captcha = $captchaObj->render(new Zend_View);
		} else {
		    $captcha = '';
		}

		$this->view->assign(compact('elements','sticky','captcha'));
	}

	public function thankyouAction()
	{

	}
        
        public function unsubscribeAction(){
            if(isset($_GET['id']) && isset($_GET['email'])):                
                if(md5(get_option('newsletter_salt').$_GET['email']) == $_GET['id']):
                    $db = get_db();
                    $select = $db->getTable('ElementText')->getSelect()
                            ->where('element_texts.text = ?', (string)$_GET['email']);
                    $text = $db->getTable('ElementText')->fetchObject($select);                    
                    $item = get_record_by_id('Item',$text->record_id);
                    
                    $item->delete();
                   
                    $html = "Het uitschrijven is gelukt. Je bent niet langer geregistreerd op onze nieuwsbrief.";
                    
                endif;              
            else:                        
                $html = false;  
                if ($this->getRequest()->isPost()) {
                    // If the form submission is valid, then send out the email               
                    $email = $this->getRequest()->getPost('email');
                    if (!Zend_Validate::is($email, 'EmailAddress')) {
                        $this->_helper->flashMessenger(__('Je e-mailadres is niet geldig.'));                        
                    }else{
                        $message = "<p>Beste,</p>
                                    <p>Klik op onderstaande link om onze nieuwsbrief niet langer te ontvangen.</p>
                                    <p>".newsletter_add_unsubscribe($email)."</p>";
                        $this->sendEmailUnsubscribe($email,$message);
                        $html = "<p>We hebben een e-mail verstuurd naar het opgegeven adres, hierin bevindt zich de nodige informatie om je uit te schrijven voor onze nieuwsbrief.</p>";
                    }
                }
                $captchaObj = $this->_setupCaptcha();
                // Render the HTML for the captcha itself.
                // Pass this a blank Zend_View b/c ZF forces it.
                if ($captchaObj) {
                    $captcha = $captchaObj->render(new Zend_View);
                } else {
                    $captcha = '';
                }
                
            endif;
            $this->view->assign(compact('html'));
        }

	protected function _validateFormSubmission($captcha = null,$elements)
	{
	    	$valid = true;
                
                foreach($elements as $element):
                    if($element->name == "Nieuwsbrief" || $element->name == "Activiteiten"):
                        
                    else:
                        if(!$this->getRequest()->getPost($element->name)):
                            $valid=false;
                            $this->_helper->flashMessenger(__('Alle invulvelden zijn verplicht'));
                            break;
                        endif;
                    endif;
                endforeach;
                
                //check checkboxes
                if(!$this->getRequest()->getPost('Nieuwsbrief') && !$this->getRequest()->getPost('Activiteiten')){
                    $valid = false;
                    $this->_helper->flashMessenger(__('Je moet minsten één type brief aanvinken'));
                }
                
                //check if email already exists
                $email = $this->getRequest()->getPost('Email');
                $db = get_db();
                $select = $db->getTable('ElementText')->getSelect()
                        ->where('element_texts.text = ?', (string)$email);
                $text = $db->getTable('ElementText')->fetchObject($select);
               
                if($text){
                    $valid = false;
                    $this->_helper->flashMessenger(__('Dit e-mailadres is reeds ingeschreven voor onze nieuwsbrief'));
                }
                
                // ZF ReCaptcha ignores the 1st arg.
                if ($captcha and !$captcha->isValid('foo', $_POST)) {
                            $this->_helper->flashMessenger(__('Your CAPTCHA submission was invalid, please try again.'));
                            $valid = false;
                }
                return $valid;
	}

    protected function _setupCaptcha()
    {
        return Omeka_Captcha::getCaptcha();
    }

    protected function sendEmailNotification($formEmail, $formName) {

        //setup smtp
        $tr = new Zend_Mail_Transport_Smtp('smtp.kuleuven.be');
        Zend_Mail::setDefaultTransport($tr);

        //notify the admin
        //use the admin email specified in the plugin configuration.
        $forwardToEmail = get_option('newsletter_forward_to_email');
        
        $adminMessage = $formName.", ".$formEmail." <p> heeft zich geregistreerd voor een van de nieuwsbrieven.</p>";
        $formMessage = "<h3>Bedankt ".$formName.",</h3><p>Je bent met succes geregistreerd.</p>";

        if (!empty($forwardToEmail)) {
            $mail = new Zend_Mail();
            $mail->setBodyHtml($adminMessage);
            $mail->setFrom($formEmail, $formName);
            $mail->addTo($forwardToEmail);
            $mail->setSubject(get_option('site_title') . ' - ' . get_option('newsletter_admin_notification_email_subject'));
            $mail->send();
        }

        //notify the user who sent the message
        $replyToEmail = get_option('newsletter_reply_from_email');
        if (!empty($replyToEmail)) {
            $mail = new Zend_Mail();
            $mail->setBodyHtml($formMessage);
            $mail->setFrom($replyToEmail);
            $mail->addTo($formEmail, $formName);
            $mail->setSubject(get_option('site_title') . ' - ' . get_option('newsletter_user_notification_email_subject'));
            $mail->send();
        }
    }
    
    protected function sendEmailUnsubscribe($formEmail, $message) {

        //setup smtp
        $tr = new Zend_Mail_Transport_Smtp('smtp.kuleuven.be');
        Zend_Mail::setDefaultTransport($tr);
        
        $replyToEmail = get_option('newsletter_reply_from_email');
        if (!empty($replyToEmail)) {
            $mail = new Zend_Mail();
            $mail->setBodyHtml($message);
            $mail->setFrom($replyToEmail);
            $mail->addTo($formEmail);
            $mail->setSubject(get_option('site_title') . ' - Uitschrijven nieuwsbrief');
            $mail->send();
        }
    }
}
