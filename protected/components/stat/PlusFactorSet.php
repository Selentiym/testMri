<?php
/**
 * Created by PhpStorm.
 * User: user
 * Date: 16.02.2017
 * Time: 15:35
 */
class PlusFactorSet extends aFactorSet {
    public function __construct(aFactor $fact1, aFactor $fact2) {
        parent::__construct([$fact1,$fact2]);
        $this -> name = '('.$fact1 -> name .'+'.$fact2 -> name.')';
    }
    public function applyCore(iFactorable $obj) {
        $rez = 0;
        foreach ($this->getFactors() as $f) {
            //Чтобы не было повторного суммирования, например,
            // заходов со звонком и формой одновременно
            $temp = (int)$f ->apply($obj);
            $rez += $temp;
            if ($temp) {
                break;
            }
        }
        return $rez;
    }
//    public function applyVector(array $objects) {
//        $rez = 0;
//        foreach ($objects as $o) {
//            foreach ($this->getFactors() as $f) {
//                //Чтобы не было повторного суммирования, например,
//                // заходов со звонком и формой одновременно
//                $temp = (int)$f ->apply($o);
//                $rez += $temp;
//                if ($temp) {
//                    break;
//                }
//                //$rez += $f -> applyVector($objects);
//            }
//        }
//        return $rez;
//    }
}