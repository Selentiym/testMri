<?php
/**
 * Created by PhpStorm.
 * User: user
 * Date: 15.09.2016
 * Time: 12:40
 */
class StatCall extends BaseCall {
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
     * @return BaseCall the static model class
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
            $tr = ["calledDate" => "call","date"=>"assign"];
            $params = [
                "dateFrom" => $range["from"],
                "dateTo" => $range["to"],
                "line" => $line,
                "key" => require_once("omri.pss.php"),
                "type" => $tr[$attr]
            ];
            $url = 'http://o.mrimaster.ru/api/contacts?'.http_build_query($params);
            curl_setopt($curl, CURLOPT_URL, $url);
            curl_setopt($curl, CURLOPT_RETURNTRANSFER,true);
            $out = curl_exec($curl);
            $oCalls = json_decode($out);

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
                            if (mb_strtolower(trim($oCall->clientName), "UTF-8") != mb_strtolower(trim($pCall->fio), "UTF-8")) {
                                continue;
                            }
                            $oT = strtotime($pCall->$attr);
                            if ($attr == "date") {
                                $pT = strtotime($oCall->appointmentTime);
                            } else {
                                $pT = strtotime($oCall->callDate);
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
}