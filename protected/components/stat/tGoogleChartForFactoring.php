<?php
/**
 * Created by PhpStorm.
 * User: user
 * Date: 16.12.2016
 * Time: 22:01
 */
trait tGoogleChartForFactoring{
    /**
     * @return iFactorResult[]
     */
    abstract public function getResult();
    /**
     * Run through all the results and apply the given function.
     * @param callable $func has to return array that
     * @return array
     */
    public function getResultArray(callable $func){
        return array_map($func, $this -> getResult());
    }

    /**
     * @return int[]
     */
    public function getStandardResultArray() {
        return $this -> getResultArray(function($res){
            /**
             * @type iFactorResult $res
             */
            return $res -> result();
        });
    }

    /**
     * @param aFactor[] $view
     * @return array
     */
    public function getArrayForChartByFactors(array $view){
        return $this -> getResultArray(function($res) use ($view){
            /**
             * @type iFactorResult $res
             * @type aFactor $f
             */
            $rez = [$res -> getId()];
            foreach ($view as $f) {
                $rez[] = $f -> applyVector($res -> giveObjects());
            }
            return $rez;
        });
    }
    /**
     * @param mixed[] $pattern initial value for array element
     * @param callable $func must return an array that consists of all
     * metrics that need to be shown in a graph later.
     * Parameters for the function are:
     *      param iFactorable $obj
     * @return mixed[]
     */
    public function getResultArrayForGoogleCharts(callable $func, $pattern) {
        return $this -> getResultArray(function($res) use ($func, $pattern){
            /**
             * @type iFactorResult $res
             */
            $objects = $res -> giveObjects();
            $numObjs = $res -> getObjectsNumber();
            //if no objects correspond to this factor realisation
            if (empty($objects)) {
                $toRet = $pattern;
            } else {
                $toRet = [];
            }
            foreach ($objects as $obj) {
                if ($func) {
                    $tempDataSource = call_user_func($func, $obj, $numObjs);
                    foreach($pattern as $key => $garbage) {
                        if (!isset($toRet[$key])) {
                            $toRet[$key] = 0;
                        }
                        $toRet[$key] += $tempDataSource[$key];
                    }
                }
            }
            //Вставляем заголовок
            array_unshift($toRet, $res -> getId());
            return $toRet;
        });
    }
}