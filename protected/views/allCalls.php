
<? if (Yii::app() -> user -> checkAccess("admin")): ?>
<?php
	/**
	 * @type Controller $this
	 */
	$datepicker = $this -> renderPartial("//_datepicker",["get" => $get, "from" => $from, "to" => $to,"url" => Yii::app() -> baseUrl."/allCalls/%startTime%/%endTime%?call_types=".$_GET["call_types"]],true);
	$range = ["from" => $_GET["from"], "to" => $_GET["to"]];
?>
<?php 
	$criteria = BaseCall::model() -> giveCriteriaForTimePeriod($range['from'], $range['to']);
	$criteria -> order = 'date DESC';
	//$criteria -> compare('id_call_type',CallType::model() -> getNumber('verifyed'));
	if ($_GET["call_types"]) {
		$typeStr = $_GET["call_types"];
		switch ($typeStr) {
			case 'ver':
				$criteria -> compare('id_call_type',CallType::model() -> getNumber('verifyed'));
			break;
			case 'assigned_ver':
				$criteria -> addInCondition('id_call_type',array(CallType::model() -> getNumber('verifyed'),CallType::model() -> getNumber('assigned'),CallType::model() -> getNumber('missed')));
			break;
		}
	}
	$calls = BaseCall::model() -> findAll($criteria);
?>
<div class="panel panel-default">
	<div class="panel-heading">Все звонки в указанный период</div>

	<div class="panel-body">
		Показаны все звонки

		<?php echo $datepicker; ?>


		<br>
		Всего звонков: <?php echo count($calls); ?><br>
		Всего записей: <?php $counted = Data::model() -> countArray($calls); echo $counted['verifyed'] + $counted['assigned'] + $counted["missed"]; ?><br>
		Всего подтвержденных: <?php echo $counted['verifyed']; ?><br>
		Всего не пришло: <?php echo $counted['missed']; ?>
		<div id="all_call_types">
			<div id="ver" class="btn btn-default navbar-btn active">Только подтвержденные</div>
			<div id="assigned_ver" class="btn btn-default navbar-btn active">Подтвержденные, не пришедшие и записанные </div>
			<div id="all_calls" class="btn btn-default navbar-btn active">Все</div>
		</div>
		<?php
			Yii::app() -> getClientScript() -> registerScript('call_types','
				var hid = $("#call_types_hidden");
				$("#all_call_types").children().click(function(){
					hid.val($(this).attr("id"));
					hid.parent().submit();
				});
			',CClientScript::POS_END);
		?>
		<form id="call_types">
			<input type="hidden" id="call_types_hidden" value="<?php echo $_GET["call_types"]; ?>" name="call_types"/>
		</form>
	</div>
</div>
<?php CHtml::setTableSorting('allCallsTable',""); ?>
<table class="table table-stripped" style="margin-top: 10px" id="allCallsTable">
    <thead><tr>
        
        
        <th>Номер</th>
        <th>Статус</th>
        <th>Дата</th>
        <th>Медпред</th>
        <th>Доктор</th>
        <th>Телефон клиента</th>
        <th>mangoTalker</th>
        <th>Линия</th>
        <th>Отчет</th>
        <th>ФИО</th>
        <th>Тип исследования</th>
        <th>Отзыв</th>
    </tr>
	</thead>
	<?php
	/*usort($calls, function ($c1, $c2) {
		return mktime($c1 -> date) - mktime($c2 -> date);
	});*/
	$num = 0;
	foreach($calls as $call){
		$num ++;
		$this -> renderPartial('//oneCall_full', array('call' => $call, 'num' => $num));
	}
	?>
	</tbody>
</table>
<?php
else:
	$this -> renderPartial('//accessDenied');
endif;
?>