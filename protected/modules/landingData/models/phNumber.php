<?php

/**
 * This is the model class for table "{{ct_number}}".
 *
 * The followings are the available columns in table '{{ct_number}}':
 * @property integer $id
 * @property string $number
 * @property string $short_number
 * @property integer $reserved
 * @property integer $noCarousel
 * @property integer $forSearch
 */
class phNumber extends landingDataModel
{
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return '{{ct_number}}';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('number, short_number', 'required'),
			array('reserved, noCarousel, forSearch', 'numerical', 'integerOnly'=>true),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('id, number, short_number, reserved, noCarousel, forSearch', 'safe', 'on'=>'search'),
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
				'enters' => array(self::HAS_MANY, 'Enter', 'id_num'),
				//'lastActiveNotCalledEnter' => array(self::HAS_ONE, 'Enter', 'id_num','condition' => 'active = 1 AND called = 0','order' => 'created DESC'),
				//'lastActiveEnter' => array(self::HAS_ONE, 'Enter', 'id_num','condition' => 'active = 1','order' => 'created DESC'),
				//'occupied' => array(self::STAT, 'Enter', 'id_num','condition' => 'active = 0'),
				'tCalls' => array(self::HAS_MANY, 'TCall', 'id_num'),
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
			'short_number' => 'Short Number',
			'reserved' => 'Reserved',
			'noCarousel' => 'No Carousel',
			'forSearch' => 'For Search',
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
		$criteria->compare('short_number',$this->short_number,true);
		$criteria->compare('reserved',$this->reserved);
		$criteria->compare('noCarousel',$this->noCarousel);
		$criteria->compare('forSearch',$this->forSearch);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return phNumber the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
}
