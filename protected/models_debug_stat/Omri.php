<?php

/**
 * This is the model class for table "{{omri}}".
 *
 * The followings are the available columns in table '{{omri}}':
 * @property integer $id
 * @property string $clinic
 * @property string $line
 * @property string $status
 * @property string $date
 * @property string $tel
 * @property string $report
 * @property string $comment
 */
class Omri extends UModel
{
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return '{{omri}}';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('comment', 'required'),
			array('clinic, status', 'length', 'max'=>256),
			array('line', 'length', 'max'=>64),
			array('tel', 'length', 'max'=>512),
			array('report, comment', 'length', 'max'=>1024),
			array('date', 'safe'),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('id, clinic, line, status, date, tel, report, comment', 'safe', 'on'=>'search'),
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
			'clinic' => 'Clinic',
			'line' => 'Line',
			'status' => 'Status',
			'date' => 'Date',
			'tel' => 'Tel',
			'report' => 'Report',
			'comment' => 'Comment',
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
		$criteria->compare('clinic',$this->clinic,true);
		$criteria->compare('line',$this->line,true);
		$criteria->compare('status',$this->status,true);
		$criteria->compare('date',$this->date,true);
		$criteria->compare('tel',$this->tel,true);
		$criteria->compare('report',$this->report,true);
		$criteria->compare('comment',$this->comment,true);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return Omri the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
}
