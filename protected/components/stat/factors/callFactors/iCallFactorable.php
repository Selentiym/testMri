<?php
/**
 * Created by PhpStorm.
 * User: user
 * Date: 14.01.2017
 * Time: 15:22
 */
interface iCallFactorable {
    /**
     * @return bool whether the call led to an assignment
     */
    public function getAssigned();

    /**
     * @return bool whether the call led to a completed research and was rewarded
     */
    public function getVerified();
}