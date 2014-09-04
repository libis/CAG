<?php

function newsletter_add_unsubscribe($email){
    $hash = md5(get_option('newsletter_salt').$email);
    
			
    $url = "<p class='explanation' style='font:11px/15px Calibri, Verdana, Arial, Helvetica, sans-serif; color:#999999;' align='center'>Indien je deze mail niet meer wenst te ontvangen, klik dan op <a href='".WEB_ROOT."/newsletter/index/unsubscribe?id=".$hash."&email=".$email."'>deze link.</a>";    
    return $url;   
}

?>
