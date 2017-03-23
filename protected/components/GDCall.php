<?php
/**
 * Created by PhpStorm.
 * User: user
 * Date: 14.09.2016
 * Time: 21:32
 */
/**
 * array (size=14)
'дата' => string '1.09' (length=4)
'типисследования' => string 'мрт гм' (length=11)
'н' => string '' (length=0)
'пожеланияклиента' => string '' (length=0)
'фио' => string 'Леготина Екатерина Николаевна' (length=56)
'датарождения' => string '' (length=0)
'контактныйтелефон' => string '' (length=0)
'клиника' => string '' (length=0)
'цена' => string '' (length=0)
'отчетпозвонку' => string 'записаны, не у нас' (length=32)
'mangotalkerномер' => string '79121212660' (length=11)
'комментарий' => string 'заявка' (length=12)
'направление' => string '' (length=0)
'sa' => string '' (length=0)
 */
class GDCall extends Call{
    public $entry;
    public $year;
    public $external_id;
    public function __construct(Google\Spreadsheet\ListEntry $entry, $year = null) {
        $data = $entry -> getValues();
        $xml = $entry -> getXml();
        //Потом по этой штуке мы сможем обращаться к строке гугл дока
        $this -> external_id = $xml -> id;
        $this -> year = $year;
        $this -> entry = $entry;
        $this -> dateString = $data["дата"];
        $this -> State = $data["sa"];
        $this -> report = $data["отчетпозвонку"];
        $this -> research_type = $data["типисследования"];
        //$this -> i = $array[2];
        $dataH = trim($data["н"]);
        //echo $data;
        if (preg_match('/id\d+/',$dataH)) {
            $this -> i = str_replace("id","",$dataH);
            $this -> IFromFile = true;
            //echo "i";
        } elseif (preg_match('/^\d+$/',$dataH)) {
            $this -> j = $dataH;
            //echo "j";
        } elseif (is_string($dataH)) {
            $this -> H = $dataH;
            //echo "H";
        }
        //$this -> j = $array[3];
        //$this -> H = $array[4];
        $this -> fio = $data["фио"];
        $this -> wishes = $data["пожеланияклиента"];
        $this -> birth = $data["датарождения"];
        $this -> number = $data["контактныйтелефон"];
        $this -> clinic = $data["клиника"];
        $this -> price = $data["цена"];
        $this -> mangoTalker = $data["mangotalkerномер"];
        $this -> comment = $data["комментарий"];
    }
    public function getYear() {
        if (!$this -> year) {
            $this -> year = parent::getYear();
        }
        return $this -> year;
    }

    /**
     * @return bool|StatCall
     */
    public function record(){
        $criteria = new CDbCriteria;
        $this -> id_call_type = $this -> ClassifyId();

        $criteria -> addCondition('TO_DAYS(date) = TO_DAYS(FROM_UNIXTIME('.$this -> giveAssignDatePREG().'))');

        $criteria -> addCondition('TO_DAYS(calledDate) = TO_DAYS(FROM_UNIXTIME('.$this -> giveCallTime().'))');

        $criteria -> compare('fio', $this -> fio);
        if ($this -> mangoTalker) {
            $criteria -> compare('mangoTalker', $this -> mangoTalker);
        }
        //echo $this -> research_type;
        if ($this -> research_type) {
            $criteria -> compare('research_type', $this -> research_type);
        }
        if ($this -> j) {
            $criteria -> compare('j', $this -> j);
        } else {
            $criteria -> addCondition('j IS NULL');
        }
        try {
            if ($stCall = StatCall::model()->find($criteria)) {
                return $stCall;
            } else {
                $time = $this->giveAssignDatePREG();
                return false;
            }
        } catch (CDbException $e) {
            return false;
        }
    }
    public static function importFromGoogleDoc($timestamp, $dayCond = false){
        $api = GoogleDocApiHelper::getLastInstance();
        //Чтобы потом не было проблем с ненайденными телефонами.
        $dateTime=new DateTime(date('Y-m-1 00:00:00',$timestamp));
        $dateTimeEnd=new DateTime(date('Y-m-31 23:59:59',$timestamp));
        //$dateTimeEnd = clone $dateTime;
        mCall::loadDataByApi($dateTime -> getTimestamp(), $dateTimeEnd -> getTimestamp());
        //mCall::import($timestamp);
        if ($api -> success) {
            $date = getdate($timestamp);
            $year = $date["year"];
            $api -> setWorkArea(Setting::model() -> find() -> GDName, date("F o",$timestamp));
            $cond = [];
            if ($dayCond) {
                $cond = ['sq' => 'дата = "'.date("j.m",$timestamp).'"' ];
            }
            $data = $api->giveData($cond);
            $i = 0;
            $noI = 0;
            $saved = 0;
            $notSaved = 0;
            $found = 0;
            foreach($data -> getEntries() as $d){
                $i ++;
                $call = new GDCall($d, $year);
                $call -> lookForIAttribute();
                if ($call -> i) {
                    $stCall = new StatCall();
                    /*$call -> setRecordAttributes($stCall);*/
                    $inBase = $call->record();

                    if (!$inBase) {
                        $call->setRecordAttributes($stCall);
                        if ($stCall->save()) {
                            $saved ++;
                            //echo $stCall -> id."<br/>";
                        } else {
                            $notSaved ++;
                        }
                    } else {
                        $found ++;
                    }
                    /*$call -> giveAssignDatePREG();
                    var_dump($d);*/
                } else {
                    $noI++;
                }

            }
        } else {
            echo "api did not work!".PHP_EOL;
        }
        echo "Сохранено ".$saved."<br/>".PHP_EOL;
        echo "Не удалось сохранить ".$notSaved."<br/>".PHP_EOL;
        echo "Найденов БД ".$found."<br/>".PHP_EOL;
        echo "Не определися номер ".$noI."<br/>".PHP_EOL;
        echo "Всего ".$i."<br/>".PHP_EOL;
    }

    /**
     * @param StatCall $record
     */
    public function setRecordAttributes($record) {
        parent::setRecordAttributes($record);
        $record -> external_id = $this -> external_id;
    }

    /**
     * Checks the ClientPhone table and sets $this -> i if there is a record corresponding to user's mangoTalker.
     */
    public function lookForIAttribute(){
        $len = mb_strlen($this -> mangoTalker, "utf-8");
        if ($len == 7 ) {
            $this -> mangoTalker = '7812'.$this -> mangoTalker;
        } elseif ($len == 10) {
            $this -> mangoTalker = '7'.$this -> mangoTalker;
        }
        $mCall = mCall::model() -> findByAttributes(array('fromPhone' => $this -> mangoTalker));
        if (is_a($mCall, 'mCall')) {
            $this -> i = $mCall -> i;
        } else {
            unset($this -> i);
        }
    }
}
?>
