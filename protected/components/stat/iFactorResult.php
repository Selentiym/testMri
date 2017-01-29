<?php
/**
 * Created by PhpStorm.
 * User: user
 * Date: 15.12.2016
 * Time: 18:25
 */

/**
 * interface iFactorResult
 * Usually stores data about one fixed realisation of the iFactor
 * Something like a simple array
 */
interface iFactorResult {
    /**
     * @param iFactorable $obj
     */
    public function addObject(iFactorable $obj);

    /**
     * Result of this group of objects
     * @return int
     */
    public function result();

    /**
     * All the objects which correspond to this fixed iFactor realisation
     * @return iFactorable[]
     */
    public function giveObjects();

    /**
     * @return string
     */
    public function getId();

    /**
     * @param string $val
     */
    public function setId($val);

    /**
     * @param iFactor $factor
     */
    public function setFactor(iFactor $factor);

    /**
     * gives the associated factor
     * @return iFactor
     */
    public function getFactor();

    /**
     * @return int number of objects with the corresponding value after factorizing
     */
    public function getObjectsNumber();
}