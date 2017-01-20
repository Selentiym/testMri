<?php
/**
 * Created by PhpStorm.
 * User: user
 * Date: 21.09.2016
 * Time: 18:13
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
    $datepicker = $this -> renderPartial("//_datepicker",["get" => $_GET, "from" => $from, "to" => $to,"url" => Yii::app() -> baseUrl."/stat/showDiff/%startTime%/%endTime%?line=".$_GET["line"]],true);
    $range = ["from" => $_GET["from"], "to" => $_GET["to"]];
    $lineObj = UserPhone::model() -> findByAttributes(["number" => $_GET["line"]]);
    if ($lineObj) :
        StatCall::compareStats($oCalls, $pCalls, $range, $lineObj, $oCallsDel, $pCallsDel, $attr);

    ?>
        <style>
            tr.call {
                height:75px;
            }
        </style>
        <h1>Сравнение по линии <?php echo $lineObj -> number; ?></h1>
        <a href="<?php echo Yii::app() -> baseUrl."/stat/full/".$_GET["from"]."/".$_GET["to"]; ?>">К списку линий</a>
        <?php $this -> renderPartial('//stat/attrButton',["attr" => $attr]); ?>
        <div><?php echo $datepicker; ?></div>
        <div>
            <?php $this -> renderPartial("//stat/compareCalls",[
                "id" => "ExtraTable",
                "caption" => "Лишние",
                "oCalls" => $oCalls,
                "pCalls" => $pCalls,
            ]); ?>
        </div>
        <div>
            <?php $this -> renderPartial("//stat/compareCalls",[
                "id" => "LinkedTable",
                "caption" => "Сопоставленные",
                "oCalls" => $oCallsDel,
                "pCalls" => $pCallsDel,
            ]); ?>
        </div>
    <?php


    else:
        echo "Не найдена линия";
    endif;
    else:
    $this -> renderPartial("//accessDenied");
    endif;
?>