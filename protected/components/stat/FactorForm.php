<?php
/**
 * Created by PhpStorm.
 * User: user
 * Date: 30.01.2017
 * Time: 10:26
 */
class FactorForm {
    private static $_types = [
        1 => 'Count',
        2 => 'Experiment',
        3 => 'Time',
        4 => 'Lead',
        5 => 'Called',
        6 => 'Formed',
        7 => 'Assigned',
        8 => 'Verified',
        9 => 'Day',
        10 => 'Number',
        11 => 'Parameter',
        12 => 'Device',
    ];
    private static  $_actions = [
        '' => ['Выберите действие', null],
        '+' => ['Плюс', 'Plus'],
        '/' => ['Делить', 'Divide'],
        '/!' => ['Делить ненулевые', 'DivideSelective']
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
    public static function getActions(){
        $rez = [];
        foreach (self::$_actions as $key => $a) {
            $rez[$key] = $a[0];
        }
        return $rez;
    }
    public static function getActionName($id){
        return self::$_actions[$id][1];
    }
    public static function getActionId($name){
        return array_flip(self::getActions())[$name];
    }
    public static function createGraphFactorsFromConfig($config){
        $view = [];
        $savedFactor = null;
        $action = null;
        $savedAction = null;
        if (!empty($config['showFactors'])) {
            foreach ($config['showFactors'] as $fConf) {
                $action = $fConf['action'];
                unset($fConf['action']);
                $fact = self::createFactorFromConfig($fConf);


                if ($savedAction) {
                    $className = self::getActionName($savedAction).'FactorSet';
                    if (!($savedFactor instanceof aFactor)) {
                        throw new StatisticalException("Saved factor for action '$savedAction' missing!");
                    } else {
                        $savedFactor = new $className($savedFactor, $fact);
                    }
                } else {
                    $savedFactor = $fact;
                }

                if (!$action) {
                    $view[] = $savedFactor;
                    $savedFactor = null;
                }
                $savedAction = $action;
            }
            if ($savedFactor instanceof aFactor) {
                $view[] = $savedFactor;
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
        unset($config['action']);
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