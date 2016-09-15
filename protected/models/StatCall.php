<?php
/**
 * Created by PhpStorm.
 * User: user
 * Date: 15.09.2016
 * Time: 12:40
 */
class StatCall extends BaseCall {
    /**
     * @return string the associated database table name
     */
    public function tableName()
    {
        return '{{stat_call}}';
    }

    /**
     * Returns the static model of the specified AR class.
     * Please note that you should have this exact method in all your CActiveRecord descendants!
     * @param string $className active record class name.
     * @return BaseCall the static model class
     */
    public static function model($className=__CLASS__)
    {
        return parent::model($className);
    }
}