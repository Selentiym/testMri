<?php
/**
 *
 */
    $i = 0;
    $arr = [];
    $arr[] = CallType::model()->getNumber("assigned");
    $arr[] = CallType::model()->getNumber("missed");
    $arr[] = CallType::model()->getNumber("verifyed");
    $arr[] = CallType::model()->getNumber("cancelled");
    $pAssigned = 0;
    $pTableBody = '';
    foreach ($pCalls as $pCall) {
        $i++;
        $pTableBody .= $this -> renderPartial("//call/_short",["i"=>$i,"model" => $pCall], true);
        $pAssigned += (int)(in_array($pCall -> id_call_type, $arr));
    }
    $i = 0;
    $oAssigned = 0;
    foreach ($oCalls as $oCall) {
        $i++;
        $oTableBody .= $this -> renderPartial("//oMri/_short",["i"=>$i,"model" => $oCall], true);
        $oAssigned += (int)($oCall -> status == 'ЗАПИСЬ');
    }
    ?>
    <p>Записанных на o.mri: <?php echo $oAssigned; ?></p>
    <p>Записанных на p.mri: <?php echo $pAssigned; ?></p>
    <table class="table table-stripped" id="<?php echo "o".$id; ?>" style="max-width:45%;display:inline-block;vertical-align:top;">
    <caption>"<?php echo $caption; ?>" на o.mri</caption>
    <thead>
    <th>Номер</th>
    <th>Статус</th>
    <th style="width:100px">Телефоны</th>
    <th>ФИО</th>
    <th style="width:150px">Дата звонка</th>
    </thead>
    <tbody>
    <?php
    echo $oTableBody;
    ?>
    </tbody>
</table>
<table class="table table-stripped" id="<?php echo "p".$id; ?>" style="max-width:45%;display:inline-block;vertical-align:top;">
    <caption>"<?php echo $caption; ?>" на p.mri</caption>
    <thead>
    <th>Номер</th>
    <th>Статус</th>
    <th>mangoTalker</th>
    <th>ФИО</th>
    <th>Дата звонка</th>
    </thead>
    <tbody>
    <?php echo $pTableBody; ?>
    </tbody>
</table>

<?php

    //Делаем сортировку
    CHtml::setTableSorting("o".$id,"");
    CHtml::setTableSorting("p".$id,"");
?>