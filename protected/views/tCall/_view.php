<?php
/**
 * Created by PhpStorm.
 * User: user
 * Date: 03.02.2017
 * Time: 14:21
 *
 * @type TCall $call
 */
?>
<p>
    <?php echo $call -> CallerIDNum; ?> -> <?php echo $call -> number; ?>: <?php echo $call -> CallID; ?>, <?php echo date('c',$call -> called); ?>
</p>
