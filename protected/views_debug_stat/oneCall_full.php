<tr class="<?php echo $call -> Classify(); ?>">
<?php
//'verifyed', 'missed', 'cancelled', 'side', 'declined', 'assigned')
	$translate = array(
		'verifyed' => 'Подтвержден',
		'missed' => 'Не пришел',
		'cancelled' => 'Отменен',
		'side' => 'Нецелевой',
		'declined' => 'Не записан',
		'assigned' => 'Записан',
	);
	//$criteria = new CDbCriteria();
	//удалить!!
	//$criteria -> compare('tel',$call -> mangoTalker,true);
	//Omri::model() -> find($criteria);
?>
<td><?php echo $num; ?></td>
<!--<td><?php echo $translate[$call -> Classify()]; ?></td>-->
<td><?php if ($missed) {echo "missed!!";}   if ($dupl) {echo "double!";}?></td>
<td><?php echo $call -> date; ?></td>
<td><?php echo $call -> user -> fio; ?></td>
<td><?php echo $call -> number; ?></td>
<td><?php echo $call -> mangoTalker; ?></td>
<td><?php $phone = $call -> givePhone(); echo $phone ->number.":".$phone -> i; ?></td>
<td><?php echo $call -> giveReport(); ?></td>
<td><?php echo $call -> giveName(); ?></td>
<td><?php echo $call -> research_type; ?></td>
<td><?php echo CHtml::link('Отзыв', Yii::app() -> baseUrl.'/addreview/'.$call -> id); ?></td>
</tr>