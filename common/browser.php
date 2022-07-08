<?php
function getUserBrowser($userAgent){
    if (strpos($userAgent,"Edg")){
        return " Microsoft Edge";
    }
    else if (strpos($userAgent,"Opera") || strpos($userAgent,"OPR/")){
        return "Opera";
    }
    else if (strpos($userAgent,"Chrome")){
        return "Chrome";
    }
    else if (strpos($userAgent,"Firefox")){
        return "Firefox";
    }
    else if (strpos($userAgent,"Safari")){
        return "Safari";
    }

    else  if (strpos($userAgent,"MSIE") || strpos($userAgent,"Trident/7")){
        return "IE";
    }

    else{
        return "else";
    }
}