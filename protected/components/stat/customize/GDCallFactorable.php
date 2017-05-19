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
    private $_cachedTCall;
    public function initialize(Google\Spreadsheet\ListEntry $entry, aGDCallFactory $factory) {
        parent::initialize($entry, $factory);
        //Определили линию звонка
        $this -> lookForIAttribute();
        //$i = $this -> i;
        //Если нам удалось определить, что звонок с лендинг, то сохраняем связь.
        //Не сохраняем эту связь в случае, если звонок прицепляется к любой другой линии.
        //Если звонок без линии, то он нас тоже не интересует.
        $tCall = $this -> getTCall('max');
        //$attrs = $tCall -> attributes;
        if (($tCall)&&($this -> i == $tCall -> getLandingId())&&($this -> i)) {
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
        //Получили самый поздний манго звонок по этому номеру.
        $mCall = mCall::model() -> findByAttributes(array('fromPhone' => $this -> mangoTalker), ['order' => 'date DESC']);
        $maxTCall = $this -> getTCall('max');
        //Теперь выбираем что позже: с лендинга или с манго.
        $maxCall = $maxTCall;
        if (($maxTCall)&&($mCall)) {
            $delta = $maxTCall -> getCallTime() - $mCall -> getCallTime();
            $t1 = date('c', $maxTCall -> getCallTime());
            $t2 = date('c', $mCall -> getCallTime());
            if ($delta > -30) {
                $maxCall = $maxTCall;
            } else {
                $maxCall = $mCall;
            }
        } elseif($mCall) {
            $maxCall = $mCall;
        } elseif($maxTCall) {
            $maxCall = $maxTCall;
        } else {

        }
        if ($maxCall) {
            $this -> i = $maxCall -> getLineI();
        }
        return $this -> i;
    }

    /**
     * @param string $m has to be min or max
     * @return mixed|null|TCall
     */
    public function getTCall($m = 'min') {
        if (!$this -> _cachedTCall[$m]) {
            /**
             * @type landingDataModule $mod
             */
            $mod = Yii::app()->getModule('landingData');
            $cr = new CDbCriteria();
            $cr->compare('CallerIDNum', $this->mangoTalker, true);
            if ($m=='min') {
                $cr->order = 'called ASC';
            } elseif($m=='max') {
                $cr->order = 'called DESC';
            }
            //Получили все звонки со всех лендингов
            $tCalls = $mod->iterateLandingsForClassData('TCall', $cr);
            //Нашли самый ранний из звонков по лендингам
            $s = null;
            $sTCall = null;
            $sLanding = null;
            foreach ($tCalls as $landingId => $calls) {
                if (!empty($calls)) {
                    $tCall = current($calls);
                    $time = $tCall->getCallTime();
                    if ($m == 'min') {
                        $change = ($time < $s);
                    }
                    if ($m == 'max') {
                        $change = ($time > $s);
                    }
                    $change |= (!$sTCall);
                    if ($change) {
                        $s = $time;
                        $sTCall = $tCall;
                        $sLanding = $landingId;
                    }
                }
            }
            if ($sTCall) {
                $sTCall->setLanding($sLanding);
            }
            $this -> _cachedTCall[$m] = $sTCall;
        }
        return $this -> _cachedTCall[$m];
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

    /**
     * @param string $name
     * @return bool whether the load was successful
     */
    public static function loadGooglePrices($name) {

        $reader = new CsvReader($name);
        $reader -> codeFileEncoding = 'utf-8';
        $reader -> exportFileEncoding = 'utf-8';
        //Первая строка не хранит информации
        $reader -> line();
        //Сохраняем заголовок
        $reader -> saveHeader();
        $time = new DateTime();
        $time -> setTimestamp($_POST['time']);
        $time -> setTime(0,0,0);
        $from = $time -> getTimestamp();
        $time -> setTime(23,59,59);
        $to = $time -> getTimestamp();
        $mod = Yii::app() -> getModule('landingData');
        /**
         * @type landingDataModule $mod
         */
        $landing = $mod -> getDefaultLanding();
        while($line = $reader -> line()){
            $term = trim($line[5]);
//            echo $term.'<br/>';
//            $term = 'мозг';
            $price = str_replace(',','.',$line[6]);
            $crit = StatCall::giveCriteriaForTimePeriod($from, $to, 'created');
            $crit -> compare("utm_term", $term);
            $crit -> compare("utm_term", trim($line[1]),false, 'OR');

            $found = $mod -> getEnterData($landing -> textId, $crit);
            foreach ($found as $e) {
                if (!$e->price) {
                    $e->price = (float)$price;
                }
                var_dump($e);
                $e -> save();
            }
        }
        return true;
    }
}