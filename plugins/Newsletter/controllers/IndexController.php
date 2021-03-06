<?php
class Newsletter_IndexController extends Omeka_Controller_AbstractActionController
{
    public function indexAction(){
        $db = get_db();
        $pages = $db->getTable('SimplePagesPage')->findAll();
        $this->view->assign(compact('pages'));
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

        //$contacts = get_records('Item',array('type'=>'Newsletter-contact'),9999);
        $page = get_record_by_id('SimplePagesPage',$page);

        $message_head = '
            <table width="600" cellspacing="0" cellpadding="0">
            <div class="wrapper" width="600" style="">
            <style type="text/css">
                /* /\/\/\/\/\/\/\/\/ RESET STYLES /\/\/\/\/\/\/\/\/ */
                body{
                    margin:0;
                    padding:0;
                }

                img{
                    border:0 none;
                    height:auto;
                    line-height:100%;
                    outline:none;
                    text-decoration:none;
                }

                a img{
                    border:0 none;
                }

                .imageFix{
                    display:block;
                }

                table, td{
                    border-collapse:collapse;
                }

                #bodyTable{
                    height:100% !important;
                    margin:0;
                    padding:0;
                    width:100% !important;
                }
            </style>
            <div class="frame" width="600" style="width:600px;color: #006c68;font:13px Calibri,Verdana,Arial,Helvetica,sans-serif;">';

            $message_head_2 = '<table width="576" style="font:13px Calibri, Verdana, Arial, Helvetica, sans-serif;border:2px solid #006c68;padding:10px;"cellspacing="0" cellpadding="0">
		          <div class="content" class="top" >
                  <h2 style="font-weight:bold; line-height:31px; color:#006c68;padding5px;">
                     Nieuwsbrief
                  </h2>
      			      <table style="width:100%;">
                    <tr>
            			    <td><a href="#"><img src="'.WEB_PUBLIC_THEME.'/CAG/images/cag_logo.png" alt="CAG_email" width="165" height="70" border="0" /></a></td>
                      <td style="text-align:right;">
                        <a href="https://www.facebook.com/pages/Centrum-Agrarische-Geschiedenis/127938257279959?fref=ts" target="_blank">
                					<span style="color:#fff; text-decoration:none;">
                						<img style="float:right;" src="'.WEB_PUBLIC_THEME.'/CAG/images/FB_Logo.png" border="0"; width="149" height="52" />
                					</span>
            				    </a>
            			    </td>
                    </tr>
            			</table>
            			<h2 width="233" valign="top" style="line-height:20px; color:#fff;background:#006c68;padding:5px;clear:both;">
            				 	&nbsp;'.$page->title.'
            			</h2>';

      // Create the Mailer using any Transport
      $mailer = Swift_Mailer::newInstance(
        Swift_SmtpTransport::newInstance('smtp.kuleuven.be')
      );

      //100 mails/30 seconden
      $mailer->registerPlugin(new Swift_Plugins_AntiFloodPlugin(100, 30));

      //send test mail and return to index
      if(isset($_POST['send_test'])){
         $unsub = newsletter_add_unsubscribe(get_option('newsletter_reply_from_email'));
          $message_foot = '
          </div>
          <div class="footer">
<a href="#"><img src="'.WEB_PUBLIC_THEME.'/CAG/images/bg-footer_.gif" alt="www.HetVirtueleLand.be" width="600" height="252" border="0" /></a>
          </div>'.$unsub.'</table>
          </div>
          </div></table>';

          // Create the message
          $message = Swift_Message::newInstance()
            // Give the message a subject
            ->setSubject("Nieuwsbrief - Het Virtuele Land: ".$page->title)
            // Set the From address with an associative array

            ->setFrom(array(get_option('newsletter_reply_from_email')))
            // Set the To addresses with an associative array
            ->setTo(array(get_option('newsletter_reply_from_email')))
            // Give it a body
            ->setBody($message_head.$message_head_2.$page->text.$message_foot,'text/html');
            // Send the message
           $result = $mailer->send($message);

        }else{
            $from_email = get_option('newsletter_reply_from_email');

            $unsub = newsletter_add_unsubscribe();

            $message_foot = '
            </div>
            <div class="footer">
<a href="#"><img src="'.WEB_PUBLIC_THEME.'/CAG/images/bg-footer_.gif" alt="www.HetVirtueleLand.be" width="600" height="252" border="0" /></a>
            </div>'.$unsub.'</table>
            </div>
            </div></table>';

            // Create the message
            $message = Swift_Message::newInstance()
                // Give the message a subject

                ->setSubject("Nieuwsbrief - Het Virtuele Land: ".$page->title)
                ->setFrom(array($from_email))
                ->setTo(get_option('newsletter_mailing_list').'@'.get_option('newsletter_listserv'))
                ->setBody($message_head.$message_head_2.$page->text.$message_foot,'text/html');
            // Send the message
            $result = $mailer->send($message);
        }

        $this->view->assign(compact('page'));
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
            //send to listserv
            $this->sendListservEmail(filter_var($this->getRequest()->getPost('Email'),FILTER_SANITIZE_EMAIL),filter_var($this->getRequest()->getPost('Naam'),FILTER_SANITIZE_STRING),'add');
            //send to subscriber
            $this->sendEmailNotification(filter_var($this->getRequest()->getPost('Email'),FILTER_SANITIZE_EMAIL), filter_var($this->getRequest()->getPost('Naam'),FILTER_SANITIZE_STRING));

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
      $html = false;
      if ($this->getRequest()->isPost()) {
          // If the form submission is valid, then send out the email
          $email = $this->getRequest()->getPost('email');
          if (!Zend_Validate::is($email, 'EmailAddress')) {
              $this->_helper->flashMessenger(__('Je e-mailadres is niet geldig.'));
          }else{
              //send to listserv
              $this->sendListservEmail($email,"",'delete');
              $html = "Het uitschrijven is gelukt. Je bent niet langer geregistreerd op onze nieuwsbrief.";
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

      $this->view->assign(compact('html'));
  }

	protected function _validateFormSubmission($captcha = null,$elements)
	{
	    	$valid = true;

        if(!$this->getRequest()->getPost('Email') || !$this->getRequest()->getPost('Naam')){
            $valid = false;
            $this->_helper->flashMessenger(__('Naam en email zijn verplichten velden'));
        }

        //check checkboxes
        if(!$this->getRequest()->getPost('Nieuwsbrief') && !$this->getRequest()->getPost('Activiteiten')){
            $valid = false;
            $this->_helper->flashMessenger(__('Je moet minsten één type brief aanvinken'));
        }

        if(!$this->getRequest()->getPost('privacy')){
  				$this->_helper->flashMessenger(__('Je hebt de privacy voorwaarden nog niet geaccepteerd.'));
  				$valid = false;
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

  protected function sendListservEmail($email, $name,$task='add',$changemail=null) {
      //setup smtp
      $tr = new Zend_Mail_Transport_Smtp('smtp.kuleuven.be');
      Zend_Mail::setDefaultTransport($tr);

      $message='';

      switch($task):
          case 'add':
              $message ='QUIET ADD '.get_option('newsletter_mailing_list').' '.$email.' '.$name;
              break;
          case 'delete':
              $message='QUIET DELETE '.get_option('newsletter_mailing_list').' '.$email;
              break;
          case 'change':
              $message='QUIET change '.get_option('newsletter_mailing_list').' '.$email.' '.$changemail;
              break;
          default:
              $message='';
      endswitch;

      //if($message !=''):
      $mail = new Zend_Mail();
      $mail->setBodyHtml($message);
      $mail->setFrom(get_option('newsletter_list_owner'));
      $mail->addTo('listserv@'.get_option('newsletter_listserv'));
      $mail->setSubject(get_option('site_title') . ' - ' . get_option('newsletter_user_notification_email_subject'));
      $mail->send();
      //endif;

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
