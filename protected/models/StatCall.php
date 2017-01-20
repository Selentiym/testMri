<?php
/**
 * Created by PhpStorm.
 * User: user
 * Date: 15.09.2016
 * Time: 12:40
 */
class StatCall extends BaseCall {
    /**
     * Будет давать связь между данным объектом и записью GD
     * @var string $external_id
     */
    /**
     * @return string the associated database table name
     */
    public function tableName()
    {
        return '{{stat_call}}';
    }

    /**
     * Returns the static model of the specified AR class.
     * Please note that you should have this exact method in all your CActiveRecord descendants!
     * @param string $className active record class name.
     * @return StatCall the static model class
     */
    public static function model($className=__CLASS__)
    {
        return parent::model($className);
    }
    public static function compareStats(&$oCalls, &$pCalls, $range,UserPhone $lineObj, &$oCallsDel, &$pCallsDel, $attr){
        $oCallsDel = [];
        $pCallsDel = [];
        if (!$lineObj) {
            return;
        }
        if( $curl = curl_init() ) {
            /*$range = [
                "from" => strtotime("last month"),
                "to" => time(),
            ];*/

            /*$line = "78124071126";
            $line = "78124071024";*/
            $line = $lineObj -> number;
            $tr = ["calledDate" => "call_date","date"=>"app_date"];
            $params = [
                "dateFrom" => $range["from"],
                "dateTo" => $range["to"],
                "key" => OmriPss::pss(),
                "city" => 1,
                "line" => $line,
                "filterBy" => $tr[$attr]
            ];
            //$url = "http://web-utils.ru/api/export?".http_build_query($params);
            $url = 'http://o.mrimaster.ru/api/contacts?'.http_build_query($params);
            curl_setopt($curl, CURLOPT_URL, $url);
            curl_setopt($curl, CURLOPT_RETURNTRANSFER,true);
            $out = curl_exec($curl);
            $oCalls = json_decode($out);

            if (empty($oCalls)) {
                $oCalls = [];
            }

            if ($lineObj) {
                $crit = StatCall::giveCriteriaForTimePeriod($range["from"], $range["to"],$attr);
                $crit->compare("i", $lineObj->i);
                $pCalls = StatCall::model()->findAll($crit);
                $oWas = count($oCalls);
                $pWas = count($pCalls);
                //function compareOandP(&$oCalls,&$pCalls, $attr){
                    foreach ($oCalls as $oKey => $oCall) {
                        //Поочередно ищем каждый звонок из omri на pmri
                        foreach ($pCalls as $pKey => $pCall) {
                            if (mb_strtolower(trim($oCall->name), "UTF-8") != mb_strtolower(trim($pCall->fio), "UTF-8")) {
                                continue;
                            }
                            $oT = strtotime($pCall->$attr);
                            if ($attr == "date") {
                                $pT = strtotime($oCall->app_date);
                            } else {
                                $pT = strtotime($oCall->call_date);
                            }
                            if (abs($oT - $pT) > 3600 * 12) {
                                continue;
                            }
                            $oCallsDel[] = $oCall;
                            $pCallsDel[] = $pCall;
                            //Если нашлось совпадение, то удаляем звонок из обоих массивов.
                            unset($oCalls[$oKey]);
                            unset($pCalls[$pKey]);
                            break;
                        }
                    }
                //}
                //compareOandP($oCalls,$pCalls, $attr);
                //compareOandP($oCalls,$pCalls);
                $oIs = count($oCalls);
                $pIs = count($pCalls);

                //echo "O: $oIs/$oWas<br/>P:$pIs/$pWas";
            } else {
                echo "noLine";
            }
            curl_close($curl);
        }
    }
    public static function loadFormDataFromOmri($from, $to){
        if( $curl = curl_init() ) {
            /*$from = $_GET["from"];
            $to = $_GET["to"];*/
            $params = [
                'dateFrom' => $from,
                'dateTo' => $to,
                'key' => OmriPss::pss(),
                'city' => 1
            ];
            $url = "http://o.mrimaster.ru/api/forms?".http_build_query($params);
            curl_setopt($curl, CURLOPT_URL, $url);
            curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
            $out = curl_exec($curl);
            $calls = json_decode($out);
            foreach ($calls as $call) {
                $stCall = new StatCall();
                $stCall -> type = 'form';
                $stCall -> date = new CDbExpression('FROM_UNIXTIME(\''.strtotime($call -> dateCreated).'\')');
                $stCall -> report = $call -> description;
                $stCall -> fio = $call -> name;
                $stCall -> number = $call -> phone;
                $stCall -> i = $call -> pid;
                $criteria = new CDbCriteria();
                $criteria -> compare('i', $stCall -> i);
                $criteria -> compare('number', $stCall -> number);
                $criteria -> compare('report', $stCall -> report);
                $criteria -> addCondition('date = FROM_UNIXTIME('.strtotime($call -> dateCreated).')');
                $rec = StatCall::model() -> find($criteria);
                if (!$rec) {
                    if (!$stCall->save()) {
                        var_dump($stCall->getErrors());
                    }
                }
            }
            curl_close($curl);
        }
    }
    public static function giveFormDataAverageByPeriod ($from, $to, $periodMins) {
        $sql = "SELECT COUNT(`id`) as `count`, @mins := FLOOR((UNIX_TIMESTAMP(`date`)%(86400))/(60*$periodMins))*$periodMins as `minutesFromDaystart`, FLOOR(@mins/60) as `hours`, @mins%60 as `minutes` FROM `tbl_stat_call` WHERE `i` < 0 AND `type`='form' AND `date` > FROM_UNIXTIME($from) AND `date` < FROM_UNIXTIME($to) GROUP BY FLOOR((UNIX_TIMESTAMP(`date`)%(86400))/(60*$periodMins)) ORDER BY @mins ASC";
        //echo $sql;
        //Yii::app() -> end();
        $q = mysqli_query(MysqlConnect::getConnection(), $sql);
        return externalStat::AverageByPeriodFromSQLRez($q, $periodMins);
    }
    /**
     * Checks the ClientPhone table and sets $this -> i if there is a record corresponding to user's mangoTalker.
     */
    public function lookForIAttribute(){
        $mCall = mCall::model() -> findByAttributes(array('fromPhone' => $this -> mangoTalker));
        if (is_a($mCall, 'mCall')) {
            $this -> i = $mCall -> i;
        } else {
            unset($this -> i);
        }
    }

    /**
     * Возвращает количество полей, в которых отличие.
     * @param \Google\Spreadsheet\ListEntry $entry
     * @return int
     */
    public function compareWithGD(\Google\Spreadsheet\ListEntry $entry) {
        $diffs = 0;
        $data = array_map('trim',$entry -> getValues());

        if ($data["дата"] != trim(date('j.n',strtotime($this -> calledDate)))) {
            $diffs ++;
        }
        if ($data["фио"] != trim($this -> fio)) {
            $diffs ++;
        }
        return $diffs;
    }

    /**
     * @return \Google\Spreadsheet\ListEntry
     */
    public function findGdByFields() {
        $entry = false;
        $months = [1 => 'January',2 => 'February',3 => 'March',4 => 'April',5 => 'May',6 => 'June',7 => 'July',8 => 'August',9 => 'September',10 => 'October',11 => 'November',12 => 'December'];
        $time = strtotime($this -> date);
        //Массив даты, на которую запись.
        $arr = getdate($time);
        $month = $arr['mon'];
        $year = $arr['year'];
        //День, месяц поступления звонка.
        $alterMon = date('n',strtotime($this -> calledDate));
        //В нормальной ситауции месяц звонка меньше месяца записи, но если не так, то звонок был в предыдущем году.
        if ($alterMon > $month) {
            $year --;
        }
        //Получили название листа гугл дока.
        $work = $months[$month].' '.$year;
        $queryString = 'дата = "'.date('j.m', strtotime($this -> calledDate)).'"';
        //Количество параметров, по которым происходит посик.
        $params = 1;
        if ($this -> research_type) {
            //$queryString .= ' and типисследования="'.$this -> research_type.'"';
            $params ++;
        }
        if ($this -> clinic) {
            //$queryString .= ' and клиника = "'.$this -> clinic.'"';
            $params ++;
        }
        if ($this -> fio) {
            //$queryString .= ' and фио = "'.$this -> fio.'"';
            $params ++;
        }
        if ($this -> birth) {
            //$queryString .= ' and датарождения = "'.$this -> birth.'"';
            $params ++;
        }
        $qArr = array('sq' => $queryString);
        //Ищем запись.
        return GoogleDocApiHelper::getLastInstance() -> searchEverywhere($qArr, $work);
    }

    /**
     * Ищет соответсвующую запись в таблице.
     * Помимо обычного поиска по номеру ищем еще и по полям.
     * @return \Google\Spreadsheet\ListEntry
     */
    public function findGD() {
        $rez = $this -> findGDByLink();
        /**
         * @type \Google\Spreadsheet\ListEntry $rez
         */
        if (is_a($rez,'\Google\Spreadsheet\ListEntry')) {
            $lookMore = $this -> compareWithGD($rez) > 0;
        } else {
            $lookMore = true;
        }
        //Ищем через поля только если заметили какое-то несоответствие.
        //Это менее точный поиск, но что уж поделать
        if (!$lookMore) {
            return $rez;
        }
        return $this -> findGdByFields();
    }

    /**
     * Ищет запись гугл дока только по ссылке на ячейку.
     * Может меняться иногда. Например, если была вставлена дополнительная строка.
     * @return \Google\Spreadsheet\ListEntry
     */
    public function findGDByLink(){
        if ($this -> external_id) {
            $a = $this -> external_id;
            return GoogleDocApiHelper::getLastInstance()->getEntryByUrl($this->external_id);
        } else {
            return null;
        }
    }

    /**
     * Goes to google doc file and refreshes data
     * @return bool - whether the refresh took place
     */
    public function refreshData() {
        $entry = $this -> findGD();
        if (is_a($entry, '\Google\Spreadsheet\ListEntry')) {
            $tempCall = new GDCall($entry);
            $tempCall -> setRecordAttributes($this);
            return $this -> save();
        }
        return false;
    }

    /**
     * @param integer $from
     * @param integer $to
     * @return integer
     */
    public static function refreshInPeriod($from = null, $to = null) {
        $toRefresh = StatCall::model() -> findAll(StatCall::giveCriteriaForTimePeriod($from, $to));
        /**
         * @type StatCall[] $toRefresh
         */
        $changed = 0;
        $changedStatuses = [];
        //Задаем массив типов, чтобы подсчитать изменения.
        $types = CallType::model() -> findAll();
        foreach($types as $t) {
            $temp = array('count' => 0, 'name' => $t -> name);
            $changedStatuses[$t -> id] = $temp;
        }
        foreach ($toRefresh as $record) {
            $record -> setScenario("allSafe");
            $attributesOld = $record -> attributes;
            $record -> refreshData();
            $changed += (int)($attributesOld != $record -> attributes);
            if ($attributesOld["id_call_type"] != $record -> id_call_type) {
                //Наращиваем счетчик увеличенных статусов.
                $changedStatuses[$record -> id_call_type]['count'] ++;
            }
        }
        //var_dump($changedStatuses);
        return $changed;
    }
}