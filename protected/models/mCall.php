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
class mCall extends UModel
{
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
			array('direction, status, duration, i', 'numerical', 'integerOnly'=>true),
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
	 * @return MangoCall the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}

	/**
	 *
	 * @param int|bool $from unix timestamp. Will be replaced by the last loaded date if
	 * not explicitly specified
	 * @param int|bool $to
	 */
	public static function loadDataByApi($from = false, $to = false){
		if( $curl = curl_init() ) {
			if (!$from) {
				$from = reset(mysqli_fetch_all(mysqli_query(MysqlConnect::getConnection(), 'SELECT UNIX_TIMESTAMP(MAX(`date`)) FROM `tbl_mango_call`')));
			}
			$params = array_filter([
					'dateFrom' => $from,
					'dateTo' => $to,
				//'key' => "950fc1f2cef61dcbb9252cdd66a4899e",
					'key' => OmriPss::pss(),
					'city' => 1
			]);

			$url = 'http://new.web-utils.ru/api/calls?'.http_build_query($params);
			curl_setopt($curl, CURLOPT_URL, $url);
			curl_setopt($curl, CURLOPT_RETURNTRANSFER,true);
			$out = curl_exec($curl);
			$mCalls = json_decode($out);
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
						}
					}
				}
			}
			curl_close($curl);
		}
	}

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
		$rez = [];
		$mins = 0;

		while($mins < 24*60) {
			$key = date('G:i',$mins*60);
			$rez[$key] = [$key, 0];
			$mins += $periodMins;
		}
		$cc = 0;
		while($arr = mysqli_fetch_array($q, MYSQLI_ASSOC)) {
			//var_dump($arr);
			$key = date('G:i',$arr['minutesFromDaystart']*60);
			$count = (int)$arr['count'];
			if ($count > 0) {
				$rez[$key] = [$key, $count];
				$cc ++;
			}
		}
		//echo $cc;
		//var_dump($rez);
		return $rez;
	}
}
