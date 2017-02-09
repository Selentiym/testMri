<?php
/**
 * @type Enter $model
 */
$exp = $model -> experiment;
$tCall = $model -> tCalls;
$gd = $model -> gd;
$hasGD = $gd instanceof GDCallFactorable;
$hasExp = $exp instanceof GlobalExperiment;
$status = $hasGD ? callStatusHelper::getClassName($gd -> id_call_type) : 'no_status';
?>
<tr class="<?php echo $status; ?>" title="<?php echo $status; ?>">
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
    <td>
        <?php
            echo $hasGD ? $gd -> fio : '';
        ?>
    </td>
    <td>
        <?php
            echo $hasGD ? $gd -> mangoTalker: '';
        ?>
    </td>
    <td>
        <?php
            echo $hasGD ? $gd -> report: '';
        ?>
    </td>
    <td>
        <?php
            echo $hasGD ? $gd -> date: '';
        ?>
    </td>

    <td>
        <?php
            echo $hasExp ? $exp -> isMobile : '';
        ?>
    </td>
    <td>
        <?php
            echo $hasExp ? $exp -> price : '';
        ?>
    </td>
</tr>