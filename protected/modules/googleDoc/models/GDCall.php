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
/**
 * This is the model class for table "gd_call_cached".
 *
 * The followings are the available columns in table 'gd_call_cached':
 * @property integer $id
 * @property string $research_type
 * @property integer $i
 * @property integer $j
 * @property string $H
 * @property string $wishes
 * @property string $fio
 * @property string $birth
 * @property string $number
 * @property string $clinic
 * @property string $price
 * @property string $report
 * @property string $mangoTalker
 * @property string $comment
 * @property integer $id_call_type
 * @property integer $id_user
 * @property integer $id_error
 * @property string $date
 * @property integer $prev_month
 * @property string $calledDate
 * @property string $type
 * @property string $external_id
 * @property integer $id_enter
 * @property string $State
 */
class GDCall extends aGDCall{

    public $entry;
    public $year;
    public $IFromFile = false;
    private $_callTime;
    private $_dateTime;

    const DATESTRING_KEY="Дата";

    const verified = 'verified';
    const missed = 'missed';
    const cancelled = 'cancelled';
    const side = 'side';
    const declined = 'declined';
    const assigned = 'assigned';

    /**
     * @param \Google\Spreadsheet\ListEntry $entry
     * @param aGDCallFactory $factory
     * @throws GoogleDocApiException
     * Since this class is descendant to CActiveRecord, __construct must not be used.
     * I don't believe in the CActiveRecord::init() either, so I created my own initialize method,
     * which will be used by all the factories
     */
    public function initialize(Google\Spreadsheet\ListEntry $entry, aGDCallFactory $factory) {
        parent::initialize($entry, $factory);
        if (!is_a($factory, 'SimpleGDFactory')) {
            throw new GoogleDocApiException('Invalid factory for '.get_class($this));
        }
        /**
         * @type SimpleGDFactory $factory
         */
        $data = $this -> _data;

        $this -> year = $factory -> getYear();
        $this -> entry = $entry;
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

        $this -> id_call_type = $this -> getStatus();
        $this -> calledDate = new CDbExpression("FROM_UNIXTIME('".$this -> getCallTime()."')");
        $this -> date = new CDbExpression("FROM_UNIXTIME('".$this -> getAssignTime()."')");
    }
    public function getYear() {
        if (!$this -> year) {
            $this -> year = $this -> getFactory() -> getYear();
        }
        return $this -> year;
    }

    /**
     * @param aGDCall $gdCall
     * @return bool whether these two objects correspond to the same call
     */
    public function compareWith(aGDCall $gdCall) {
        if (get_class($gdCall) != get_class($this)) {
            return false;
        }
        /**
         * @type self $gdCall
         */
         return $this -> countDiff($gdCall) == 0;
    }

    /**
     * @param self $gdCall
     * @return integer
     */
    protected function countDiff(self $gdCall) {
        $diffs = 0;
        if (date('j.n',strtotime($this -> getCallTime()))!= date('j.n',strtotime($gdCall -> getCallTime()))) {
            $diffs ++;
        }
        if ($this -> fio != $gdCall -> fio) {
            $diffs ++;
        }
        return $diffs;
    }

    /**
     * @return string the telephone number
     */
    public function getNumber() {
        return $this -> mangoTalker;
    }

    /**
     * Возвращаем время, на которое произошла запись.
     * @return int
     */
    public function getAssignTime(){
        if ((!$this -> _dateTime)&&($this -> date)) {
            $this -> _dateTime = strtotime($this -> date);
        }
        if (!$this -> _dateTime) {

            $del = "(\.|,|\/| |:|\\\\)";
            $dig = "(\d{1,2})";
            $pattern = '/' . $dig . $del . $dig . $del . $dig . $del . $dig . '/';
            preg_match('/' . $dig . $del . $dig . $del . $dig . $del . $dig . '/', $this -> report, $matches);
            if ($matches[0]) {
                $year = $this->getYear();
                //Поправка на смену года.
                if ($matches[7] < date("n",strtotime($this -> getCallTime()))) {
                    $year ++;
                }
                $this -> _dateTime = mktime($matches[1], $matches[3], 0, $matches[7], $matches[5], $year);
            } else {
                $this -> _dateTime = $this->getCallTime();
            }
        }
        return $this -> _dateTime;
    }
    /**
     * @return integer
     */
    public function getCallTime(){
        if (!$this -> _callTime) {
            if (!$this -> calledDate) {
                $this->_callTime = strtotime("12:00:00 " . $this->_data[self::DATESTRING_KEY] . '.' . $this->getYear());
            } else {
                $this->_callTime = strtotime($this -> calledDate);
            }
        }
        return $this -> _callTime;
    }
    /**
     * @return integer - the type of call.
     */
    public function getStatus(){
        return callStatusHelper::standardProcedure($this -> report, $this -> State);
    }
}
