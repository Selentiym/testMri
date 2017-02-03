<?php
/**
 * @type Enter $model
 */
$exp = $model -> experiment;
$tCall = $model -> tCalls;
$gd = $model -> gd;
?>
<tr>
<td>
    <?php echo $model -> created; ?>
</td>
<td>
    <?php echo $model -> utm_term; ?>
</td>
<td>
    <?php
        if (!empty($tCalls)) {
            foreach ($tCalls as $call) {
                $this->renderPartial('/tCall/_view', ['call' => $call]);
            }
        }
    ?>
</td>
</tr>