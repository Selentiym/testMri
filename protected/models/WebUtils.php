<?php
/**
 * Created by PhpStorm.
 * User: user
 * Date: 06.10.2016
 * Time: 20:50
 */
class WebUtils {
    private $_url;
    private $_callback;
    private $_params = [];
    private $_curlParams = [
        CURLOPT_RETURNTRANSFER => true,
    ];

    private static $strange;
    public static $pss;
    public static function pss(){
        if (!self::$pss) {
            self::$pss = require_once(Yii::getPathOfAlias('application.config')."/omri.pss.php");
        }
        return self::$pss;
    }

    public function __construct($url){
        $this -> _url = $url;
    }

    public function setPortionObtainCallback(callable $func){
        $this -> _callback = $func;
    }

    public function setParams(array $params){
        $this -> _params = $params;
    }

    public function getParams(){
        $this -> _params['key'] = self::pss();
        return $this -> _params;
    }

    public function getData($from, $to){
        $rez = [];
        foreach ($this -> prepareTimeParameters($from, $to) as $time) {
            $params = array_merge($this -> getParams(), $time);
            $rez = array_merge($rez, $this -> sendRequest($params));
        }
        return $rez;
    }

    public function getCurlOptions(){
        return $this -> _curlParams;
    }
    public function setCurlOptions($options){
        $this -> _curlParams = $options;
    }

    private function sendRequest($params){
        $curl = curl_init();
        $options = $this -> getCurlOptions();
        $url = $this->_url;
        $queryString = http_build_query($params);
        if ($options[CURLOPT_POST]) {
            $options[CURLOPT_POSTFIELDS] = $queryString;
        } else {
            $url .= '?' . $queryString;
        }
        curl_setopt($curl, CURLOPT_URL, $url);
        foreach ($options as $opt => $val) {
            curl_setopt($curl, $opt, $val);
        }
        $out = $this -> processOutputPortion(curl_exec($curl));
        curl_close($curl);
        return $out;
    }

    /**
     * @param string $response
     * @return mixed[]
     */
    private function processOutputPortion($response){
        if (is_callable($this -> _callback)) {
            return call_user_func($this -> _callback, $response);
        }
        return [$response];
    }

    /**
     * @param int $from
     * @param int $to
     * @return mixed[]
     */
    private function prepareTimeParameters($from, $to){
        $fromDate=new DateTime(date('Y-m-d 00:00:00',$from));
        $fromTime = $fromDate -> getTimestamp();
        $generated = [];
        $interval = 86400;
        while ($fromTime < $to) {
            $temp = [];
            $temp['dateFrom'] = $fromTime;
            $fromTime += $interval;
            $temp['dateTo'] = $fromTime;
            $generated[] = $temp;
        }
        return $generated;
//        $dateTimeEnd=new DateTime(date('Y-m-31 23:59:59',$timestamp));
    }

    /**
     * @return bool
     */
    public static function login(){
        require_once(Yii::getPathOfAlias('application.components.simple_html_dom').'.php');
        //Сначала получаем форму для получения уникального ключа
        $url = 'http://web-utils.ru/site/login';
        $first = self::request($url);
        $html = str_get_html($first);
        //Если уже залогинены, то нам логиниться заново не нужно
        if (self::isAuth($html)) {
            return true;
        }
        //Вытаскиваем нужное поле
        self::$strange = $html -> find('input[name="_csrf"]')[0] -> getAttribute('value');
        $auth = array(
            //'LoginForm'=>['username'=>'nikita.bondartsev', 'password'=>'bR9Cv70y','rememberMe' => 1],
            'LoginForm[username]'=>'nikita.bondartsev',
            'LoginForm[password]'=>include(Yii::getPathOfAlias('application.config').'/webutils.pss.php'),
            'LoginForm[rememberMe]' => 1
        );
        $auth['_csrf'] = self::$strange;
        $authD = self::request($url,$auth);
        return self::isAuth($authD);
    }
    public static function isAuth($data){
        return preg_match('/Logout/ui',$data);
    }
    public static function request($url,$post = 0){
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url ); // отправляем на
        curl_setopt($ch, CURLOPT_HEADER, 0); // пустые заголовки
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1); // возвратить то что вернул сервер
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1); // следовать за редиректами
        curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 30);// таймаут4
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_COOKIEJAR, dirname(__FILE__).'/cookie.pss'); // сохранять куки в файл
        curl_setopt($ch, CURLOPT_COOKIEFILE,  dirname(__FILE__).'/cookie.pss');
        curl_setopt($ch, CURLOPT_POST, $post!==0 ); // использовать данные в post
        if($post)
            curl_setopt($ch, CURLOPT_POSTFIELDS, $post);
        $data = curl_exec($ch);
        curl_close($ch);
        return $data;
    }
}