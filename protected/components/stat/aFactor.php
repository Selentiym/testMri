<?php
/**
 * Created by PhpStorm.
 * User: user
 * Date: 14.12.2016
 * Time: 19:47
 */
abstract class aFactor implements iFactor {
    use tGoogleChartForFactoring;
    public $name;
    /**
     * @var mixed[] saves the parameters for the check
     */
    private $_params = [];

    /**
     * @var iFactorResult[] $_results
     */
    private $_results = [];
    /**
     * @var array $_possibleValues stores all possible fixed realisations of the factor
     */
    private $_possibleValues = [];

    /**
     * @param array[] $data
     * @return iFactorResult[]
     */
    public function factorizeData(array $data) {
        foreach ($data as $obj) {
            $this -> apply($obj);
        }
        //$this -> normalizeFactoringResult();
        $this -> _results = $this -> normalizeFactoringResult();
        return $this -> _results;
    }

    /**
     * @return iFactorResult[]
     */
    public function normalizeFactoringResult() {
        $toRet = [];
        $values = $this -> getPossibleValues();
        foreach ($this -> getPossibleValues() as $rez) {
            $toSave = $this -> _results[$rez];
            if (!($this -> _results[$rez] instanceof iFactorResult)) {
                $toSave = $this -> addNewPossibleValue($rez);
            }
            $toRet[$rez] = $toSave;
        }
        return $toRet;
    }

    /**
     * @param string $name
     * @param $val
     */
    public function setParam($name, $val) {
        $this -> _params[$name] = $val;
    }

    /**
     * @param string $name
     * @return mixed
     */
    public function getParam($name) {
        return $this -> _params[$name];
    }

    /**
     * @param iFactorable $obj
     * @return bool
     */
    public function checkApplicability(iFactorable $obj) {
        return is_a($obj, 'i'.get_called_class());
    }
    /**
     * @param iFactorable $obj
     * @return string - name of the apply method in the corresponding interface
     */
    public function getApplyMethodName(iFactorable $obj = null) {
        /**
         * Задаем конвенцию именования методов по умолчанию.
         * Например, для TimeFactor будет искаться метод calculateTimeFactor
         * Вызываться всегда будет именно здесь заданный метод.
         */
        return 'calculate'.get_called_class();
    }

    /**
     * @param iFactorable $obj
     * @return string - realisation of the factor
     * @throws StatisticalException
     * @throws notApplicableException
     */
    public function apply(iFactorable $obj) {
        //Проверяем, подходит ли полученный объект для проверки
        if (!$this -> checkApplicability($obj)) {
            throw new notApplicableException(get_called_class()." is not applicable to ".get_class($obj));
        }
        $rez = $this -> applyCore($obj);
        //Сохраняем результат
        $this -> storeResult($rez, $obj);
        return $rez;
    }

    /**
     * @param iFactorable $obj
     * @return string
     */
    public function applyCore(iFactorable $obj) {
        //Классифицируем наш объект
        return call_user_func([$obj, $this -> getApplyMethodName($obj)],$this);
    }

    /**
     * @param $rez
     * @param iFactorable $obj
     * @throws StatisticalException
     */
    public function storeResult($rez, iFactorable $obj) {
        $temp = $this -> _results[$rez];
        $whereToAdd = false;
        if ($temp) {
            if (is_a($temp, 'iFactorResult')) {
                $whereToAdd = $temp;
            } else {
                throw new StatisticalException("Expected 'iFactorResult', got ".get_class($temp));
            }
        }
        if(!$whereToAdd) {
            $whereToAdd = $this -> addNewPossibleValue($rez);
        }
        $whereToAdd -> addObject($obj);
    }

    /**
     * @param string $rez
     * @return iFactorResult
     */
    public function addNewPossibleValue($rez){
        $toRet = $this -> _results[$rez];
        if (! ($toRet instanceof iFactorResult) ) {
            $new = $this->getResultFactory()->build($rez, $this);
            $this->_results[$rez] = $new;
            $this->_possibleValues[] = $rez;
            $toRet = $new;
        }
        return $toRet;
    }

    /**
     * @return string[]
     */
    public function getPossibleValues() {
        return array_keys($this -> _results);
    }

    /**
     * @return aFactorResultFactory
     */
    public function getResultFactory() {
        return aFactorResultFactory::getFactory($this);
    }

    /**
     * @param iFactorable $obj
     * @return int
     * By default all the objects are equal
     */
    public function calculateWeight(iFactorable $obj) {
        return 1;
    }

    /**
     * @return iFactorResult[]
     */
    public function getResult() {
        return $this -> _results;
    }

    /**
     * @param iFactor $factor
     * @return iFactor
     */
    public function multiplyBy(iFactor $factor) {
        return new SimpleFactorSet([$this, $factor]);
    }

    /**
     * @return string
     */
    public function getName() {
        return $this -> name;
    }
}