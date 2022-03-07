<?php

use App\Core\Hooks;

/* START HOOKS: actions and filters */

function add_action($tag, $callback, $priority = 10, $accepted_args = 1){
    return Hooks::getInstance()->add_action($tag, $callback, $priority, $accepted_args);
}

function do_action($tag, ...$args){
    return Hooks::getInstance()->do_action($tag, ...$args);
}

function remove_action($tag, $function_to_remove, $priority = NULL){
    return Hooks::getInstance()->remove_action($tag, $function_to_remove, $priority);
}

function has_action($tag, $function_to_check = NULL){
    return Hooks::getInstance()->has_action($tag, $function_to_check);
}

function did_action($tag){
    return Hooks::getInstance()->did_action($tag);
}

function add_filter($tag, $function_to_add, $priority = 10, $accepted_args = 1){
    return Hooks::getInstance()->add_filter($tag, $function_to_add, $priority, $accepted_args);
}

function remove_filter($tag, $function_to_remove, $priority = 10){
    return Hooks::getInstance()->remove_filter($tag, $function_to_remove, $priority);
}

function has_filter($tag, $function_to_check){
    return Hooks::getInstance()->has_filter($tag, $function_to_check);
}

function apply_filters($tag, ...$args){
    return Hooks::getInstance()->apply_filters($tag, ...$args);
}