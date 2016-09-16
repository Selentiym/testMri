<?php
/**
 * Created by PhpStorm.
 * User: user
 * Date: 16.09.2016
 * Time: 12:13
 */
if (Yii::app() -> user -> checkAccess("admin")):
    $this -> renderPartial('//navBar',array('button' => 'no'));
/**
 * @type Controller $this
 */
$datepicker = $this -> renderPartial("//_datepicker",["get" => $get, "from" => $from, "to" => $to,"url" => Yii::app() -> baseUrl."/stat/full/%startTime%/%endTime%"],true);
?>
    <div><?php echo $datepicker; ?></div>
    <?
    $range = ["from" => $_GET["from"], "to" => $_GET["to"]];
    $arr = [];
    $arr[] = CallType::model()->getNumber("assigned");
    $arr[] = CallType::model()->getNumber("missed");
    $arr[] = CallType::model()->getNumber("verifyed");
    $arr[] = CallType::model()->getNumber("cancelled");
    $attr = "calledDate";
    echo "<h1>Статистика по линиям (по дате звонка)</h1>";
    echo "<table class='table table-bordered'>";
    echo "<tr><td>Линия</td><td>Звонков</td><td>Записей</td></tr>";
    foreach (UserPhone::model() -> findAll() as $p) {
        $this -> renderPartial("//phone/_stat", ['range' => $range, "types" => $arr, "attr" => $attr,"model" => $p]);
    }
    echo "</table>";
?>

<?php else:
$this -> renderPartial("//accessDenied");
endif; ?>
