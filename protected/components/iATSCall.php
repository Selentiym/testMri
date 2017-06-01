<?php
/**
 * Created by PhpStorm.
 * User: user
 * Date: 26.01.2017
 * Time: 15:27
 */
interface iATSCall {
    /**
     * @return int UNIX timestamp of the call
     */
    public function getCallTime();

    /**
     * @param mixed $external
     * @return int|string line identification(not just table id!) which this call corresponds to
     */
    public function getLineI($external = null);

    /**
     * @return int
     */
    public function getEnterId();
}