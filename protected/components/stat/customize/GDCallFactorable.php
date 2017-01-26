<?php
/**
 * Created by PhpStorm.
 * User: user
 * Date: 15.01.2017
 * Time: 10:28
 */
class GDCallFactorable extends GDCallDBCached implements iCallFactorable, iFactorable, iTimeFactorable{
    /**
     * @var TCall
     */
    private $_minTCall;
    public function initialize(Google\Spreadsheet\ListEntry $entry, aGDCallFactory $factory) {
        parent::initialize($entry, $factory);
        //Определили линию звонка
        $this -> lookForIAttribute();
        //Если нам удалось определить, что звонок с лендинг, то сохраняем связь.
        //Даже если реально звонок прикреплен к другой линии.
        if ($tCall = $this -> getMinTCall()) {
            $this -> id_enter = $tCall -> id_enter;
        }
    }

    /**
     * @return string|int line i which google record corresponds to
     */
    public function lookForIAttribute() {

        $len = mb_strlen($this -> mangoTalker, "utf-8");
        if ($len == 7 ) {
            $this -> mangoTalker = '7812'.$this -> mangoTalker;
        } elseif ($len == 10) {
            $this -> mangoTalker = '7'.$this -> mangoTalker;
        }
        //Получили самый ранний манго звонок по этому номеру.
        $mCall = mCall::model() -> findByAttributes(array('fromPhone' => $this -> mangoTalker), ['order' => 'date ASC']);
        $minTCall = $this -> getMinTCall();
        //Теперь выбираем что раньше: с лендинга или с манго.
        $minCall = $minTCall;
        if (($minTCall)&&($mCall)) {
            $delta = $minTCall -> getCallTime() - $mCall -> getCallTime();
            if ($delta > 30) {
                $minCall = $minTCall;
            } else {
                $minCall = $mCall;
            }
        } elseif($mCall) {
            $minCall = $mCall;
        } elseif($minTCall) {
            $minCall = $minTCall;
        } else {

        }
        if ($minCall) {
            $this -> i = $minCall -> getLineI();
        }
        return $this -> i;
    }
    public function getMinTCall() {
        if (!$this -> _minTCall) {
            /**
             * @type landingDataModule $mod
             */
            $mod = Yii::app()->getModule('landingData');
            $cr = new CDbCriteria();
            $cr->compare('CallerIDNum', $this->mangoTalker, true);
            $cr->order = 'called ASC';
            //Получили все звонки со всех лендингов
            $tCalls = $mod->iterateLandingsForClassData('TCall', $cr);
            //Нашли самый ранний из звонков по лендингам
            $min = null;
            $minTCall = null;
            $minLanding = null;
            foreach ($tCalls as $landingId => $calls) {
                if (!empty($calls)) {
                    $tCall = current($calls);
                    $time = $tCall->getCallTime();
                    if (($time < $min) || (!$minTCall)) {
                        $min = $time;
                        $minTCall = $tCall;
                        $minLanding = $landingId;
                    }
                }
            }
            if ($minTCall) {
                $minTCall->setLanding($minLanding);
            }
            $this -> _minTCall = $minTCall;
        }
        return $this -> _minTCall;
    }
    /**
     * @return bool whether the call led to an assignment
     */
    public function getAssigned() {
        $a = callStatusHelper::getStatusesArray();
        $b = in_array($this -> id_call_type,[$a['verified'],$a['assigned'],$a['cancelled']]);
        $st = $this -> id_call_type;
        if (!$b) {
            $a = 11;
        }
        return $b ? 1 : 0;
    }

    /**
     * @return bool whether the call led to a completed research and was rewarded
     */
    public function getVerified() {
        return in_array($this -> id_call_type,[callStatusHelper::getClassId('verified')]) ? 1 : 0;
    }

    /**
     * @return DateTime
     */
    public function getDateTime() {
        return new DateTime($this -> date);
    }
    /**
     * Returns the static model of the specified AR class.
     * Please note that you should have this exact method in all your CActiveRecord descendants!
     * @param string $className active record class name.
     * @return static the static model class
     */
    public static function model($className=__CLASS__)
    {
        return parent::model($className);
    }
}