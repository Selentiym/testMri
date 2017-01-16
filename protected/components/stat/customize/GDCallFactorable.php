<?php
/**
 * Created by PhpStorm.
 * User: user
 * Date: 15.01.2017
 * Time: 10:28
 */
class GDCallFactorable extends GdCallDBCached implements iCallFactorable, iFactorable, iTimeFactorable{

    /**
     * @return bool whether the call led to an assignment
     */
    public function getAssigned() {
        $a = callStatusHelper::getStatusesArray();
        $b = in_array($this -> id_call_type,[$a['verified'],$a['assigned'],$a['cancelled']]);
        $st = $this -> id_call_type;
        if (!$b) {
            $a = 11;
        }
        return $b ? 1 : 0;
    }

    /**
     * @return bool whether the call led to a completed research and was rewarded
     */
    public function getVerified() {
        return in_array($this -> id_call_type,[callStatusHelper::getClassId('verified')]) ? 1 : 0;
    }

    /**
     * @return DateTime
     */
    public function getDateTime() {
        return new DateTime($this -> date);
    }
    /**
     * Returns the static model of the specified AR class.
     * Please note that you should have this exact method in all your CActiveRecord descendants!
     * @param string $className active record class name.
     * @return static the static model class
     */
    public static function model($className=__CLASS__)
    {
        return parent::model($className);
    }
}