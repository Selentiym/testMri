<?php

/**
 * This is the model class for table "{{call}}".
 *
 * The followings are the available columns in table '{{call}}':
 * @property integer $id
 * @property string $research_type
 * @property integer $i
 * @property integer $j
 * @property string $H
 * @property string $wishes
 * @property string $fio
 * @property string $birth
 * @property string $number
 * @property string $clinic
 * @property integer $price
 * @property string $report
 * @property string $mangoTalker
 * @property string $comment
 * @property integer $id_call_type
 * @property integer $id_user
 * @property string $date
 * @property bool $prev_month
 * @property string $calledDate
 * @property string $type
 *
 * The followings are the available model relations:
 * @property User $idUser
 * @property CallType $idCallType
 */

class BaseCall extends UModel
{
	public $phone;
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return '{{call}}';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('date', 'required'),
			array('i, j, id_call_type, id_user', 'numerical', 'integerOnly'=>true),
			array('H, wishes, report, comment', 'length', 'max'=>1024),
			array('fio, birth', 'length', 'max'=>256),
			array('number', 'length', 'max'=>100),
			array('clinic', 'length', 'max'=>512),
			array('mangoTalker', 'length', 'max'=>128),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('id, i, j, H, wishes, fio, birth, number, clinic, price, report, mangoTalker, comment, id_call_type, id_user, date', 'safe', 'on'=>'search'),
			array('*', 'safe', 'on'=>'allSafe'),
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
			'user' => array(self::BELONGS_TO, 'User', 'id_user'),
			'callType' => array(self::BELONGS_TO, 'CallType', 'id_call_type'),
			'error' => array(self::BELONGS_TO, 'CallError', 'id_error'),
			//'phone' => array(self::BELONGS_TO, 'UserPhone', array('mangoTalker' => 'number'))
		);
	}
	/**
	 * Find the corresponding UserPhone and set it.
	 */
	public function setPhone(){
		$CPhone = ClientPhone::model() -> findByAttributes(array('mangoTalker' => $this -> mangoTalker), array('with' => 'phone'));
		if ($CPhone) {
			$this -> phone = $CPhone -> phone;
			$this -> i = $this -> phone -> i;
		}
	}
	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'id' => 'ID',
			'i' => 'I',
			'j' => 'J',
			'H' => 'H',
			'wishes' => 'Wishes',
			'fio' => 'Fio',
			'birth' => 'Birth',
			'number' => 'Number',
			'clinic' => 'Clinic',
			'price' => 'Price',
			'report' => 'Report',
			'mangoTalker' => 'Mango Talker',
			'comment' => 'Comment',
			'id_call_type' => 'Id Call Type',
			'id_user' => 'Id User',
			'date' => 'Date',
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

		$criteria->compare('id',$this->id);
		$criteria->compare('i',$this->i);
		$criteria->compare('j',$this->j);
		$criteria->compare('H',$this->H,true);
		$criteria->compare('wishes',$this->wishes,true);
		$criteria->compare('fio',$this->fio,true);
		$criteria->compare('birth',$this->birth,true);
		$criteria->compare('number',$this->number,true);
		$criteria->compare('clinic',$this->clinic,true);
		$criteria->compare('price',$this->price);
		$criteria->compare('report',$this->report,true);
		$criteria->compare('mangoTalker',$this->mangoTalker,true);
		$criteria->compare('comment',$this->comment,true);
		$criteria->compare('id_call_type',$this->id_call_type);
		$criteria->compare('id_user',$this->id_user);
		$criteria->compare('date',$this->date,true);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
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
	/**
	 * @return integer - unix time of the moment to which the patient is assigned
	 */
	public function giveAssignDate(){
		//18,40 2/11
		$arr = array_values(array_filter(array_map('trim',explode(' ', $this -> report))));
		//print_r($arr);
		$date = $arr[1];
		//echo $date.' - date';
		$date_arr = array_map('trim',explode('/', $date));
		if (count($date_arr) < 2) {
			$date_arr = array_map('trim',explode('.', $date));
		}
		if (count($date_arr) < 2) {
			$date_arr = array_map('trim',explode(',', $date));
		}
		if (count($date_arr) < 2) {
			$date_arr = array_map('trim',explode('\\', $date));
		}if (count($date_arr) < 2) {
			$date_arr = array_map('trim',explode('\\', $date));
		}
		if ((count($date_arr) < 2)||(!(int)$date_arr[0])||(!(int)$date_arr[1])) {
			new CustomFlash('error','BaseCall','FormatMistake'.$this->fio,'Не удалось определить дату, на которую записан клиент '.$this -> fio . '. Строка отчета: '.$this -> report.'. Один из корректных форматов отчета при записи это "<час>,<время> <день>,<месяц>". Важно, чтобы дату от времени отделял пробел!',true);
			//var_dump($this -> report);
			//echo "oops";
			//Yii::app() -> end();
			return $this -> date;
		}
		$day = $date_arr[0];
		$month = $date_arr[1];
		//свойство date уж должно быть задано!
		$call_date = $this -> giveDate();
		//print_r( $this -> giveDate());
		//echo "123";
		//Сначала считаем, что запись была сделана на тот же год.
		$year = $call_date['year'];
		//Но если номер месяца записи меньше номера месяца звонка, то считаем, что запись произошла на следующий год.
		if ($call_date['mon'] > $month) {
			$year ++;
		}
		//echo $month.' - '.$day.' - '.$year.'<br/>';
		//echo $this -> report;
		//var_dump($day);
		return mktime(12,0,0,$month,$day,$year);
	}
	/**
	 * Sets propper values for date
	 */
	public function beforeSave(){
		if ($this -> isNewRecord) {
			if (($this -> id_call_type == CallType::model() -> getNumber('verifyed'))||($this -> id_call_type == CallType::model() -> getNumber('assigned'))) {
				//echo "123";
				$assign_time = $this -> giveAssignDate();
				$assign = getdate($assign_time);
				$date = getdate($this -> date);
				if ($assign ["mon"] != $date ["mon"]) {
					$this -> date = $assign_time;
					$this -> prev_month = 1;
					//echo "next!";
				}
			}//*/
		}

		if ($this -> date > 1376046800) {
			$this -> date = new CDbExpression('FROM_UNIXTIME('.$this -> date.')');
		}
		if ($this -> calledDate > 1376046800) {
			$this -> calledDate = new CDbExpression('FROM_UNIXTIME('.$this -> calledDate.')');
		}
		return parent::beforeSave();
	}
	public function beforeDelete() {
		if (parent::beforeDelete()) {
			if ($this -> id_user != 1) {
				$this -> id_user = 1;
				$this -> save();
				return false;
			}
			return true;
		}
	}
	/*public function afterSave() {
		echo $this -> id;
	}*/
	/**
	 * @return array - the array returned by getdate function from create_time attr
	 */
	public function giveDate() {
		/*$dateArr = explode('.', $this -> dateString);
		$rez['day'] = (int)$dateArr[0];
		$rez['mon'] = (int)$dateArr[1];*/
		//$rez['year'] = $dateArr[2];
		return getdate($this -> giveTime());
	}
	/**
	 * @return integer - the unix time of the call (it is valid only up to days)
	 */
	public function giveTime(){
		if (!($this -> date * 1)) {
			return strtotime($this -> date);
		} else {
			return $this -> date;
		}
	}
	/**
	 * @arg int from - the time to search from
	 * @arg int to - the time to search to
	 * @arg string $attr - attribute the condition is set on
	 * @return object[CDbCRiteria]
	 */
	public static function giveCriteriaForTimePeriod($from = NULL, $to = NULL, $attr="date"){
		$criteria = new CDbCriteria;
		if ((int)($from)) {
			$criteria -> addCondition($attr.' >= FROM_UNIXTIME('.$from.')');
		}
		if ((int)($to)) {
			$criteria -> addCondition($attr.' < FROM_UNIXTIME('.$to.')');
		}
		return $criteria;
	}
	/**
	 * @return string - the type of call. They are stored in the database table {{call_type}}
	 */
	public function Classify(){
		//echo $this -> callType -> string.' - ';
		return $this -> callType -> string;
	}
	/**
	 * @return string - the report on the call
	 */
	public function giveReport(){
		if (preg_match('/[a-zA-Zа-яА-Я]/',$this -> report)){
			return $this -> report;
		} else {
			$arr = explode(',',$this -> report);
			$day_month = explode('/',$arr[2]);
			return 'Записан на '.$arr[0].':'.$arr[1].' '.$arr[2];
		}
	}
	/**
	 * Checks the ClientPhone table and sets $this -> i if there is a record corresponding to user's mangoTalker.
	 */
	public function lookForIAttribute(){
		$CPhone = ClientPhone::model() -> findByAttributes(array('mangoTalker' => $this -> mangoTalker), array('with' => 'phone'));
		if ((!$CPhone)&&(preg_match('/^7812\d+/',$this -> mangoTalker))) {
			$CPhone = ClientPhone::model() -> findByAttributes(array('mangoTalker' => str_replace('7812','',$this -> mangoTalker)), array('with' => 'phone'));
		}
		if ($CPhone) {
			$this -> i = $CPhone -> phone -> i;
		}
		return $this -> i;
	}
	/**
	 * Checks the ClientPhone table and returns the corresponding Phone Object
	 */
	public function givePhone(){
		$CPhone = ClientPhone::model() -> findByAttributes(array('mangoTalker' => $this -> mangoTalker), array('with' => 'phone'));
		if ((!$CPhone)&&(preg_match('/^7812\d+/',$this -> mangoTalker))) {
			$CPhone = ClientPhone::model() -> findByAttributes(array('mangoTalker' => str_replace('7812','',$this -> mangoTalker)), array('with' => 'phone'));
		}
		return UserPhone::model() -> findByPK($CPhone -> id_phone);
	}
	/**
	 * @return string - full name if user is admin and short name for others
	 */
	public function giveName(){
		if (Yii::app() -> user -> checkAccess('admin')) {
			return $this -> fio;
		} else {
			$words = array_map('trim',explode(' ',$this -> fio));
			$rez = substr($words[0],0,4).'. ';
			unset($words[0]);
			foreach ($words as $word) {
				$rez.=''.substr($word,0,2).'.';
			}
			return $rez;
		}
	}
	/**
	 * @param int[2] $range time range, must have from and to attrs set.
	 * @param CDbCriteria $criteria
	 * @param string $attr attr which to set time condition to
	 * @return int
	 */
	public static function callsInPeriod($range, $criteria = NULL, $attr = NULL){
		if (!is_a($criteria, "CDbCriteria")) {
			$criteria = new CDbCriteria();
		}
		$criteria -> mergeWith(StatCall::giveCriteriaForTimePeriod($range["from"], $range["to"], $attr));
		return static::model() -> count($criteria);
	}
}
