<?php
	$form=$this->beginWidget('CActiveForm', array(
        // Please note: When you enable ajax validation, make sure the corresponding
        // controller action is handling ajax validation correctly.
        // There is a call to performAjaxValidation() commented in generated controller code.
        // See class documentation of CActiveForm for details on this.
        'enableAjaxValidation'=>false,
        'htmlOptions'=>array('enctype'=>'multipart/form-data'),
		'action' => Yii::app() -> baseUrl . '/assignCall'
    ));
	echo CHtml::hiddenField('id_call', $call -> id);
?>
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
?>
<td><?php echo CHtml::tag('input', array("type"=>"checkbox", "class" => "groupCheckbox","value" => $call -> id)); ?></td>
<td><?php echo $translate[$call -> Classify()]; ?></td>
<td><?php echo $call -> date; ?></td>
<td class='mistake'><?php echo $call -> error -> text; ?></td>
<?php $phone = UserPhone::model() -> findByAttributes (array('i' => $call -> lookForIAttribute())); ?>
<td><?php echo $phone -> number .":". $phone -> i; ?></td>
<td><?php echo $call -> j; ?></td>
<td><?php echo $call -> H; ?></td>
<td><?php echo $call -> giveStringFromArray($phone -> main_users,',','fio'); ?></td>
<td><?php echo $call -> comment; ?></td>
<td><?php echo $call -> research_type; ?></td>
<td><?php echo $call -> clinic; ?></td>
<td><?php echo $call -> number; ?></td>
<td><?php echo $call -> giveReport(); ?></td>
<td><?php echo $call -> fio; ?></td>
<td><?php echo $call -> research_type; ?></td>
<td><?php echo CHtml::link('del', Yii::app() -> baseUrl.'/deleteCall/'.$call -> id); ?></td>
<td>
<?php CHtml::activeDropDownListChosen2(User::model(), 'id',$users, array('class' => 'select2','name' => 'id_user', 'id' => 'AssignCall'.$call -> id, 'multiple' => 'multiple'), array(), '{
	placeholder:"Выберите пользователя",
	maximumSelectionLength: 1
}'); 
//echo CHtml::button('Присвоить',array('class' => 'assign', 'idlist' => 'AssignCall'.$call -> id ,'call' => $call -> id) );
?>
</td>
<td>
<?php 
	$call -> setPhone();
	if ($call -> phone) {
		//var_dump($call -> phone -> regular_users);
		$addresses = $call -> phone -> giveAddresses();
	} else {
		$addresses = array();
	}
	$ids = array();
	if ($call -> H) {
		$addr = UserAddress::model() -> findByAttributes(array('address' => $call -> H));
		if ($addr) {
			$ids[] = $addr -> id;
		}
	}
?>
<?php CHtml::activeDropDownListChosen2(UserAddress::model(), 'id',CHtml::listData($addresses, 'id', 'address'), array('class' => 'select2','name' => 'id_address', 'id' => 'AssignCallAddr'.$call -> id, 'multiple' => 'multiple'), $ids, '{
	placeholder:"Выберите адрес/клинику",
	maximumSelectionLength: 1
}'); 
echo CHtml::submitButton('Сохранить',array('class' => 'assign', 'idlist' => 'AssignCall'.$call -> id ,'call' => $call -> id) );
?>
</td>
</tr>
<?php
	$this -> endWidget();
?>