

<?php if (Yii::app() -> user -> checkAccess('admin')) :  
	//$this -> renderPartial('//navBar');
	$data = new Data();
	//print_r($get);
	$from = (int)$get['from'] ? (int)$get['from'] : time() - 86400*5;
	$to = (int)$get['to'] ? (int)$get['to'] : time();
	//$to = (int)$get['to'];
	$range = array('from' => $from, 'to' => $to);
	//print_r($range);
	Yii::app()->getClientScript()->registerCssFile(Yii::app()->baseUrl.'/css/bundle-bundle_daterangepicker_defer.css');
	Yii::app()->getClientScript()->registerScriptFile(Yii::app()->baseUrl.'/js/bundle-bundle_daterangepicker_defer.js');
	
	
	
	Yii::app()->getClientScript()->registerScript('DatePickerRange',"
		$(function () {

            $('#reportrange').daterangepicker({
                format: 'DD.MM.YYYY',
                startDate: '".date('d.m.Y', $range['from'])."',
                endDate: '".date('d.m.Y', $range['to'])."',
                minDate: '01.05.2015',
                maxDate: moment(),
                dateLimit: { months: 3 },
                ranges: {
                    'Сегодня': [moment(), moment()],
                    'Вчера': [moment().subtract(1, 'days'), moment().subtract(1, 'days')],
                    'Последние 7 дней': [moment().subtract(6, 'days'), moment()],
                    'Последние 30 дней': [moment().subtract(29, 'days'), moment()],
                    'Текущий месяц': [moment().startOf('month'), moment().endOf('month')],
                    'Предыдущий месяц': [moment().subtract(1, 'month').startOf('month'), moment().subtract(1, 'month').endOf('month')]
                },
                buttonClasses: ['btn', 'btn-sm'],
                applyClass: 'btn-primary',
                cancelClass: 'btn-default',
                separator: ' по ',
                locale: {
                    applyLabel: 'Показать',
                    cancelLabel: 'Отмена',
                    fromLabel: 'С',
                    toLabel: 'По',
                    customRangeLabel: 'Выбрать даты',
                    daysOfWeek: ['Вс', 'Пн', 'Вт', 'Ср', 'Чт', 'Пт', 'Сб'],
                    monthNames: ['Январь', 'Февраль', 'Март', 'Апрель', 'Май', 'Июнь', 'Июлю', 'Август', 'Сентябрь', 'Октябрь', 'Ноябрь', 'Декабрь'],
                    firstDay: 1
                }
            }, function (start, end, label) {
                //end.subtract(1, 'days');
                window.location.href = '".Yii::app() -> baseUrl."/allCalls/' + start.unix() + '/' + end.unix();
            });

        });
	", CClientScript::POS_END);
 $translate = array(
    "am" => "дп",
    "pm" => "пп",
    "AM" => "ДП",
    "PM" => "ПП",
    "Monday" => "Понедельник",
    "Mon" => "Пн",
    "Tuesday" => "Вторник",
    "Tue" => "Вт",
    "Wednesday" => "Среда",
    "Wed" => "Ср",
    "Thursday" => "Четверг",
    "Thu" => "Чт",
    "Friday" => "Пятница",
    "Fri" => "Пт",
    "Saturday" => "Суббота",
    "Sat" => "Сб",
    "Sunday" => "Воскресенье",
    "Sun" => "Вс",
    "January" => "Января",
    "Jan" => "Янв",
    "February" => "Февраля",
    "Feb" => "Фев",
    "March" => "Марта",
    "Mar" => "Мар",
    "April" => "Апреля",
    "Apr" => "Апр",
    "May" => "Мая",
    "May" => "Мая",
    "June" => "Июня",
    "Jun" => "Июн",
    "July" => "Июля",
    "Jul" => "Июл",
    "August" => "Августа",
    "Aug" => "Авг",
    "September" => "Сентября",
    "Sep" => "Сен",
    "October" => "Октября",
    "Oct" => "Окт",
    "November" => "Ноября",
    "Nov" => "Ноя",
    "December" => "Декабря",
    "Dec" => "Дек",
    "st" => "ое",
    "nd" => "ое",
    "rd" => "е",
    "th" => "ое"
    );
	
?>
<?php 
	$criteria = BaseCall::model() -> giveCriteriaForTimePeriod($range['from'], $range['to']);
	$criteria -> compare('id_call_type',CallType::model() -> getNumber('verifyed'));
	$calls = BaseCall::model() -> findAll($criteria);
?>
<div class="panel panel-default">
	<div class="panel-heading">Все звонки в указанный период</div>

	<div class="panel-body">
		Показаны все звонки
		<span id="reportrange" style="border-bottom: dotted 1px; font-size: 150%">
			<i class="glyphicon glyphicon-calendar fa fa-calendar"></i>
			<span><small>с</small>
			<?php echo strtr(date("d F Y",$range['from']), $translate); ?>
			<small>по</small>
			<?php echo strtr(date("d F Y",$range['to']),$translate); ?>
			</span> <b class="caret"></b>
		</span>


		<br>
		Всего звонков: <?php echo count($calls); ?><br>
		Всего записей: <?php $counted = Data::model() -> countArray($calls); echo $counted['verifyed'] + $counted['assigned'] + $counted["missed"]; ?><br>
		Всего подтвержденных: <?php echo $counted['verifyed']; ?><br>
		Всего не пришло: <?php echo $counted['missed']; ?>
	</div>
</div>
<?php CHtml::setTableSorting('allCallsTable',""); ?>
<table class="table table-stripped" style="margin-top: 10px" id="allCallsTable">
    <thead><tr>
        
        
        <th>Номер</th>
        <th>Статус</th>
        <th>Дата</th>
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
	$arr = array();
	foreach($calls as $call){
		$num ++;
		$criteria = new CDbCriteria();
		//удалить!!
		$criteria -> compare('tel',trim($call -> mangoTalker),true);
		$found = Omri::model() -> find($criteria);
		$missed = (!$found);
		$doub = (!$missed)&&in_array($found -> id,$arr);
		if (!$doub) {
			$arr[$found -> id] = $found -> id;
		}
		if ($missed) {
			echo "missed:".$call -> mangoTalker."<br/>";
		}
		$this -> renderPartial('//oneCall_full', array('call' => $call, 'num' => $num, 'missed' => $missed,'dupl' => $doub));
		
	}
	sort($arr);
	$full = array();
	for ($i = 1; $i <= 104; $i++) {
		$full[$i] = $i;
	}
	print_r($full);
	print_r(array_diff($full,$arr));
	//print_r($arr);
	?>
	</tbody>
</table>
<?php
else:
	$this -> renderPartial('//accessDenied');
endif;
?>