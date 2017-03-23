<?php
/**
 * Created by PhpStorm.
 * User: user
 * Date: 06.10.2016
 * Time: 20:50
 */
class WebUtils {
    private static $strange;
    public static $pss;
    public static function pss(){
        if (!self::$pss) {
            self::$pss = require_once(Yii::getPathOfAlias('application.config')."/omri.pss.php");
        }
        return self::$pss;
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