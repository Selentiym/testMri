<?php
/**
 *
 * @var int $i
 * @var BaseCall $model
 */
$translate = array(
    'verifyed' => 'Подтвержден',
    'missed' => 'Не пришел',
    'cancelled' => 'Отменен',
    'side' => 'Нецелевой',
    'declined' => 'Не записан',
    'assigned' => 'Записан',
);
?>
<tr class="call <?php echo $model -> Classify(); ?>">
    <td><?php echo $i; ?></td>
    <td><?php echo $translate[$model -> Classify()]; ?></td>
    <td><?php echo $model -> mangoTalker; ?></td>
    <td><?php echo $model -> fio; ?></td>
    <td><?php echo date("d-m-Y H:i",strtotime($model -> calledDate)); ?></td>
</tr>
