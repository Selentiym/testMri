<?php

/**
 * This is the model class for table "{{ct_tCall}}".
 *
 * The followings are the available columns in table '{{ct_tCall}}':
 * @property integer $id
 * @property string $number
 * @property integer $id_num
 * @property integer $id_enter
 * @property string $called
 * @property integer $status
 * @property string $CallID
 * @property string $CallerIDNum
 * @property string $CallAPIID
 * @property string $_error
 *
 * @property phNumber $numberDialed
 * @property Enter $enter
 */
class TCall extends landingDataModel
{
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return '{{ct_tCall}}';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('called', 'required'),
			array('id_num, id_enter, status', 'numerical', 'integerOnly'=>true),
			array('number', 'length', 'max'=>100),
			array('CallID, CallerIDNum, CallAPIID, _error', 'safe'),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('id, number, id_num, id_enter, called, status, CallID, CallerIDNum, CallAPIID, _error', 'safe', 'on'=>'search'),
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
				'enter' => array(self::BELONGS_TO, 'Enter', 'id_enter'),
				'numberDialed' => array(self::BELONGS_TO, 'phNumber', 'id_num'),
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
			'id_num' => 'Id Num',
			'id_enter' => 'Id Enter',
			'called' => 'Called',
			'status' => 'Status',
			'CallID' => 'Call',
			'CallerIDNum' => 'Caller Idnum',
			'CallAPIID' => 'Call Apiid',
			'_error' => 'Error',
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
		$criteria->compare('id_num',$this->id_num);
		$criteria->compare('id_enter',$this->id_enter);
		$criteria->compare('called',$this->called,true);
		$criteria->compare('status',$this->status);
		$criteria->compare('CallID',$this->CallID,true);
		$criteria->compare('CallerIDNum',$this->CallerIDNum,true);
		$criteria->compare('CallAPIID',$this->CallAPIID,true);
		$criteria->compare('_error',$this->_error,true);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return TCall the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
}
