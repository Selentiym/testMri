<?php
/**
 * Created by PhpStorm.
 * User: user
 * Date: 15.12.2016
 * Time: 18:51
 */
abstract class aFactorResult implements iFactorResult {
    /**
     * @var iFactorable[]
     */
    private $_objects = [];
    /**
     * factor which the result is generated for
     * @var iFactor
     */
    private $_factor;
    /**
     * Stores the total weight of all saved iFactorable objects
     * @var int
     */
    private $_summary = 0;
    /**
     * @var string
     */
    private $_id = '';

    /**
     * aFactorResult constructor.
     * @param string $string
     * @param iFactor $fact
     */
    public function __construct($string, iFactor $fact) {
        $this -> _factor = $fact;
        $this -> _id = $string;
    }
    public function addObject(iFactorable $obj) {
        $this -> _objects[] = $obj;
        $this -> _summary += $this -> _factor -> calculateWeight($obj);
    }

    /**
     * @param iFactor $factor
     */
    public function setFactor(iFactor $factor) {
        if (!($this -> _factor instanceof iFactor)) {
            $this -> _factor = $factor;
        }
    }

    /**
     * @return iFactor
     */
    public function getFactor() {
        return $this -> _factor;
    }

    /**
     * @return string
     */
    public function getId() {
        return $this -> _id;
    }

    /**
     * @param string $val
     */
    public function setId($val)
    {
        if (!($this -> getId())) {
            $this -> _id = $val;
        }
    }
    /**
     * Result of this group of objects
     * @return int
     */
    public function result() {
        return $this -> _summary;
    }

    /**
     * All the objects which correspond to this fixed iFactor realisation
     * @return iFactorable[]
     */
    public function giveObjects() {
        return $this -> _objects;
    }
}