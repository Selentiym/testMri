<?php
/**
 * Created by PhpStorm.
 * User: user
 * Date: 16.09.2016
 * Time: 12:13
 */
if (Yii::app() -> user -> checkAccess("admin")):
    $this -> renderPartial('//navBar',array('button' => 'no'));
    $attr = $_GET["attr"];
    if (!in_array($attr,["calledDate","date"])) {
        $attr = Yii::app() -> user -> getState("dateAttr","calledDate");
    }
    if (!in_array($attr,["calledDate","date"])) {
        $attr = "date";
    }
    Yii::app()->user->setState("dateAttr", $attr, "calledDate");
/**
 * @type Controller $this
 */
$datepicker = $this -> renderPartial("//_datepicker",["get" => $get, "from" => $from, "to" => $to,"url" => Yii::app() -> baseUrl."/stat/full/%startTime%/%endTime%"],true);
?>
    <h1>Статистика по линиям</h1>
    <?php $this -> renderPartial('//stat/attrButton',["attr" => $attr]); ?>
    Подгрузить статистику из googleDoc (сохраняется в базе) за:
    <?php
    echo "<br/>";
    $this -> renderPartial("//stat/_statButton",[
        "text" => "3 месяца назад",
        "time" => strtotime("last month",strtotime("last month",strtotime("last month")))
    ]);
    echo "<br/>";
    $this -> renderPartial("//stat/_statButton",[
        "text" => "2 месяца назад",
        "time" => strtotime("last month",strtotime("last month"))
    ]);
    echo "<br/>";
    $this -> renderPartial("//stat/_statButton",[
        "text" => "Прошлый месяц",
        "time" => strtotime("last month")
    ]);
    echo "<br/>";
    $this -> renderPartial("//stat/_statButton",[
        "text" => "Этот месяц",
        "time" => time()
    ]);
    echo "<br/>".CHtml::link("Обновить статусы за выбранный период", Yii::app() ->createUrl('stat/refreshData', ["from" => $_GET["from"], "to" => $_GET["to"]]));
    ?>
    <div><?php echo $datepicker; ?></div>
    <?
    $range = ["from" => $_GET["from"], "to" => $_GET["to"]];
    $arr = [];
    $arr[] = CallType::model()->getNumber("assigned");
    $arr[] = CallType::model()->getNumber("missed");
    $arr[] = CallType::model()->getNumber("verifyed");
    $arr[] = CallType::model()->getNumber("cancelled");

    //asdasd
//    function isAuth( $data ){
//        return preg_match('/Logout/ui',$data);
//    }
//    function request($url,$post = 0){
//        $ch = curl_init();
//        curl_setopt($ch, CURLOPT_URL, $url ); // отправляем на
//        curl_setopt($ch, CURLOPT_HEADER, 0); // пустые заголовки
//        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1); // возвратить то что вернул сервер
//        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1); // следовать за редиректами
//        curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 30);// таймаут4
//        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
//        curl_setopt($ch, CURLOPT_COOKIEJAR, dirname(__FILE__).'/cookie.pss'); // сохранять куки в файл
//        curl_setopt($ch, CURLOPT_COOKIEFILE,  dirname(__FILE__).'/cookie.pss');
//        curl_setopt($ch, CURLOPT_POST, $post!==0 ); // использовать данные в post
//        if($post)
//            curl_setopt($ch, CURLOPT_POSTFIELDS, $post);
//        $data = curl_exec($ch);
//        curl_close($ch);
//        return $data;
//    }
//    $url = 'http://web-utils.ru/site/login';
//    $first = request($url);
//    $html = str_get_html($first);
//    if (isAuth($html)) {
//        echo "Authed!";
//        return;
//    }
//    $strange = $html -> find('input[name="_csrf"]')[0] -> getAttribute('value');
//    //$url = 'http://web-utils.ru/owner/lines';
////    $data = request('http://web-utils.ru/site/login');
//    //$data = str_get_html($data);
//    $auth = array(
//        //'LoginForm'=>['username'=>'nikita.bondartsev', 'password'=>'bR9Cv70y','rememberMe' => 1],
//        'LoginForm[username]'=>'nikita.bondartsev',
//        'LoginForm[password]'=>'bR9Cv70y',
//        'LoginForm[rememberMe]' => 1,
//        '_csrf' => $strange
//    );
////    $data->clear();
////    unset($data);
//    $authD = request($url,$auth);
//    echo isAuth($authD)?'Success':'Failed';;
//    //asdasdads
    $params = [
        'to_date'=>date('Y-m-d',$_GET['to']),
        'from_date'=>date('Y-m-d',$_GET['from']),
        'city'=>'spb'
    ];
    if ($attr) {
        $params['mode'] = $attr!='calledDate' ? 'app_date' : 'call_date';
    }
    if (WebUtils::login()) {
        echo "yahoo";
    } else {
        echo "bad";
    }
    $url = 'http://web-utils.ru/owner/lines?'.http_build_query($params);
    $yahoo = str_get_html(WebUtils::request($url));
    //echo $yahoo;
    $line = '78124261375';
    /**
     * @type simple_html_dom_node $table
     */
    $table = current($yahoo -> find('table'));
    $nums = [];
    foreach ($table -> childNodes() as $child) {
        $nums[trim(strip_tags($child -> childNodes(0) -> innerText()))] = [
            'total' => $child -> childNodes(1) -> innerText(),
            'assigned' => $child -> childNodes(2) -> innerText(),
            'verified' => $child -> childNodes(3) -> innerText(),
        ];
    }
    //var_dump($nums);
    //$html = text_get_html($url);
    echo "<table class='table table-bordered'>";
    $_GET["sum_assigned"] = 0;
    $_GET["sum_ver"] = 0;
    $_GET["sum"] = 0;
    echo "<tr><td>Линия</td><td>Звонков</td><td>Записей</td><td>Подтвержденных</td></tr>";
    foreach (UserPhone::model() -> findAll() as $p) {
        $this -> renderPartial("//phone/_stat", ['range' => $range, "types" => $arr, "attr" => $attr,"model" => $p,'webutils' => $nums]);
    }
    echo "</table>";
    echo "<p>Всего звонков: ".$_GET["sum"]."</p>";
    echo "<p>Записанных: ".$_GET["sum_assigned"]."</p>";
    echo "<p>Подтвержденных: ".$_GET["sum_ver"]."</p>";
?>

<?php else:
$this -> renderPartial("//accessDenied");
endif; ?>
