<?php
/**
 * Created by PhpStorm.
 * User: user
 * Date: 14.01.2017
 * Time: 17:12
 */
class StatCallFactorable extends StatCall implements iCallFactorable, iFactorable, iTimeFactorable{

    /**
     * Returns the static model of the specified AR class.
     * Please note that you should have this exact method in all your CActiveRecord descendants!
     * @param string $className active record class name.
     * @return StatCall the static model class
     */
    public static function model($className=__CLASS__)
    {
        return parent::model($className);
    }

    /**
     * @return bool whether the call led to an assignment
     */
    public function getAssigned() {
        $b = in_array($this -> id_call_type,[1,2,3,6]);
        $a = 0;
        if ($b) {
            $a = 1;
        }
        return $b ? 1 : 0;
    }

    /**
     * @return bool whether the call led to a completed research and was rewarded
     */
    public function getVerified() {
        return in_array($this -> id_call_type,[1]) ? 1 : 0;
    }

    /**
     * @return DateTime
     */
    public function getDateTime() {
        return new DateTime($this -> date);
    }
}