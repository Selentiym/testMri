<?php
/**
 *
 */
?>
<table class="table table-stripped" id="<?php echo "o".$id; ?>" style="max-width:45%;display:inline-block;vertical-align:top;">
    <caption>"<?php echo $caption; ?>" на o.mri</caption>
    <thead>
    <th>Номер</th>
    <th>Статус</th>
    <th>Телефоны</th>
    <th>ФИО</th>
    <th>Дата звонка</th>
    </thead>
    <tbody>
    <?php
    $i = 0;
    foreach ($oCalls as $oCall) {
        $i++;
        $this -> renderPartial("//oMri/_short",["i"=>$i,"model" => $oCall]);
    }
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
    <?php
    $i = 0;
    foreach ($pCalls as $pCall) {
        $i++;
        $this -> renderPartial("//call/_short",["i"=>$i,"model" => $pCall]);
    }
    ?>
    </tbody>
</table>
<?php
    //Делаем сортировку
    CHtml::setTableSorting("o".$id,"");
    CHtml::setTableSorting("p".$id,"");
?>