<?php

/**
 * This is the model class for table "{{form_submit}}".
 *
 * The followings are the available columns in table '{{form_submit}}':
 * @property integer $id
 * @property string $number
 * @property string $fio
 * @property integer $i
 * @property string $date
 * @property string $lineNumber
 * @property string $numberFormatted
 */
class FormSubmit extends UModel implements iATSCall
{
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return '{{form_submit}}';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('i', 'numerical', 'integerOnly'=>true),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('id, number, fio, i, date, lineNumber', 'safe', 'on'=>'search'),
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
			'number' => 'Number',
			'fio' => 'Fio',
			'i' => 'I',
			'date' => 'Date',
			'lineNumber' => 'Line Number',
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
		$criteria->compare('number',$this->number,true);
		$criteria->compare('fio',$this->fio,true);
		$criteria->compare('i',$this->i);
		$criteria->compare('date',$this->date,true);
		$criteria->compare('lineNumber',$this->lineNumber);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return FormSubmit the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}

	/**
	 * @return int UNIX timestamp of the call
	 */
	public function getCallTime() {
		return strtotime($this -> date);
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
	public function getEnterId() {
		$mod = Yii::app() -> getModule('landingData');
		/**
		 * @type landingDataModule $mod
		 */
		$crit = new CDbCriteria();
		$crit -> compare('id_submit', $this -> id);
		$rez = $mod -> iterateLandingsForClassData('Enter', $crit);
		$save = -1;
		$id = null;
		foreach ($rez as $landing => $enters) {
			$enter = current($enters);
			if (!$enter instanceof Enter) {
				continue;
			}
			/**
			 * @type Enter $enter
			 */
			//
			$time = $enter -> getDateTime() -> getTimestamp();
			if ($time > $save) {
				$id = $enter -> id;
				$save = $time;
			}
		}
		return $id;
	}
}
