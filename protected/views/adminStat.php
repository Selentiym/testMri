<?php if (Yii::app() -> user -> checkAccess('admin')) :  

	$admin = User::model() -> findByPk(Yii::app() -> user -> getId());
	$this -> renderPartial('//navBar',array('user' => $admin, 'button' => 'no'));
	$data = Setting::getDataObj();
	$command = Yii::app()->db->createCommand('SELECT UNIX_TIMESTAMP(MIN(`create_time`)) FROM {{user}}');
	$earliest = $command -> queryScalar();
	
	$to = $_GET['to'] ? $_GET['to'] : time();
	//Не выводим слишком большой период, а то виснет.
	$from = $_GET['from'] ? $_GET['from'] : $earliest;
	//Укорачиваем только если по умолчанию ничего не задано. Если набрано время, то в соответсвтвии со временем.
	if ((!$_GET['from'])&&(($to - $from) > (24 * 3600 * 91)))  {
		$from = $to - 24 * 3600 * 91;
	}
	//Генерим календарик
	$datepicker = $this -> renderPartial("//_datepicker",["get" => $_GET, "from" => $from, "to" => $to,"url" => Yii::app() -> baseUrl."/allstat/%startTime%/%endTime%"],true);
	$from = $_GET["from"];
	$to = $_GET["to"];

	$sortable = array('fio','create_time');
	$frompage = $_POST;
	//$frompage["sortby"] = 'create_time';
	if (in_array($frompage["sortby"],$sortable)) {
		$sortby = $frompage["sortby"];
	}
	$users = User::model() -> findAllByMainDocs($frompage["medPreds"],$sortby);
	//Сортировка в браузере
	Yii::app()->clientScript->registerScript('SortingFunc','
	var ch = "hello";
	
	function myFunc (node){
		node = $(node);
		if (node.hasClass("month")) {
			var text = node.html();
			
			//return text.substr(text.lastIndexOf(\'-&gt;\')+6);
			//alert(text.substr(text.lastIndexOf(\'-&gt;\')+6));
			var rez = text.substr(text.lastIndexOf(\'-&gt;\')+6);
			var maxl = rez.length;
			for (var i = 0; i < 5-maxl; i++) {
				rez = "0" + rez;
			}
			//alert(rez);
			//node.html(rez);
			return rez;
		} else {
			return node.html();
		}
	}
	//alert($("#admin_table tr:nth-child(2) td:nth-child(9)").html());
	//myFunc();
	',CClientScript::POS_END);
	CHtml::setTableSorting('admin_table',"{textExtraction:myFunc}");

	Yii::app()->getClientScript()->registerCssFile(Yii::app()->baseUrl.'/css/bundle-bundle_daterangepicker_defer.css');
	Yii::app()->getClientScript()->registerScriptFile(Yii::app()->baseUrl.'/js/bundle-bundle_daterangepicker_defer.js');


	
	Yii::app()->getClientScript()->registerScript('deleteScript','$(".delete-doctor").click(function(){
		if (!confirm("Вы уверены, что хотите удалить доктора "+$(this).attr("name")+"?")) {
			return false;
		} else {
			location.replace($(this).attr("goto"));
		}
	});',CClientScript::POS_END); 	
	
	
	//$data = Setting::getDataObj();
	//print_r($get);

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
<!-- Form to contain technical information and to be submitted if the changes are to take place -->
<form id="controlForm" method = "post">
	<input type="hidden" name="sortby" value = "<?php echo $frompage["sortby"]; ?>"/>
	<input type="hidden" name="sortDirection" value = "<?php echo $frompage["sortDirection"]; ?>"/>
	<input type="hidden" name="page" value = "<?php echo $frompage["page"]; ?>"/>

	<div class="panel panel-default">
		<div class="panel-heading">Статистика по записям</div>

		<div class="panel-body">
			Показана статистика
			<?php //Календарик для выбора дат.
			echo $datepicker; ?>
			<br>
		</div>
	</div>

	<table class="table table-bordered" id="admin_table">
	<thead>
	<tr>
	<th class="fio">Номер</th>
	<th class="fio" sortby="fio">ФИО</th>
	<th class="tel">Телефон</th>
	<th class="mail">email</th>
	<th class="create" sortby="create_time">Начало работы</th>
	<th class="del">del</th>

	<?php
		//print_r($frompage);
		/**
		 * //Это вариант загрузки из csv файла
		 * $data = new Data;
		 */
		//Это вариант загрузки из GoogleDoc
		//$data = new DataGD;
		/*$user = User::model() -> findByAttributes(array('username' => 'doctor99'));
		print_r($user -> calls);*/
		$add = 604800;
		//print_r($data -> giveArrayKeys());
		foreach ($data -> giveArrayKeys($from, $to) as $moment) {
			echo "<th>";
			echo date('d M',$moment);
			echo " - ";
			echo date('d M',$moment+$add);
			echo "</th>";
		}
	?>
	<th class="del">Общее</th>
	</tr>
	</thead>
	<?php
		$num = 0;
		$pageSize = 10;
		$maxPage = ceil(count($users) / $pageSize);
		if (($frompage["page"])&& ($frompage["page"] > 0)) {
			$page = $frompage["page"];
			if ($maxPage < $frompage["page"]) {
				$page = $maxPage;
			}
		} else {
			$page = 1;
		}
		foreach($users as $key => $user){
			$num ++;
			$this -> renderPartial('//_showStat',array('user' => $user, 'data' => $data, 'from' => $from, 'to' => $to, 'num' => $num));
		}
	?>
	</table>
	<?php
		/* Вывод менюшки с главными докторами */
		$initials = function($name){
			$words = explode (' ',$name);
			$rez = $words[0].' ';
			for ($i = 1; $i < count($words); $i++) {
				$rez .= mb_substr($words[$i],0,1,'utf-8').'.';
			}
			return $rez;
		};
		echo "<div id='medPreds'>";
		if (!is_array($frompage["medPreds"])) {
			$frompage["medPreds"] = array();
		}
		foreach (CHtml::listData(User::GiveMedPreds(),'id','fio') as $id => $name) {
			echo '<div>';
			echo "<input type='checkbox' value='".$id."' name='medPreds[]'";
			if (in_array($id,$frompage["medPreds"])) {
				echo " checked='checked' ";
			}
			echo "/>";
			echo "<span>".$initials($name)."</span>";
			echo '</div>';
		}
		echo "</div>";
		
	?>
	<input type="submit" value="Применить"/>
	<!--<div id="pager">
		<?php
			$start = max(1,$page - 4);
			$stop = min($page + 4, $maxPage);
			if ($start != $page) {
				echo "<div id='list_left'></div>";
			}
			for ($i = $start; $i <= $stop; $i++){
				$active = $page == $i ? 'active' : '' ;
				echo "<div class='pageNum ".$active."'>".$i."</div>";
			}
			if (($stop != $page)&&($stop > 0)) {
				echo "<div id='list_right'></div>";
			}
		?>

	</div>
	<div id="goTo" style="display:inline-block; vertical-align:middle;">
		<input type="text" style="width:50px;"/>
		<input type="button" value="Перейти"/>(1-<?php echo $maxPage; ?>)
	</div>-->
	<?php
	else:
		$this -> renderPartial('//accessDenied');
	endif;
	?>
</form>