<?php
/**
 * Created by PhpStorm.
 * User: user
 * Date: 15.12.2016
 * Time: 19:28
 */
abstract class aFactorResultFactory {
    /**
     * @var array
     */
    protected static $instances = array();

    /**
     * @param iFactor $factor
     * @return aFactorResultFactory
     * @throws StatisticalException
     */
    public static function getFactory(iFactor $factor) {
        $class = get_class($factor);
        $toCheck = $class.'ResultFactory';
        if (@ Yii::autoload($toCheck)) {
            return self::getFactoryByClass($toCheck, $factor);
        }
        /*if (class_exists($toCheck)) {
            return self::getFactoryByClass($toCheck, $factor);
        }*/
        //По умолчанию
        return self::getFactoryByClass('SimpleFactorResultFactory', $factor);
    }

    /**
     * implementation of Multiton pattern
     * @param string $factoryClass
     * @param iFactor $factor
     * @return aFactorResultFactory
     * @throws StatisticalException
     */
    private static function getFactoryByClass($factoryClass, iFactor $factor){
        $factory = self::$instances[$factoryClass];
        if (!($factory instanceof $factoryClass)) {
            if (class_exists($factoryClass)) {
                $factory = new $factoryClass($factor);
            } else {
                throw new StatisticalException("No $factoryClass found");
            }
        }
        return $factory;
    }

    /**
     * @param string $arg
     * @param iFactor $factor
     * @return iFactorResult
     */
    abstract public function build($arg, iFactor $factor);


    protected function __construct() {
    }

    final protected function __clone(){
    }

    final protected function __wakeup(){
    }

    final protected function __sleep(){
    }
}