<?php
/**
 * Created by PhpStorm.
 * User: user
 * Date: 15.12.2016
 * Time: 19:37
 */
class SimpleResult extends aFactorResult {
    public function __toString() {
        return $this -> getId().': '.$this -> result();
    }
}