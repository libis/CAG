<?php

function newsletter_add_unsubscribe($email){
    $hash = md5(get_option('newsletter_salt').$email);
    $url = "<a href='".WEB_ROOT."/newsletter/index/unsubscribe?id=".$hash."&email=".$email."'>Schrijf je uit voor de nieuwsbrief.</a>";    
    return $url;   
}

?>
