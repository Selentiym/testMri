<?php
/**
 * Created by PhpStorm.
 * User: user
 * Date: 30.01.2017
 * Time: 10:26
 */
class FactorForm {
    private static $_types = [
        1 => 'Parameter',
        2 => 'Time',
        3 => 'Experiment',
        4 => 'Called',
        5 => 'Assigned',
        6 => 'Verified',
        7 => 'Day',
        8 => 'Number',
        9 => 'Count',
    ];
    public static function getTypes(){
        return self::$_types;
    }
    public static function getTypeName($id){
        return self::$_types[$id];
    }
    public static function getTypeId($name){
        return array_flip(self::$_types)[$name];
    }
    public static function createGraphFactorsFromConfig($config){
        $view = [];
        if (!empty($config['showFactors'])) {
            foreach ($config['showFactors'] as $fConf) {
                $view[] = self::createFactorFromConfig($fConf);
            }
        }
        $filterFactor = null;
        if (!empty($config['filterFactors'])) {
            foreach ($config['filterFactors'] as $fConf) {
                $nextFact = self::createFactorFromConfig($fConf);
                $filterFactor = ($filterFactor instanceof aFactor) ? $filterFactor -> multiplyBy($nextFact) : $nextFact;
            }
        }
        return ['view' => $view, 'filter' => $filterFactor] ;
    }

    /**
     * @param $config
     * @throws CException
     * @throws StatisticalException
     * @return iFactor|null
     */
    public static function createFactorFromConfig($config){
        $name = self::getTypeName($config['type']).'Factor';
        $conversion = $config['conversion'];
        unset($config['conversion']);
        unset($config['type']);
        if (@Yii::autoload($name)) {
            $obj = new $name(current($config),next($config),next($config),next($config));
            $obj -> setParam('conversion',$conversion);
            //call_user_func_array([$obj,'__construct'],$config);
            return $obj;
        } else {
            throw new StatisticalException("Could not find class $name !");
        }
    }
}