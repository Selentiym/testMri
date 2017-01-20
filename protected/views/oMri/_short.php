<?php
/**
 * @type stdClass $model
 */
?>
<tr>
    <td><?php echo $i; ?></td>
    <td><?php echo $model -> status; ?></td>
    <td><?php echo $model -> phone_numbers; ?></td>
    <td><?php echo $model -> name; ?></td>
    <td><?php echo date("d-m-Y H:i",strtotime($model -> call_date)); ?></td>
</tr>