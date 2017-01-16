<?php
/**
 * Created by PhpStorm.
 * User: user
 * Date: 15.12.2016
 * Time: 21:25
 */
spl_autoload_register(function($class) {
    if (file_exists($class.'.php')) {
        require_once($class.'.php');
    }
    return false;
});
