<?php
/**
 * Created by PhpStorm.
 * User: user
 * Date: 15.12.2016
 * Time: 18:14
 */
interface iFactor {
    /**
     * @param string $name
     * @param $val
     */
    public function setParam($name, $val);

    /**
     * @param string $name
     * @return mixed
     */
    public function getParam($name);

    /**
     * @param iFactorable $obj
     * @return bool
     */
    public function checkApplicability(iFactorable $obj);
    /**
     * @param iFactorable $obj
     * @return string - name of the apply method in the corresponding interface
     */
    public function getApplyMethodName(iFactorable $obj = null);

    /**
     * @param iFactorable $obj
     * @return string - realisation of the factor
     */
    public function apply(iFactorable $obj);

    /**
     * @param $rez
     * @param iFactorable $obj
     */
    public function storeResult($rez, iFactorable $obj);

    /**
     * @param iFactorable $obj
     * @return int
     */
    public function calculateWeight(iFactorable $obj);

    /**
     * @return iFactorResult[]
     */
    public function getResult();

    /**
     * @param iFactor $factor
     * @return iFactor
     */
    public function multiplyBy(iFactor $factor);

    /**
     * @param string $rez
     * @return iFactorResult
     */
    public function addNewPossibleValue($rez);

    /**
     * Gives all fixed realisations of the factor
     * @return string[]
     */
    public function getPossibleValues();
    /**
     * Must check so that all needed combinations of factors are present in result
     * After factorizing none of the zero results are contained in the result array
     * @return iFactorResult[]
     */
    public function normalizeFactoringResult();

    /**
     * @param array[] $data
     * @return iFactorResult[]
     */
    public function factorizeData(array $data);

    /**
     * @return string
     */
    public function getName();
}