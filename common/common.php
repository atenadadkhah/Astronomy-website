<?php
date_default_timezone_set("ASIA/Tehran");
function sanitize($input){
    return trim(strip_tags($input));
}
function hashPass($password,$mode="sha1"){
    return $mode($password);
}
