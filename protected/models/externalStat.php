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
            echo $url.'?'.http_build_query($params);
            curl_setopt($curl, CURLOPT_URL, $url);
            curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($curl, CURLOPT_POSTFIELDS, http_build_query($params));
            $out = curl_exec($curl);
            return json_decode($out);
        }
        return false;
    }
}