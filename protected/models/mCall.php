<?php

/**
 * This is the model class for table "{{mangoCall}}".
 *
 * The followings are the available columns in table '{{mangoCall}}':
 * @property string $id
 * @property string $date
 * @property string $line
 * @property string $fromPhone
 * @property string $toPhone
 * @property integer $direction
 * @property integer $status
 * @property integer $duration
 * @property integer $type
 * @property integer $i
 */
class mCall extends UModel implements iATSCall
{

	private static $_lastCallTime;
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return '{{mango_call}}';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('date, line, fromPhone', 'required'),
			array('duration, i', 'numerical', 'integerOnly'=>true),
			array('line, fromPhone', 'length', 'max'=>128),
			array('toPhone', 'length', 'max'=>512),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('id, date, line, fromPhone, toPhone, direction, status, duration, type, i', 'safe', 'on'=>'search'),
		);
	}

	/**
	 * @return array relational rules.
	 */
	public function relations()
	{
		// NOTE: you may need to adjust the relation name and the related
		// class name for the relations automatically generated below.
		return array(
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'id' => 'ID',
			'date' => 'Date',
			'line' => 'Line',
			'fromPhone' => 'From Phone',
			'toPhone' => 'To Phone',
			'direction' => 'Direction',
			'status' => 'Status',
			'duration' => 'Duration',
			'type' => 'Type',
			'i' => 'I',
		);
	}

	/**
	 * Retrieves a list of models based on the current search/filter conditions.
	 *
	 * Typical usecase:
	 * - Initialize the model fields with values from filter form.
	 * - Execute this method to get CActiveDataProvider instance which will filter
	 * models according to data in model fields.
	 * - Pass data provider to CGridView, CListView or any similar widget.
	 *
	 * @return CActiveDataProvider the data provider that can return the models
	 * based on the search/filter conditions.
	 */
	public function search()
	{
		// @todo Please modify the following code to remove attributes that should not be searched.

		$criteria=new CDbCriteria;

		$criteria->compare('id',$this->id,true);
		$criteria->compare('date',$this->date,true);
		$criteria->compare('line',$this->line,true);
		$criteria->compare('fromPhone',$this->fromPhone,true);
		$criteria->compare('toPhone',$this->toPhone,true);
		$criteria->compare('direction',$this->direction);
		$criteria->compare('status',$this->status);
		$criteria->compare('duration',$this->duration);
		$criteria->compare('type',$this->type);
		$criteria->compare('i',$this->i);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return self the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}

	/**
	 * Проверяет, нужна ли загрузка звонков и загружает, если нужна
	 * @param $timestamp
	 * @return bool
	 */
	public static function import($timestamp) {
		$lc = (int)self::lastCallTime();
		if (ceil($lc / (24*60*60)) != ceil($timestamp / (24*60*60))) {
			return self::loadDataByApi(null, $timestamp);
		}
		return true;
	}

	/**
	 * @return integer
	 * @throws Exception
	 */
	public static function lastCallTime () {
		if (!self::$_lastCallTime) {
			self::$_lastCallTime = reset(mysqli_fetch_all(mysqli_query(MysqlConnect::getConnection(), 'SELECT UNIX_TIMESTAMP(MAX(`date`)) FROM `tbl_mango_call`')));
		}
		return self::$_lastCallTime;
	}
	/**
	 *
	 * @param int|bool $from unix timestamp. Will be replaced by the last loaded date if
	 * not explicitly specified
	 * @param int|bool $to
	 * @return array[] saving errors that occurred
	 */
	public static function loadDataByApi($from = false, $to = false){
		$u = new WebUtils('http://web-utils.ru/api/calls');
		$u -> setParams(['city' => 1]);
		$u -> setPortionObtainCallback(function($response){
			$mCalls = json_decode($response);
			$ret = [];
			if (!empty($mCalls)) {
				foreach ($mCalls as $mCall) {
					//Добавляем только если не найдено
					if (!(mCall::model()->findByPk($mCall->id))) {
						$b = new mCall();
						$b->id = $mCall->id;
						$b->date = $mCall->date;
						$b->line = $mCall->line;
						$b->fromPhone = $mCall->fromPhone;
						$b->toPhone = $mCall->toPhone;
						$b->direction = $mCall->direction;
						$b->status = $mCall->status;
						$b->duration = $mCall->duration;
						$b->type = $mCall->type;
						if ($ph = UserPhone::givePhoneByNumber($b->line)) {
							$b->i = $ph->i;
						}
						if (!$b->save()) {
							$err = $b->getErrors();
							$ret[$mCall -> id] = $err;
						}
					}
				}
			}
			return $ret;
		});
		$rez = $u -> getData($from,$to);
		return $rez;
	}
//	public static function loadDataByApi($from = false, $to = false){
//		if( $curl = curl_init() ) {
//			if (!$from) {
//				$from = current(current(mysqli_fetch_all(mysqli_query(MysqlConnect::getConnection(), 'SELECT UNIX_TIMESTAMP(MAX(`date`)) FROM `tbl_mango_call`'))));
//			}
//			$params = array_filter([
//					'dateFrom' => $from,
//					'dateTo' => $to,
//					'key' => WebUtils::pss(),
//					'city' => 1
//			]);
//
//			$url = 'http://web-utils.ru/api/calls?'.http_build_query($params);
//			curl_setopt($curl, CURLOPT_URL, $url);
//			curl_setopt($curl, CURLOPT_RETURNTRANSFER,true);
//			$out = curl_exec($curl);
//			$mCalls = json_decode($out);
//			if (!empty($mCalls)) {
//				foreach ($mCalls as $mCall) {
//					//Добавляем только если не найдено
//					if (!(mCall::model()->findByPk($mCall->id))) {
//						$b = new mCall();
//						$b->id = $mCall->id;
//						$b->date = $mCall->date;
//						$b->line = $mCall->line;
//						$b->fromPhone = $mCall->fromPhone;
//						$b->toPhone = $mCall->toPhone;
//						$b->direction = $mCall->direction;
//						$b->status = $mCall->status;
//						$b->duration = $mCall->duration;
//						$b->type = $mCall->type;
//						if ($ph = UserPhone::givePhoneByNumber($b->line)) {
//							$b->i = $ph->i;
//						}
//						if (!$b->save()) {
//							$err = $b->getErrors();
//						}
//					}
//				}
//			}
//			curl_close($curl);
//		}
//	}

	/**
	 * @param $from
	 * @param $to
	 * @param $periodMins
	 * @return array
	 */
	public static function callsAverageByPeriod ($from, $to, $periodMins = 10) {
		//echo "from=$from&to=$to&periodMins=$periodMins";
		$sql = "SELECT COUNT(`id`) as `count`, @mins := FLOOR((UNIX_TIMESTAMP(`date`)%(86400))/(60*$periodMins))*$periodMins as `minutesFromDaystart`, FLOOR(@mins/60) as `hours`, @mins%60 as `minutes` FROM `tbl_mango_call` WHERE (`line` = '78123132704' OR `line` = '78122411052' OR `line` = '78122411058' ) AND `date` > FROM_UNIXTIME($from) AND `date` < FROM_UNIXTIME($to) GROUP BY FLOOR((UNIX_TIMESTAMP(`date`)%(86400))/(60*$periodMins)) ORDER BY @mins ASC";
		//echo $sql;
		//Yii::app() -> end();
		$q = mysqli_query(MysqlConnect::getConnection(), $sql);
		return externalStat::AverageByPeriodFromSQLRez($q, $periodMins);
	}

	/**
	 * @return int UNIX timestamp of the call
	 */
	public function getCallTime() {
		//Задержка на всякий случай, тк звонок Телфин также появялется и в манго, а
		// необходимо их разделять
		return strtotime($this -> date) - 30;
	}

	/**
	 * @param mixed $external
	 * @return int|string line identification(not just table id!) which this call corresponds to
	 */
	public function getLineI($external = null) {
		return $this -> i;
	}

	/**
	 * @return int
	 */
	public function getTime() {
		return strtotime($this -> date);
	}
	/**
	 * @return string
	 */
	public function getLine(){
		return '';
	}

	/**
	 * @return int
	 */
	public function getEnterId() {
		return null;
	}
}
