<?php
/**
 * Created by PhpStorm.
 * User: user
 * Date: 16.12.2016
 * Time: 18:32
 */
abstract class aFactorSet extends aFactor {
    /**
     * @var iFactor[] $_factors
     */
    private $_factors = [];

    public function __construct($factors = []) {
        /**
         * I want to simplify the factor set by deleting
         */
        /*$toSave = [];
        foreach ($factors as $factor) {
            if ($factor instanceof aFactorSet) {
                $toSave = array_merge($toSave, $factor -> getFactors());
            }
        }*/
        $toSaveFactors = [];
        foreach ($factors as $factor) {
            if ($factor instanceof aFactorSet) {
                $temp = $factor -> getFactors();
            } else {
                $temp = [$factor];
            }
            foreach ($temp as $f) {
                $dupl = false;
                if (!in_array($f, $toSaveFactors)) {
                    array_push($toSaveFactors, $f);
                } else {
                    $dupl = true;
                }
            }
        }
        $this -> _factors = $toSaveFactors;
    }

    /**
     * @return iFactor[]
     */
    public function getFactors() {
        return $this -> _factors;
    }

    /**
     * @param iFactorable $obj
     * @return bool
     */
    public function checkApplicability(iFactorable $obj) {
        $rez = true;
        foreach ($this -> _factors as $factor) {
            $rez &= $factor -> checkApplicability($obj);
        }
        return $rez;
    }

    /**
     * @param iFactorable $obj
     * @return string
     */
    public function applyCore(iFactorable $obj) {
        /**
         * Apply all the accumulated factors one after another.
         * Order of factors is crucial!
         */
        $rez = '';
        foreach ($this -> _factors as $factor) {
            $rez = $this -> appendResult($factor -> apply($obj), $rez);
        }
        return $rez;
    }

    /**
     * Defines the rule for appending successive factor calculation result to the current result
     * @param string $toAdd
     * @param string $rez
     * @return string
     */
    public function appendResult($toAdd, $rez) {
        return $rez.' '.$toAdd;
    }
    public function multiplyBy(iFactor $factor) {
        return new SimpleFactorSet([$this, $factor]);
        //throw new StatisticalException('No multiply for FactorSet implemented!');
    }

    public function addStringToArray(&$arr, $str){
        if (!empty($arr)) {
            foreach ($arr as $key => $val) {
                $arr[$key] = $this -> appendResult($str, $val);
            }
        } else {
            $arr = [$this -> appendResult($str, '')];
        }
    }
    public function getPossibleValues() {

        /**
         * direct product of initial factors' results
         */
        $factors = $this -> _factors;
        $total = count($factors);
        $rez = [];
        //Run through all factors
        //for ($level = $total-1; $level >= 0 ; $level --) {
        for ($level = 0; $level < $total ; $level ++) {
            //Save the previous step result
            $initial = $rez;
            //Recreate result container
            $rez = [];
            $values = $factors[$level] -> getPossibleValues();
            if (!empty($initial)) {
                foreach ($initial as $el) {
                    foreach ($values as $value) {
                        $rez[] = $this->appendResult($value, $el);
                    }
                }
            } else {
                foreach ($values as $value) {
                    $rez[] = $this->appendResult($value, '');
                }
            }
            /*$values = $factors[$level] -> getPossibleValues();
            foreach ($values as $value) {
                //Add all next level values to the previous result
                $temp = $initial;
                self::addStringToArray($temp, $value);
                $rez = array_merge($rez, $temp);
            }*/
        }
        return $rez;
    }

    /**
     * @return string
     */
    public function getName() {
        return array_map(function ($f) { return $f -> getName();}, $this -> getFactors());
    }
}