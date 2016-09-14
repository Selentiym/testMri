<?php $this -> renderPartial('//navBar', array('user' => User::model() -> giveLogged(), 'button' => 'no')); ?>
<?php
$pageSize = 10;
$criteria = new CDbCriteria;
$criteria -> addCondition ('id_user IS NULL');
$page = $_GET['page'] ? $_GET['page'] : 1;
$criteria -> limit = $pageSize;
$criteria -> offset = ($page - 1) * $pageSize;
$criteria -> order = 'date DESC';
$calls = BaseCall::model() -> findAll($criteria);
$command = Yii::app()->db->createCommand('SELECT COUNT(`id`) FROM {{call}} WHERE `id_user` IS NULL');
$maximum = $command -> queryScalar();
/*Yii::app() -> getClientScript() -> registerScript('clickScript',"
	$('.assign').click(function(){
		if ($('#'+$(this).attr('idlist')).val()) {
			location.href = '".Yii::app() -> baseUrl."/assignCall/' + $(this).attr('call') + '/' + $('#'+$(this).attr('idlist')).val();
		} else {
			alert('Выберите пользователя');
		}
	});
", CClientScript::POS_END);*/
CustomFlash::showFlashes();
echo "<div>";
echo "Всего звонков не привязано: ". $maximum;
echo "</div>";
if ($maximum > $pageSize) {
	echo "<div class='pages'>";
	
	for ($i = 1; $i <= (float)$maximum/$pageSize + 1; $i++) {
		$this -> renderPartial('//_page', array('num' => $i, 'url' => Yii::app() -> baseUrl . '/errors', 'active' => $page));
	}
	echo "</div>";
}
if (is_array($calls)&&(!empty($calls))):
 ?>
<table class="table table-stripped" style="margin-top: 10px">
    <tbody><tr>
        
        
        <th>Статус</th>
        <th>Дата</th>
        <th class='mistake'>Текст ошибки</th>
        <th>i</th>
        <th>j</th>
        <th>H</th>
        <th>Медпред</th>
        <th>Комментарий</th>
        <th>Исследование</th>
        <th>Телефон клиента</th>
        <th>Отчет</th>
        <th>ФИО</th>
        <th>Тип исследования</th>
		<th>Клиника</th>
        <th>Удалить звонок</th>
        <th>Присвоить пользователю</th>
        <th>Изменить адрес</th>
		
    </tr>
	<?php
	$criteria = new CDbCriteria;
	$criteria -> order = 'fio ASC';
	$criteria -> compare('id_type',UserType::model() -> getNumber('doctor'));
	$users = CHtml::listData(User::model() -> findAll($criteria), 'id', 'fio');
	foreach($calls as $call){
		$this -> renderPartial('//_error_call', array('call' => $call, 'users' => $users));
	}
	?>
	</tbody>
</table>
<?php
	else:
	echo "Все звонки определены верно.";
	endif;
?>