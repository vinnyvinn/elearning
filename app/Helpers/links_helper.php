<?php
function active_link($path, $return = '', $alt = false) {
    $link = current_url();
    if(strpos($link, $path)) {
        return $return;
    }
    return $alt ? $alt : '';
}