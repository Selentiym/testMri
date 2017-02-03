<?php

/**
 * This is the model class for table "{{ct_enter}}".
 *
 * The followings are the available columns in table '{{ct_enter}}':
 * @property integer $id
 * @property integer $id_num
 * @property string $utm_term
 * @property string $created
 * @property integer $last_request
 * @property integer $active
 * @property integer $called
 * @property integer $id_gd
 *
 * @property phNumber $number
 * @property TCall[] $tCalls
 * @property GlobalExperiment $experiment
 * @property GDCallFactorable $gd
 */
class Enter extends landingDataModel implements iTimeFactorable, iNumberFactorable, iFactorable, iCallFactorable, iExperimentFactorable
{
	public $called;
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return '{{ct_enter}}';
		//return '{{ct_enter}}';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('id_num, created', 'required'),
			array('id_num, last_request, active, called', 'numerical', 'integerOnly'=>true),
			array('utm_term', 'safe'),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('id, id_num, utm_term, created, last_request, active, called', 'safe', 'on'=>'search'),
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
				'number' => array(self::BELONGS_TO, 'phNumber', 'id_num'),
				'tCalls' => array(self::HAS_MANY, 'TCall', 'id_enter'),
				'experiment' => array(self::HAS_ONE, 'GlobalExperiment','id_enter'),
				//'gd' => array(self::BELONGS_TO, 'GDCallFactorable', 'id_gd')
				'gd' => array(self::HAS_ONE, 'GDCallFactorable', 'id_enter','order' => 'id_call_type DESC')
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'id' => 'ID',
			'id_num' => 'Id Num',
			'utm_term' => 'Utm Term',
			'created' => 'Created',
			'last_request' => 'Last Request',
			'active' => 'Active',
			'called' => 'Called',
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
		$criteria->compare('id_num',$this->id_num);
		$criteria->compare('utm_term',$this->utm_term,true);
		$criteria->compare('created',$this->created,true);
		$criteria->compare('last_request',$this->last_request);
		$criteria->compare('active',$this->active);
		$criteria->compare('called',$this->called);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return Enter the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}

	/**
	 * @return DateTime
	 */
	public function getDateTime()
	{
		return new DateTime($this -> created);
	}

	/**
	 * @return TCall|null
	 */
	public function getLastTCall() {
		return end($this -> tCalls);
	}
	/**
	 * @return string the dialed number
	 */
	public function getDialedNumber() {
		if ( $this -> getLastTCall() -> numberDialed instanceof phNumber ) {
			return $this -> getLastTCall() ->numberDialed->short_number;
		}
		return 'none';
	}
	/**
	 * @return string|false client's number
	 */
	public function getClientNumber() {
		$t = $this -> getLastTCall();
		if ($t instanceof TCall) {
			return  $t -> getClientNumber();
		}
		return false;
	}

	/**
	 * @return aGDCall|null
	 */
	public function getGoogleDoc() {

		if ($gd = $this -> gd) {
			return $gd;
		}
		return null;
		//return $this -> scanDataBaseForGoogleDoc();
		//return $this -> lookForGoogleDoc();
	}

	/**
	 * @return aGDCall|null
	 */
	public function scanDataBaseForGoogleDoc(){
		if ($num = $this -> getClientNumber()) {
			$time = strtotime($this -> created);
			$cr = StatCall::giveCriteriaForTimePeriod($time - 3600 * 24, $time + 3600 * 24 * 3, 'calledDate');
			$cr->compare('number', $num);
			$cr -> order = 'calledDate DESC';
			$gd = GDCallFactorable::model() -> find($cr);
			if ($gd) {
				$this -> id_gd = $gd -> id;
				$this -> save(['id_gd']);
			}
			return $gd;
		}
		return null;
	}

	/**
	 * @return aGDCall|null
	 */
	public function lookForGoogleDoc() {
		if ($num = $this -> getClientNumber()) {
			$mod = Yii::app() -> getModule("googleDoc");
			/**
			 * @type GoogleDocModule $mod
			 */
			$gd = $mod -> lookForGD(['number' => $num, 'time' => strtotime($this -> created)]);
			if ($gd instanceof aGDCall) {
				$gd->setScenario(GDCallDBCached::REFRESH_IF_DUPLICATE);
				if (!($gd->save())) {
					var_dump($gd->getErrors());
					var_dump($gd->id);
				}
				if ($gd->id) {
					$this->id_gd = $gd->id;
					$this->save(['id_gd']);
				}
			}
			//var_dump($gd);
			return $gd;
		}
		return null;
	}

	/**
	 * @return bool whether the call led to an assignment
	 */
	public function getAssigned() {
		if ($gd = $this -> getGoogleDoc()) {
			/**
			 * @type GDCallFactorable $gd
			 */
			$ass = $gd -> getAssigned();
//			if ($ass) {
//				echo $gd -> fio.": ".$gd -> mangoTalker.", id_enter:". $gd -> id_enter.", id:".$gd -> id.", ".$this -> created."<br/>\r\n";
//			}
			return $ass;
		}
		return false;
	}

	/**
	 * @return bool whether the call led to a completed research and was rewarded
	 */
	public function getVerified() {
		if ($gd = $this -> getGoogleDoc()) {
			/**
			 * @type GDCallFactorable $gd
			 */
			return $gd -> getVerified();
		}
		return false;
	}

	/**
	 * @return GlobalExperiment
	 */
	public function getExperiment(){
		return $this -> experiment;
	}
}
