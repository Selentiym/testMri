<?php
/**
 * Created by PhpStorm.
 * User: user
 * Date: 18.01.2017
 * Time: 11:17
 */
class landingDataModel extends CActiveRecord {
    /**
     * @var CDbConnection
     */
    private $_connection;
    public function getDbConnection() {
        if (!$this -> _connection) {
            $this -> _connection = landingDataModule::getConnection();
        }
        $conn = $this -> _connection;
        return $this -> _connection;
        //$this -> getC -> getModule();
    }
}