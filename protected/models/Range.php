<?php

/**
 * This is the model class for table "{{jRange}}".
 *
 * The followings are the available columns in table '{{jRange}}':
 * @property integer $id
 * @property integer $id_user
 * @property integer $jMin
 * @property integer $jMax
 */
class Range extends UModel
{
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return '{{jRange}}';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('id_user, jMin, jMax', 'required'),
			array('id_user, jMin, jMax', 'numerical', 'integerOnly'=>true),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('id, id_user, jMin, jMax', 'safe', 'on'=>'search'),
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
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'id' => 'ID',
			'id_user' => 'Id User',
			'jMin' => 'J Min',
			'jMax' => 'J Max',
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
		$criteria->compare('id_user',$this->id_user);
		$criteria->compare('jMin',$this->jMin);
		$criteria->compare('jMax',$this->jMax);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return Range the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
	/**
	 * @arg integer $j - the number to check
	 * @return bool whether this range contains the $j.
	 */
	public function hasJ($j) {
		return (($j >= $this -> jMin)&&($j <= $this -> jMax));
	}
	/**
	 * @arg CDbCriteria $criteria - the criteria on ranges.
	 * @return User[] - an array of users of type "doctor" corresponding to the found Ranges
	 */
	public function giveDoctors($criteria){
		$all = self::model() -> findAll($criteria);
		return array_filter(array_map(function($range){
			if ($range -> user -> id_type == UserType::model() -> getNumber('doctor')) {
				return $range -> user;
			} else {
				return NULL;
			}
		},$all));
	}
}
