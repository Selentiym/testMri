<?php
/**
 * Created by PhpStorm.
 * User: user
 * Date: 15.01.2017
 * Time: 10:28
 */
class GDCallFactorableFactory extends SimpleGDFactory {
    public function buildNew() {
        return new GDCallFactorable();
    }
}