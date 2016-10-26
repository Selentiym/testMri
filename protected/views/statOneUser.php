<div class="panel panel-default">
	<div class="panel-heading">Статистика записей по месяцам</div>
	<h3>Статистика звонков</h3>
	<table class="table">
		<tbody>
			<tr>
				<th>Месяц</th>
				<th>Звонков</th>
				<th>Нецелевых</th>
				<th>Записаны</th>
				<th>Подтверждены</th>
				<th>Отменили</th>
				<th>Не пришли</th>
			</tr>

			<?php
				$monthedCalls = Setting::getDataObj() -> giveMonthedCalls($user);
				foreach($monthedCalls as $month => $calls_array) {
					$this -> renderPartial('//_month_calls_shortcut', array(
						'month' => $month,
						'calls' => $calls_array,
						'username' => (Yii::app() -> user -> getId() == $user -> id) ? '' : '/'.$user -> username 
					));
				}
			?>
		</tbody>
	</table>
</div>