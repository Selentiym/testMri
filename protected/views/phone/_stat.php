<?php
/**
 * Created by PhpStorm.
 * User: user
 * Date: 16.09.2016
 * Time: 19:28
 */
/**
 * @type UserPhone $model
 */
$allCalls = $model -> countCalls($range,[],$attr);
$assignedCalls = $model -> countCalls($range,$types,$attr);
$verifiedCalls = $model -> countCalls($range,[CallType::model()->getNumber("verifyed")],$attr);
$_GET["sum_assigned"] += $assignedCalls;
$_GET["sum_ver"] += $verifiedCalls;
$_GET["sum"] += $allCalls;
if ($allCalls == 0) {
    $percent = 0;
    $percentVer = 0;
} else {
    $percent = round($assignedCalls/$allCalls*1000)/10;
    $percentVer = round($verifiedCalls/$allCalls*1000)/10;
}
?>
<tr>
    <td>
        <?php
        echo CHtml::link($model -> number,Yii::app() -> createUrl("stat/showDiff",[
                "from" => $range["from"],
                "to" => $range["to"],
                "line" => $model -> number
            ]
        )); ?>
    </td>
    <td><?php echo $allCalls; ?></td>
    <td><?php echo $assignedCalls." (".$percent."%)"; ?></td>
    <td><?php echo $verifiedCalls." (".$percentVer."%)"; ?></td>
</tr>
