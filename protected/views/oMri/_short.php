<?php
/**
 * @type stdClass $model
 */
?>
<tr>
    <td><?php echo $i; ?></td>
    <td><?php echo $model -> status -> name; ?></td>
    <td><?php echo implode(", ",$model -> clientPhones); ?></td>
    <td><?php echo $model -> clientName; ?></td>
    <td><?php echo date("d-m-Y H:i",strtotime($model -> callDate)); ?></td>
</tr>