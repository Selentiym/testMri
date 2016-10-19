<?php
/**
 * Created by PhpStorm.
 * User: user
 * Date: 11.10.2016
 * Time: 18:11
 */
class externalStat {
    public static function giveMrtToGoData($from, $to, $periodMins){

        if( $curl = curl_init() ) {
            $params = [];
            $params['key'] = '123qwerty123jjjjkkkklll';
            $params['from'] = $from;
            $params['to'] = $to;
            $params['periodMins'] = $periodMins;

            $url = 'http://mrt-to-go.ru/site/GiveStatistics';
            //echo $url.'?'.http_build_query($params);
            curl_setopt($curl, CURLOPT_URL, $url);
            curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($curl, CURLOPT_POSTFIELDS, http_build_query($params));
            $out = curl_exec($curl);
            return json_decode($out);
        }
        return false;
    }

    /**
     * Выдает массив ключами которого являются метки времени с промежутком
     * в $periodMins минут, а значениями - количесво объектов.
     * $q - результат mysqli_query, обязательно должен быть параметр minutesFromDaystart,
     * по которому, собственно, и определяется время и count - по которому получаем количество
     */
    public static function AverageByPeriodFromSQLRez($q, $periodMins = 10){

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