<?php
/**
 * Created by PhpStorm.
 * User: user
 * Date: 16.02.2017
 * Time: 23:00
 */
interface iCallTrackerFactorable extends iCalledFactorable {
    /**
     * @return int
     */
    public function getFormed();
}