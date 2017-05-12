<?php

/**
 * This is the model class for table "phone_call".
 *
 * The followings are the available columns in table 'phone_call':
 * @property integer $id
 * @property integer $city_id
 * @property string $from_phone
 * @property string $to_phone
 * @property integer $excluded
 * @property integer $excluded_manual
 * @property integer $user_id
 * @property string $contact_ids
 * @property string $date
 * @property string $direction
 * @property string $duration
 * @property string $status
 * @property string $line
 * @property string $type
 * @property integer $version
 */
class WUPhoneCall extends UModel
{
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'wu_phone_call';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('version', 'required'),
			array('city_id, excluded, excluded_manual, user_id, version', 'numerical', 'integerOnly'=>true),
			array('from_phone, to_phone, line, type', 'length', 'max'=>255),
			array('contact_ids', 'length', 'max'=>25),
			array('direction, duration, status', 'length', 'max'=>20),
			array('date', 'safe'),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('id, city_id, from_phone, to_phone, excluded, excluded_manual, user_id, contact_ids, date, direction, duration, status, line, type, version', 'safe', 'on'=>'search'),
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
			'city_id' => 'City',
			'from_phone' => 'From Phone',
			'to_phone' => 'To Phone',
			'excluded' => 'Excluded',
			'excluded_manual' => 'Excluded Manual',
			'user_id' => 'User',
			'contact_ids' => 'Contact Ids',
			'date' => 'Date',
			'direction' => 'Direction',
			'duration' => 'Duration',
			'status' => 'Status',
			'line' => 'Line',
			'type' => 'Type',
			'version' => 'Version',
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
		$criteria->compare('city_id',$this->city_id);
		$criteria->compare('from_phone',$this->from_phone,true);
		$criteria->compare('to_phone',$this->to_phone,true);
		$criteria->compare('excluded',$this->excluded);
		$criteria->compare('excluded_manual',$this->excluded_manual);
		$criteria->compare('user_id',$this->user_id);
		$criteria->compare('contact_ids',$this->contact_ids,true);
		$criteria->compare('date',$this->date,true);
		$criteria->compare('direction',$this->direction,true);
		$criteria->compare('duration',$this->duration,true);
		$criteria->compare('status',$this->status,true);
		$criteria->compare('line',$this->line,true);
		$criteria->compare('type',$this->type,true);
		$criteria->compare('version',$this->version);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return WUPhoneCall the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
}
