<?php
/**
 * Created by PhpStorm.
 * User: user
 * Date: 16.09.2016
 * Time: 12:13
 */
if (Yii::app() -> user -> checkAccess("admin")):
    $this -> renderPartial('//navBar',array('button' => 'no'));
    Yii::app()->user->setState("dateAttr", $_GET["attr"], "calledDate");
    $attr = Yii::app() -> user -> getState("dateAttr","calledDate");
    if (!in_array($attr,["calledDate","date"])) {
        $attr = "calledDate";
    }

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
    echo "<tr><td>Линия</td><td>Звонков</td><td>Записей</td></tr>";
    foreach (UserPhone::model() -> findAll() as $p) {
        $this -> renderPartial("//phone/_stat", ['range' => $range, "types" => $arr, "attr" => $attr,"model" => $p]);
    }
    echo "</table>";
?>

<?php else:
$this -> renderPartial("//accessDenied");
endif; ?>
