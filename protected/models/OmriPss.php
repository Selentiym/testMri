<?php
/**
 * Created by PhpStorm.
 * User: user
 * Date: 06.10.2016
 * Time: 20:50
 */
class OmriPss {
    public static $pss;
    public static function pss(){
        if (!self::$pss) {
            self::$pss = require_once("omri.pss.php");
        }
        return self::$pss;
    }
}