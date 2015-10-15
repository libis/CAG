<?php

function newsletter_add_unsubscribe(){
    $url = "<p class='explanation' style='font:11px/15px Calibri, Verdana, Arial, Helvetica, sans-serif; color:#999999;' align='center' valign='top'>
			Dit is een nieuwsbrief van het Centrum Agrarische Geschiedenis vzw.</a></p>";
    $url .= "<p class='explanation' style='font:11px/15px Calibri, Verdana, Arial, Helvetica, sans-serif; color:#999999;' align='center'>Indien je deze mail niet meer wenst te ontvangen, klik dan op <a href='".WEB_ROOT."/newsletter/index/unsubscribe"."'>deze link.</a>";    
    return $url;   
}

?>
