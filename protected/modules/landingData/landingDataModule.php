<?php
/**
 * Created by PhpStorm.
 * User: user
 * Date: 18.01.2017
 * Time: 10:00
 */
class landingDataModule extends CWebModule {
    private static $_connection;
    //private $_connections;
    public $landings;
    public function init(){
        $this->setImport(array(
            'landingData.models.*',
        ));
    }
    public function createConnection($connectionString, $userName, $tablePrefix){
        /**
        array(
        'connectionString' => 'mysql:host=localhost;dbname=calls',
        'tablePrefix' => 'tbl_',
        'emulatePrepare' => true,
        'username' => 'root',
        'password' => '',
        'charset' => 'utf8',
        )
         */
        $config = $this -> config;
        $this -> _connection = new CDbConnection($config['connectionString'], $config['username'], $config['password']);
        unset($config['connectionString']);
        unset($config['username']);
        unset($config['password']);
        foreach ($config as $key => $value) {
            $this -> _connection -> $key = $value;
        }
    }

    /**
     * @param string $id
     * @return Landing|bool
     */
    public function getLanding($id = '') {
        if (!isset($this -> landings[$id])) {
            $land = Landing::model()->findByAttributes(['textId' => $id]);
            $land -> initialize();
            $this->landings[$id] = $land;
        }
        return $this -> landings[$id];
    }

    /**
     * @param $id
     * @param CDbCriteria $criteria
     * @return Enter[]
     */
    public function getEnterData($id, CDbCriteria $criteria = null){
        if (!$criteria) {
            $criteria = new CDbCriteria();
        }
        $land = $this -> getLanding($id);
        $conn = $land -> getDataConnection();
        self::setConnection($conn);
        $conn = Enter::model() -> getDbConnection();
        return Enter::model()->findAll($criteria);
    }


    /**
     * UNIX timestamp required
     * @param int $from - the time to search from
     * @param int $to - the time to search to
     * @param string $attr - attribute the condition is set on
     * @return CDbCriteria
     */
    public static function giveCriteriaForTimePeriod($from = NULL, $to = NULL, $attr="created"){
        $criteria = new CDbCriteria();
        if ((int)($from)) {
            $criteria -> addCondition($attr.' >= FROM_UNIXTIME('.$from.')');
        }
        if ((int)($to)) {
            $criteria -> addCondition($attr.' < FROM_UNIXTIME('.$to.')');
        }
        return $criteria;
    }
    public static function setConnection(CDbConnection $c){
        self::$_connection = $c;
    }

    public static function getConnection(){
        if (! (self::$_connection instanceof CDbConnection)) {
            self::setConnection(Yii::app() -> db);
        }
        return self::$_connection;
    }
}