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

    echo "<table class='table table-bordered'>";
    $_GET["sum_assigned"] = 0;
    $_GET["sum_ver"] = 0;
    $_GET["sum"] = 0;
    echo "<tr><td>Линия</td><td>Звонков</td><td>Записей</td><td>Подтвержденных</td></tr>";
    foreach (UserPhone::model() -> findAll() as $p) {
        $this -> renderPartial("//phone/_stat", ['range' => $range, "types" => $arr, "attr" => $attr,"model" => $p]);
    }
    echo "</table>";
    echo "<p>Всего звонков: ".$_GET["sum"]."</p>";
    echo "<p>Записанных: ".$_GET["sum_assigned"]."</p>";
    echo "<p>Подтвержденных: ".$_GET["sum_ver"]."</p>";
?>

<?php else:
$this -> renderPartial("//accessDenied");
endif; ?>
